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
use craft\helpers\App;
use lindemannrock\logginglibrary\services\RuntimeLogStoreService;
use lindemannrock\logginglibrary\services\runtime\GenericCacheRuntimeLogStorage;
use lindemannrock\logginglibrary\services\runtime\RedisRuntimeLogStorage;
use lindemannrock\logginglibrary\services\runtime\RuntimeLogRedisConnectionFactory;
use lindemannrock\logginglibrary\tests\TestCase;
use Symfony\Component\Process\Process;
use yii\log\Logger;
use yii\redis\Cache as RedisCache;
use yii\redis\Connection;

/**
 * Verifies Runtime Logs list semantics against the local Redis service.
 *
 * @since 5.18.0
 */
final class RuntimeLogRedisIntegrationTest extends TestCase
{
    private ?Connection $connection = null;
    private ?RedisRuntimeLogStorage $storage = null;
    private string $key = '';
    private string $sentinelKey = '';

    protected function setUp(): void
    {
        parent::setUp();

        $namespace = bin2hex(random_bytes(12));
        $this->key = sprintf('logging-library:{runtime-logs-test:%s}:v2:records', $namespace);
        $this->sentinelKey = sprintf('logging-library-runtime-logs-sentinel:%s', $namespace);
        $this->connection = new Connection([
            'hostname' => App::env('REDIS_HOST') ?: 'redis',
            'port' => App::env('REDIS_PORT') ?: 6379,
            'username' => App::env('REDIS_USERNAME') ?: null,
            'password' => App::env('REDIS_PASSWORD') ?: null,
            'database' => (int)(App::env('REDIS_DATABASE') ?: 0),
            'connectionTimeout' => 1,
            'dataTimeout' => 1,
        ]);
        $this->storage = new RedisRuntimeLogStorage($this->connection, $this->key, 'literal');

        try {
            $this->connection->executeCommand('DEL', [$this->key, $this->sentinelKey]);
        } catch (\Throwable $e) {
            self::markTestSkipped('Local Redis is unavailable: ' . $e->getMessage());
        }
    }

    protected function cleanupExternalState(): void
    {
        if ($this->connection !== null && $this->key !== '') {
            try {
                $this->connection->executeCommand('DEL', [$this->key, $this->sentinelKey]);
            } catch (\Throwable) {
                // Best-effort cleanup after a failed Redis integration assertion.
            }

            try {
                $this->connection->close();
            } catch (\Throwable) {
                // Best-effort cleanup after a failed Redis integration assertion.
            }
        }

        parent::cleanupExternalState();
    }

    public function testRealRedisListRetainsOrderingBoundingAndTtl(): void
    {
        self::assertNotNull($this->storage);
        self::assertNotNull($this->connection);

        self::assertTrue($this->storage->append([
            ['id' => 'newest'],
            ['id' => 'middle'],
            ['id' => 'oldest'],
        ], 3, 90));

        self::assertSame(['newest', 'middle', 'oldest'], array_column($this->storage->read(), 'id'));
        $ttl = (int)$this->connection->executeCommand('TTL', [$this->key]);
        self::assertGreaterThan(0, $ttl);
        self::assertLessThanOrEqual(90, $ttl);

        self::assertTrue($this->storage->append([
            ['id' => 'latest'],
            ['id' => 'next-latest'],
        ], 3, 90));

        self::assertSame(['latest', 'next-latest', 'newest'], array_column($this->storage->read(), 'id'));
        self::assertSame('3', $this->connection->executeCommand('LLEN', [$this->key]));
    }

    public function testConcurrentRealRedisWritersPreserveAcceptedBatches(): void
    {
        $writers = 4;
        $batchesPerWriter = 5;
        $processes = [];
        self::assertNotNull($this->connection);

        for ($writer = 0; $writer < $writers; $writer++) {
            $payload = base64_encode(json_encode([
                'connection' => [
                    'hostname' => $this->connection->hostname,
                    'port' => $this->connection->port,
                    'username' => $this->connection->username,
                    'password' => $this->connection->password,
                    'database' => $this->connection->database,
                    'connectionTimeout' => $this->connection->connectionTimeout,
                    'dataTimeout' => $this->connection->dataTimeout,
                ],
                'key' => $this->key,
                'writer' => $writer,
                'batches' => $batchesPerWriter,
            ], JSON_THROW_ON_ERROR));
            $process = new Process(
                [PHP_BINARY, dirname(__DIR__) . '/Support/runtime-log-redis-writer.php'],
                null,
                ['LOGGING_LIBRARY_RUNTIME_WRITER' => $payload],
            );
            $process->start();
            $processes[] = $process;
        }

        foreach ($processes as $process) {
            self::assertSame(0, $process->wait(), $process->getErrorOutput() . $process->getOutput());
        }

        self::assertNotNull($this->storage);
        self::assertNotNull($this->connection);

        $records = $this->storage->read();
        self::assertCount($writers * $batchesPerWriter * 2, $records);
        self::assertCount(count($records), array_unique(array_column($records, 'id')));

        $ids = array_column($records, 'id');
        for ($writer = 0; $writer < $writers; $writer++) {
            for ($batch = 0; $batch < $batchesPerWriter; $batch++) {
                $newIndex = array_search(sprintf('writer-%d-batch-%d-new', $writer, $batch), $ids, true);
                $oldIndex = array_search(sprintf('writer-%d-batch-%d-old', $writer, $batch), $ids, true);
                self::assertIsInt($newIndex);
                self::assertSame($newIndex + 1, $oldIndex);
            }
        }

        $ttl = (int)$this->connection->executeCommand('TTL', [$this->key]);
        self::assertGreaterThan(0, $ttl);
        self::assertLessThanOrEqual(120, $ttl);
    }

