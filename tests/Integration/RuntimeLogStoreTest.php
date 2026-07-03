<?php
/**
 * LindemannRock Logging Library
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Integration;

use Craft;
use craft\console\Request as CraftConsoleRequest;
use lindemannrock\logginglibrary\controllers\LogsController;
use lindemannrock\logginglibrary\helpers\UserLabelHelper;
use lindemannrock\logginglibrary\log\targets\RuntimeLogTarget;
use lindemannrock\logginglibrary\LoggingLibrary;
use lindemannrock\logginglibrary\services\RuntimeLogStoreService;
use lindemannrock\logginglibrary\tests\TestCase;
use yii\web\ForbiddenHttpException;
use yii\log\Logger;

/**
 * Covers the cache-backed recent runtime log store used by edge-safe viewing.
 *
 * @since 5.14.0
 */
class RuntimeLogStoreTest extends TestCase
{
    private RuntimeLogStoreService $store;

    protected function setUp(): void
    {
        parent::setUp();

        $this->store = LoggingLibrary::getInstance()->runtimeLogStore;
        $this->store->clear();
    }

    protected function cleanupExternalState(): void
    {
        $this->store->clear();

        parent::cleanupExternalState();
    }

    public function testRuntimeMessagesAreStoredNewestFirst(): void
    {
        $this->store->appendMessages([
            ['First runtime event', Logger::LEVEL_INFO, 'runtime-alpha', strtotime('2026-07-02 10:00:00'), [], 100],
            ['Second runtime event', Logger::LEVEL_WARNING, 'runtime-beta', strtotime('2026-07-02 10:01:00'), [], 200],
        ], $this->settings());

        $page = $this->store->getLogPage('all', 'all', '', 'timestamp', 'desc', 1, 10);

        self::assertSame(2, $page['total']);
        self::assertSame('Second runtime event', $page['entries'][0]['message']);
        self::assertSame('warning', $page['entries'][0]['canonicalLevel']);
        self::assertSame('lr-level-warning', $page['entries'][0]['levelClass']);
        self::assertSame('runtime-beta', $page['entries'][0]['category']);
        self::assertSame('First runtime event', $page['entries'][1]['message']);
        self::assertSame('lr-level-info', $page['entries'][1]['levelClass']);
    }

    public function testRuntimePageFiltersByLevelCategoryAndSearch(): void
    {
        $this->store->appendMessages([
            ['Needle warning message', Logger::LEVEL_WARNING, 'runtime-alpha', strtotime('2026-07-02 10:00:00'), [], 100],
            ['Other error message', Logger::LEVEL_ERROR, 'runtime-beta', strtotime('2026-07-02 10:01:00'), [], 200],
        ], $this->settings());

        $page = $this->store->getLogPage('warning', 'runtime-alpha', 'needle', 'timestamp', 'desc', 1, 10);

        self::assertSame(1, $page['total']);
        self::assertSame('Needle warning message', $page['entries'][0]['message']);
        self::assertSame('runtime-alpha', $page['category']);
        self::assertSame(['all', 'runtime-alpha'], array_column($page['categoryOptions'], 'value'));
        self::assertSame(['Source', 'runtime-alpha'], array_column($page['categoryOptions'], 'label'));
        self::assertSame(['(1)', '(1)'], array_column($page['categoryOptions'], 'extra'));
    }

    public function testRuntimeCategoryOptionsAreScopedToLevelAndSearch(): void
    {
        $this->store->appendMessages([
            ['Needle warning message', Logger::LEVEL_WARNING, 'runtime-alpha', strtotime('2026-07-02 10:00:00'), [], 100],
            ['Other error message', Logger::LEVEL_ERROR, 'runtime-beta', strtotime('2026-07-02 10:01:00'), [], 200],
            ['Needle info message', Logger::LEVEL_INFO, 'runtime-gamma', strtotime('2026-07-02 10:02:00'), [], 300],
        ], $this->settings());

        $page = $this->store->getLogPage('warning', 'runtime-gamma', 'needle', 'timestamp', 'desc', 1, 10);

        self::assertSame('all', $page['category']);
        self::assertSame(1, $page['total']);
        self::assertSame(['all', 'runtime-alpha'], array_column($page['categoryOptions'], 'value'));
    }

    public function testRuntimeCategoryOptionsMatchIndexedCaseInsensitiveSort(): void
    {
        $this->store->appendMessages([
            ['Job 2 message', Logger::LEVEL_INFO, 'job-2', strtotime('2026-07-02 10:00:00'), [], 100],
            ['Job 10 message', Logger::LEVEL_INFO, 'job-10', strtotime('2026-07-02 10:01:00'), [], 100],
            ['Apple message', Logger::LEVEL_INFO, 'Apple', strtotime('2026-07-02 10:02:00'), [], 100],
        ], $this->settings());

        $page = $this->store->getLogPage('all', 'all', '', 'timestamp', 'desc', 1, 10);

        self::assertSame(['all', 'Apple', 'job-10', 'job-2'], array_column($page['categoryOptions'], 'value'));
    }

