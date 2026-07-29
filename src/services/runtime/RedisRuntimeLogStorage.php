<?php
/**
 * Logging Library for Craft CMS
 *
 * Atomic Redis-list storage for recent runtime log records.
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\services\runtime;

use craft\helpers\Json;
use yii\redis\Connection;

/**
 * Dedicated Redis backend using independently encoded bounded list items.
 *
 * @internal
 * @since 5.18.0
 */
final class RedisRuntimeLogStorage implements RuntimeLogStorageInterface
{
    private bool $_available = true;
    private ?string $_failure = null;

    public function __construct(
        private Connection $connection,
        private string $key,
        private string $databaseMode,
    ) {
    }

    public static function ownedKey(string $applicationId): string
    {
        $namespace = hash('sha256', $applicationId);

        return sprintf('logging-library:{runtime-logs:%s}:v2:records', $namespace);
    }

    public function append(array $records, int $maxEntries, int $ttl): bool
    {
        if ($records === []) {
            return true;
        }

        try {
            $encoded = array_map(
                static fn(array $record): string => Json::encode($record),
                $records,
            );

            $this->connection->open();
            $this->connection->executeCommand('MULTI');
            $this->connection->executeCommand('LPUSH', array_merge([$this->key], array_reverse($encoded)));
            $this->connection->executeCommand('LTRIM', [$this->key, 0, max(0, $maxEntries - 1)]);
            $this->connection->executeCommand('EXPIRE', [$this->key, max(1, $ttl)]);
            $result = $this->connection->executeCommand('EXEC');

            if (!is_array($result) || count($result) !== 3) {
                throw new \RuntimeException('Unexpected Redis transaction result.');
            }

            $this->_markAvailable();
            return true;
        } catch (\Throwable) {
            $this->_markUnavailable('redis-command-failure');
            return false;
        }
    }

    public function read(): array
    {
        try {
            $this->connection->open();
            $items = $this->connection->executeCommand('LRANGE', [$this->key, 0, -1]);
            if (!is_array($items)) {
                throw new \RuntimeException('Unexpected Redis read result.');
            }
            $this->_markAvailable();
        } catch (\Throwable) {
            $this->_markUnavailable('redis-read-failure');
            return [];
        }

        $records = [];
        foreach ($items as $item) {
            if (!is_string($item)) {
                continue;
            }

            try {
                $record = Json::decode($item);
                if (is_array($record)) {
                    $records[] = $record;
                }
            } catch (\Throwable) {
                // Ignore malformed owned list items without hiding valid neighbors.
            }
        }

        return $records;
    }

    public function clear(): bool
    {
        try {
            $this->connection->open();
            $this->connection->executeCommand('DEL', [$this->key]);
            $this->_markAvailable();
            return true;
        } catch (\Throwable) {
            $this->_markUnavailable('redis-delete-failure');
            return false;
        }
    }

    public function isAvailable(): bool
    {
        return $this->_available;
    }

    public function status(): array
    {
        return [
            'backend' => 'redis',
            'available' => $this->_available,
            'database' => $this->databaseMode === 'none' ? null : (int)$this->connection->database,
            'databaseMode' => $this->databaseMode,
            'failureReason' => $this->_failure,
            'ownedKeys' => [$this->key],
        ];
    }

    private function _markAvailable(): void
    {
        $this->_available = true;
        $this->_failure = null;
    }

    private function _markUnavailable(string $failure): void
    {
        $this->_available = false;
        $this->_failure = $failure;

        try {
            $this->connection->close();
        } catch (\Throwable) {
            // Closing a failed diagnostic connection is also fail-soft.
        }
    }
}
