<?php
/**
 * LindemannRock Logging Library
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Support;

use yii\mutex\Mutex;

/**
 * Mutex canary proving neither the Redis backend nor default skips acquire Craft mutex.
 *
 * @internal
 * @since 5.18.0
 */
final class RuntimeLogQueueForbiddenMutex extends Mutex
{
    public string $metricsPath = '';
    public string $runId = '';

    protected function acquireLock($name, $timeout = 0): bool
    {
        RuntimeLogQueueMetrics::write($this->metricsPath, $this->runId, 'mutex-called');

        return false;
    }

    protected function releaseLock($name): bool
    {
        return true;
    }
}
