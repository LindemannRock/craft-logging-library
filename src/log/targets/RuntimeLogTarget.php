<?php
/**
 * Logging Library for Craft CMS
 *
 * Yii log target that writes recent runtime logs to Craft cache.
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\log\targets;

use Craft;
use lindemannrock\logginglibrary\LoggingLibrary;
use yii\log\Target;

/**
 * Cache-backed runtime log target.
 *
 * @since 5.14.0
 */
class RuntimeLogTarget extends Target
{
    private const QUEUE_EXECUTION_ROUTES = [
        'queue/run',
        'queue/listen',
        'queue/exec',
        'queue/retry',
        'queue/retry-all',
    ];

    /**
     * @var array Runtime log store settings.
     */
    public array $runtimeSettings = [];

    /**
     * @inheritdoc
     */
    public $logVars = [];

    /**
     * @inheritdoc
     */
    public $exportInterval = 20;

    /**
     * @inheritdoc
     */
    public function export(): void
    {
        try {
            if ($this->_shouldSkipCapture()) {
                // Queue detection is intentionally batch-level: any queue signal skips this entire buffered export batch.
                return;
            }

            LoggingLibrary::getInstance()?->runtimeLogStore->appendMessages($this->messages, $this->runtimeSettings);
        } catch (\Throwable) {
            // Runtime logging must never break the request, console command, or queue job.
        }
    }

    /**
     * Skip beta capture in conservative execution contexts.
     */
    private function _shouldSkipCapture(): bool
    {
        try {
            $request = Craft::$app->getRequest();

            if (($this->runtimeSettings['skipConsoleRequests'] ?? true) && $request->getIsConsoleRequest()) {
                return true;
            }

            if (($this->runtimeSettings['skipQueueRequests'] ?? true) && $this->_isQueueExecution()) {
                return true;
            }

            return $this->_isRuntimeRefreshRequest();
        } catch (\Throwable) {
            return true;
        }
    }

    /**
     * Detect active Craft queue execution without instantiating the queue.
     */
    private function _isQueueExecution(): bool
    {
        $requestedRoute = trim((string)(Craft::$app->requestedRoute ?? ''), '/');
        if (in_array($requestedRoute, self::QUEUE_EXECUTION_ROUTES, true)) {
            return true;
        }

        try {
            if (Craft::$app->has('queue', true)) {
                $queue = Craft::$app->get('queue');
                if (method_exists($queue, 'getWorkerPid') && $queue->getWorkerPid() !== null) {
                    return true;
                }
            }
        } catch (\Throwable) {
            // Fall through to the narrow message-category check.
        }

        foreach ($this->messages as $message) {
            $category = (string)($message[2] ?? '');
            if (preg_match('/^craft\\\\queue\\\\QueueLogBehavior::(?:beforeExec|afterExec|afterError)$/', $category)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Avoid poll-loop noise from the runtime viewer's own AJAX endpoint.
     */
    private function _isRuntimeRefreshRequest(): bool
    {
        try {
            $request = Craft::$app->getRequest();
            if ($request->getIsConsoleRequest()) {
                return false;
            }

            $pathInfo = method_exists($request, 'getPathInfo') ? (string)$request->getPathInfo() : '';
            $actionParam = (string)$request->getParam('action', '');

            return str_contains($pathInfo, 'logging-library/logs/runtime-data')
                || $actionParam === 'logging-library/logs/runtime-data';
        } catch (\Throwable) {
            return false;
        }
    }
}
