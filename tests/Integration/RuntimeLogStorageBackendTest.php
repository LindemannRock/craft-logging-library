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
use lindemannrock\logginglibrary\controllers\LogsController;
use lindemannrock\logginglibrary\log\targets\RuntimeLogTarget;
use lindemannrock\logginglibrary\LoggingLibrary;
use lindemannrock\logginglibrary\services\RuntimeLogStoreService;
use lindemannrock\logginglibrary\services\runtime\GenericCacheRuntimeLogStorage;
use lindemannrock\logginglibrary\services\runtime\RedisRuntimeLogStorage;
use lindemannrock\logginglibrary\services\runtime\RuntimeLogRedisConnectionFactory;
use lindemannrock\logginglibrary\services\runtime\UnavailableRedisRuntimeLogStorage;
use lindemannrock\logginglibrary\tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use yii\caching\ArrayCache;
use yii\db\Exception as YiiDbException;
use yii\log\Logger;
use yii\mutex\Mutex;
use yii\redis\Cache as RedisCache;
use yii\redis\Connection;

/**
 * Covers fail-soft behavior and the Redis atomic-command contract.
 *
 * @since 5.18.0
 */
final class RuntimeLogStorageBackendTest extends TestCase
{
    public function testRuntimeTargetExportNeverLeaksStoreExceptions(): void
    {
        $store = new ThrowingRuntimeLogStoreService();
        $this->swapPluginComponent('logging-library', 'runtimeLogStore', $store);

        $originalRequest = Craft::$app->getRequest();
        $originalRoute = Craft::$app->requestedRoute;

        try {
            Craft::$app->set('request', new RuntimeStorageWebRequest());
            Craft::$app->requestedRoute = 'site/index';

            $target = new RuntimeLogTarget([
                'runtimeSettings' => $this->settings(),
            ]);
            $target->messages = [
                ['Never escape', Logger::LEVEL_WARNING, 'runtime-test', microtime(true), [], 100],
            ];

            $target->export();
            self::assertSame(1, $store->appendCalls);
        } finally {
            Craft::$app->requestedRoute = $originalRoute;
            Craft::$app->set('request', $originalRequest);
        }
    }

    public function testRedisAppendUsesOnlyAtomicListCommands(): void
    {
        $connection = new RecordingRedisConnection();
        $storage = new RedisRuntimeLogStorage($connection, 'owned-key', 'literal');

        self::assertTrue($storage->append([
            ['id' => 'newest'],
            ['id' => 'oldest'],
        ], 25, 60));

        self::assertSame(['MULTI', 'LPUSH', 'LTRIM', 'EXPIRE', 'EXEC'], array_column($connection->commands, 0));
        self::assertSame(['owned-key', '{"id":"oldest"}', '{"id":"newest"}'], $connection->commands[1][1]);
        self::assertSame(['owned-key', 0, 24], $connection->commands[2][1]);
        self::assertSame(['owned-key', 60], $connection->commands[3][1]);
        self::assertSame(0, $connection->mutexCalls);

        $forbidden = ['GET', 'LRANGE', 'SET', 'MGET'];
        self::assertSame([], array_values(array_intersect($forbidden, array_column($connection->commands, 0))));
    }

    public function testRedisOpenAndTransactionFailuresNeverEscape(): void
    {
        $openFailure = new RecordingRedisConnection();
        $openFailure->failOpen = true;
        $openStorage = new RedisRuntimeLogStorage($openFailure, 'owned-key', 'literal');

        self::assertFalse($openStorage->append([['id' => 'dropped']], 10, 60));
        self::assertFalse($openStorage->isAvailable());

        $execFailure = new RecordingRedisConnection();
        $execFailure->failCommand = 'EXEC';
        $execStorage = new RedisRuntimeLogStorage($execFailure, 'owned-key', 'literal');

        self::assertFalse($execStorage->append([['id' => 'dropped']], 10, 60));
        self::assertFalse($execStorage->isAvailable());
    }

