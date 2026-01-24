<?php
/**
 * Logging Library for Craft CMS
 *
 * Log cache service for high-performance log parsing and querying
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2025 LindemannRock
 */

namespace lindemannrock\logginglibrary\services;

use Craft;
use craft\base\Component;
use craft\helpers\FileHelper;
use lindemannrock\base\helpers\PluginHelper;
use lindemannrock\logginglibrary\LoggingLibrary;
use yii2mod\query\ArrayQuery;

/**
 * Log Cache Service
 * Provides cached, queryable access to parsed log files
 *
 * @since 1.0.0
 */
class LogCacheService extends Component
{
    /**
     * Get logs from file as ArrayQuery (with caching)
     *
     * @param string $logFile Full path to log file
     * @return ArrayQuery
     * @since 1.0.0
     */
    public function getLogs(string $logFile): ArrayQuery
    {
        $cacheKey = $this->_getCacheKey($logFile);
        $cacheFile = $this->_getCacheFile($cacheKey);

        // Check if cache exists and is valid
        if (file_exists($cacheFile)) {
            $data = @file_get_contents($cacheFile);
            if ($data) {
                $cache = @unserialize($data);
                if ($cache && is_array($cache)) {
                    return (new ArrayQuery())->from($cache);
                }
            }
        }

        // Parse file and cache
        $logs = $this->_parseLogFile($logFile);
        $this->_cacheData($cacheKey, $logs);

        return (new ArrayQuery())->from($logs);
    }

    /**
     * Invalidate all log caches
     *
     * @since 1.0.0
     */
    public function invalidateCaches(): void
    {
        $cachePath = $this->_getCachePath();

        if (is_dir($cachePath)) {
            $files = glob($cachePath . '*.cache');
            $count = 0;

            foreach ($files as $file) {
                if (@unlink($file)) {
                    $count++;
                }
            }

            Craft::info("Invalidated {$count} log cache files", 'logging-library');
        }
    }

    /**
     * Invalidate cache for specific log file
     *
     * @param string $logFile Full path to log file
     * @since 1.0.0
     */
    public function invalidateLogCache(string $logFile): void
    {
        $cacheKey = $this->_getCacheKey($logFile);
        $cacheFile = $this->_getCacheFile($cacheKey);

        if (file_exists($cacheFile)) {
            @unlink($cacheFile);
        }
    }

    /**
     * Get cache statistics
     *
     * @return array
     * @since 1.0.0
     */
    public function getCacheStats(): array
    {
        $cachePath = $this->_getCachePath();
        $stats = [
            'totalFiles' => 0,
            'totalSize' => 0,
            'files' => [],
        ];

        if (is_dir($cachePath)) {
            $files = glob($cachePath . '*.cache');
            $stats['totalFiles'] = count($files);

            foreach ($files as $file) {
                $size = filesize($file);
                $stats['totalSize'] += $size;
                $stats['files'][] = [
                    'file' => basename($file),
                    'size' => $size,
                    'modified' => filemtime($file),
                ];
            }

            $stats['formattedSize'] = Craft::$app->getFormatter()->asShortSize($stats['totalSize']);
        }

        return $stats;
    }

    /**
     * Parse log file into structured array
     *
     * @param string $logFile Full path to log file
     * @return array
     */
    private function _parseLogFile(string $logFile): array
    {
        if (!file_exists($logFile)) {
            return [];
        }

        $fileHandle = fopen($logFile, 'rb');
        if (!$fileHandle) {
            return [];
        }

        $logs = [];
        $key = -1;

        // Detect log format pattern
        $lineStart = $this->_getLinePattern($logFile);

        // Loop through all lines
        while (!feof($fileHandle)) {
            $line = fgets($fileHandle);

            // Find lines that start a new log entry
            if (preg_match($lineStart, $line)) {
                $key++;
                $logs[$key] = $line;
            } else {
                // Multi-line entries: append to current entry
                if (isset($logs[$key])) {
                    $logs[$key] .= $line;
                }
            }
        }

        fclose($fileHandle);

        // Parse each log entry
        $this->_parseLogContent($logFile, $logs);

        return $logs;
    }