    public function testRuntimePageFiltersRecordsOlderThanTtl(): void
    {
        $now = time();

        $this->store->appendMessages([
            ['Recent runtime event', Logger::LEVEL_WARNING, 'runtime-alpha', $now - 10, [], 100],
            ['Expired runtime event', Logger::LEVEL_WARNING, 'runtime-beta', $now - 120, [], 200],
        ], $this->settings());

        $page = $this->store->getLogPage('all', 'all', '', 'timestamp', 'desc', 1, 10, 60);

        self::assertSame(1, $page['total']);
        self::assertSame('Recent runtime event', $page['entries'][0]['message']);
        self::assertSame(['all', 'runtime-alpha'], array_column($page['categoryOptions'], 'value'));
    }

    public function testRuntimeStoreHonorsMaxEntries(): void
    {
        $settings = $this->settings(['maxEntries' => 2]);

        $this->store->appendMessages([
            ['One', Logger::LEVEL_INFO, 'runtime-alpha', strtotime('2026-07-02 10:00:00'), [], 100],
            ['Two', Logger::LEVEL_INFO, 'runtime-alpha', strtotime('2026-07-02 10:01:00'), [], 100],
            ['Three', Logger::LEVEL_INFO, 'runtime-alpha', strtotime('2026-07-02 10:02:00'), [], 100],
        ], $settings);

        $page = $this->store->getLogPage('all', 'all', '', 'timestamp', 'desc', 1, 10);

        self::assertSame(2, $page['total']);
        self::assertSame(['Three', 'Two'], array_column($page['entries'], 'message'));
    }

    public function testRuntimeTimestampSortUsesSequenceTiebreaker(): void
    {
        $timestamp = strtotime('2026-07-02 10:00:00');

        $this->store->appendMessages([
            ['First same-second event', Logger::LEVEL_INFO, 'runtime-alpha', $timestamp, [], 100],
            ['Second same-second event', Logger::LEVEL_INFO, 'runtime-alpha', $timestamp, [], 100],
            ['Third same-second event', Logger::LEVEL_INFO, 'runtime-alpha', $timestamp, [], 100],
        ], $this->settings());

        $page = $this->store->getLogPage('all', 'all', '', 'timestamp', 'desc', 1, 10);

        self::assertSame([
            'Third same-second event',
            'Second same-second event',
            'First same-second event',
        ], array_column($page['entries'], 'message'));
    }

    public function testRuntimeLevelSortUsesSeverityOrder(): void
    {
        $timestamp = strtotime('2026-07-02 10:00:00');

        $this->store->appendMessages([
            ['Warning event', Logger::LEVEL_WARNING, 'runtime-alpha', $timestamp, [], 100],
            ['Info event', Logger::LEVEL_INFO, 'runtime-alpha', $timestamp, [], 100],
            ['Error event', Logger::LEVEL_ERROR, 'runtime-alpha', $timestamp, [], 100],
            ['Debug event', Logger::LEVEL_TRACE, 'runtime-alpha', $timestamp, [], 100],
        ], $this->settings());

        $page = $this->store->getLogPage('all', 'all', '', 'level', 'asc', 1, 10);

        self::assertSame([
            'Error event',
            'Warning event',
            'Info event',
            'Debug event',
        ], array_column($page['entries'], 'message'));
    }

    public function testRuntimeStoreClampsMaxEntriesToTenThousand(): void
    {
        $messages = [];
        $baseTimestamp = strtotime('2026-07-02 10:00:00');

        for ($index = 0; $index < 10002; $index++) {
            $messages[] = [
                'Message ' . $index,
                Logger::LEVEL_INFO,
                'runtime-alpha',
                $baseTimestamp + $index,
                [],
                100,
            ];
        }

        $this->store->appendMessages($messages, $this->settings(['maxEntries' => 20000]));

        $page = $this->store->getLogPage('all', 'all', '', 'timestamp', 'desc', 1, 1);

        self::assertSame(10000, $page['total']);
        self::assertSame('Message 10001', $page['entries'][0]['message']);
    }

    public function testRuntimeStoreTruncatesOversizedMessagesAndContext(): void
    {
        $settings = $this->settings([
            'maxMessageBytes' => 12,
            'maxContextBytes' => 40,
        ]);

        $this->store->appendMessages([
            [
                'This message should be truncated',
                Logger::LEVEL_ERROR,
                'runtime-alpha',
                strtotime('2026-07-02 10:00:00'),
                [['file' => __FILE__, 'line' => __LINE__]],
                100,
            ],
        ], $settings);

        $page = $this->store->getLogPage('all', 'all', '', 'timestamp', 'desc', 1, 10);

        self::assertStringEndsWith('...', $page['entries'][0]['message']);
        self::assertLessThanOrEqual(15, strlen($page['entries'][0]['message']));
        self::assertStringEndsWith('...', $page['entries'][0]['context']);
        self::assertLessThanOrEqual(43, strlen($page['entries'][0]['context']));
    }

