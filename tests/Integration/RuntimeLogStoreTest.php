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
use craft\config\BaseConfig;
use craft\console\Request as CraftConsoleRequest;
use craft\queue\Queue as CraftQueue;
use craft\services\Config;
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

    public function testRuntimeConfigDefaultsSkipConsoleAndQueueRequests(): void
    {
        $config = require dirname(__DIR__, 2) . '/src/config.php';

        self::assertTrue($config['*']['runtimeLogStore']['skipConsoleRequests']);
        self::assertTrue($config['*']['runtimeLogStore']['skipQueueRequests']);

        $this->withRuntimeConfig([], function(array $runtimeConfig): void {
            self::assertTrue($runtimeConfig['skipConsoleRequests']);
            self::assertTrue($runtimeConfig['skipQueueRequests']);
        });
    }

    public function testRuntimeConfigPreservesExplicitFalseSkipValues(): void
    {
        $this->withRuntimeConfig([
            'skipConsoleRequests' => false,
            'skipQueueRequests' => false,
        ], function(array $runtimeConfig): void {
            self::assertFalse($runtimeConfig['skipConsoleRequests']);
            self::assertFalse($runtimeConfig['skipQueueRequests']);
        });
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
        self::assertSame(2, $page['storedTotal']);
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
            ['Apple message', Logger::LEVEL_INFO, 'Apple', strtotime('2026-07-02 10:00:00'), [], 100],
            ['Job 10 message', Logger::LEVEL_INFO, 'job-10', strtotime('2026-07-02 10:01:00'), [], 100],
            ['Job 2 message', Logger::LEVEL_INFO, 'job-2', strtotime('2026-07-02 10:02:00'), [], 100],
        ], $this->settings());

        $page = $this->store->getLogPage('all', 'all', '', 'timestamp', 'desc', 1, 10);

        self::assertSame(['all', 'Apple', 'job-10', 'job-2'], array_column($page['categoryOptions'], 'value'));
    }

    public function testRuntimeCategoryOptionsGroupPluginHandlesAndClassCategories(): void
    {
        $this->store->appendMessages([
            ['Explicit handle event', Logger::LEVEL_INFO, 'logging-library', strtotime('2026-07-02 10:00:00'), [], 100],
            ['Plugin class event', Logger::LEVEL_INFO, 'lindemannrock\logginglibrary\LoggingLibrary::init', strtotime('2026-07-02 10:01:00'), [], 100],
            ['System event', Logger::LEVEL_INFO, 'yii\db\Command::query', strtotime('2026-07-02 10:02:00'), [], 100],
        ], $this->settings());

        $page = $this->store->getLogPage('all', 'plugin:logging-library', '', 'timestamp', 'desc', 1, 10);

        self::assertSame('plugin:logging-library', $page['category']);
        self::assertSame('Logging Library', $page['categoryLabel']);
        self::assertSame(2, $page['total']);
        self::assertSame([
            'lindemannrock\logginglibrary\LoggingLibrary::init',
            'logging-library',
        ], array_column($page['entries'], 'category'));
        self::assertSame(['Logging Library', 'Logging Library'], array_column($page['entries'], 'categoryLabel'));

        $optionValues = array_column($page['categoryOptions'], 'value');
        self::assertContains('plugin:logging-library', $optionValues);
        self::assertContains('system:db-command:query', $optionValues);

        $pluginIndex = array_search('plugin:logging-library', $optionValues, true);
        self::assertSame('Logging Library', $page['categoryOptions'][$pluginIndex]['label']);
        self::assertSame('(2)', $page['categoryOptions'][$pluginIndex]['extra']);

        $systemIndex = array_search('system:db-command:query', $optionValues, true);
        self::assertSame('DB Queries', $page['categoryOptions'][$systemIndex]['label']);

        $legacyPage = $this->store->getLogPage('all', 'logging-library', '', 'timestamp', 'desc', 1, 10);

        self::assertSame('plugin:logging-library', $legacyPage['category']);
        self::assertSame(2, $legacyPage['total']);
    }

    public function testRuntimeCategoryOptionsGroupCommonSystemCategories(): void
    {
        $this->store->appendMessages([
            ['DB query event', Logger::LEVEL_INFO, 'yii\db\Command::query', strtotime('2026-07-02 10:00:00'), [], 100],
            ['DB execute event', Logger::LEVEL_INFO, 'yii\db\Command::execute', strtotime('2026-07-02 10:01:00'), [], 100],
            ['DB connection event', Logger::LEVEL_INFO, 'yii\db\Connection::open', strtotime('2026-07-02 10:02:00'), [], 100],
            ['Redis connection event', Logger::LEVEL_INFO, 'yii\redis\Connection::open', strtotime('2026-07-02 10:03:00'), [], 100],
            ['Redis command event', Logger::LEVEL_INFO, 'yii\redis\Connection::executeCommand', strtotime('2026-07-02 10:04:00'), [], 100],
            ['URL route event', Logger::LEVEL_INFO, 'craft\web\UrlManager::_getMatchedUrlRoute', strtotime('2026-07-02 10:05:00'), [], 100],
            ['URL rule event', Logger::LEVEL_INFO, 'yii\web\UrlRule::parseRequest', strtotime('2026-07-02 10:06:00'), [], 100],
            ['Request event', Logger::LEVEL_INFO, 'craft\web\Application::_processActionRequest', strtotime('2026-07-02 10:07:00'), [], 100],
            ['Controller event', Logger::LEVEL_INFO, 'yii\base\Controller::runAction', strtotime('2026-07-02 10:08:00'), [], 100],
            ['Queue event', Logger::LEVEL_INFO, 'craft\queue\QueueLogBehavior::beforeExec', strtotime('2026-07-02 10:09:00'), [], 100],
            ['Session event', Logger::LEVEL_INFO, 'yii\web\Session::open', strtotime('2026-07-02 10:10:00'), [], 100],
            ['View event', Logger::LEVEL_INFO, 'craft\web\View::renderTemplate', strtotime('2026-07-02 10:11:00'), [], 100],
            ['Module event', Logger::LEVEL_INFO, 'yii\base\Module::getModule', strtotime('2026-07-02 10:12:00'), [], 100],
            ['Vite helper event', Logger::LEVEL_INFO, 'nystudio107\pluginvite\helpers\FileHelper::fetchResponse', strtotime('2026-07-02 10:13:00'), [], 100],
            ['Code editor event', Logger::LEVEL_INFO, 'nystudio107\codeeditor\CodeEditor::bootstrap', strtotime('2026-07-02 10:14:00'), [], 100],
        ], $this->settings());

        $page = $this->store->getLogPage('all', 'all', '', 'timestamp', 'desc', 1, 20);
        $optionsByValue = array_column($page['categoryOptions'], null, 'value');

        self::assertSame('DB Queries', $optionsByValue['system:db-command:query']['label']);
        self::assertSame('DB Commands', $optionsByValue['system:db-command:execute']['label']);
        self::assertSame('DB Connection', $optionsByValue['system:db-connection:open']['label']);
        self::assertSame('Redis Connection', $optionsByValue['system:redis-connection:open']['label']);
        self::assertSame('Redis Commands', $optionsByValue['system:redis-connection:executeCommand']['label']);
        self::assertSame('URL Routing', $optionsByValue['system:url-routing']['label']);
        self::assertSame('(2)', $optionsByValue['system:url-routing']['extra']);
        self::assertSame('Web Request', $optionsByValue['system:web-request']['label']);
        self::assertSame('(2)', $optionsByValue['system:web-request']['extra']);
        self::assertSame('Queue', $optionsByValue['system:queue']['label']);
        self::assertSame('Session', $optionsByValue['system:session']['label']);
        self::assertSame('Template Rendering', $optionsByValue['system:view-rendering']['label']);
        self::assertSame('Modules', $optionsByValue['system:modules']['label']);
        self::assertSame('Vite', $optionsByValue['plugin:vite']['label']);
        self::assertSame('Code Editor', $optionsByValue['package:codeeditor']['label']);

        $filteredPage = $this->store->getLogPage('all', 'system:url-routing', '', 'timestamp', 'desc', 1, 20);

        self::assertSame('system:url-routing', $filteredPage['category']);
        self::assertSame('URL Routing', $filteredPage['categoryLabel']);
        self::assertSame(2, $filteredPage['total']);
        self::assertSame(['URL Routing', 'URL Routing'], array_column($filteredPage['entries'], 'categoryLabel'));
    }

    public function testStandaloneSourceLabelsUsePluginDisplayNames(): void
    {
        $controller = Craft::createObject(LogsController::class, ['logs', Craft::$app]);
        $method = new \ReflectionMethod(LogsController::class, '_extractSources');

        $sources = $method->invoke($controller, [
            ['source' => 'web'],
            ['source' => 'logging-library'],
        ]);

        self::assertSame('Web', $sources['web']);
        self::assertSame('Logging Library', $sources['logging-library']);
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
        self::assertSame(1, $page['storedTotal']);
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

    public function testClearRuntimeStoreReturnsFalseWhenLockCannotBeAcquired(): void
    {
        $lockConstant = (new \ReflectionClass($this->store))->getReflectionConstant('LOCK_KEY');
        self::assertNotNull($lockConstant);
        $lockKey = (string)$lockConstant->getValue();
        $mutex = Craft::$app->getMutex();

        self::assertTrue($mutex->acquire($lockKey, 1));

        try {
            self::assertFalse($this->store->clear());
        } finally {
            $mutex->release($lockKey);
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
        $originalRequest = Craft::$app->getRequest();
        $originalRequestedRoute = Craft::$app->requestedRoute;

        try {
            Craft::$app->set('request', new RuntimeLogStoreWebRequest());
            Craft::$app->requestedRoute = 'site/index';
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
            Craft::$app->requestedRoute = $originalRequestedRoute;
            Craft::$app->set('request', $originalRequest);
        }
    }

    public function testRuntimeTargetSkipsConsoleRequestsBeforeAppend(): void
    {
        $store = new RecordingRuntimeLogStoreService();
        $this->swapPluginComponent('logging-library', 'runtimeLogStore', $store);

        $target = $this->target($this->settings());
        $target->export();

        self::assertSame(0, $store->appendCalls);
    }

    public function testRuntimeTargetSkipsActiveCraftQueueExecution(): void
    {
        $store = new RecordingRuntimeLogStoreService();
        $this->swapPluginComponent('logging-library', 'runtimeLogStore', $store);

        $queue = Craft::$app->getQueue();
        self::assertInstanceOf(CraftQueue::class, $queue);

        $workerPid = new \ReflectionProperty(\yii\queue\cli\Queue::class, '_workerPid');
        $originalWorkerPid = $workerPid->getValue($queue);
        $workerPid->setValue($queue, 12345);

        try {
            $target = $this->target($this->settings([
                'skipConsoleRequests' => false,
                'skipQueueRequests' => true,
            ]));
            $target->export();

            self::assertSame(0, $store->appendCalls);
        } finally {
            $workerPid->setValue($queue, $originalWorkerPid);
        }
    }

    public function testRuntimeTargetSkipsQueueExecutionRoutesWithoutWorkerPid(): void
    {
        $store = new RecordingRuntimeLogStoreService();
        $this->swapPluginComponent('logging-library', 'runtimeLogStore', $store);

        $queue = Craft::$app->getQueue();
        self::assertInstanceOf(CraftQueue::class, $queue);

        $workerPid = new \ReflectionProperty(\yii\queue\cli\Queue::class, '_workerPid');
        $originalWorkerPid = $workerPid->getValue($queue);
        $originalRequest = Craft::$app->getRequest();
        $originalRequestedRoute = Craft::$app->requestedRoute;

        try {
            $workerPid->setValue($queue, null);
            Craft::$app->set('request', new RuntimeLogStoreWebRequest());

            foreach (['queue/run', 'queue/listen', 'queue/exec', 'queue/retry', 'queue/retry-all'] as $requestedRoute) {
                Craft::$app->requestedRoute = $requestedRoute;
                $this->target($this->settings())->export();
            }

            self::assertSame(0, $store->appendCalls);
        } finally {
            $workerPid->setValue($queue, $originalWorkerPid);
            Craft::$app->requestedRoute = $originalRequestedRoute;
            Craft::$app->set('request', $originalRequest);
        }
    }

    public function testRuntimeTargetStillSkipsRuntimeAjaxEndpoint(): void
    {
        $store = new RecordingRuntimeLogStoreService();
        $this->swapPluginComponent('logging-library', 'runtimeLogStore', $store);
        $originalRequest = Craft::$app->getRequest();
        $originalRequestedRoute = Craft::$app->requestedRoute;

        try {
            Craft::$app->set('request', new RuntimeLogStoreWebRequest('logging-library/logs/runtime-data'));
            Craft::$app->requestedRoute = 'logging-library/logs/runtime-data';
            $target = $this->target($this->settings());
            $target->export();

            self::assertSame(0, $store->appendCalls);
        } finally {
            Craft::$app->requestedRoute = $originalRequestedRoute;
            Craft::$app->set('request', $originalRequest);
        }
    }

    private function target(array $settings): RuntimeLogTarget
    {
        $target = new RuntimeLogTarget([
            'runtimeSettings' => $settings,
        ]);
        $target->messages = [
            ['Runtime target test message', Logger::LEVEL_WARNING, 'runtime-target-test', microtime(true), [], 100],
        ];

        return $target;
    }

    /**
     * @param callable(array): void $assertions
     */
    private function withRuntimeConfig(array $runtimeConfig, callable $assertions): void
    {
        $originalConfig = Craft::$app->getConfig();
        Craft::$app->set('config', new RuntimeLogStoreConfig($runtimeConfig));

        try {
            $assertions(LoggingLibrary::getRuntimeLogStoreConfig());
        } finally {
            Craft::$app->set('config', $originalConfig);
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

final class RuntimeLogStoreWebRequest extends CraftConsoleRequest
{
    public function __construct(private string $pathInfo = '')
    {
        parent::__construct();
        $this->setIsConsoleRequest(false);
    }

    public function getPathInfo(): string
    {
        return $this->pathInfo;
    }

    public function getParam(string $name, mixed $defaultValue = null): mixed
    {
        return $defaultValue;
    }
}

final class RuntimeLogStoreConfig extends Config
{
    public function __construct(private array $runtimeConfig)
    {
        parent::__construct();
    }

    public function getConfigFromFile(string $filename): array|callable|BaseConfig
    {
        if ($filename === 'logging-library') {
            return ['runtimeLogStore' => $this->runtimeConfig];
        }

        return parent::getConfigFromFile($filename);
    }
}

final class RecordingRuntimeLogStoreService extends RuntimeLogStoreService
{
    public int $appendCalls = 0;

    public function appendMessages(array $messages, array $settings): void
    {
        $this->appendCalls++;
    }
}