    /**
     * Parse log content into structured format
     *
     * @param string $logFile Log file path
     * @param array $logs Log entries (by reference)
     */
    private function _parseLogContent(string $logFile, array &$logs): void
    {
        $pattern = $this->_getPattern($logFile);

        foreach ($logs as $key => $log) {
            preg_match($pattern, $log, $matches);

            $datetime = $matches['datetime'] ?? null;

            if ($datetime) {
                $logs[$key] = [
                    'timestamp' => $matches['datetime'] ?? null,  // Use 'timestamp' to match template expectations
                    'user' => $matches['user'] ?? 'System',
                    'level' => isset($matches['level']) ? strtolower($matches['level']) : null,
                    'category' => $matches['category'] ?? null,
                    'message' => $matches['message'] ?? null,
                    'context' => $matches['context'] ?? null,
                    'raw' => $log,
                ];
            } else {
                // Invalid entry, remove it
                unset($logs[$key]);
            }
        }
    }

    /**
     * Get regex pattern for log entry parsing
     *
     * @param string $logFile Log file path
     * @return string
     */
    private function _getPattern(string $logFile): string
    {
        $format = LoggingLibrary::detectLogFormat(file_get_contents($logFile, false, null, 0, 500));

        if ($format === 'plugin') {
            // Plugin format: YYYY-MM-DD HH:MM:SS [user][LEVEL][category] message | context
            return '/^(?P<datetime>\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\s+\[(?P<user>.*?)\]\[(?P<level>.*?)\]\[(?P<category>.*?)\]\s+(?P<message>.*?)(?:\s+\|\s+(?P<context>.*))?$/s';
        } elseif ($format === 'craft') {
            // Craft format: YYYY-MM-DD HH:MM:SS [category.LEVEL] [class] message
            return '/^(?P<datetime>\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\s+\[(?P<category>[a-z]+)\.(?P<level>[A-Z]+)\]\s+\[(?P<class>.*?)\]\s+(?P<message>.*?)(?:\s+(?P<context>\{.*\}))?$/s';
        } elseif ($format === 'php') {
            // PHP error format: [DD-MMM-YYYY HH:MM:SS Timezone] PHP Error: message
            return '/^\[(?P<datetime>.*)\] PHP\s+(?P<level>[^:]+):\s+(?P<message>.*)/s';
        }

        // Default/fallback pattern
        return '/^(?P<datetime>\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\s+(?P<message>.*)/s';
    }

    /**
     * Get regex pattern for line detection
     *
     * @param string $logFile Log file path
     * @return string
     */
    private function _getLinePattern(string $logFile): string
    {
        if (str_contains($logFile, 'phperrors')) {
            return '/^\[.*\]/';
        }

        // Default: lines starting with date
        return '/^\d{4}-\d{2}-\d{2}/';
    }

    /**
     * Get cache key for log file
     *
     * @param string $logFile Full path to log file
     * @return string
     */
    private function _getCacheKey(string $logFile): string
    {
        return md5($logFile . ':' . filesize($logFile));
    }

    /**
     * Get cache file path for cache key
     *
     * @param string $cacheKey Cache key
     * @return string
     */
    private function _getCacheFile(string $cacheKey): string
    {
        return $this->_getCachePath() . $cacheKey . '.cache';
    }

    /**
     * Get cache directory path
     *
     * @return string
     */
    private function _getCachePath(): string
    {
        return PluginHelper::getCachePath(LoggingLibrary::getInstance(), 'logs');
    }

    /**
     * Cache parsed data
     *
     * @param string $cacheKey Cache key
     * @param array $data Data to cache
     */
    private function _cacheData(string $cacheKey, array $data): void
    {
        $cachePath = $this->_getCachePath();

        // Create cache directory if it doesn't exist
        if (!is_dir($cachePath)) {
            FileHelper::createDirectory($cachePath);
        }

        $cacheFile = $cachePath . $cacheKey . '.cache';
        file_put_contents($cacheFile, serialize($data));
    }
}
