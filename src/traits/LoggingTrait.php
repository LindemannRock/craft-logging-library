<?php
/**
 * Logging Library for Craft CMS
 *
 * Reusable logging trait for consistent logging across plugins
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2025 LindemannRock
 */

namespace lindemannrock\logginglibrary\traits;

use Craft;

/**
 * Logging trait for Craft CMS plugins
 * Provides consistent logging to dedicated plugin log files
 */
trait LoggingTrait
{
    /**
     * The plugin handle for logging (must be set by the using class)
     */
    private ?string $_loggingHandle = null;

    /**
     * Set the logging handle for this plugin
     */
    protected function setLoggingHandle(string $handle): void
    {
        $this->_loggingHandle = $handle;
    }

    /**
     * Get the logging handle (auto-detect if not set)
     */
    protected function getLoggingHandle(): string
    {
        if ($this->_loggingHandle === null) {
            // Try to auto-detect from class name or plugin handle
            if (method_exists($this, 'handle') && $this->handle) {
                $this->_loggingHandle = $this->handle;
            } elseif (property_exists($this, 'handle') && $this->handle) {
                $this->_loggingHandle = $this->handle;
            } else {
                // Fallback: derive from class name
                $className = (new \ReflectionClass($this))->getShortName();
                $this->_loggingHandle = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $className));
            }
        }

        return $this->_loggingHandle;
    }

    /**
     * Log an info message
     */
    protected function logInfo(string $message, array $params = []): void
    {
        Craft::info($this->formatMessage($message, $params), $this->getLoggingHandle());
    }

    /**
     * Log a warning message
     */
    protected function logWarning(string $message, array $params = []): void
    {
        Craft::warning($this->formatMessage($message, $params), $this->getLoggingHandle());
    }

    /**
     * Log an error message
     */
    protected function logError(string $message, array $params = []): void
    {
        Craft::error($this->formatMessage($message, $params), $this->getLoggingHandle());
    }

    /**
     * Log a debug message (most verbose level for debugging internal operations)
     */
    protected function logDebug(string $message, array $params = []): void
    {
        Craft::debug($this->formatMessage($message, $params), $this->getLoggingHandle());
    }

    /**
     * Format message with parameters
     */
    private function formatMessage(string $message, array $params = []): string
    {
        if (empty($params)) {
            return $message;
        }

        // Add parameters as JSON if provided
        return $message . ' | ' . json_encode($params, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}