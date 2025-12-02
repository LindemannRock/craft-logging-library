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
use lindemannrock\logginglibrary\LoggingModule;

/**
 * Logging Service
 * Provides utilities for managing plugin logs
 */
class LoggingService extends Component
{
    /**
     * Log a message for a specific plugin
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
     */
    public static function getLogStats(string $pluginHandle): array
    {
        $logFiles = LoggingModule::getLogFiles($pluginHandle);
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
     */
    public static function getRecentEntries(string $pluginHandle, int $limit = 10, string $level = 'all'): array
    {
        $logFiles = LoggingModule::getLogFiles($pluginHandle);
        if (empty($logFiles)) {
            return [];
        }

        // Get the most recent log file
        $latestFile = $logFiles[0];
        $entries = [];

        if (($handle = fopen($latestFile['path'], 'r')) !== false) {
            $lines = [];
            while (($line = fgets($handle)) !== false) {
                $lines[] = trim($line);
            }
            fclose($handle);

            // Process lines in reverse order (newest first)
            $lines = array_reverse($lines);
            $lineNumber = count($lines);

            foreach ($lines as $line) {
                if (count($entries) >= $limit) {
                    break;
                }

                $entry = self::_parseLogEntry($line, $lineNumber--);
                if ($entry && ($level === 'all' || $entry['level'] === $level)) {
                    $entries[] = $entry;
                }
            }
        }

        return $entries;
    }

    /**
     * Check if logging is configured for a plugin
     */
    public static function isConfigured(string $pluginHandle): bool
    {
        return LoggingModule::getConfig($pluginHandle) !== null;
    }

    /**
     * Get the effective log level for a plugin
     */
    public static function getLogLevel(string $pluginHandle): ?string
    {
        $config = LoggingModule::getConfig($pluginHandle);
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

        if (!file_exists($filePath) || !($handle = fopen($filePath, 'r'))) {
            return $counts;
        }

        while (($line = fgets($handle)) !== false) {
            $entry = self::_parseLogEntry(trim($line));
            if ($entry && isset($counts[$entry['level']])) {
                $counts[$entry['level']]++;
            }
        }

        fclose($handle);
        return $counts;
    }

    /**
     * Parse a log entry line (shared with LogsController)
     */
    private static function _parseLogEntry(string $line, int $lineNumber = 0): ?array
    {
        // Skip empty lines
        if (empty($line)) {
            return null;
        }

        // Parse log format: timestamp [user:id][level][category] message | context
        if (preg_match('/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\s+\[(.*?)\]\[(.*?)\]\[(.*?)\]\s+(.*?)(?:\s+\|\s+(.*))?$/', $line, $matches)) {
            return [
                'timestamp' => $matches[1],
                'user' => $matches[2],
                'level' => strtolower(str_replace('.', '', $matches[3])),
                'category' => $matches[4],
                'message' => $matches[5],
                'context' => isset($matches[6]) ? $matches[6] : '',
                'lineNumber' => $lineNumber,
                'raw' => $line,
            ];
        }

        // Fallback for non-standard format
        return [
            'timestamp' => '',
            'user' => '',
            'level' => 'unknown',
            'category' => '',
            'message' => $line,
            'context' => '',
            'lineNumber' => $lineNumber,
            'raw' => $line,
        ];
    }
}