    public function testTransientRedisFailureRetriesOnlyTheSameAuthoritativeBackend(): void
    {
        $connection = new RecordingRedisConnection();
        $connection->failCommand = 'LPUSH';
        $storage = new RedisRuntimeLogStorage($connection, 'owned-key', 'literal');

        self::assertFalse($storage->append([['id' => 'dropped']], 10, 60));
        self::assertFalse($storage->isAvailable());
        self::assertSame([], $connection->list);

        $connection->failCommand = null;

        self::assertTrue($storage->append([['id' => 'accepted']], 10, 60));
        self::assertTrue($storage->isAvailable());
        self::assertSame(['{"id":"accepted"}'], $connection->list);
        self::assertSame(0, $connection->outsideTransactionWrites);
        self::assertSame([
            'MULTI',
            'LPUSH',
            'MULTI',
            'LPUSH',
            'LTRIM',
            'EXPIRE',
            'EXEC',
        ], array_column($connection->commands, 0));
    }

    public function testInvalidRedisConfigurationNeverSelectsGenericCache(): void
    {
        $source = new RecordingRedisConnection(['database' => 0]);
        $cache = new RecordingRedisRuntimeCache(['redis' => $source]);
        $originalCache = Craft::$app->getCache();
        Craft::$app->set('cache', $cache);

        try {
            $service = new RuntimeLogStoreService();
            $service->appendMessages([
                ['Dropped', Logger::LEVEL_WARNING, 'runtime-test', microtime(true), [], 100],
            ], $this->settings([
                'redis' => ['database' => '0'],
            ]));

            $storage = (new \ReflectionProperty(RuntimeLogStoreService::class, '_storage'))->getValue($service);
            self::assertInstanceOf(UnavailableRedisRuntimeLogStorage::class, $storage);
            self::assertSame([
                'backend' => 'redis',
                'available' => false,
                'database' => null,
                'databaseMode' => 'literal',
                'failureReason' => 'invalid-database',
            ], array_intersect_key($storage->status(), array_flip([
                'backend',
                'available',
                'database',
                'databaseMode',
                'failureReason',
            ])));
            self::assertSame(0, $cache->getCalls);
            self::assertSame(0, $cache->setCalls);
            self::assertSame(0, $cache->deleteCalls);
            self::assertSame([], $source->commands);
        } finally {
            Craft::$app->set('cache', $originalCache);
        }
    }

    public function testMissingRedisDatabaseEnvironmentValuePerformsZeroBackendWork(): void
    {
        $environmentName = 'LOGGING_LIBRARY_TEST_MISSING_AUTHORITATIVE_DB';
        $previous = $_SERVER[$environmentName] ?? null;
        unset($_SERVER[$environmentName]);

        $source = new RecordingRedisConnection(['database' => 0]);
        $cache = new RecordingRedisRuntimeCache(['redis' => $source]);
        $originalCache = Craft::$app->getCache();
        Craft::$app->set('cache', $cache);

        try {
            $service = new RuntimeLogStoreService();
            $service->appendMessages([
                ['Dropped', Logger::LEVEL_WARNING, 'runtime-test', microtime(true), [], 100],
            ], $this->settings([
                'redis' => ['database' => '$' . $environmentName],
            ]));

            $storage = (new \ReflectionProperty(RuntimeLogStoreService::class, '_storage'))->getValue($service);
            self::assertInstanceOf(UnavailableRedisRuntimeLogStorage::class, $storage);
            self::assertSame('invalid-environment-database', $storage->status()['failureReason']);
            self::assertSame(0, $cache->getCalls);
            self::assertSame(0, $cache->setCalls);
            self::assertSame(0, $cache->deleteCalls);
            self::assertSame([], $source->commands);
        } finally {
            Craft::$app->set('cache', $originalCache);
            if ($previous === null) {
                unset($_SERVER[$environmentName]);
            } else {
                $_SERVER[$environmentName] = $previous;
            }
        }
    }

    public function testPersistentSourceWithDifferentDatabaseStillSelectsDedicatedRedis(): void
    {
        $source = new RecordingRedisConnection([
            'database' => 0,
            'socketClientFlags' => STREAM_CLIENT_PERSISTENT,
        ]);
        $cache = new RecordingRedisRuntimeCache(['redis' => $source]);
        $originalCache = Craft::$app->getCache();
        Craft::$app->set('cache', $cache);

        try {
            $service = new RuntimeLogStoreService();
            $method = new \ReflectionMethod(RuntimeLogStoreService::class, '_storage');
            $storage = $method->invoke($service, $this->settings([
                'redis' => ['database' => 7],
            ]));

            self::assertInstanceOf(RedisRuntimeLogStorage::class, $storage);
            self::assertNotInstanceOf(GenericCacheRuntimeLogStorage::class, $storage);
            self::assertSame(0, $cache->setCalls);
        } finally {
            Craft::$app->set('cache', $originalCache);
        }
    }

