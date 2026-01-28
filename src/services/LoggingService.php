<?php
/**
 * Logging Library for Craft CMS
 *
 * Service providing logging utilities and helper methods
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2025 LindemannRock
 */

namespace lindemannrock\logginglibrary\services;

use Craft;
use craft\base\Component;
use lindemannrock\logginglibrary\LoggingLibrary;

/**
 * Logging Service
 * Provides utilities for managing plugin logs
 *
 * @since 1.0.0
 */
class LoggingService extends Component
{
    /**
     * Log a message for a specific plugin
     *
     * @param string $message Message to log
     * @param string $level Log level (debug, info, warning, error)
     * @param string|null $pluginHandle Plugin handle
     * @param array $context Additional context
     * @since 1.0.0
     */
    public static function log(string $message, string $level = 'info', string $pluginHandle = null, array $context = []): void
    {
        if (!$pluginHandle) {
            throw new \InvalidArgumentException('Plugin handle is required for logging');
        }

        // Format message with context if provided
        $formattedMessage = $message;
        if (!empty($context)) {
            $formattedMessage .= ' | ' . json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        // Use appropriate Craft logging method based on level
        match ($level) {
            'debug' => Craft::debug($formattedMessage, $pluginHandle),
            'info' => Craft::info($formattedMessage, $pluginHandle),
            'warning' => Craft::warning($formattedMessage, $pluginHandle),
            'error' => Craft::error($formattedMessage, $pluginHandle),
            default => Craft::info($formattedMessage, $pluginHandle)
        };
    }

    /**
     * Get log statistics for a plugin
     *
     * @param string $pluginHandle Plugin handle
     * @return array Log statistics
     * @since 1.0.0
     */
    public static function getLogStats(string $pluginHandle): array
    {
        $logFiles = LoggingLibrary::getLogFiles($pluginHandle);
        $stats = [
            'totalFiles' => count($logFiles),
            'totalSize' => 0,
            'oldestDate' => null,
            'newestDate' => null,
            'levels' => [
                'error' => 0,
                'warning' => 0,
                'info' => 0,
                'debug' => 0,
            ],
        ];

        foreach ($logFiles as $file) {
            $stats['totalSize'] += $file['size'];

            if ($stats['oldestDate'] === null || $file['date'] < $stats['oldestDate']) {
                $stats['oldestDate'] = $file['date'];
            }

            if ($stats['newestDate'] === null || $file['date'] > $stats['newestDate']) {
                $stats['newestDate'] = $file['date'];
            }

            // Count log levels in this file
            $levelCounts = self::_countLogLevelsInFile($file['path']);
            foreach ($levelCounts as $level => $count) {
                $stats['levels'][$level] = ($stats['levels'][$level] ?? 0) + $count;
            }
        }

        $stats['formattedSize'] = Craft::$app->getFormatter()->asShortSize($stats['totalSize']);

        return $stats;
    }

    /**
     * Clean up old log files for a plugin
     *
     * @param string $pluginHandle Plugin handle
     * @param int $retentionDays Days to retain logs
     * @return array List of deleted files
     * @since 1.0.0
     */
    public static function cleanupOldLogs(string $pluginHandle, int $retentionDays = 30): array
    {
        $logPath = Craft::$app->getPath()->getLogPath();
        $pattern = $logPath . '/' . $pluginHandle . '-*.log';
        $cutoffDate = (new \DateTime())->modify("-{$retentionDays} days");

        $deleted = [];
        $logFiles = glob($pattern);

        foreach ($logFiles as $file) {
            if (preg_match('/' . preg_quote($pluginHandle, '/') . '-(\d{4}-\d{2}-\d{2})\.log$/', basename($file), $matches)) {
                $fileDate = new \DateTime($matches[1]);

                if ($fileDate < $cutoffDate) {
                    if (unlink($file)) {
                        $deleted[] = basename($file);
                    }
                }
            }
        }

        return $deleted;
    }

    /**
     * Get recent log entries for a plugin (useful for dashboards)
     *
     * @param string $pluginHandle Plugin handle
     * @param int $limit Maximum entries to return
     * @param string $level Filter by log level
     * @return array Log entries
     * @since 1.0.0
     */
    public static function getRecentEntries(string $pluginHandle, int $limit = 10, string $level = 'all'): array
    {
        $logFiles = LoggingLibrary::getLogFiles($pluginHandle);
        if (empty($logFiles)) {
            return [];
        }

        // Get the most recent log file
        $latestFile = $logFiles[0];
        $entries = [];

        $logQuery = LoggingLibrary::getInstance()->logCache->getLogs($latestFile['path']);
        $logs = $logQuery->all();

        // Process entries in reverse order (newest first)
        $logs = array_reverse($logs);
        $lineNumber = count($logs);

        foreach ($logs as $log) {
            if (count($entries) >= $limit) {
                break;
            }

            $logLevel = $log['level'] ?? 'unknown';
            if ($level !== 'all' && $logLevel !== $level) {
                $lineNumber--;
                continue;
            }

            $entries[] = [
                'timestamp' => $log['timestamp'] ?? '',
                'user' => $log['user'] ?? '',
                'level' => $logLevel,
                'category' => $log['category'] ?? '',
                'message' => $log['message'] ?? '',
                'context' => $log['context'] ?? '',
                'lineNumber' => $lineNumber--,
                'raw' => $log['raw'] ?? '',
            ];
        }

        return $entries;
    }

    /**
     * Check if logging is configured for a plugin
     *
     * @param string $pluginHandle Plugin handle
     * @return bool Whether logging is configured
     * @since 1.0.0
     */
    public static function isConfigured(string $pluginHandle): bool
    {
        return LoggingLibrary::getConfig($pluginHandle) !== null;
    }

    /**
     * Get the effective log level for a plugin
     *
     * @param string $pluginHandle Plugin handle
     * @return string|null Log level or null if not configured
     * @since 1.0.0
     */
    public static function getLogLevel(string $pluginHandle): ?string
    {
        $config = LoggingLibrary::getConfig($pluginHandle);
        return $config['logLevel'] ?? null;
    }

    /**
     * Count log levels in a specific file
     */
    private static function _countLogLevelsInFile(string $filePath): array
    {
        $counts = [
            'error' => 0,
            'warning' => 0,
            'info' => 0,
            'debug' => 0,
        ];

        if (!file_exists($filePath)) {
            return $counts;
        }

        $logQuery = LoggingLibrary::getInstance()->logCache->getLogs($filePath);
        $logs = $logQuery->all();
        foreach ($logs as $log) {
            $level = $log['level'] ?? 'unknown';
            if (isset($counts[$level])) {
                $counts[$level]++;
            }
        }
        return $counts;
    }
}
