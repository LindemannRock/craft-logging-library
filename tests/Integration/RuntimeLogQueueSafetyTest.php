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
use craft\db\Query;
use craft\helpers\App;
use craft\queue\Queue;
use lindemannrock\logginglibrary\services\runtime\RedisRuntimeLogStorage;
use lindemannrock\logginglibrary\services\runtime\RuntimeLogRedisConnectionFactory;
use lindemannrock\logginglibrary\tests\Support\RuntimeLogQueueSafetyJob;
use lindemannrock\logginglibrary\tests\TestCase;
use Symfony\Component\Process\Process;
use yii\db\Schema;
use yii\redis\Cache as RedisCache;
use yii\redis\Connection;

/**
 * Proves queue safety through a dedicated channel and isolated child execution.
 *
 * @since 5.18.0
 */
final class RuntimeLogQueueSafetyTest extends TestCase
{
    private array $queueChannels = [];
    private array $queueComponents = [];
    private array $businessTables = [];
    private array $redisKeys = [];
    private ?Process $monitorProcess = null;
    private ?Connection $cleanupRedis = null;

    protected function cleanupExternalState(): void
    {
        if ($this->monitorProcess?->isRunning()) {
            $this->monitorProcess->stop(1);
        }
        $this->monitorProcess = null;

        foreach ($this->queueChannels as $channel) {
            Craft::$app->getDb()->createCommand()
                ->delete('{{%queue}}', ['channel' => $channel])
                ->execute();
        }

        foreach ($this->queueComponents as $componentId) {
            Craft::$app->clear($componentId);
        }

        foreach ($this->businessTables as $table) {
            if (Craft::$app->getDb()->getTableSchema($table, true) !== null) {
                Craft::$app->getDb()->createCommand()->dropTable($table)->execute();
            }
        }

        if ($this->cleanupRedis !== null) {
            try {
                if ($this->redisKeys !== []) {
                    $this->cleanupRedis->executeCommand('DEL', $this->redisKeys);
                }
            } catch (\Throwable) {
                // Best-effort fallback after the job's own finally cleanup.
            }

            try {
                $this->cleanupRedis->close();
            } catch (\Throwable) {
                // Best-effort connection cleanup after a failed assertion.
            }
        }

        parent::cleanupExternalState();
    }

    public function testDefaultQueueSafeguardsAvoidBackendAndMutexWork(): void
    {
        $result = $this->runQueueRegression('safeguard');
        $events = $result['events'];

        $this->assertCommonQueueEvidence($result);
        self::assertSame(1, $this->eventCount($events, 'console-skip-complete'));
        self::assertSame(1, $this->eventCount($events, 'queue-skip-complete'));
        self::assertSame(0, $this->eventCount($events, 'storage-called'));
        self::assertSame(0, $this->eventCount($events, 'mutex-called'));
    }

    public function testRedisQueueStoresSentinelAndUsesObservedAtomicAppend(): void
    {
        $result = $this->runQueueRegression('redis');
        $events = $result['events'];

        $this->assertCommonQueueEvidence($result);
        self::assertSame(1, $this->eventCount($events, 'runtime-target-exported'));

        $backend = $this->firstEvent($events, 'runtime-backend');
        self::assertSame(RedisRuntimeLogStorage::class, $backend['storageClass']);
        self::assertSame('redis', $backend['status']['backend']);
        self::assertTrue($backend['status']['available']);
        self::assertSame([$result['ownedKey']], $backend['status']['ownedKeys']);

        $sentinel = $this->firstEvent($events, 'runtime-sentinel-read');
        self::assertTrue($sentinel['found']);
        self::assertContains($result['runtimeSentinel'], $sentinel['messages']);

        $clear = $this->firstEvent($events, 'redis-clear');
        self::assertTrue($clear['success']);
        self::assertSame($result['ownedKey'], $clear['ownedKey']);
        self::assertSame('0', $clear['ownedExists']);
        self::assertSame($result['unrelatedRedisKey'], $clear['unrelatedKey']);
        self::assertSame('preserve-me', $clear['unrelatedValue']);
        self::assertSame(0, $this->eventCount($events, 'mutex-called'));

        $this->assertObservedRedisCommands(
            $result['monitorOutput'],
            $result['ownedKey'],
            $result['unrelatedRedisKey'],
        );
    }