    public function testUnsupportedSelectCannotWriteAndNullDatabaseDoesNotSelect(): void
    {
        $rejected = new RecordingRedisConnection(['database' => 8]);
        $rejected->rejectSelect = true;
        $rejectedStorage = new RedisRuntimeLogStorage($rejected, 'owned-key', 'literal');

        self::assertFalse($rejectedStorage->append([['id' => 'blocked']], 10, 60));
        self::assertSame(1, $rejected->selectAttempts);
        self::assertSame([], $rejected->commands);

        $clusterStyle = new RecordingRedisConnection(['database' => null]);
        $clusterStorage = new RedisRuntimeLogStorage($clusterStyle, 'owned-key', 'none');

        self::assertTrue($clusterStorage->append([['id' => 'accepted']], 10, 60));
        self::assertSame(0, $clusterStyle->selectAttempts);
        self::assertNull($clusterStorage->status()['database']);
        self::assertSame(['MULTI', 'LPUSH', 'LTRIM', 'EXPIRE', 'EXEC'], array_column($clusterStyle->commands, 0));
    }

    public function testRedisDatabaseLiteralsAndEnvironmentValuesResolve(): void
    {
        self::assertSame([
            'valid' => true,
            'database' => 4,
            'mode' => 'literal',
            'error' => null,
        ], RuntimeLogRedisConnectionFactory::resolveDatabase(['database' => 4], 1));

        $name = 'LOGGING_LIBRARY_TEST_RUNTIME_DB';
        $previous = $_SERVER[$name] ?? null;
        $_SERVER[$name] = '6';

        try {
            self::assertSame([
                'valid' => true,
                'database' => 6,
                'mode' => 'environment',
                'error' => null,
            ], RuntimeLogRedisConnectionFactory::resolveDatabase(['database' => '$' . $name], 1));
        } finally {
            if ($previous === null) {
                unset($_SERVER[$name]);
            } else {
                $_SERVER[$name] = $previous;
            }
        }
    }

    #[DataProvider('invalidRedisDatabaseProvider')]
    public function testInvalidExplicitRedisDatabaseValuesFailClosed(mixed $value): void
    {
        $name = 'LOGGING_LIBRARY_TEST_INVALID_RUNTIME_DB';
        $previous = $_SERVER[$name] ?? null;

        if (is_string($value) && str_starts_with($value, 'env:')) {
            $envValue = substr($value, 4);
            if ($envValue === '__MISSING__') {
                unset($_SERVER[$name]);
            } else {
                $_SERVER[$name] = $envValue;
            }
            $value = '$' . $name;
        }

        try {
            $resolved = RuntimeLogRedisConnectionFactory::resolveDatabase(['database' => $value], 3);
            self::assertFalse($resolved['valid']);
            self::assertNull($resolved['database']);
        } finally {
            if ($previous === null) {
                unset($_SERVER[$name]);
            } else {
                $_SERVER[$name] = $previous;
            }
        }
    }

    public static function invalidRedisDatabaseProvider(): array
    {
        return [
            'negative literal' => [-1],
            'numeric string literal' => ['2'],
            'arbitrary string literal' => ['invalid'],
            'missing environment value' => ['env:__MISSING__'],
            'empty environment value' => ['env:'],
            'non-integer environment value' => ['env:abc'],
            'negative environment value' => ['env:-2'],
        ];
    }

    public function testOmittedAndExplicitNullDatabaseHaveDistinctValidSemantics(): void
    {
        self::assertSame([
            'valid' => true,
            'database' => 3,
            'mode' => 'inherited',
            'error' => null,
        ], RuntimeLogRedisConnectionFactory::resolveDatabase([], 3));

        self::assertSame([
            'valid' => true,
            'database' => null,
            'mode' => 'none',
            'error' => null,
        ], RuntimeLogRedisConnectionFactory::resolveDatabase(['database' => null], 3));
    }

