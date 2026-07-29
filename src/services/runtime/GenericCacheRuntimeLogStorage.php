<?php
/**
 * Logging Library for Craft CMS
 *
 * Fail-soft generic Craft cache storage for recent runtime log records.
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\services\runtime;

use yii\caching\CacheInterface;
use yii\mutex\Mutex;

/**
 * Bounded compatibility backend for non-Redis Craft cache components.
 *
 * @internal
 * @since 5.18.0
 */
final class GenericCacheRuntimeLogStorage implements RuntimeLogStorageInterface
{
    public const CACHE_KEY = 'logging-library:runtime-log-store:v1';
    public const LOCK_KEY = 'logging-library:runtime-log-store:v1:lock';

    public function __construct(
        private CacheInterface $cache,
        private Mutex $mutex,
    ) {
    }

    public function append(array $records, int $maxEntries, int $ttl): bool
    {
        if ($records === []) {
            return true;
        }

        try {
            if (!$this->mutex->acquire(self::LOCK_KEY, 0)) {
                return false;
            }
        } catch (\Throwable) {
            return false;
        }

        try {
            try {
                $existing = $this->cache->get(self::CACHE_KEY);
            } catch (\Throwable) {
                return false;
            }

            if ($existing === false) {
                $existing = [];
            } elseif (!is_array($existing)) {
                return false;
            }

            $replacement = array_slice(
                array_merge($records, array_values(array_filter($existing, 'is_array'))),
                0,
                max(1, $maxEntries),
            );

            try {
                return $this->cache->set(self::CACHE_KEY, $replacement, max(1, $ttl));
            } catch (\Throwable) {
                return false;
            }
        } finally {
            try {
                $this->mutex->release(self::LOCK_KEY);
            } catch (\Throwable) {
                // Runtime diagnostics never propagate mutex cleanup failures.
            }
        }
    }

    public function read(): array
    {
        try {
            $records = $this->cache->get(self::CACHE_KEY);
        } catch (\Throwable) {
            return [];
        }

        return is_array($records)
            ? array_values(array_filter($records, 'is_array'))
            : [];
    }

    public function clear(): bool
    {
        try {
            if (!$this->mutex->acquire(self::LOCK_KEY, 0)) {
                return false;
            }
        } catch (\Throwable) {
            return false;
        }

        try {
            try {
                return $this->cache->delete(self::CACHE_KEY);
            } catch (\Throwable) {
                return false;
            }
        } finally {
            try {
                $this->mutex->release(self::LOCK_KEY);
            } catch (\Throwable) {
                // Runtime diagnostics never propagate mutex cleanup failures.
            }
        }
    }

    public function isAvailable(): bool
    {
        return true;
    }

    public function status(): array
    {
        return [
            'backend' => 'generic-cache',
            'available' => true,
            'database' => null,
            'databaseMode' => null,
            'failureReason' => null,
            'ownedKeys' => [self::CACHE_KEY],
        ];
    }
}