    /**
     * @return array{
     *     events: array,
     *     business: array,
     *     mainQueueId: string,
     *     followUpQueueId: string,
     *     channel: string,
     *     ownedKey: string,
     *     runtimeSentinel: string,
     *     unrelatedRedisKey: string,
     *     monitorOutput: string
     * }
     */
    private function runQueueRegression(string $mode): array
    {
        $token = bin2hex(random_bytes(8));
        $runId = sprintf('__logginglibrary_runtime_queue_%s_%s', $mode, $token);
        $channel = 'logging-library-runtime-queue-' . $token;
        $componentId = 'loggingLibraryRuntimeQueue' . $token;
        $businessTable = '{{%logginglibrary_runtime_queue_' . $token . '}}';
        $metricsPath = sys_get_temp_dir() . '/' . $runId . '.jsonl';
        $runtimeSentinel = 'runtime-queue-sentinel-' . $token;
        $unrelatedRedisKey = 'logging-library-runtime-queue-unrelated-' . $token;
        $monitorStopKey = 'logging-library-runtime-queue-monitor-stop-' . $token;
        $ownedKey = RedisRuntimeLogStorage::ownedKey($runId);
        $monitorOutput = '';

        $this->queueChannels[] = $channel;
        $this->queueComponents[] = $componentId;
        $this->businessTables[] = $businessTable;
        $this->redisKeys[] = $ownedKey;
        $this->redisKeys[] = $unrelatedRedisKey;
        $this->redisKeys[] = $monitorStopKey;
        $this->trackTempPath($metricsPath);
        $this->createBusinessTable($businessTable, $runId);

        $queue = new Queue([
            'db' => Craft::$app->getDb(),
            'mutex' => Craft::$app->getMutex(),
            'tableName' => '{{%queue}}',
            'channel' => $channel,
        ]);
        Craft::$app->set($componentId, $queue);

        if ($mode === 'redis') {
            $this->monitorProcess = $this->startRedisMonitor($monitorStopKey);
        }

        $mainQueueId = (string)$queue->push(new RuntimeLogQueueSafetyJob([
            'runId' => $runId,
            'metricsPath' => $metricsPath,
            'businessTable' => $businessTable,
            'mode' => $mode,
            'runtimeSentinel' => $runtimeSentinel,
            'unrelatedRedisKey' => $unrelatedRedisKey,
            'redisConnectionConfig' => $this->localRedisConnectionConfig(),
        ]));

        $this->runQueueJob($mainQueueId, $channel, $componentId, $metricsPath, $runId);

        if ($this->monitorProcess !== null) {
            self::assertNotNull($this->cleanupRedis);
            $this->cleanupRedis->executeCommand('SET', [$monitorStopKey, 'stop', 'EX', 60]);
            self::assertSame(0, $this->monitorProcess->wait(), $this->monitorProcess->getErrorOutput());
            $monitorOutput = $this->monitorProcess->getOutput();
            $this->monitorProcess = null;
        }

        $events = $this->readEvents($metricsPath);
        $followUpEvents = $this->eventsNamed($events, 'follow-up-queued');
        self::assertCount(1, $followUpEvents);

        $followUpQueueId = (string)$followUpEvents[0]['queueId'];
        $this->runQueueJob($followUpQueueId, $channel, $componentId, $metricsPath, $runId);

        return [
            'events' => $this->readEvents($metricsPath),
            'business' => $this->businessState($businessTable, $runId),
            'mainQueueId' => $mainQueueId,
            'followUpQueueId' => $followUpQueueId,
            'channel' => $channel,
            'ownedKey' => $ownedKey,
            'runtimeSentinel' => $runtimeSentinel,
            'unrelatedRedisKey' => $unrelatedRedisKey,
            'monitorOutput' => $monitorOutput,
        ];
    }

    private function createBusinessTable(string $table, string $runId): void
    {
        Craft::$app->getDb()->createCommand()->createTable($table, [
            'run_id' => Schema::TYPE_STRING . '(191) NOT NULL',
            'main_started' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'main_completed' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'main_attempt' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'main_failed' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'follow_up_completed' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'follow_up_attempt' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'follow_up_failed' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
        ])->execute();
        Craft::$app->getDb()->createCommand()->insert($table, [
            'run_id' => $runId,
        ])->execute();
    }