    public function testDedicatedConnectionPreservesCraftRedisProperties(): void
    {
        $source = new Connection([
            'hostname' => 'redis.internal',
            'scheme' => 'tls',
            'redirectConnectionString' => 'tcp://redirect.internal:6380',
            'port' => 6380,
            'unixSocket' => '/tmp/redis.sock',
            'username' => 'runtime-user',
            'password' => 'runtime-password',
            'database' => 2,
            'connectionTimeout' => 1.25,
            'dataTimeout' => 2.5,
            'useSSL' => true,
            'contextOptions' => ['ssl' => ['verify_peer' => true]],
            'socketClientFlags' => STREAM_CLIENT_PERSISTENT | STREAM_CLIENT_ASYNC_CONNECT,
            'retries' => 3,
            'retryInterval' => 25000,
            'redisCommands' => ['PING', 'MULTI', 'EXEC'],
        ]);

        $dedicated = RuntimeLogRedisConnectionFactory::create($source, 7);

        foreach ([
            'hostname',
            'scheme',
            'port',
            'unixSocket',
            'username',
            'password',
            'connectionTimeout',
            'dataTimeout',
            'useSSL',
            'contextOptions',
            'retryInterval',
            'redisCommands',
        ] as $property) {
            self::assertSame($source->{$property}, $dedicated->{$property}, $property);
        }

        self::assertNull($dedicated->redirectConnectionString);
        self::assertSame(
            STREAM_CLIENT_ASYNC_CONNECT | STREAM_CLIENT_CONNECT,
            $dedicated->socketClientFlags,
        );
        self::assertSame(0, $dedicated->socketClientFlags & STREAM_CLIENT_PERSISTENT);
        self::assertSame(0, $dedicated->retries);
        self::assertSame(7, $dedicated->database);
        self::assertNotSame($source, $dedicated);
        self::assertSame('tcp://redirect.internal:6380', $source->redirectConnectionString);
        self::assertSame(STREAM_CLIENT_PERSISTENT | STREAM_CLIENT_ASYNC_CONNECT, $source->socketClientFlags);
        self::assertSame(3, $source->retries);
        self::assertSame(2, $source->database);
    }

    #[DataProvider('transactionFailureStageProvider')]
    public function testTransactionStageFailuresNeverReplayOutsideMulti(
        string $failureCommand,
        bool $failAfterExec,
        bool $expectCommitted,
    ): void
    {
        $source = new RecordingRedisConnection([
            'database' => 0,
            'retries' => 5,
        ]);
        $runtime = RuntimeLogRedisConnectionFactory::create($source, 0);
        self::assertSame(5, $source->retries);
        self::assertSame(0, $runtime->retries);

        $connection = new RecordingRedisConnection([
            'database' => $runtime->database,
            'retries' => $runtime->retries,
            'socketClientFlags' => $runtime->socketClientFlags,
        ]);
        $connection->failCommand = $failureCommand;
        $connection->failAfterExec = $failAfterExec;
        $storage = new RedisRuntimeLogStorage($connection, 'owned-key', 'literal');

        self::assertFalse($storage->append([
            ['id' => 'newest'],
            ['id' => 'oldest'],
        ], 2, 60));

        $expectedPrefix = ['MULTI', 'LPUSH', 'LTRIM', 'EXPIRE', 'EXEC'];
        $failureIndex = array_search($failureCommand, $expectedPrefix, true);
        self::assertIsInt($failureIndex);
        self::assertSame(
            array_slice($expectedPrefix, 0, $failureIndex + 1),
            array_column($connection->commands, 0),
        );
        self::assertSame(0, $connection->outsideTransactionWrites);
        self::assertSame(1, $connection->closeCalls);

        if ($expectCommitted) {
            self::assertSame(['{"id":"newest"}', '{"id":"oldest"}'], $connection->list);
            self::assertSame(60, $connection->ttl);
        } else {
            self::assertSame([], $connection->list);
            self::assertNull($connection->ttl);
        }

        self::assertCount(count(array_unique(array_column($connection->commands, 0))), $connection->commands);
        self::assertTrue($source->executeCommand('PING'));
        self::assertSame(5, $source->retries);
        self::assertSame(0, $source->database);
        self::assertSame(0, $source->outsideTransactionWrites);
    }

    public static function transactionFailureStageProvider(): array
    {
        return [
            'MULTI before server acceptance' => ['MULTI', false, false],
            'LPUSH while queued' => ['LPUSH', false, false],
            'LTRIM while queued' => ['LTRIM', false, false],
            'EXPIRE while queued' => ['EXPIRE', false, false],
            'EXEC before server acceptance' => ['EXEC', false, false],
            'EXEC response lost after commit' => ['EXEC', true, true],
        ];
    }

