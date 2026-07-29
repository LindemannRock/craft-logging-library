<?php
/**
 * LindemannRock Logging Library
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Support;

/**
 * Append-only process-safe evidence writer.
 *
 * @internal
 * @since 5.18.0
 */
final class RuntimeLogQueueMetrics
{
    public static function write(string $path, string $runId, string $event, array $context = []): void
    {
        $record = array_merge([
            'runId' => $runId,
            'event' => $event,
            'pid' => getmypid(),
        ], $context);

        file_put_contents(
            $path,
            json_encode($record, JSON_THROW_ON_ERROR) . PHP_EOL,
            FILE_APPEND | LOCK_EX,
        );
    }
}
