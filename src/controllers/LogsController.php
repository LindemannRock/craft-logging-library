<?php
/**
 * Logging Library for Craft CMS
 *
 * Generic controller for viewing plugin logs
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2025 LindemannRock
 */

namespace lindemannrock\logginglibrary\controllers;

use Craft;
use craft\web\Controller;
use craft\web\Response;
use lindemannrock\logginglibrary\LoggingLibrary;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Generic Logs Controller
 * Provides log viewing functionality for any plugin using the logging library
 */
class LogsController extends Controller
{
    /**
     * @inheritdoc
     */
    protected array|int|bool $allowAnonymous = false;

    /**
     * Display logs with pagination
     */
    public function actionIndex(): Response
    {
        $request = Craft::$app->getRequest();

        // Get plugin handle from the URL segment
        $pluginHandle = $this->_getPluginHandleFromUrl();

        // Detect standalone mode (viewing all logs)
        $isStandalone = ($pluginHandle === 'logging-library');

        if (!$isStandalone) {
            // Plugin-specific mode - use existing config
            $config = LoggingLibrary::getConfig($pluginHandle);

            if (!$config) {
                throw new NotFoundHttpException('Plugin logging not configured');
            }

            // Check if log viewer is enabled
            if (!($config['enableLogViewer'] ?? false)) {
                throw new NotFoundHttpException('Log viewer is disabled for this plugin');
            }

            // Check permissions if specified
            $this->_checkPermissions($config['permissions'] ?? []);

            $limit = $config['itemsPerPage'] ?? 50;
            $pluginName = $config['pluginName'];
        } else {
            // Standalone mode - no specific config needed
            $config = null;
            $limit = 50; // Default for standalone
            $pluginName = 'All Logs';

            // TODO: Add admin permission check for standalone mode
        }

        // Get filter parameters
        $level = trim($request->getParam('level', 'all'));
        $source = trim($request->getParam('source', 'all')); // New: source/category filter
        $search = trim($request->getParam('search', ''));
        $sort = trim($request->getParam('sort', 'timestamp'));
        $dir = trim($request->getParam('dir', 'desc'));
        $page = (int) trim($request->getParam('page', 1));

        // Get available log files
        if ($isStandalone) {
            $allLogFiles = LoggingLibrary::getAllLogFiles();

            // Extract unique sources for filter dropdown
            $sources = $this->_extractSources($allLogFiles);

            // Filter files by selected source
            if ($source !== 'all') {
                $logFiles = array_values(array_filter($allLogFiles, fn($file) => $file['source'] === $source));
            } else {
                $logFiles = $allLogFiles;
            }
        } else {
            $logFiles = LoggingLibrary::getLogFiles($pluginHandle);
            $sources = [];
        }

        // Get the selected log file
        $selectedFile = $this->_getSelectedLogFile($request, $logFiles, $isStandalone);

        // Read and parse log entries (cached for performance)
        if ($selectedFile) {
            $logEntries = $this->_getLogEntriesFromFile(
                $selectedFile['path'],
                $level,
                $search,
                $sort,
                $dir,
                $page,
                $limit
            );

            $totalEntries = $this->_getLogEntriesCountFromFile(
                $selectedFile['path'],
                $level,
                $search
            );

            // Detect which columns have variance (should be shown)
            $columnVariance = $this->_detectColumnVariance($logEntries);
        } else {
            $logEntries = [];
            $totalEntries = 0;
            $columnVariance = [
                'level' => true,
                'user' => true,
                'category' => true,
            ];
        }

        // Calculate pagination info
        $totalPages = $totalEntries > 0 ? ceil($totalEntries / $limit) : 0;

        return $this->renderTemplate('logging-library/logs/index', [
            'pluginHandle' => $pluginHandle,
            'pluginName' => $pluginName,
            'isStandalone' => $isStandalone,
            'logFiles' => array_values($logFiles),
            'selectedFile' => $selectedFile,
            'sources' => $sources,
            'logEntries' => $logEntries,
            'columnVariance' => $columnVariance,
            'filters' => [
                'level' => $level,
                'source' => $source,
                'search' => $search,
                'sort' => $sort,
                'dir' => $dir,
                'page' => $page,
            ],
            'pagination' => [
                'total' => $totalEntries,
                'perPage' => $limit,
                'currentPage' => $page,
                'totalPages' => $totalPages,
            ],
            'levels' => [
                'all' => 'All Levels',
                'error' => 'Error',
                'warning' => 'Warning',
                'info' => 'Info',
                'debug' => 'Debug',
            ],
            'config' => $config,
        ]);
    }

