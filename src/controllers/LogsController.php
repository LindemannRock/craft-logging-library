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
use lindemannrock\logginglibrary\LoggingLibrary;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Generic Logs Controller
 * Provides log viewing functionality for any plugin using the logging library
 *
 * @since 1.0.0
 */
class LogsController extends Controller
{
    /**
     * @inheritdoc
     */
    protected array|int|bool $allowAnonymous = false;

    /**
     * Display logs with pagination
     *
     * @return Response
     * @since 1.0.0
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

            // Check view permissions if specified
            $this->_checkPermissions($config['viewPermissions'] ?? []);

            // Check if user can download (only if downloadPermissions is configured)
            $downloadPermissions = $config['downloadPermissions'] ?? [];
            $canDownload = !empty($downloadPermissions) && $this->_hasPermission($downloadPermissions);

            $limit = $config['itemsPerPage'] ?? 50;
            $pluginName = $config['pluginName'];
        } else {
            // Standalone mode - no specific config needed
            $this->_checkPermissions([LoggingLibrary::PERMISSION_VIEW_ALL_LOGS]);
            $config = null;
            $limit = 50; // Default for standalone
            $pluginName = 'All Logs';
            $canDownload = $this->_hasPermission([LoggingLibrary::PERMISSION_DOWNLOAD_ALL_LOGS]);
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
            'canDownload' => $canDownload,
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
            'logConfig' => $config,
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
     *
     * @return Response
     * @since 1.0.0
     */
    public function actionDownload(): Response
    {
        $request = Craft::$app->getRequest();

        // Get plugin handle from query param (passed by template)
        $pluginHandle = trim($request->getRequiredParam('pluginHandle'));

        // Validate plugin handle (only allow alphanumeric and dash)
        if (!preg_match('/^[a-zA-Z0-9\-]+$/', $pluginHandle)) {
            throw new \InvalidArgumentException('Invalid plugin handle');
        }

        // Detect standalone mode (viewing all logs)
        $isStandalone = ($pluginHandle === 'logging-library');

        if ($isStandalone) {
            // Standalone mode - permission-gated
            $this->_checkPermissions([LoggingLibrary::PERMISSION_VIEW_ALL_LOGS]);
            $this->_checkPermissions([LoggingLibrary::PERMISSION_DOWNLOAD_ALL_LOGS]);

            // Get filename from query param
            $filename = trim($request->getRequiredParam('file'));

            // Validate filename (only allow alphanumeric, dash, underscore, dot)
            if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $filename)) {
                throw new \InvalidArgumentException('Invalid filename');
            }

            // Ensure it's a .log file
            if (!str_ends_with(strtolower($filename), '.log')) {
                throw new \InvalidArgumentException('Invalid file type');
            }

            $logPath = Craft::$app->getPath()->getLogPath() . '/' . $filename;

            if (!file_exists($logPath)) {
                throw new NotFoundHttpException('Log file not found');
            }

            return Craft::$app->getResponse()->sendFile($logPath, $filename, [
                'mimeType' => 'text/plain',
                'inline' => false,
            ]);
        }

        // Plugin-specific mode
        $config = LoggingLibrary::getConfig($pluginHandle);

        if (!$config) {
            throw new NotFoundHttpException('Plugin logging not configured');
        }

        // Check if log viewer is enabled
        if (!($config['enableLogViewer'] ?? false)) {
            throw new NotFoundHttpException('Log viewer is disabled for this plugin');
        }

        // Check download permissions
        $this->_checkPermissions($config['downloadPermissions'] ?? []);

        $date = trim($request->getRequiredParam('date'));

        // Validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            throw new \InvalidArgumentException('Invalid date format');
        }

        $logPath = Craft::$app->getPath()->getLogPath() . "/{$pluginHandle}-{$date}.log";

        if (!file_exists($logPath)) {
            throw new NotFoundHttpException('Log file not found');
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
     * Check permissions for accessing logs (throws exception if not allowed)
     */
    private function _checkPermissions(array $permissions): void
    {
        if (empty($permissions)) {
            return; // No permissions required
        }

        if (!$this->_hasPermission($permissions)) {
            throw new ForbiddenHttpException('User does not have permission to view logs');
        }
    }

    /**
     * Check if user has any of the specified permissions
     */
    private function _hasPermission(array $permissions): bool
    {
        if (Craft::$app->getUser()->getIsAdmin()) {
            return true;
        }

        if (empty($permissions)) {
            return true; // No permissions required
        }

        foreach ($permissions as $permission) {
            if (Craft::$app->getUser()->checkPermission($permission)) {
                return true;
            }
        }

        return false;
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

        // Apply level filter
        if ($level !== 'all') {
            $logQuery->andFilterWhere(['level' => $level]);
        }

        // Get all results first (we'll filter search manually)
        $logs = $logQuery->all();

        // Apply search filter manually since ArrayQuery doesn't support LIKE
        if ($search) {
            $logs = array_values(array_filter($logs, function($log) use ($search) {
                return stripos($log['message'] ?? '', $search) !== false ||
                       stripos($log['context'] ?? '', $search) !== false;
            }));
        }

        // Count total before pagination
        $totalCount = count($logs);

        // Apply sorting
        $orderDirection = $dir === 'asc' ? SORT_ASC : SORT_DESC;

        if ($sort === 'level') {
            $levelOrder = ['error' => 1, 'warning' => 2, 'info' => 3, 'debug' => 4, 'unknown' => 5];
            usort($logs, function($a, $b) use ($levelOrder, $orderDirection) {
                $aLevel = $levelOrder[$a['level'] ?? 'unknown'] ?? 99;
                $bLevel = $levelOrder[$b['level'] ?? 'unknown'] ?? 99;
                $result = $aLevel - $bLevel;
                return $orderDirection === SORT_ASC ? $result : -$result;
            });
        } else {
            // Sort by the requested column
            usort($logs, function($a, $b) use ($sort, $orderDirection) {
                $aVal = $a[$sort] ?? '';
                $bVal = $b[$sort] ?? '';
                $result = $aVal <=> $bVal;
                return $orderDirection === SORT_ASC ? $result : -$result;
            });
        }

        // Apply pagination
        $offset = ($page - 1) * $limit;
        $logs = array_slice($logs, $offset, $limit);

        // Add line numbers
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

        // Apply level filter
        if ($level !== 'all') {
            $logQuery->andFilterWhere(['level' => $level]);
        }

        // Get all results
        $logs = $logQuery->all();

        // Apply search filter manually since ArrayQuery doesn't support LIKE
        if ($search) {
            $logs = array_filter($logs, function($log) use ($search) {
                return stripos($log['message'] ?? '', $search) !== false ||
                       stripos($log['context'] ?? '', $search) !== false;
            });
        }

        return count($logs);
    }
}