    public function testRuntimeStatusLabelsReportEffectiveBackendAndDatabase(): void
    {
        $controller = Craft::createObject(LogsController::class, ['logs', Craft::$app]);
        $method = new \ReflectionMethod(LogsController::class, '_runtimeStoreLabel');

        self::assertSame('Craft cache', $method->invoke($controller, [
            'backend' => 'generic-cache',
            'available' => true,
        ]));
        self::assertSame('Redis unavailable', $method->invoke($controller, [
            'backend' => 'redis',
            'available' => false,
            'database' => 7,
        ]));
        self::assertSame('Redis (SELECT disabled)', $method->invoke($controller, [
            'backend' => 'redis',
            'available' => true,
            'database' => null,
        ]));
        self::assertSame('Redis database 7', $method->invoke($controller, [
            'backend' => 'redis',
            'available' => true,
            'database' => 7,
        ]));

        $locationMethod = new \ReflectionMethod(LogsController::class, '_runtimeLocationLabel');
        self::assertSame('craft.app.cache', $locationMethod->invoke($controller, [
            'backend' => 'generic-cache',
        ]));
        self::assertSame('Dedicated Redis key', $locationMethod->invoke($controller, [
            'backend' => 'redis',
            'ownedKeys' => ['owned-runtime-key'],
        ]));

        $titleMethod = new \ReflectionMethod(LogsController::class, '_runtimeLocationTitle');
        self::assertSame('', $titleMethod->invoke($controller, [
            'backend' => 'generic-cache',
        ]));
        self::assertSame('owned-runtime-key', $titleMethod->invoke($controller, [
            'backend' => 'redis',
            'ownedKeys' => ['owned-runtime-key'],
        ]));
        self::assertNotSame(
            $locationMethod->invoke($controller, [
                'backend' => 'redis',
                'ownedKeys' => ['owned-runtime-key'],
            ]),
            $titleMethod->invoke($controller, [
                'backend' => 'redis',
                'ownedKeys' => ['owned-runtime-key'],
            ]),
        );

        $connection = new RecordingRedisConnection(['database' => 7]);
        $connection->failCommand = 'LPUSH';
        $storage = new RedisRuntimeLogStorage($connection, 'owned-recovery-key', 'literal');
        $storage->append([['id' => 'dropped']], 10, 60);

        self::assertSame('Redis unavailable', $method->invoke($controller, $storage->status()));
        self::assertSame('Dedicated Redis key', $locationMethod->invoke($controller, $storage->status()));
        self::assertSame('owned-recovery-key', $titleMethod->invoke($controller, $storage->status()));

        $connection->failCommand = null;
        $storage->append([['id' => 'accepted']], 10, 60);

        self::assertSame('Redis database 7', $method->invoke($controller, $storage->status()));
        self::assertSame('Dedicated Redis key', $locationMethod->invoke($controller, $storage->status()));
        self::assertSame('owned-recovery-key', $titleMethod->invoke($controller, $storage->status()));
    }

    public function testGenericReadFailurePerformsNoSetOrDelete(): void
    {
        $cache = new RecordingRuntimeCache();
        $cache->throwGet = true;
        $mutex = new RecordingRuntimeMutex();
        $storage = new GenericCacheRuntimeLogStorage($cache, $mutex);

        self::assertFalse($storage->append([['id' => 'new']], 10, 60));
        self::assertSame(1, $cache->getCalls);
        self::assertSame(0, $cache->setCalls);
        self::assertSame(0, $cache->deleteCalls);
        self::assertSame([0], $mutex->acquireTimeouts);
    }

    public function testGenericSetFailurePreservesPreviousBufferWithoutDelete(): void
    {
        $cache = new RecordingRuntimeCache();
        $cache->set(GenericCacheRuntimeLogStorage::CACHE_KEY, [['id' => 'previous']], 60);
        $cache->setCalls = 0;
        $cache->returnSetFalse = true;
        $storage = new GenericCacheRuntimeLogStorage($cache, new RecordingRuntimeMutex());

        self::assertFalse($storage->append([['id' => 'new']], 10, 60));
        self::assertSame([['id' => 'previous']], $cache->get(GenericCacheRuntimeLogStorage::CACHE_KEY));
        self::assertSame(1, $cache->setCalls);
        self::assertSame(0, $cache->deleteCalls);
    }