    public function testRuntimeStoreClampsNonPositiveMessageAndContextLimits(): void
    {
        $this->store->appendMessages([
            [
                'This message should still be bounded',
                Logger::LEVEL_ERROR,
                'runtime-alpha',
                strtotime('2026-07-02 10:00:00'),
                [['file' => __FILE__, 'line' => __LINE__]],
                100,
            ],
        ], $this->settings([
            'maxMessageBytes' => 0,
            'maxContextBytes' => -10,
        ]));

        $page = $this->store->getLogPage('all', 'all', '', 'timestamp', 'desc', 1, 10);

        self::assertSame('T...', $page['entries'][0]['message']);
        self::assertSame('{...', $page['entries'][0]['context']);
    }

    public function testRuntimeStoreCapsOversizedMessageAndContextLimits(): void
    {
        $this->store->appendMessages([
            [
                str_repeat('A', RuntimeLogStoreService::MAX_BYTES_LIMIT + 10),
                Logger::LEVEL_ERROR,
                'runtime-alpha',
                strtotime('2026-07-02 10:00:00'),
                [['file' => __FILE__, 'line' => __LINE__, 'payload' => str_repeat('B', RuntimeLogStoreService::MAX_BYTES_LIMIT + 10)]],
                100,
            ],
        ], $this->settings([
            'maxMessageBytes' => RuntimeLogStoreService::MAX_BYTES_LIMIT + 1000,
            'maxContextBytes' => RuntimeLogStoreService::MAX_BYTES_LIMIT + 1000,
        ]));

        $page = $this->store->getLogPage('all', 'all', '', 'timestamp', 'desc', 1, 10);

        self::assertSame(RuntimeLogStoreService::MAX_BYTES_LIMIT + 3, strlen($page['entries'][0]['message']));
        self::assertLessThanOrEqual(RuntimeLogStoreService::MAX_BYTES_LIMIT + 3, strlen($page['entries'][0]['context']));
    }

    public function testRuntimeStorePreservesMicrosecondTimestampPrecision(): void
    {
        $this->store->appendMessages([
            ['Microsecond runtime event', Logger::LEVEL_INFO, 'runtime-alpha', 1783000800.123456, [], 100],
        ], $this->settings());

        $page = $this->store->getLogPage('all', 'all', '', 'timestamp', 'desc', 1, 10);

        self::assertStringContainsString('.123456', $page['entries'][0]['timestamp']);
    }

    public function testRuntimeUserLabelsResolveAndFallBack(): void
    {
        $records = [
            ['user' => 'user:999999999', 'message' => 'a'],
            ['user' => '', 'message' => 'b'],
            ['user' => 'cli', 'message' => 'c'],
        ];

        $out = UserLabelHelper::withUserLabels($records);

        self::assertSame('User #999999999', $out[0]['userLabel']);
        self::assertSame('System', $out[1]['userLabel']);
        self::assertSame('cli', $out[2]['userLabel']);
    }

    public function testClearRuntimeRejectsUserWithoutClearCachePermission(): void
    {
        $user = $this->createTestUser('__logginglibrary_test_');
        $this->grantPermissions($user, [LoggingLibrary::PERMISSION_VIEW_ALL_LOGS]);
        $this->actingAs($user);

        $originalRequest = Craft::$app->getRequest();
        Craft::$app->set('request', new RuntimeLogStorePostRequest());

        try {
            $this->expectException(ForbiddenHttpException::class);

            (new LogsController('logs', LoggingLibrary::getInstance()))->actionClearRuntime();
        } finally {
            Craft::$app->set('request', $originalRequest);
        }
    }

    public function testRuntimeTargetCapturesDirectCraftLogCalls(): void
    {
        $target = new RuntimeLogTarget([
            'levels' => ['warning'],
            'categories' => ['runtime-target-test'],
            'runtimeSettings' => $this->settings(),
        ]);

        $logger = Craft::getLogger();
        $dispatcher = $logger->dispatcher;
        $logger->messages = [];

        try {
            $dispatcher->targets[] = $target;
            $target->init();

            Craft::warning('Captured through Yii target', 'runtime-target-test');
            $logger->flush(true);

            $page = $this->store->getLogPage('warning', 'runtime-target-test', '', 'timestamp', 'desc', 1, 10);

            self::assertGreaterThanOrEqual(1, $page['total']);
            self::assertSame('Captured through Yii target', $page['entries'][0]['message']);
        } finally {
            $dispatcher->targets = array_values(array_filter(
                $dispatcher->targets,
                fn($registeredTarget) => $registeredTarget !== $target
            ));
            $logger->messages = [];
        }
    }

    private function settings(array $overrides = []): array
    {
        return array_merge(LoggingLibrary::getRuntimeLogStoreConfig(), [
            'enabled' => true,
            'ttl' => 60,
            'maxEntries' => 100,
            'maxMessageBytes' => 8000,
            'maxContextBytes' => 8000,
            'privacy' => [
                'includeUserId' => false,
            ],
        ], $overrides);
    }
}

final class RuntimeLogStorePostRequest extends CraftConsoleRequest
{
    public function getIsPost(): bool
    {
        return true;
    }
}
