<?php
/**
 * Logging Library for Craft CMS
 *
 * Log cache service for high-performance log parsing and querying
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2025-2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\services;

use Craft;
use craft\base\Component;
use craft\helpers\FileHelper;
use lindemannrock\base\helpers\PluginHelper;
use lindemannrock\logginglibrary\helpers\UserLabelHelper;
use lindemannrock\logginglibrary\LoggingLibrary;
use PDO;
use PDOException;
use yii2mod\query\ArrayQuery;

/**
 * Log Cache Service
 * Provides cached, queryable access to parsed log files
 *
 * @since 5.2.2
 */
class LogCacheService extends Component
{
    private const PARSER_CACHE_VERSION = '2026-06-23-undated-monolog-source-logs';
    private const INDEX_SCHEMA_VERSION = 1;

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
     * Return one filtered/sorted page of log entries without loading the whole
     * log file into PHP memory.
     *
     * The CP viewer uses this indexed cache path. The older `getLogs()` method
     * remains available for public API callers that expect an ArrayQuery.
     *
     * @return array{entries: array, total: int, category: string, categoryOptions: array}
     * @since 5.9.0
     */
    public function getLogPage(string $logFile, string $level, string $category, string $search, string $sort, string $dir, int $page, int $limit): array
    {
        if (!file_exists($logFile)) {
            return [
                'entries' => [],
                'total' => 0,
                'category' => 'all',
                'categoryOptions' => [],
            ];
        }

        if (!self::supportsIndexedCache()) {
            return $this->_getArrayQueryLogPage($logFile, $level, $category, $search, $sort, $dir, $page, $limit);
        }

        try {
            $pdo = $this->_getIndexConnection($logFile);
        } catch (\Throwable $e) {
            Craft::warning('Falling back to legacy log cache because indexed cache failed: ' . $e->getMessage(), 'logging-library');
            return $this->_getArrayQueryLogPage($logFile, $level, $category, $search, $sort, $dir, $page, $limit);
        }
        $where = [];
        $params = [];

        if ($level !== 'all') {
            $where[] = 'level = :level';
            $params[':level'] = $level;
        }

        if ($search !== '') {
            $where[] = '(message LIKE :search ESCAPE \'\\\' OR context LIKE :search ESCAPE \'\\\' OR category LIKE :search ESCAPE \'\\\')';
            $params[':search'] = '%' . $this->_escapeLike($search) . '%';
        }

        $whereSql = $where ? ' WHERE ' . implode(' AND ', $where) : '';
        $categoryCounts = $this->_getIndexedCategoryCounts($pdo, $whereSql, $params);

        if ($category !== 'all' && !isset($categoryCounts[$category])) {
            $category = 'all';
        }

        if ($category !== 'all') {
            $where[] = 'category = :category';
            $params[':category'] = $category;
        }

        $whereSql = $where ? ' WHERE ' . implode(' AND ', $where) : '';
        $total = $this->_getIndexedCount($pdo, $whereSql, $params);
        $entries = $this->_getIndexedEntries($pdo, $whereSql, $params, $sort, $dir, $page, $limit);

        return [
            'entries' => UserLabelHelper::withUserLabels($entries),
            'total' => $total,
            'category' => $category,
            'categoryOptions' => $this->_buildCategoryOptions($categoryCounts),
        ];
    }

    /**
     * Whether the current PHP runtime can use the indexed SQLite cache.
     *
     * @since 5.9.0
     */
    public static function supportsIndexedCache(): bool
    {
        return class_exists(PDO::class) && in_array('sqlite', PDO::getAvailableDrivers(), true);
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
            $files = array_merge(
                glob($cachePath . '*.cache') ?: [],
                glob($cachePath . '*.sqlite') ?: [],
            );
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
        $indexFile = $this->_getIndexFile($this->_getIndexKey($logFile));

        if (file_exists($cacheFile)) {
            @unlink($cacheFile);
        }

        if (file_exists($indexFile)) {
            @unlink($indexFile);
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
            $files = array_merge(
                glob($cachePath . '*.cache') ?: [],
                glob($cachePath . '*.sqlite') ?: [],
            );
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
            $entry = $this->_parseLogEntry($logFile, $log, $pattern);

            if ($entry === null) {
                unset($logs[$key]);
                continue;
            }

            $logs[$key] = $entry;
        }
    }

