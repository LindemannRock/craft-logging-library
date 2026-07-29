<?php
/**
 * LindemannRock Logging Library
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Support;

use Craft;
use craft\queue\BaseJob;
use lindemannrock\logginglibrary\log\targets\RuntimeLogTarget;
use lindemannrock\logginglibrary\LoggingLibrary;
use lindemannrock\logginglibrary\services\RuntimeLogStoreService;
use lindemannrock\logginglibrary\services\runtime\RedisRuntimeLogStorage;
use yii\log\Logger;
use yii\queue\RetryableJobInterface;
use yii\redis\Connection;

/**
 * Queue job that records independent business, backend, and lifecycle evidence.
 *
 * @internal
 * @since 5.18.0
 */
final class RuntimeLogQueueSafetyJob extends BaseJob implements RetryableJobInterface
{
    public string $runId = '';
    public string $metricsPath = '';
    public string $businessTable = '';
    public string $mode = 'safeguard';
    public string $runtimeSentinel = '';
    public string $unrelatedRedisKey = '';
    public array $redisConnectionConfig = [];

    public function execute($queue): void
    {
        RuntimeLogQueueBusinessState::recordMainStarted($this->businessTable, $this->runId, $queue);
        RuntimeLogQueueMetrics::write($this->metricsPath, $this->runId, 'start');

        $plugin = LoggingLibrary::getInstance();
        $originalStore = $plugin->runtimeLogStore;
        $originalMutex = Craft::$app->getMutex();
        $originalCache = Craft::$app->getCache();
        $originalApplicationId = Craft::$app->id;
        Craft::$app->set('mutex', new RuntimeLogQueueForbiddenMutex([
            'metricsPath' => $this->metricsPath,
            'runId' => $this->runId,
        ]));

        try {
            if ($this->mode === 'safeguard') {
                $plugin->set('runtimeLogStore', new RuntimeLogQueueBlockingStore([
                    'metricsPath' => $this->metricsPath,
                    'runId' => $this->runId,
                ]));
                $this->exportSafeguardCanaries();
            } else {
                Craft::$app->set('cache', new \yii\redis\Cache([
                    'redis' => new Connection($this->redisConnectionConfig),
                ]));
                Craft::$app->id = $this->runId;
                $this->exportRedisSentinel($originalStore);
            }
        } finally {
            Craft::$app->id = $originalApplicationId;
            Craft::$app->set('cache', $originalCache);
            $plugin->set('runtimeLogStore', $originalStore);
            Craft::$app->set('mutex', $originalMutex);
        }

        RuntimeLogQueueBusinessState::recordMainCompleted($this->businessTable, $this->runId);
        RuntimeLogQueueMetrics::write($this->metricsPath, $this->runId, 'complete');
        $followUpId = $queue->push(new RuntimeLogQueueFollowUpJob([
            'runId' => $this->runId,
            'metricsPath' => $this->metricsPath,
            'businessTable' => $this->businessTable,
        ]));
        RuntimeLogQueueMetrics::write($this->metricsPath, $this->runId, 'follow-up-queued', [
            'queueId' => $followUpId,
        ]);
    }

    private function exportSafeguardCanaries(): void
    {
        $this->exportMessage(
            'Console skip canary',
            'logging-library.runtime-console-skip',
            true,
            false,
        );
        RuntimeLogQueueMetrics::write($this->metricsPath, $this->runId, 'console-skip-complete');

        $this->exportMessage(
            'Queue skip canary',
            'craft\queue\QueueLogBehavior::beforeExec',
            false,
            true,
        );
        RuntimeLogQueueMetrics::write($this->metricsPath, $this->runId, 'queue-skip-complete');
    }

    private function exportRedisSentinel(RuntimeLogStoreService $store): void
    {
        $target = $this->exportMessage(
            $this->runtimeSentinel,
            'logging-library.runtime-queue-sentinel',
            false,
            false,
        );
        RuntimeLogQueueMetrics::write($this->metricsPath, $this->runId, 'runtime-target-exported', [
            'targetClass' => $target::class,
            'message' => $this->runtimeSentinel,
        ]);

        $storageProperty = new \ReflectionProperty(RuntimeLogStoreService::class, '_storage');
        $storage = $storageProperty->getValue($store);
        $status = method_exists($storage, 'status') ? $storage->status() : [];
        RuntimeLogQueueMetrics::write($this->metricsPath, $this->runId, 'runtime-backend', [
            'storageClass' => is_object($storage) ? $storage::class : get_debug_type($storage),
            'status' => $status,
        ]);

        if (!$storage instanceof RedisRuntimeLogStorage) {
            return;
        }

        $records = $storage->read();
        $storedMessages = array_column($records, 'message');
        RuntimeLogQueueMetrics::write($this->metricsPath, $this->runId, 'runtime-sentinel-read', [
            'found' => in_array($this->runtimeSentinel, $storedMessages, true),
            'messages' => $storedMessages,
        ]);

        $connectionProperty = new \ReflectionProperty(RedisRuntimeLogStorage::class, 'connection');
        $connection = $connectionProperty->getValue($storage);
        if (!$connection instanceof Connection) {
            return;
        }

        $ownedKey = (string)($status['ownedKeys'][0] ?? '');

        try {
            $connection->executeCommand('SET', [$this->unrelatedRedisKey, 'preserve-me', 'EX', 120]);
            $clearSuccess = $storage->clear();
            $ownedExists = $ownedKey === ''
                ? null
                : (string)$connection->executeCommand('EXISTS', [$ownedKey]);
            $unrelatedValue = $connection->executeCommand('GET', [$this->unrelatedRedisKey]);

            RuntimeLogQueueMetrics::write($this->metricsPath, $this->runId, 'redis-clear', [
                'success' => $clearSuccess,
                'ownedKey' => $ownedKey,
                'ownedExists' => $ownedExists,
                'unrelatedKey' => $this->unrelatedRedisKey,
                'unrelatedValue' => $unrelatedValue,
            ]);
        } finally {
            try {
                if ($ownedKey !== '') {
                    $connection->executeCommand('DEL', [$ownedKey]);
                }
                $connection->executeCommand('DEL', [$this->unrelatedRedisKey]);
            } catch (\Throwable) {
                // The parent test also cleans both unique keys through a fresh connection.
            }
        }
    }

    private function exportMessage(
        string $message,
        string $category,
        bool $skipConsoleRequests,
        bool $skipQueueRequests,
    ): RuntimeLogTarget {
        $target = new RuntimeLogTarget([
            'runtimeSettings' => [
                'enabled' => true,
                'skipConsoleRequests' => $skipConsoleRequests,
                'skipQueueRequests' => $skipQueueRequests,
                'ttl' => 60,
                'maxEntries' => 100,
                'maxMessageBytes' => 8000,
                'maxContextBytes' => 8000,
                'redis' => [],
                'privacy' => ['includeUserId' => false],
            ],
        ]);
        $target->messages = [
            [
                $message,
                Logger::LEVEL_WARNING,
                $category,
                microtime(true),
                [],
                100,
            ],
        ];
        $target->export();

        return $target;
    }

    public function getTtr(): int
    {
        return 30;
    }

    public function canRetry($attempt, $error): bool
    {
        return $attempt < 3;
    }

    protected function defaultDescription(): ?string
    {
        return 'logging-library-runtime-tight-ttr:' . $this->runId;
    }
}
