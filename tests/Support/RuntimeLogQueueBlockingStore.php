<?php
/**
 * LindemannRock Logging Library
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Support;

use lindemannrock\logginglibrary\services\RuntimeLogStoreService;

/**
 * Storage canary proving skipped targets perform no backend operation.
 *
 * @internal
 * @since 5.18.0
 */
final class RuntimeLogQueueBlockingStore extends RuntimeLogStoreService
{
    public string $metricsPath = '';
    public string $runId = '';

    public function appendMessages(array $messages, array $settings): void
    {
        RuntimeLogQueueMetrics::write($this->metricsPath, $this->runId, 'storage-called');
    }
}