    public function testClearUsesOwnedExactKeyAndPreservesSentinel(): void
    {
        self::assertNotNull($this->storage);
        self::assertNotNull($this->connection);

        $this->connection->executeCommand('SET', [$this->sentinelKey, 'preserve-me', 'EX', 120]);
        self::assertTrue($this->storage->append([['id' => 'owned']], 10, 120));

        self::assertTrue($this->storage->clear());
        self::assertSame('0', $this->connection->executeCommand('EXISTS', [$this->key]));
        self::assertSame('preserve-me', $this->connection->executeCommand('GET', [$this->sentinelKey]));
    }

    public function testProductionOwnedKeyIsVersionedNamespacedAndClusterSlotSafe(): void
    {
        $first = RedisRuntimeLogStorage::ownedKey('first-app');
        $same = RedisRuntimeLogStorage::ownedKey('first-app');
        $other = RedisRuntimeLogStorage::ownedKey('other-app');

        self::assertSame($first, $same);
        self::assertNotSame($first, $other);
        self::assertMatchesRegularExpression('/^logging-library:\\{runtime-logs:[a-f0-9]{64}\\}:v2:records$/', $first);
    }

    public function testPersistentCraftTransportRemainsIsolatedAfterRuntimeFailureAndClose(): void
    {
        $source = new Connection(array_merge($this->connectionConfig(), [
            'socketClientFlags' => STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT,
            'retries' => 4,
        ]));
        $runtime = null;
        $laterRuntime = null;
        $runtimeKey = $this->key . ':persistent-isolation';

        try {
            $source->open();
            $sourceId = (string)$source->executeCommand('CLIENT ID');
            $sourceDatabase = $this->clientDatabase($source);
            $runtimeDatabase = $sourceDatabase === 0 ? 1 : 0;
            $genericBefore = $source->executeCommand('GET', [GenericCacheRuntimeLogStorage::CACHE_KEY]);

            $runtime = RuntimeLogRedisConnectionFactory::create($source, $runtimeDatabase);
            self::assertSame(0, $runtime->socketClientFlags & STREAM_CLIENT_PERSISTENT);
            self::assertSame(0, $runtime->retries);
            self::assertInstanceOf(Connection::class, $runtime);
            self::assertNotSame($source, $runtime);

            $storage = new RedisRuntimeLogStorage($runtime, $runtimeKey, 'inherited');
            $runtime->open();
            $runtimeId = (string)$runtime->executeCommand('CLIENT ID');

            self::assertNotSame($sourceId, $runtimeId);
            self::assertSame($runtimeDatabase, $this->clientDatabase($runtime));
            self::assertSame($sourceDatabase, $this->clientDatabase($source));
            self::assertSame('1', (string)$source->executeCommand('CLIENT KILL', ['ID', $runtimeId]));
            self::assertFalse($storage->append([['id' => 'dropped-after-kill']], 10, 60));
            self::assertFalse($storage->isAvailable());

            self::assertNotFalse($source->executeCommand('PING'));
            self::assertSame($sourceId, (string)$source->executeCommand('CLIENT ID'));
            self::assertSame($sourceDatabase, $this->clientDatabase($source));
            self::assertNotFalse($source->executeCommand('MULTI'));
            self::assertNotFalse($source->executeCommand('DISCARD'));

            self::assertTrue($storage->append([['id' => 'accepted-after-reconnect']], 10, 60));
            self::assertSame(['accepted-after-reconnect'], array_column($storage->read(), 'id'));
            self::assertSame($genericBefore, $source->executeCommand('GET', [GenericCacheRuntimeLogStorage::CACHE_KEY]));
            self::assertSame('0', (string)$source->executeCommand('EXISTS', [$runtimeKey]));

            $laterRuntime = RuntimeLogRedisConnectionFactory::create($source, $runtimeDatabase);
            $laterStorage = new RedisRuntimeLogStorage($laterRuntime, $runtimeKey, 'inherited');
            self::assertSame(['accepted-after-reconnect'], array_column($laterStorage->read(), 'id'));
            self::assertTrue($laterStorage->clear());
            self::assertSame([], $laterStorage->read());
            self::assertSame($genericBefore, $source->executeCommand('GET', [GenericCacheRuntimeLogStorage::CACHE_KEY]));

            $runtime->close();

            self::assertNotFalse($source->executeCommand('PING'));
            self::assertSame($sourceId, (string)$source->executeCommand('CLIENT ID'));
            self::assertSame($sourceDatabase, $this->clientDatabase($source));
        } finally {
            try {
                $source->executeCommand('DEL', [$runtimeKey]);
            } catch (\Throwable) {
                // Best-effort cleanup after a failed isolation assertion.
            }

            try {
                $runtime?->executeCommand('DEL', [$runtimeKey]);
            } catch (\Throwable) {
                // Best-effort cleanup from the independently selected database.
            }

            try {
                $runtime?->close();
            } catch (\Throwable) {
                // Best-effort cleanup after a failed isolation assertion.
            }

            try {
                $laterRuntime?->close();
            } catch (\Throwable) {
                // Best-effort cleanup after a failed isolation assertion.
            }

            try {
                $source->close();
            } catch (\Throwable) {
                // Best-effort cleanup after a failed isolation assertion.
            }
        }
    }

