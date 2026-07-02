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
use lindemannrock\logginglibrary\log\targets\RuntimeLogTarget;
use lindemannrock\logginglibrary\LoggingLibrary;
use lindemannrock\logginglibrary\services\RuntimeLogStoreService;
use lindemannrock\logginglibrary\tests\TestCase;
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
        self::assertGreaterThanOrEqual(3, count($page['categoryOptions']));
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