    /**
     * Parse one raw log entry into the normalized shape used by all cache paths.
     *
     * @return array{timestamp: string, user: string, level: string, channel: string|null, category: string, message: string, context: string, raw: string}|null
     */
    private function _parseLogEntry(string $logFile, string $log, string $pattern): ?array
    {
        preg_match($pattern, $log, $matches);

        $datetime = $matches['datetime'] ?? null;
        if (!$datetime) {
            return null;
        }

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
        } elseif ($format === 'monolog') {
            $datetime = $this->_normalizeIsoTimestamp($datetime);
            [$message, $context] = $this->_splitMonologContext($message, $context);
        }

        if ($category === '') {
            $category = match ($format) {
                'php' => 'php-errors',
                default => $this->_inferCategoryFromFilename($logFile),
            };
        }

        return [
            'timestamp' => $datetime,
            'user' => $matches['user'] ?? 'System',
            'level' => $level,
            'channel' => $matches['channel'] ?? null,
            'category' => $category,
            'message' => $message !== '' ? $message : trim($log),
            'context' => $context,
            'raw' => $log,
        ];
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
        } elseif ($format === 'monolog') {
            // Monolog format: [YYYY-MM-DDTHH:MM:SS.microseconds+TZ] channel.LEVEL: message
            return '/^\[(?P<datetime>\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:Z|[+\-]\d{2}:\d{2}))\]\s+(?P<channel>[a-z0-9_.\\\\-]+)\.(?P<level>[A-Z]+):\s+(?P<message>[^\r\n]*)(?P<context>.*)?$/s';
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
     * Convert ISO-8601 Monolog timestamps into the viewer's canonical format.
     */
    private function _normalizeIsoTimestamp(string $datetime): string
    {
        try {
            $date = new \DateTimeImmutable($datetime);
        } catch (\Exception) {
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
     * Move Monolog's trailing context/extra payloads out of the table headline.
     *
     * @return array{0: string, 1: string}
     */
    private function _splitMonologContext(string $message, string $context): array
    {
        $combined = trim($message . ($context !== '' ? $context : ''));

        if (preg_match('/^(?P<headline>.*?)\s+(?P<payload>(?:\[[^\r\n\]]*\]|\{[^\r\n]*\})(?:\s+(?:\[[^\r\n\]]*\]|\{[^\r\n]*\}))*)$/', $combined, $matches)) {
            return [
                trim($matches['headline']),
                trim($matches['payload']),
            ];
        }

        return [trim($message), trim($context)];
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

        if (preg_match('/^([a-z0-9][a-z0-9\-_]*)\.log$/', $basename, $matches)) {
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
        if (is_string($sample) && LoggingLibrary::detectLogFormat($sample) === 'monolog') {
            return '/^\[\d{4}-\d{2}-\d{2}T/';
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
     * Get cache key for the SQLite index. Include schema version so structural
     * index changes cannot reuse incompatible files.
     */
    private function _getIndexKey(string $logFile): string
    {
        return self::INDEX_SCHEMA_VERSION . '-' . $this->_getCacheKey($logFile);
    }

    /**
     * Get SQLite index file path for cache key.
     */
    private function _getIndexFile(string $cacheKey): string
    {
        return $this->_getCachePath() . $cacheKey . '.sqlite';
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
     * Legacy full-array page builder used when PDO SQLite is unavailable.
     *
     * @return array{entries: array, total: int, category: string, categoryOptions: array}
     */
    private function _getArrayQueryLogPage(string $filePath, string $level, string $category, string $search, string $sort, string $dir, int $page, int $limit): array
    {
        $logs = $this->getLogs($filePath)->all();

        if ($level !== 'all') {
            $logs = array_values(array_filter($logs, fn($log) => ($log['level'] ?? '') === $level));
        }

        if ($search !== '') {
            $logs = array_values(array_filter($logs, function($log) use ($search) {
                return stripos($log['message'] ?? '', $search) !== false ||
                       stripos($log['context'] ?? '', $search) !== false ||
                       stripos($log['category'] ?? '', $search) !== false;
            }));
        }

        $categoryCounts = $this->_countCategories($logs);
        if ($category !== 'all' && !isset($categoryCounts[$category])) {
            $category = 'all';
        }

        if ($category !== 'all') {
            $logs = array_values(array_filter($logs, fn($log) => ($log['category'] ?? '') === $category));
        }

        $totalCount = count($logs);
        $logs = self::sortLogs($logs, $sort, $dir);

        $offset = max(0, ($page - 1) * $limit);
        $entries = array_slice($logs, $offset, $limit);

        foreach ($entries as $index => &$log) {
            $levelLower = strtolower((string)($log['level'] ?? ''));
            $canonical = self::_canonicalLevel($levelLower);

            $log['lineNumber'] = $offset + $index + 1;
            $log['canonicalLevel'] = $canonical;
            $log['levelClass'] = $canonical !== '' ? 'lr-level-' . $canonical : '';
        }
        unset($log);

        return [
            'entries' => UserLabelHelper::withUserLabels($entries),
            'total' => $totalCount,
            'category' => $category,
            'categoryOptions' => $this->_buildCategoryOptions($categoryCounts),
        ];
    }

    /**
     * Count categories for the legacy fallback path.
     *
     * @param array $logs
     * @return array<string, int>
     */
    private function _countCategories(array $logs): array
    {
        $counts = [];
        foreach ($logs as $log) {
            $category = (string)($log['category'] ?? '');
            if ($category === '') {
                continue;
            }

            $counts[$category] = ($counts[$category] ?? 0) + 1;
        }

        ksort($counts, SORT_NATURAL | SORT_FLAG_CASE);
        return $counts;
    }

    /**
     * Open an indexed cache database for a log file, building it when needed.
     */
    private function _getIndexConnection(string $logFile): PDO
    {
        $indexFile = $this->_getIndexFile($this->_getIndexKey($logFile));

        if (!file_exists($indexFile)) {
            $this->_buildIndexCache($logFile, $indexFile);
        }

        return $this->_openIndex($indexFile);
    }

    /**
     * Build a SQLite cache by streaming the source log file one entry at a time.
     */
    private function _buildIndexCache(string $logFile, string $indexFile): void
    {
        if (file_exists($indexFile)) {
            return;
        }

        $cachePath = $this->_getCachePath();
        if (!is_dir($cachePath)) {
            FileHelper::createDirectory($cachePath);
        }

        $tmpFile = $indexFile . '.tmp.' . getmypid();
        if (file_exists($tmpFile)) {
            @unlink($tmpFile);
        }

        try {
            $pdo = $this->_openIndex($tmpFile);
            $this->_initializeIndexSchema($pdo);
            $this->_streamLogIntoIndex($pdo, $logFile);

            $pdo = null;

            if (file_exists($indexFile)) {
                @unlink($tmpFile);
                return;
            }

            if (!@rename($tmpFile, $indexFile) && !file_exists($indexFile)) {
                throw new \RuntimeException('Unable to move indexed log cache into place.');
            }
        } catch (\Throwable $e) {
            @unlink($tmpFile);
            throw $e;
        }
    }

    /**
     * Open a SQLite cache database.
     */
    private function _openIndex(string $indexFile): PDO
    {
        try {
            $pdo = new PDO('sqlite:' . $indexFile);
        } catch (PDOException $e) {
            throw new \RuntimeException('Unable to open indexed log cache.', 0, $e);
        }
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }

    /**
     * Create the indexed cache schema.
     */
    private function _initializeIndexSchema(PDO $pdo): void
    {
        $pdo->exec('PRAGMA journal_mode = OFF');
        $pdo->exec('PRAGMA synchronous = OFF');
        $pdo->exec('CREATE TABLE meta (key TEXT PRIMARY KEY, value TEXT NOT NULL)');
        $pdo->exec(
            'CREATE TABLE entries (
                seq INTEGER PRIMARY KEY,
                timestamp TEXT NOT NULL,
                user TEXT NOT NULL,
                level TEXT NOT NULL,
                channel TEXT,
                category TEXT NOT NULL,
                message TEXT NOT NULL,
                context TEXT NOT NULL
            )'
        );
        $pdo->exec('CREATE INDEX idx_entries_timestamp_seq ON entries (timestamp, seq)');
        $pdo->exec('CREATE INDEX idx_entries_level_seq ON entries (level, seq)');
        $pdo->exec('CREATE INDEX idx_entries_category_seq ON entries (category, seq)');

        $statement = $pdo->prepare('INSERT INTO meta (key, value) VALUES (:key, :value)');
        $statement->execute([
            ':key' => 'schemaVersion',
            ':value' => (string)self::INDEX_SCHEMA_VERSION,
        ]);
    }

    /**
     * Stream one source log file into the indexed cache.
     */
    private function _streamLogIntoIndex(PDO $pdo, string $logFile): void
    {
        if (!file_exists($logFile)) {
            return;
        }

        $fileHandle = fopen($logFile, 'rb');
        if (!$fileHandle) {
            return;
        }

        $lineStart = $this->_getLinePattern($logFile);
        $pattern = $this->_getPattern($logFile);
        $seq = 0;
        $current = null;

        $statement = $pdo->prepare(
            'INSERT INTO entries (seq, timestamp, user, level, channel, category, message, context)
             VALUES (:seq, :timestamp, :user, :level, :channel, :category, :message, :context)'
        );

        $pdo->beginTransaction();
        try {
            while (!feof($fileHandle)) {
                $line = fgets($fileHandle);
                if ($line === false) {
                    break;
                }

                if (preg_match($lineStart, $line)) {
                    if ($current !== null) {
                        $this->_insertIndexedEntry($statement, $logFile, $current, $pattern, $seq);
                        $seq++;
                    }
                    $current = $line;
                } elseif ($current !== null) {
                    $current .= $line;
                }
            }

            if ($current !== null) {
                $this->_insertIndexedEntry($statement, $logFile, $current, $pattern, $seq);
            }

            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        } finally {
            fclose($fileHandle);
        }
    }

    /**
     * Parse and insert one streamed log entry.
     */
    private function _insertIndexedEntry(\PDOStatement $statement, string $logFile, string $rawEntry, string $pattern, int $seq): void
    {
        $entry = $this->_parseLogEntry($logFile, $rawEntry, $pattern);
        if ($entry === null) {
            return;
        }

        $statement->execute([
            ':seq' => $seq,
            ':timestamp' => $entry['timestamp'],
            ':user' => $entry['user'],
            ':level' => $entry['level'],
            ':channel' => $entry['channel'],
            ':category' => $entry['category'],
            ':message' => $entry['message'],
            ':context' => $entry['context'],
        ]);
    }

    /**
     * Count indexed rows for the current filter set.
     *
     * @param array<string, string> $params
     */
    private function _getIndexedCount(PDO $pdo, string $whereSql, array $params): int
    {
        $statement = $pdo->prepare('SELECT COUNT(*) FROM entries' . $whereSql);
        $statement->execute($params);
        return (int)$statement->fetchColumn();
    }

    /**
     * Count categories before the selected category filter is applied.
     *
     * @param array<string, string> $params
     * @return array<string, int>
     */
    private function _getIndexedCategoryCounts(PDO $pdo, string $whereSql, array $params): array
    {
        $statement = $pdo->prepare('SELECT category, COUNT(*) AS count FROM entries' . $whereSql . ' GROUP BY category ORDER BY category COLLATE NOCASE ASC');
        $statement->execute($params);

        $counts = [];
        foreach ($statement->fetchAll() as $row) {
            $category = (string)($row['category'] ?? '');
            if ($category === '') {
                continue;
            }
            $counts[$category] = (int)($row['count'] ?? 0);
        }

        return $counts;
    }

    /**
     * Read the requested page from the indexed cache.
     *
     * @param array<string, string> $params
     * @return list<array<string, mixed>>
     */
    private function _getIndexedEntries(PDO $pdo, string $whereSql, array $params, string $sort, string $dir, int $page, int $limit): array
    {
        $direction = $dir === 'asc' ? 'ASC' : 'DESC';
        $offset = max(0, ($page - 1) * $limit);
        $orderBy = $this->_getIndexedOrderBy($sort, $direction);

        $statement = $pdo->prepare(
            'SELECT seq, timestamp, user, level, channel, category, message, context
             FROM entries' . $whereSql . ' ORDER BY ' . $orderBy . ' LIMIT :limit OFFSET :offset'
        );

        foreach ($params as $name => $value) {
            $statement->bindValue($name, $value);
        }
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        $entries = [];
        $lineNumber = $offset + 1;
        foreach ($statement->fetchAll() as $row) {
            $level = strtolower((string)($row['level'] ?? ''));
            $canonical = self::_canonicalLevel($level);

            $entries[] = [
                'timestamp' => (string)($row['timestamp'] ?? ''),
                'user' => (string)($row['user'] ?? ''),
                'level' => $level,
                'channel' => $row['channel'] ?? null,
                'category' => (string)($row['category'] ?? ''),
                'message' => (string)($row['message'] ?? ''),
                'context' => (string)($row['context'] ?? ''),
                'lineNumber' => $lineNumber++,
                'canonicalLevel' => $canonical,
                'levelClass' => $canonical !== '' ? 'lr-level-' . $canonical : '',
            ];
        }

        return $entries;
    }

    /**
     * Build a safe SQL ORDER BY clause for the indexed cache.
     */
    private function _getIndexedOrderBy(string $sort, string $direction): string
    {
        if ($sort === 'level') {
            return 'CASE level
                WHEN \'error\' THEN 1
                WHEN \'warning\' THEN 2
                WHEN \'info\' THEN 3
                WHEN \'debug\' THEN 4
                WHEN \'unknown\' THEN 5
                ELSE 99
            END ' . $direction . ', seq ' . $direction;
        }

        $columns = [
            'timestamp' => 'timestamp',
            'user' => 'user',
            'category' => 'category',
            'message' => 'message',
        ];
        $column = $columns[$sort] ?? 'timestamp';

        return $column . ' ' . $direction . ', seq ' . $direction;
    }

    /**
     * Escape SQLite LIKE wildcard characters while preserving bound parameters.
     */
    private function _escapeLike(string $value): string
    {
        return strtr($value, [
            '\\' => '\\\\',
            '%' => '\\%',
            '_' => '\\_',
        ]);
    }

    /**
     * Canonicalize log levels for row tinting and badge rendering.
     */
    private static function _canonicalLevel(string $level): string
    {
        if ($level === '') {
            return '';
        }

        if (
            str_contains($level, 'fatal')
            || str_contains($level, 'parse')
            || str_contains($level, 'recoverable')
            || str_contains($level, 'error')
        ) {
            return 'error';
        }

        if (str_contains($level, 'warning')) {
            return 'warning';
        }

        if (
            str_contains($level, 'notice')
            || str_contains($level, 'deprecated')
            || str_contains($level, 'strict')
        ) {
            return 'info';
        }

        if (in_array($level, ['debug', 'info'], true)) {
            return $level;
        }

        return '';
    }

    /**
     * Build category filter options from indexed counts.
     *
     * @param array<string, int> $categoryCounts
     * @return array
     */
    private function _buildCategoryOptions(array $categoryCounts): array
    {
        $formatter = Craft::$app->getFormatter();

        $options = [[
            'value' => 'all',
            'label' => Craft::t('logging-library', 'Source'),
            'extra' => '(' . $formatter->asInteger(array_sum($categoryCounts)) . ')',
        ]];

        foreach ($categoryCounts as $category => $count) {
            $options[] = [
                'value' => $category,
                'label' => $category,
                'extra' => '(' . $formatter->asInteger($count) . ')',
            ];
        }

        return $options;
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