    public function testRejectedSelectNeverWritesDatabaseZeroOrGenericCache(): void
    {
        $source = new Connection($this->connectionConfig());
        $cache = new RedisCache(['redis' => $source]);
        $originalCache = Craft::$app->getCache();
        $originalApplicationId = Craft::$app->id;
        $applicationId = '__logginglibrary_rejected_select_' . bin2hex(random_bytes(8));
        $ownedKey = RedisRuntimeLogStorage::ownedKey($applicationId);

        try {
            $source->open();
            $genericBefore = $source->executeCommand('GET', [GenericCacheRuntimeLogStorage::CACHE_KEY]);
            self::assertSame('0', (string)$source->executeCommand('EXISTS', [$ownedKey]));

            Craft::$app->id = $applicationId;
            Craft::$app->set('cache', $cache);

            $service = new RuntimeLogStoreService();
            $service->appendMessages([
                ['Rejected SELECT batch', Logger::LEVEL_WARNING, 'runtime-test', microtime(true), [], 100],
            ], [
                'enabled' => true,
                'ttl' => 60,
                'maxEntries' => 10,
                'maxMessageBytes' => 8000,
                'maxContextBytes' => 8000,
                'redis' => ['database' => 999999],
                'privacy' => ['includeUserId' => false],
            ]);

            $storage = (new \ReflectionProperty(RuntimeLogStoreService::class, '_storage'))->getValue($service);
            self::assertInstanceOf(RedisRuntimeLogStorage::class, $storage);
            self::assertFalse($storage->isAvailable());
            self::assertSame('redis-command-failure', $storage->status()['failureReason']);
            self::assertSame($genericBefore, $source->executeCommand('GET', [GenericCacheRuntimeLogStorage::CACHE_KEY]));
            self::assertSame('0', (string)$source->executeCommand('EXISTS', [$ownedKey]));
            self::assertSame((int)$this->connectionConfig()['database'], $this->clientDatabase($source));
            self::assertNotFalse($source->executeCommand('PING'));
        } finally {
            Craft::$app->set('cache', $originalCache);
            Craft::$app->id = $originalApplicationId;

            try {
                $source->executeCommand('DEL', [$ownedKey]);
            } catch (\Throwable) {
                // Best-effort cleanup after a failed SELECT assertion.
            }

            try {
                $source->close();
            } catch (\Throwable) {
                // Best-effort cleanup after a failed SELECT assertion.
            }
        }
    }

    private function connectionConfig(): array
    {
        return [
            'hostname' => App::env('REDIS_HOST') ?: 'redis',
            'port' => App::env('REDIS_PORT') ?: 6379,
            'username' => App::env('REDIS_USERNAME') ?: null,
            'password' => App::env('REDIS_PASSWORD') ?: null,
            'database' => (int)(App::env('REDIS_DATABASE') ?: 0),
            'connectionTimeout' => 1,
            'dataTimeout' => 1,
        ];
    }

    private function clientDatabase(Connection $connection): int
    {
        $info = (string)$connection->executeCommand('CLIENT INFO');
        self::assertSame(1, preg_match('/(?:^|\\s)db=(\\d+)(?:\\s|$)/', $info, $matches));

        return (int)$matches[1];
    }
}