    private function runQueueJob(
        string $queueId,
        string $channel,
        string $componentId,
        string $metricsPath,
        string $runId,
    ): void {
        $payload = base64_encode(json_encode([
            'action' => 'run',
            'queueId' => $queueId,
            'channel' => $channel,
            'componentId' => $componentId,
            'metricsPath' => $metricsPath,
            'runId' => $runId,
        ], JSON_THROW_ON_ERROR));
        $process = new Process(
            [PHP_BINARY, dirname(__DIR__) . '/Support/runtime-log-queue-runner.php'],
            Craft::getAlias('@root'),
            ['LOGGING_LIBRARY_RUNTIME_QUEUE_RUNNER' => $payload],
        );
        $process->setTimeout(20);
        $process->run();

        self::assertTrue(
            $process->isSuccessful(),
            sprintf(
                "Queue runner exit code %s.\n%s%s",
                (string)$process->getExitCode(),
                $process->getErrorOutput(),
                $process->getOutput(),
            ),
        );
    }

    private function startRedisMonitor(string $stopKey): Process
    {
        $cache = new RedisCache([
            'redis' => new Connection($this->localRedisConnectionConfig()),
        ]);
        $redis = $cache->redis;
        self::assertInstanceOf(Connection::class, $redis);

        $resolved = RuntimeLogRedisConnectionFactory::resolveDatabase([], $redis->database);
        self::assertTrue($resolved['valid']);
        $connection = RuntimeLogRedisConnectionFactory::create($redis, $resolved['database']);
        $this->cleanupRedis = RuntimeLogRedisConnectionFactory::create($redis, $resolved['database']);
        $config = [
            'hostname' => $connection->hostname,
            'scheme' => $connection->scheme,
            'redirectConnectionString' => $connection->redirectConnectionString,
            'port' => $connection->port,
            'unixSocket' => $connection->unixSocket,
            'username' => $connection->username,
            'password' => $connection->password,
            'database' => $connection->database,
            'connectionTimeout' => $connection->connectionTimeout,
            'dataTimeout' => $connection->dataTimeout,
            'useSSL' => $connection->useSSL,
            'contextOptions' => $connection->contextOptions,
            'socketClientFlags' => $connection->socketClientFlags,
            'retries' => 0,
            'retryInterval' => $connection->retryInterval,
            'redisCommands' => $connection->redisCommands,
            '_stopKey' => $stopKey,
        ];
        $process = new Process(
            [PHP_BINARY, dirname(__DIR__) . '/Support/runtime-log-redis-monitor.php'],
            Craft::getAlias('@root'),
            [
                'LOGGING_LIBRARY_RUNTIME_REDIS_MONITOR' => base64_encode(json_encode($config, JSON_THROW_ON_ERROR)),
            ],
        );
        $process->setTimeout(20);
        $process->start();

        $readyOutput = '';
        $ready = $process->waitUntil(
            static function(string $type, string $output) use (&$readyOutput): bool {
                if ($type === Process::OUT) {
                    $readyOutput .= $output;
                }

                return str_contains($readyOutput, "READY\n");
            },
        );
        self::assertTrue($ready, $process->getErrorOutput());

        return $process;
    }

    private function localRedisConnectionConfig(): array
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

    private function assertCommonQueueEvidence(array $result): void
    {
        $events = $result['events'];
        $business = $result['business'];

        self::assertSame(1, $this->eventCount($events, 'start'));
        self::assertSame(1, $this->eventCount($events, 'complete'));
        self::assertSame(1, $this->eventCount($events, 'follow-up-queued'));
        self::assertSame(1, $this->eventCount($events, 'follow-up-complete'));
        self::assertSame(0, $this->eventCount($events, 'queue-after-error'));
        self::assertSame(0, $this->eventCount($events, 'queue-process-error'));

        self::assertSame(1, (int)$business['main_started']);
        self::assertSame(1, (int)$business['main_completed']);
        self::assertSame(1, (int)$business['main_attempt']);
        self::assertSame(0, (int)$business['main_failed']);
        self::assertSame(1, (int)$business['follow_up_completed']);
        self::assertSame(1, (int)$business['follow_up_attempt']);
        self::assertSame(0, (int)$business['follow_up_failed']);

        $this->assertQueueLifecycle($events, $result['mainQueueId'], 'main');
        $this->assertQueueLifecycle($events, $result['followUpQueueId'], 'follow-up');

        self::assertSame('0', (string)(new Query())
            ->from('{{%queue}}')
            ->where([
                'channel' => $result['channel'],
                'id' => [$result['mainQueueId'], $result['followUpQueueId']],
            ])
            ->count('*', Craft::$app->getDb()));
    }