    /**
     * Detect which columns have variance (should be shown)
     */
    private function _detectColumnVariance(array $logEntries): array
    {
        if (empty($logEntries)) {
            return [
                'level' => true,
                'user' => true,
                'category' => true,
            ];
        }

        $uniqueValues = [
            'level' => [],
            'user' => [],
            'category' => [],
        ];

        // Collect unique values for each column
        foreach ($logEntries as $entry) {
            $uniqueValues['level'][$entry['level'] ?? ''] = true;
            $uniqueValues['user'][$entry['user'] ?? ''] = true;
            $uniqueValues['category'][$entry['category'] ?? ''] = true;
        }

        // Column should be shown if it has more than 1 unique value
        return [
            'level' => count($uniqueValues['level']) > 1,
            'user' => count($uniqueValues['user']) > 1,
            'category' => count($uniqueValues['category']) > 1,
        ];
    }

    /**
     * Download a log file
     */
    public function actionDownload(): Response
    {
        $request = Craft::$app->getRequest();
        $pluginHandle = $this->_getPluginHandleFromUrl();
        $config = LoggingLibrary::getConfig($pluginHandle);

        if (!$config) {
            throw new NotFoundHttpException('Plugin logging not configured');
        }

        // Check if log viewer is enabled
        if (!($config['enableLogViewer'] ?? false)) {
            throw new NotFoundHttpException('Log viewer is disabled for this plugin');
        }

        // Check permissions
        $this->_checkPermissions($config['permissions'] ?? []);

        $date = trim($request->getRequiredParam('date'));

        // Validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            throw new \InvalidArgumentException('Invalid date format');
        }

        $logPath = Craft::$app->getPath()->getLogPath() . "/{$pluginHandle}-{$date}.log";

        if (!file_exists($logPath)) {
            throw new \Exception('Log file not found');
        }