    public function testGenericCacheAndMutexFailuresNeverEscape(): void
    {
        $setCache = new RecordingRuntimeCache();
        $setCache->throwSet = true;
        $setStorage = new GenericCacheRuntimeLogStorage($setCache, new RecordingRuntimeMutex());
        self::assertFalse($setStorage->append([['id' => 'new']], 10, 60));

        $deleteCache = new RecordingRuntimeCache();
        $deleteCache->throwDelete = true;
        $deleteStorage = new GenericCacheRuntimeLogStorage($deleteCache, new RecordingRuntimeMutex());
        self::assertFalse($deleteStorage->clear());

        $falseDeleteCache = new RecordingRuntimeCache();
        $falseDeleteCache->returnDeleteFalse = true;
        $falseDeleteStorage = new GenericCacheRuntimeLogStorage($falseDeleteCache, new RecordingRuntimeMutex());
        self::assertFalse($falseDeleteStorage->clear());

        $acquireMutex = new RecordingRuntimeMutex();
        $acquireMutex->throwAcquire = true;
        $acquireStorage = new GenericCacheRuntimeLogStorage(new RecordingRuntimeCache(), $acquireMutex);
        self::assertFalse($acquireStorage->append([['id' => 'new']], 10, 60));

        $releaseMutex = new RecordingRuntimeMutex();
        $releaseMutex->throwRelease = true;
        $releaseStorage = new GenericCacheRuntimeLogStorage(new RecordingRuntimeCache(), $releaseMutex);
        self::assertTrue($releaseStorage->append([['id' => 'new']], 10, 60));
        $releaseMutex->throwRelease = false;
        self::assertTrue($releaseMutex->release(GenericCacheRuntimeLogStorage::LOCK_KEY));
    }

    public function testGenericNonRedisCacheKeepsBoundedRecentBuffer(): void
    {
        $storage = new GenericCacheRuntimeLogStorage(new ArrayCache(), new RecordingRuntimeMutex());

        self::assertTrue($storage->append([
            ['id' => 'three'],
            ['id' => 'two'],
            ['id' => 'one'],
        ], 2, 60));
        self::assertSame([
            ['id' => 'three'],
            ['id' => 'two'],
        ], $storage->read());
    }

    public function testExistingTargetRetainsStartupConfigAndRestartedTargetUsesNewConfig(): void
    {
        $startup = $this->settings(['skipQueueRequests' => false, 'redis' => ['database' => 2]]);
        $existing = new RuntimeLogTarget(['runtimeSettings' => $startup]);

        $changed = $this->settings(['skipQueueRequests' => true, 'redis' => ['database' => 4]]);
        $restarted = new RuntimeLogTarget(['runtimeSettings' => $changed]);

        self::assertFalse($existing->runtimeSettings['skipQueueRequests']);
        self::assertSame(2, $existing->runtimeSettings['redis']['database']);
        self::assertTrue($restarted->runtimeSettings['skipQueueRequests']);
        self::assertSame(4, $restarted->runtimeSettings['redis']['database']);
    }

    private function settings(array $overrides = []): array
    {
        return array_merge(LoggingLibrary::getRuntimeLogStoreConfig(), [
            'enabled' => true,
            'skipConsoleRequests' => false,
            'skipQueueRequests' => false,
        ], $overrides);
    }
}

final class ThrowingRuntimeLogStoreService extends RuntimeLogStoreService
{
    public int $appendCalls = 0;

    public function appendMessages(array $messages, array $settings): void
    {
        $this->appendCalls++;
        throw new \RuntimeException('Injected Runtime Logs failure.');
    }
}

final class RuntimeStorageWebRequest extends \craft\console\Request
{
    public function __construct()
    {
        parent::__construct();
        $this->setIsConsoleRequest(false);
    }

    public function getPathInfo(): string
    {
        return '';
    }

    public function getParam(string $name, mixed $defaultValue = null): mixed
    {
        return $defaultValue;
    }
}

final class RecordingRedisConnection extends Connection
{
    public array $commands = [];
    public array $list = [];
    public bool $failOpen = false;
    public ?string $failCommand = null;
    public bool $failAfterExec = false;
    public bool $rejectSelect = false;
    public int $selectAttempts = 0;
    public int $mutexCalls = 0;
    public int $outsideTransactionWrites = 0;
    public int $closeCalls = 0;
    public ?int $ttl = null;
    private bool $transactionActive = false;
    private array $queuedCommands = [];

    public function open(): void
    {
        if ($this->database !== null) {
            $this->selectAttempts++;
            if ($this->rejectSelect) {
                throw new YiiDbException('SELECT is unsupported.');
            }
        }

        if ($this->failOpen) {
            throw new YiiDbException('Connection failed.');
        }
    }