    private function assertQueueLifecycle(array $events, string $queueId, string $role): void
    {
        foreach (['queue-before-exec', 'queue-after-exec', 'queue-released'] as $eventName) {
            $matches = array_values(array_filter(
                $this->eventsNamed($events, $eventName),
                static fn(array $event): bool => (string)$event['queueId'] === $queueId,
            ));
            self::assertCount(1, $matches);
            self::assertSame($role, $matches[0]['role']);
            self::assertSame(1, $matches[0]['attempt']);
            self::assertFalse($matches[0]['failed']);
        }
    }

    private function assertObservedRedisCommands(
        string $monitorOutput,
        string $ownedKey,
        string $unrelatedKey,
    ): void {
        $commands = $this->parseMonitorCommands($monitorOutput);
        $lpushIndex = null;
        foreach ($commands as $index => $command) {
            if ($command['name'] === 'LPUSH' && str_contains($command['raw'], $ownedKey)) {
                $lpushIndex = $index;
                break;
            }
        }

        self::assertIsInt($lpushIndex, $monitorOutput);
        $client = $commands[$lpushIndex]['client'];
        $multiIndex = null;
        for ($index = $lpushIndex; $index >= 0; $index--) {
            if ($commands[$index]['client'] === $client && $commands[$index]['name'] === 'MULTI') {
                $multiIndex = $index;
                break;
            }
        }
        self::assertIsInt($multiIndex, $monitorOutput);

        $transaction = [];
        $execIndex = null;
        foreach (array_slice($commands, $multiIndex) as $offset => $command) {
            if ($command['client'] !== $client) {
                continue;
            }

            $transaction[] = $command['name'];
            if ($command['name'] === 'EXEC') {
                $execIndex = $multiIndex + $offset;
                break;
            }
        }

        self::assertSame(['MULTI', 'LPUSH', 'LTRIM', 'EXPIRE', 'EXEC'], $transaction);
        self::assertSame([], array_values(array_intersect(
            ['GET', 'LRANGE', 'MGET', 'LPOP', 'RPOP'],
            $transaction,
        )));
        self::assertIsInt($execIndex);

        $readIndex = null;
        $clearIndex = null;
        foreach ($commands as $index => $command) {
            if (
                $index > $execIndex
                && $readIndex === null
                && $command['name'] === 'LRANGE'
                && str_contains($command['raw'], $ownedKey)
            ) {
                $readIndex = $index;
            }
            if (
                $index > $execIndex
                && $clearIndex === null
                && $command['name'] === 'DEL'
                && str_contains($command['raw'], $ownedKey)
            ) {
                $clearIndex = $index;
            }
        }

        self::assertIsInt($readIndex, $monitorOutput);
        self::assertIsInt($clearIndex, $monitorOutput);
        self::assertLessThan($clearIndex, $readIndex);

        $ownedDeletes = array_values(array_filter(
            $commands,
            static fn(array $command): bool => $command['name'] === 'DEL'
                && str_contains($command['raw'], $ownedKey),
        ));
        self::assertNotEmpty($ownedDeletes);
        foreach ($ownedDeletes as $delete) {
            self::assertStringNotContainsString($unrelatedKey, $delete['raw']);
        }
    }

    private function parseMonitorCommands(string $output): array
    {
        $commands = [];
        foreach (preg_split('/\R/', $output) ?: [] as $line) {
            if (!preg_match('/^\+?\d+\.\d+ \[(?<client>[^\]]+)] "(?<command>[^"]+)"(?<args>.*)$/', $line, $matches)) {
                continue;
            }

            $commands[] = [
                'client' => $matches['client'],
                'name' => strtoupper($matches['command']),
                'raw' => $line,
            ];
        }

        return $commands;
    }

    private function businessState(string $table, string $runId): array
    {
        $row = (new Query())
            ->from($table)
            ->where(['run_id' => $runId])
            ->one(Craft::$app->getDb());
        self::assertIsArray($row);

        return $row;
    }

    private function readEvents(string $path): array
    {
        if (!is_file($path)) {
            return [];
        }

        return array_values(array_filter(array_map(
            static fn(string $line): array => json_decode($line, true, 512, JSON_THROW_ON_ERROR),
            file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [],
        )));
    }

    private function eventsNamed(array $events, string $event): array
    {
        return array_values(array_filter(
            $events,
            static fn(array $row): bool => ($row['event'] ?? '') === $event,
        ));
    }

    private function eventCount(array $events, string $event): int
    {
        return count($this->eventsNamed($events, $event));
    }

    private function firstEvent(array $events, string $event): array
    {
        $matches = $this->eventsNamed($events, $event);
        self::assertCount(1, $matches);

        return $matches[0];
    }
}
