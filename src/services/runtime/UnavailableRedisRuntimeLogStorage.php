<?php
/**
 * Logging Library for Craft CMS
 *
 * Fail-closed Runtime Logs storage state for invalid Redis configuration.
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\services\runtime;

/**
 * Keeps Redis authoritative when its configuration cannot be used safely.
 *
 * @internal
 * @since 5.18.0
 */
final class UnavailableRedisRuntimeLogStorage implements RuntimeLogStorageInterface
{
    public function __construct(
        private string $key,
        private ?int $database,
        private ?string $databaseMode,
        private string $failureReason,
    ) {
    }

    public function append(array $records, int $maxEntries, int $ttl): bool
    {
        return false;
    }

    public function read(): array
    {
        return [];
    }

    public function clear(): bool
    {
        return false;
    }

    public function isAvailable(): bool
    {
        return false;
    }

    public function status(): array
    {
        return [
            'backend' => 'redis',
            'available' => false,
            'database' => $this->database,
            'databaseMode' => $this->databaseMode,
            'failureReason' => $this->failureReason,
            'ownedKeys' => [$this->key],
        ];
    }
}