    public function close(): void
    {
        $this->closeCalls++;
        $this->transactionActive = false;
        $this->queuedCommands = [];
    }

    public function executeCommand(string $name, array $params = [])
    {
        $this->commands[] = [$name, $params];

        if ($this->failCommand === $name && !($name === 'EXEC' && $this->failAfterExec)) {
            throw new YiiDbException('Command failed.');
        }

        return match ($name) {
            'MULTI' => $this->beginTransaction(),
            'LPUSH', 'LTRIM', 'EXPIRE' => $this->queueOrApply($name, $params),
            'EXEC' => $this->executeTransaction(),
            'LRANGE' => [],
            'DEL' => '1',
            default => true,
        };
    }

    private function beginTransaction(): bool
    {
        $this->transactionActive = true;
        $this->queuedCommands = [];

        return true;
    }

    private function queueOrApply(string $name, array $params): string
    {
        if (!$this->transactionActive) {
            $this->outsideTransactionWrites++;
            $this->applyCommand($name, $params);
            return '1';
        }

        $this->queuedCommands[] = [$name, $params];

        return 'QUEUED';
    }

    private function executeTransaction(): array
    {
        foreach ($this->queuedCommands as [$name, $params]) {
            $this->applyCommand($name, $params);
        }

        $this->transactionActive = false;
        $this->queuedCommands = [];

        if ($this->failCommand === 'EXEC' && $this->failAfterExec) {
            throw new YiiDbException('EXEC response was lost after commit.');
        }

        return ['2', true, '1'];
    }

    private function applyCommand(string $name, array $params): void
    {
        if ($name === 'LPUSH') {
            foreach (array_slice($params, 1) as $value) {
                array_unshift($this->list, $value);
            }
            return;
        }

        if ($name === 'LTRIM') {
            $this->list = array_slice($this->list, (int)$params[1], (int)$params[2] + 1);
            return;
        }

        if ($name === 'EXPIRE') {
            $this->ttl = (int)$params[1];
        }
    }
}

final class RecordingRuntimeCache extends ArrayCache
{
    public int $getCalls = 0;
    public int $setCalls = 0;
    public int $deleteCalls = 0;
    public bool $throwGet = false;
    public bool $throwSet = false;
    public bool $throwDelete = false;
    public bool $returnSetFalse = false;
    public bool $returnDeleteFalse = false;

    public function get($key)
    {
        $this->getCalls++;
        if ($this->throwGet) {
            throw new \RuntimeException('Cache get failed.');
        }

        return parent::get($key);
    }

    public function set($key, $value, $duration = null, $dependency = null)
    {
        $this->setCalls++;
        if ($this->throwSet) {
            throw new \RuntimeException('Cache set failed.');
        }
        if ($this->returnSetFalse) {
            return false;
        }

        return parent::set($key, $value, $duration, $dependency);
    }

    public function delete($key)
    {
        $this->deleteCalls++;
        if ($this->throwDelete) {
            throw new \RuntimeException('Cache delete failed.');
        }
        if ($this->returnDeleteFalse) {
            return false;
        }

        return parent::delete($key);
    }
}

final class RecordingRedisRuntimeCache extends RedisCache
{
    public int $getCalls = 0;
    public int $setCalls = 0;
    public int $deleteCalls = 0;

    public function get($key)
    {
        $this->getCalls++;

        return false;
    }

    public function set($key, $value, $duration = null, $dependency = null)
    {
        $this->setCalls++;

        return true;
    }

    public function delete($key)
    {
        $this->deleteCalls++;

        return true;
    }
}

final class RecordingRuntimeMutex extends Mutex
{
    public array $acquireTimeouts = [];
    public bool $throwAcquire = false;
    public bool $throwRelease = false;

    public function acquire($name, $timeout = 0)
    {
        $this->acquireTimeouts[] = $timeout;
        if ($this->throwAcquire) {
            throw new \RuntimeException('Mutex acquire failed.');
        }

        return parent::acquire($name, $timeout);
    }

    public function release($name)
    {
        if ($this->throwRelease) {
            throw new \RuntimeException('Mutex release failed.');
        }

        return parent::release($name);
    }

    protected function acquireLock($name, $timeout = 0): bool
    {
        return true;
    }

    protected function releaseLock($name): bool
    {
        return true;
    }
}
