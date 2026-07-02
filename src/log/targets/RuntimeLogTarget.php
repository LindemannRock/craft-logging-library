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
    public $exportInterval = 1;

    /**
     * @inheritdoc
     */
    public function export(): void
    {
        if ($this->_isRuntimeRefreshRequest()) {
            return;
        }

        LoggingLibrary::getInstance()?->runtimeLogStore->appendMessages($this->messages, $this->runtimeSettings);
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
