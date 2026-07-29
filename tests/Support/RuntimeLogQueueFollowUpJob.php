<?php
/**
 * LindemannRock Logging Library
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Support;

use craft\queue\BaseJob;

/**
 * Follow-up side effect used to detect duplicate queue execution.
 *
 * @internal
 * @since 5.18.0
 */
final class RuntimeLogQueueFollowUpJob extends BaseJob
{
    public string $runId = '';
    public string $metricsPath = '';
    public string $businessTable = '';

    public function execute($queue): void
    {
        RuntimeLogQueueBusinessState::recordFollowUp($this->businessTable, $this->runId, $queue);
        RuntimeLogQueueMetrics::write($this->metricsPath, $this->runId, 'follow-up-complete');
    }

    protected function defaultDescription(): ?string
    {
        return 'logging-library-runtime-tight-ttr-follow-up:' . $this->runId;
    }
}
