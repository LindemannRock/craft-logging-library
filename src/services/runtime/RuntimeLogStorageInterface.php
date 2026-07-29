<?php
/**
 * Logging Library for Craft CMS
 *
 * Internal storage contract for recent runtime log records.
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\services\runtime;

/**
 * Separates Runtime Logs record handling from backend-specific persistence.
 *
 * @internal
 * @since 5.18.0
 */
interface RuntimeLogStorageInterface
{
    /**
     * Append a normalized newest-first record batch.
     */
    public function append(array $records, int $maxEntries, int $ttl): bool;

    /**
     * Read all stored records in newest-first storage order.
     */
    public function read(): array;

    /**
     * Clear only keys owned by this storage backend.
     */
    public function clear(): bool;

    /**
     * Whether the last backend operation succeeded.
     */
    public function isAvailable(): bool;

    /**
     * Return backend details for the Runtime Logs status UI.
     *
     * @return array{backend: string, available: bool, database: ?int, databaseMode: ?string, failureReason: ?string, ownedKeys: array}
     */
    public function status(): array;
}
