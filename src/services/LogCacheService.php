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
 * @since 5.2.2
 */
class LogCacheService extends Component
{
    private const PARSER_CACHE_VERSION = '2026-05-25-php-timestamp-normalization';

    /**
     * Get logs from file as ArrayQuery (with caching)
     *
     * @param string $logFile Full path to log file
     * @return ArrayQuery
     */
    public function getLogs(string $logFile): ArrayQuery
    {
        $cacheKey = $this->_getCacheKey($logFile);
        $cacheFile = $this->_getCacheFile($cacheKey);

        // Check if cache exists and is valid
        if (file_exists($cacheFile)) {
            $data = @file_get_contents($cacheFile);
            if ($data !== false && $data !== '') {
                $cache = json_decode($data, true);
                if (is_array($cache)) {
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
     * Sort log entries with a stable parse-order tiebreaker.
     *
     * Standard `usort` is stable for equal items, which means entries sharing the same
     * value for the sort column (commonly same-second timestamps) keep their input order
     * regardless of direction. For `desc` sort this leaves later events buried *below*
     * earlier ones in the same-second cluster — the opposite of what the operator expects.
     *
     * This helper injects a synthetic `_seq` index before sorting, applies it as a
     * tiebreaker, then strips it from the result.
     *
     * @param array $logs Parsed log entries (assoc arrays with at least the `$sort` key)
     * @param string $sort Column name (`timestamp`, `level`, `user`, `category`, `message`)
     * @param string $dir `asc` or `desc`
     * @return array Sorted entries with `_seq` stripped
     * @since 5.9.0
     */
    public static function sortLogs(array $logs, string $sort, string $dir): array
    {
        $orderDirection = $dir === 'asc' ? SORT_ASC : SORT_DESC;

        foreach ($logs as $i => &$log) {
            $log['_seq'] = $i;
        }
        unset($log);

        if ($sort === 'level') {
            $levelOrder = ['error' => 1, 'warning' => 2, 'info' => 3, 'debug' => 4, 'unknown' => 5];
            usort($logs, function($a, $b) use ($levelOrder, $orderDirection) {
                $aLevel = $levelOrder[$a['level'] ?? 'unknown'] ?? 99;
                $bLevel = $levelOrder[$b['level'] ?? 'unknown'] ?? 99;
                $result = $aLevel - $bLevel;
                if ($result === 0) {
                    $result = ($a['_seq'] ?? 0) <=> ($b['_seq'] ?? 0);
                }
                return $orderDirection === SORT_ASC ? $result : -$result;
            });
        } else {
            usort($logs, function($a, $b) use ($sort, $orderDirection) {
                $aVal = $a[$sort] ?? '';
                $bVal = $b[$sort] ?? '';
                $result = $aVal <=> $bVal;
                if ($result === 0) {
                    $result = ($a['_seq'] ?? 0) <=> ($b['_seq'] ?? 0);
                }
                return $orderDirection === SORT_ASC ? $result : -$result;
            });
        }

        foreach ($logs as &$log) {
            unset($log['_seq']);
        }
        unset($log);

        return $logs;
    }

    /**
     * Invalidate all log caches
     */
    public function invalidateCaches(): void
    {
        $cachePath = $this->_getCachePath();

        if (is_dir($cachePath)) {
            $files = glob($cachePath . '*.cache') ?: [];
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
            $files = glob($cachePath . '*.cache') ?: [];
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
                $format = LoggingLibrary::detectLogFormat($log);
                $level = isset($matches['level']) ? strtolower(trim($matches['level'])) : 'unknown';
                $category = trim($matches['category'] ?? '');
                $message = trim($matches['message'] ?? '');
                $context = trim($matches['context'] ?? '');

                if ($level === '') {
                    $level = 'unknown';
                }

                if ($format === 'php') {
                    $datetime = $this->_normalizePhpErrorTimestamp($datetime);
                    $level = $this->_normalizePhpErrorLevel($level);
                    [$message, $context] = $this->_splitPhpErrorContext($message, $context);
                }

                if ($category === '') {
                    $category = match ($format) {
                        'php' => 'php-errors',
                        default => $this->_inferCategoryFromFilename($logFile),
                    };
                }

                $logs[$key] = [
                    'timestamp' => $datetime,  // Use 'timestamp' to match template expectations
                    'user' => $matches['user'] ?? 'System',
                    'level' => $level,
                    'channel' => $matches['channel'] ?? null,
                    'category' => $category,
                    'message' => $message !== '' ? $message : trim($log),
                    'context' => $context,
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
        $sample = @file_get_contents($logFile, false, null, 0, 500);
        if ($sample === false) {
            return '/^(?P<datetime>\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\s+(?P<message>.*)/s';
        }

        $format = LoggingLibrary::detectLogFormat($sample);

        if ($format === 'plugin') {
            // Plugin format: YYYY-MM-DD HH:MM:SS [user][LEVEL][category] message | context
            return '/^(?P<datetime>\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\s+\[(?P<user>.*?)\]\[(?P<level>.*?)\]\[(?P<category>.*?)\]\s+(?P<message>.*?)(?:\s+\|\s+(?P<context>.*))?$/s';
        } elseif ($format === 'craft') {
            // Craft format: YYYY-MM-DD HH:MM:SS [category.LEVEL] [class] message
            return '/^(?P<datetime>\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\s+\[(?P<channel>[a-z0-9_.\\\\-]+)\.(?P<level>[A-Z ]+)\](?:\s+\[(?P<category>.*?)\])?\s+(?P<message>[^\r\n]*)(?P<context>.*)?$/s';
        } elseif ($format === 'bracket-level') {
            // Simple plugin format used by some third-party plugins: YYYY-MM-DD HH:MM:SS [LEVEL] message
            return '/^(?P<datetime>\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\s+\[(?P<level>[A-Z ]+)\]\s+(?P<message>[^\r\n]*)(?P<context>.*)?$/s';
        } elseif ($format === 'php') {
            // PHP error format: [DD-MMM-YYYY HH:MM:SS Timezone] PHP Error: message
            return '/^\[(?P<datetime>.*)\] PHP\s+(?P<level>[^:]+):\s+(?P<message>[^\r\n]*)(?P<context>.*)?$/s';
        }

        // Default/fallback pattern
        return '/^(?P<datetime>\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\s+(?P<message>.*)/s';
    }

    /**
     * Map PHP's error labels onto the viewer's canonical log levels.
     */
    private function _normalizePhpErrorLevel(string $level): string
    {
        if (
            str_contains($level, 'fatal') ||
            str_contains($level, 'parse') ||
            str_contains($level, 'recoverable') ||
            str_contains($level, 'error')
        ) {
            return 'error';
        }

        if (str_contains($level, 'warning')) {
            return 'warning';
        }

        if (str_contains($level, 'notice') || str_contains($level, 'deprecated') || str_contains($level, 'strict')) {
            return 'info';
        }

        return 'unknown';
    }

    /**
     * Convert PHP error timestamps like `17-May-2026 19:46:49 UTC` into the
     * canonical sortable format used by Craft/plugin logs.
     */
    private function _normalizePhpErrorTimestamp(string $datetime): string
    {
        $date = \DateTimeImmutable::createFromFormat('d-M-Y H:i:s T', $datetime);

        if ($date === false) {
            return $datetime;
        }

        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Keep PHP exception/error headlines in the table and move traces into context.
     *
     * PHP error logs often write `Stack trace:` on the same first line as the
     * error headline, so the regex newline split alone is not enough.
     *
     * @return array{0: string, 1: string}
     */
    private function _splitPhpErrorContext(string $message, string $context): array
    {
        $stackTraceMarker = ' Stack trace:';
        $stackTracePosition = strpos($message, $stackTraceMarker);

        if ($stackTracePosition !== false) {
            $trace = trim(substr($message, $stackTracePosition + 1));
            $message = trim(substr($message, 0, $stackTracePosition));
            $context = trim($trace . ($context !== '' ? "\n" . $context : ''));
        }

        if (preg_match('/^(?P<headline>.+?)\s+(?P<location>in\s+\/.+?(?::\d+| on line \d+))$/s', $message, $matches)) {
            $message = trim($matches['headline']);
            $context = trim($matches['location'] . ($context !== '' ? "\n" . $context : ''));
        }

        return [$message, $context];
    }

    /**
     * Infer a stable source category for log formats that don't carry one.
     */
    private function _inferCategoryFromFilename(string $logFile): string
    {
        $basename = basename($logFile);

        if ($basename === 'phperrors.log') {
            return 'php-errors';
        }

        if (preg_match('/^([a-z0-9\-]+)-\d{4}-\d{2}-\d{2}\.log$/', $basename, $matches)) {
            return $matches[1];
        }

        if (preg_match('/^(web|console|queue)(?:-\d{4}-\d{2}-\d{2})?\.log(?:\.\d+)?$/', $basename, $matches)) {
            return $matches[1];
        }

        return 'unknown';
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

        $sample = @file_get_contents($logFile, false, null, 0, 500);
        if (is_string($sample) && LoggingLibrary::detectLogFormat($sample) === 'php') {
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
        $size = @filesize($logFile);
        $mtime = @filemtime($logFile);
        return md5(self::PARSER_CACHE_VERSION . ':' . $logFile . ':' . $size . ':' . $mtime);
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
        $encoded = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($encoded === false) {
            return;
        }

        file_put_contents($cacheFile, $encoded);
    }
}