        return Craft::$app->getResponse()->sendFile($logPath, "{$pluginHandle}-{$date}.log", [
            'mimeType' => 'text/plain',
            'inline' => false,
        ]);
    }

    /**
     * Get plugin handle from the current URL
     */
    private function _getPluginHandleFromUrl(): string
    {
        $segments = Craft::$app->getRequest()->getSegments();

        // The plugin handle should be the first segment before '/logs'
        foreach ($segments as $index => $segment) {
            if ($segment === 'logs' && $index > 0) {
                return $segments[$index - 1];
            }
        }

        throw new NotFoundHttpException('Unable to determine plugin handle from URL');
    }

    /**
     * Check permissions for accessing logs
     */
    private function _checkPermissions(array $permissions): void
    {
        if (empty($permissions)) {
            return; // No permissions required
        }

        $hasPermission = false;
        foreach ($permissions as $permission) {
            if (Craft::$app->getUser()->checkPermission($permission)) {
                $hasPermission = true;
                break;
            }
        }

        if (!$hasPermission) {
            throw new ForbiddenHttpException('User does not have permission to view logs');
        }
    }

    /**
     * Extract unique sources from log files for filter dropdown
     */
    private function _extractSources(array $logFiles): array
    {
        $sources = ['all' => 'All Sources'];
        $seen = [];

        foreach ($logFiles as $file) {
            $source = $file['source'] ?? 'unknown';
            if (!isset($seen[$source])) {
                $seen[$source] = true;
                // Create display name
                $displayName = match ($source) {
                    'web' => 'Web',
                    'console' => 'Console',
                    'queue' => 'Queue',
                    'php-errors' => 'PHP Errors',
                    'other' => 'Other',
                    default => ucwords(str_replace('-', ' ', $source)),
                };
                $sources[$source] = $displayName;
            }
        }

        return $sources;
    }

    /**
     * Get the selected log file from request parameters
     */
    private function _getSelectedLogFile($request, array $logFiles, bool $isStandalone): ?array
    {
        if (empty($logFiles)) {
            return null;
        }

        if ($isStandalone) {
            // In standalone mode, select by filename
            $requestedFile = trim($request->getParam('file', ''));

            if ($requestedFile) {
                foreach ($logFiles as $file) {
                    if ($file['filename'] === $requestedFile) {
                        return $file;
                    }
                }
            }

            // Default to most recent file (use reset since array_filter preserves keys)
            return reset($logFiles) ?: null;
        } else {
            // Plugin mode: select by date
            $requestedDate = trim($request->getParam('date', ''));

            if ($requestedDate) {
                foreach ($logFiles as $file) {
                    if (($file['date'] ?? '') === $requestedDate) {
                        return $file;
                    }
                }
            }

            // Default to most recent file (use reset since array keys might not start at 0)
            return reset($logFiles) ?: null;
        }
    }

    /**
     * Get log entries from a file with filtering, sorting, and pagination
     * Uses cached ArrayQuery for high performance
     */
    private function _getLogEntriesFromFile(string $filePath, string $level, string $search, string $sort, string $dir, int $page, int $limit): array
    {
        if (!file_exists($filePath)) {
            return [];
        }

        // Get cached parsed logs as ArrayQuery
        $logQuery = LoggingLibrary::getInstance()->logCache->getLogs($filePath);

        // Apply filters
        if ($level !== 'all') {
            $logQuery->andFilterWhere(['level' => $level]);
        }

        if ($search) {
            // ArrayQuery doesn't support LIKE, so we'll filter manually
            $logQuery->andFilterWhere(function($log) use ($search) {
                return stripos($log['message'] ?? '', $search) !== false ||
                       stripos($log['context'] ?? '', $search) !== false;
            });
        }

        // Apply sorting
        $orderDirection = $dir === 'asc' ? SORT_ASC : SORT_DESC;

        if ($sort === 'level') {
            // Custom level sorting
            $levelOrder = ['error' => 1, 'warning' => 2, 'info' => 3, 'debug' => 4, 'unknown' => 5];
            $logQuery->addOrderBy(function($a, $b) use ($levelOrder, $orderDirection) {
                $aLevel = $levelOrder[$a['level'] ?? 'unknown'] ?? 99;
                $bLevel = $levelOrder[$b['level'] ?? 'unknown'] ?? 99;
                $result = $aLevel - $bLevel;
                return $orderDirection === SORT_ASC ? $result : -$result;
            });
        } else {
            $logQuery->orderBy([$sort => $orderDirection]);
        }

        // Save total before pagination
        $totalCount = $logQuery->count();

        // Apply pagination
        $offset = ($page - 1) * $limit;
        $logQuery->limit($limit)->offset($offset);

        // Get results and add line numbers
        $logs = $logQuery->all();

        foreach ($logs as $index => &$log) {
            $log['lineNumber'] = $offset + $index + 1;
        }

        return $logs;
    }

    /**
     * Get total count of log entries from a file
     */
    private function _getLogEntriesCountFromFile(string $filePath, string $level, string $search): int
    {
        if (!file_exists($filePath)) {
            return 0;
        }

        // Get cached parsed logs as ArrayQuery
        $logQuery = LoggingLibrary::getInstance()->logCache->getLogs($filePath);

        // Apply filters
        if ($level !== 'all') {
            $logQuery->andFilterWhere(['level' => $level]);
        }

        if ($search) {
            $logQuery->andFilterWhere(function($log) use ($search) {
                return stripos($log['message'] ?? '', $search) !== false ||
                       stripos($log['context'] ?? '', $search) !== false;
            });
        }

        return $logQuery->count();
    }


    /**
     * Get log entries for a specific date with filtering, sorting, and pagination
     * @deprecated Use _getLogEntriesFromFile() instead
     */
    private function _getLogEntries(string $pluginHandle, string $date, string $level, string $search, string $sort, string $dir, int $page, int $limit): array
    {
        $logPath = Craft::$app->getPath()->getLogPath() . "/{$pluginHandle}-{$date}.log";
        return $this->_getLogEntriesFromFile($logPath, $level, $search, $sort, $dir, $page, $limit);
    }

    /**
     * Get total count of log entries for pagination
     * @deprecated Use _getLogEntriesCountFromFile() instead
     */
    private function _getLogEntriesCount(string $pluginHandle, string $date, string $level, string $search): int
    {
        $logPath = Craft::$app->getPath()->getLogPath() . "/{$pluginHandle}-{$date}.log";
        return $this->_getLogEntriesCountFromFile($logPath, $level, $search);
    }

    /**
     * Detect format and parse log entry line
     */
    private function _parseLogEntry(string $line, int $lineNumber = 0): ?array
    {
        if (empty($line)) {
            return null;
        }

        // Detect format and route to appropriate parser
        $format = LoggingLibrary::detectLogFormat($line);

        return match ($format) {
            'plugin' => $this->_parsePluginLogEntry($line, $lineNumber),
            'craft' => $this->_parseCraftLogEntry($line, $lineNumber),
            'php' => $this->_parsePhpErrorEntry($line, $lineNumber),
            default => $this->_parseUnknownLogEntry($line, $lineNumber),
        };
    }

    /**
     * Parse plugin log format: timestamp [user][LEVEL][category] message | context
     */
    private function _parsePluginLogEntry(string $line, int $lineNumber = 0): ?array
    {
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
                'format' => 'plugin',
            ];
        }

        return null;
    }

    /**
     * Parse Craft log format: timestamp [category.LEVEL] [class.name] message {"memory":...}
     */
    private function _parseCraftLogEntry(string $line, int $lineNumber = 0): ?array
    {
        // Parse Craft format: YYYY-MM-DD HH:MM:SS [category.LEVEL] [class.name] message
        if (preg_match('/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\s+\[([a-z]+)\.([A-Z]+)\]\s+\[(.*?)\]\s+(.*?)(?:\s+(\{.*\}))?$/', $line, $matches)) {
            return [
                'timestamp' => $matches[1],
                'user' => 'System', // Craft logs don't have user field
                'level' => strtolower($matches[3]),
                'category' => $matches[2], // web, console, queue, etc.
                'class' => $matches[4], // Fully qualified class name
                'message' => $matches[5],
                'context' => isset($matches[6]) ? $matches[6] : '',
                'lineNumber' => $lineNumber,
                'raw' => $line,
                'format' => 'craft',
            ];
        }

        return null;
    }

    /**
     * Parse PHP error log format: [DD-MMM-YYYY HH:MM:SS Timezone] PHP Error Type: message
     */
    private function _parsePhpErrorEntry(string $line, int $lineNumber = 0): ?array
    {
        // Parse PHP error format: [01-Nov-2025 12:34:56 UTC] PHP Warning: message in /path/to/file.php on line 123
        if (preg_match('/^\[(\d{2}-[A-Za-z]{3}-\d{4} \d{2}:\d{2}:\d{2}[^\]]*)\]\s+PHP\s+([^:]+):\s+(.*)$/', $line, $matches)) {
            $phpTimestamp = $matches[1];
            $errorType = trim($matches[2]);
            $message = $matches[3];

            // Convert PHP timestamp to standard format
            try {
                $date = \DateTime::createFromFormat('d-M-Y H:i:s T', $phpTimestamp);
                $timestamp = $date ? $date->format('Y-m-d H:i:s') : $phpTimestamp;
            } catch (\Exception $e) {
                $timestamp = $phpTimestamp;
            }

            // Map PHP error types to log levels
            $level = match (true) {
                str_contains($errorType, 'Fatal') || str_contains($errorType, 'Error') => 'error',
                str_contains($errorType, 'Warning') => 'warning',
                str_contains($errorType, 'Notice') || str_contains($errorType, 'Deprecated') => 'info',
                default => 'error',
            };

            return [
                'timestamp' => $timestamp,
                'user' => 'System',
                'level' => $level,
                'category' => 'php-errors',
                'message' => $errorType . ': ' . $message,
                'context' => '',
                'lineNumber' => $lineNumber,
                'raw' => $line,
                'format' => 'php',
            ];
        }

        return null;
    }

    /**
     * Parse unknown/fallback log format
     */
    private function _parseUnknownLogEntry(string $line, int $lineNumber = 0): array
    {
        return [
            'timestamp' => '',
            'user' => '',
            'level' => 'unknown',
            'category' => '',
            'message' => $line,
            'context' => '',
            'lineNumber' => $lineNumber,
            'raw' => $line,
            'format' => 'unknown',
        ];
    }
}
