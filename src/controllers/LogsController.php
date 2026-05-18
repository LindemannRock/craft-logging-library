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
use lindemannrock\base\helpers\CpNavHelper;
use lindemannrock\logginglibrary\LoggingLibrary;
use lindemannrock\logginglibrary\models\Settings;
use lindemannrock\logginglibrary\services\LogCacheService;
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
     */
    public function actionIndex(): Response
    {
        $request = Craft::$app->getRequest();
        $user = Craft::$app->getUser();

        // Get plugin handle from the URL segment
        $pluginHandle = $this->_getPluginHandleFromUrl();

        // Detect standalone mode (viewing all logs)
        $isStandalone = ($pluginHandle === 'logging-library');

        if (!$isStandalone) {
            // Plugin-specific mode - use existing config
            $config = LoggingLibrary::getConfig($pluginHandle);

            if (!$config) {
                throw new NotFoundHttpException(Craft::t('logging-library', 'Plugin logging not configured'));
            }

            // Check if log viewer is enabled
            if (!($config['enableLogViewer'] ?? false)) {
                // System logs are disabled - check if we can redirect to an alternative
                $logMenuItems = $config['logMenuItems'] ?? null;
                $segments = $request->getSegments();
                $isBaseLogsUrl = !in_array('system', $segments, true);

                // If on base /logs URL and there are alternative menu items, redirect to first non-system item
                if ($isBaseLogsUrl && !empty($logMenuItems)) {
                    foreach ($logMenuItems as $key => $item) {
                        if ($key !== 'system' && !empty($item['url'])) {
                            return $this->redirect($item['url']);
                        }
                    }
                }

                throw new NotFoundHttpException(Craft::t('logging-library', 'Log viewer is disabled for this plugin'));
            }

            // Check view permissions if specified
            $this->_checkPermissions($config['viewSystemLogsPermissions'] ?? []);

            // Check if user can download (only if downloadSystemLogsPermissions is configured)
            $downloadPermissions = $config['downloadSystemLogsPermissions'] ?? [];
            $canDownload = !empty($downloadPermissions) && $this->_hasPermission($downloadPermissions);

            $limit = $config['itemsPerPage'] ?? 50;
            $pluginName = $config['pluginName'];
            $logMenuItems = $config['logMenuItems'] ?? null;
            $logMenuLabel = $config['logMenuLabel'] ?? null;
        } else {
            // Standalone mode - no specific config needed
            $settings = LoggingLibrary::getInstance()->getSettings();
            if ($settings instanceof Settings && !LoggingLibrary::areLogViewersAvailable($settings)) {
                throw new NotFoundHttpException(Craft::t('logging-library', 'Log viewer is disabled for this environment'));
            }

            if (!$user->checkPermission(LoggingLibrary::PERMISSION_VIEW_ALL_LOGS)) {
                if ($settings instanceof Settings) {
                    $sections = LoggingLibrary::getInstance()->getCpSections($settings);
                    $route = CpNavHelper::firstAccessibleRoute($user, $settings, $sections);
                    if ($route) {
                        return $this->redirect($route);
                    }
                }

                $this->requirePermission(LoggingLibrary::PERMISSION_VIEW_ALL_LOGS);
            }

            $this->_checkPermissions([LoggingLibrary::PERMISSION_VIEW_ALL_LOGS]);
            $config = null;
            $limit = $settings instanceof Settings ? $settings->itemsPerPage : 50;
            $pluginName = $settings instanceof Settings ? $settings->getFullName() : LoggingLibrary::getInstance()->name;
            $canDownload = $this->_hasPermission([LoggingLibrary::PERMISSION_DOWNLOAD_ALL_LOGS]);
            $logMenuItems = null;
            $logMenuLabel = null;
        }

        // Get filter parameters
        $level = trim($request->getParam('level', 'all'));
        $category = trim($request->getParam('category', 'all'));
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
            $sourceGroups = $this->_buildSourceGroups($allLogFiles, $sources);

            // Filter files by selected source
            if ($source !== 'all') {
                $logFiles = array_values(array_filter($allLogFiles, fn($file) => $file['source'] === $source));
            } else {
                $logFiles = $allLogFiles;
            }
        } else {
            $logFiles = LoggingLibrary::getLogFiles($pluginHandle);
            $sources = [];
            $sourceGroups = [];
        }

        // Get the selected log file
        $selectedFile = $this->_getSelectedLogFile($request, $logFiles, $isStandalone);

        // Read and parse log entries (cached for performance)
        if ($selectedFile) {
            $logPage = $this->_getLogPageFromFile(
                $selectedFile['path'],
                $level,
                $category,
                $search,
                $sort,
                $dir,
                $page,
                $limit
            );

            $logEntries = $logPage['entries'];
            $totalEntries = $logPage['total'];
            $category = $logPage['category'];
            $categoryOptions = $logPage['categoryOptions'];

            // Detect which columns have variance (should be shown)
            $columnVariance = $this->_detectColumnVariance($logEntries);
        } else {
            $logEntries = [];
            $totalEntries = 0;
            $categoryOptions = [];
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
            'logMenuItems' => $logMenuItems,
            'logMenuLabel' => $logMenuLabel,
            'logFiles' => array_values($logFiles),
            'selectedFile' => $selectedFile,
            'sources' => $sources,
            'sourceGroups' => $sourceGroups,
            'categoryOptions' => $categoryOptions,
            'logEntries' => $logEntries,
            'columnVariance' => $columnVariance,
            'canDownload' => $canDownload,
            'filters' => [
                'level' => $level,
                'category' => $category,
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
                'all' => Craft::t('logging-library', 'All Levels'),
                'error' => Craft::t('logging-library', 'Error'),
                'warning' => Craft::t('logging-library', 'Warning'),
                'info' => Craft::t('logging-library', 'Info'),
                'debug' => Craft::t('logging-library', 'Debug'),
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
            $settings = LoggingLibrary::getInstance()->getSettings();
            if ($settings instanceof Settings && !LoggingLibrary::areLogViewersAvailable($settings)) {
                throw new NotFoundHttpException(Craft::t('logging-library', 'Log viewer is disabled for this environment'));
            }

            // Standalone mode - permission-gated
            $this->_checkPermissions([LoggingLibrary::PERMISSION_VIEW_ALL_LOGS]);
            $this->_checkPermissions([LoggingLibrary::PERMISSION_DOWNLOAD_ALL_LOGS]);

            // Get filename from query param
            $filename = trim($request->getRequiredParam('file'));

            // Validate filename (only allow alphanumeric, dash, underscore, dot)
            if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $filename)) {
                throw new \InvalidArgumentException('Invalid filename');
            }

            // Ensure it's a .log file (allow rotated variants like web.log.1)
            if (!preg_match('/\.log(\.\d+)?$/i', $filename)) {
                throw new \InvalidArgumentException('Invalid file type');
            }

            $logPath = Craft::$app->getPath()->getLogPath() . '/' . $filename;

            if (!file_exists($logPath)) {
                throw new NotFoundHttpException(Craft::t('logging-library', 'Log file not found'));
            }

            return Craft::$app->getResponse()->sendFile($logPath, $filename, [
                'mimeType' => 'text/plain',
                'inline' => false,
            ]);
        }

        // Plugin-specific mode
        $config = LoggingLibrary::getConfig($pluginHandle);

        if (!$config) {
            throw new NotFoundHttpException(Craft::t('logging-library', 'Plugin logging not configured'));
        }

        // Check if log viewer is enabled
        if (!($config['enableLogViewer'] ?? false)) {
            throw new NotFoundHttpException(Craft::t('logging-library', 'Log viewer is disabled for this plugin'));
        }

        // Check download permissions
        $this->_checkPermissions($config['downloadSystemLogsPermissions'] ?? []);

        $date = trim($request->getRequiredParam('date'));

        // Validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            throw new \InvalidArgumentException('Invalid date format');
        }

        $logPath = Craft::$app->getPath()->getLogPath() . "/{$pluginHandle}-{$date}.log";

        if (!file_exists($logPath)) {
            throw new NotFoundHttpException(Craft::t('logging-library', 'Log file not found'));
        }

        return Craft::$app->getResponse()->sendFile($logPath, "{$pluginHandle}-{$date}.log", [
            'mimeType' => 'text/plain',
            'inline' => false,
        ]);
    }

    /**
     * Invalidate the parsed cache for the selected log file and return to the viewer.
     */
    public function actionRefreshCache(): Response
    {
        $this->requirePostRequest();

        $request = Craft::$app->getRequest();
        $pluginHandle = trim($request->getRequiredParam('pluginHandle'));

        if (!preg_match('/^[a-zA-Z0-9\-]+$/', $pluginHandle)) {
            throw new \InvalidArgumentException('Invalid plugin handle');
        }

        $isStandalone = ($pluginHandle === 'logging-library');

        if ($isStandalone) {
            $settings = LoggingLibrary::getInstance()->getSettings();
            if ($settings instanceof Settings && !LoggingLibrary::areLogViewersAvailable($settings)) {
                throw new NotFoundHttpException(Craft::t('logging-library', 'Log viewer is disabled for this environment'));
            }

            $this->_checkPermissions([LoggingLibrary::PERMISSION_VIEW_ALL_LOGS]);

            $filename = trim($request->getRequiredParam('file'));
            if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $filename) || !preg_match('/\.log(\.\d+)?$/i', $filename)) {
                throw new \InvalidArgumentException('Invalid filename');
            }

            $logPath = Craft::$app->getPath()->getLogPath() . '/' . $filename;
        } else {
            $config = LoggingLibrary::getConfig($pluginHandle);
            if (!$config) {
                throw new NotFoundHttpException(Craft::t('logging-library', 'Plugin logging not configured'));
            }

            if (!($config['enableLogViewer'] ?? false)) {
                throw new NotFoundHttpException(Craft::t('logging-library', 'Log viewer is disabled for this plugin'));
            }

            $this->_checkPermissions($config['viewSystemLogsPermissions'] ?? []);

            $date = trim($request->getRequiredParam('date'));
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                throw new \InvalidArgumentException('Invalid date format');
            }

            $logPath = Craft::$app->getPath()->getLogPath() . "/{$pluginHandle}-{$date}.log";
        }

        if (!file_exists($logPath)) {
            throw new NotFoundHttpException(Craft::t('logging-library', 'Log file not found'));
        }

        LoggingLibrary::getInstance()->logCache->invalidateLogCache($logPath);
        $entryCount = LoggingLibrary::getInstance()->logCache->getLogs($logPath)->count();

        if ($request->getAcceptsJson()) {
            return $this->asJson([
                'success' => true,
                'message' => Craft::t('logging-library', 'Log cache refreshed.'),
                'entries' => $entryCount,
            ]);
        }

        Craft::$app->getSession()->setNotice(Craft::t('logging-library', 'Log cache refreshed.'));

        return $this->redirectToPostedUrl(null, $isStandalone ? 'logging-library' : "{$pluginHandle}/logs/system");
    }

    /**
     * Get plugin handle from the current URL
     */
    private function _getPluginHandleFromUrl(): string
    {
        $segments = Craft::$app->getRequest()->getSegments();

        if (($segments[0] ?? null) === 'logging-library') {
            return 'logging-library';
        }

        // The plugin handle should be the first segment before '/logs'
        foreach ($segments as $index => $segment) {
            if ($segment === 'logs' && $index > 0) {
                return $segments[$index - 1];
            }
        }

        throw new NotFoundHttpException(Craft::t('logging-library', 'Unable to determine plugin handle from URL'));
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
            throw new ForbiddenHttpException(Craft::t('logging-library', 'User does not have permission to view logs'));
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
        $sources = ['all' => Craft::t('logging-library', 'All Sources')];
        $seen = [];

        foreach ($logFiles as $file) {
            $source = $file['source'] ?? 'unknown';
            if (!isset($seen[$source])) {
                $seen[$source] = true;
                // Create display name
                $displayName = match ($source) {
                    'web' => Craft::t('logging-library', 'Web'),
                    'console' => Craft::t('logging-library', 'Console'),
                    'queue' => Craft::t('logging-library', 'Queue'),
                    'php-errors' => Craft::t('logging-library', 'PHP Errors'),
                    'other' => Craft::t('logging-library', 'Other'),
                    default => ucwords(str_replace('-', ' ', $source)),
                };
                $sources[$source] = $displayName;
            }
        }

        return $sources;
    }

    /**
     * Build grouped source options for the standalone source filter.
     */
    private function _buildSourceGroups(array $logFiles, array $sources): array
    {
        $systemSources = ['web' => true, 'console' => true, 'queue' => true, 'php-errors' => true];
        $systemColors = [
            'web' => '#3b82f6',
            'queue' => '#8b5cf6',
            'console' => '#14b8a6',
            'php-errors' => '#ef4444',
        ];
        $pluginColor = '#64748b';

        $groups = [
            'all' => [
                'options' => [[
                    'value' => 'all',
                    'label' => $sources['all'],
                    'status' => 'all',
                    'extraParams' => ['file' => '', 'category' => 'all'],
                ]],
            ],
            'system' => [
                'header' => Craft::t('logging-library', 'System'),
                'options' => [],
            ],
            'plugins' => [
                'header' => Craft::t('logging-library', 'Plugins'),
                'options' => [],
            ],
        ];

        $seen = [];
        foreach ($logFiles as $file) {
            $source = $file['source'] ?? 'unknown';
            if (isset($seen[$source]) || $source === 'all') {
                continue;
            }

            $seen[$source] = true;
            $option = [
                'value' => $source,
                'label' => $sources[$source] ?? ucwords(str_replace('-', ' ', $source)),
                'statusColor' => $systemColors[$source] ?? $pluginColor,
                'extraParams' => ['file' => '', 'category' => 'all'],
            ];

            if (isset($systemSources[$source])) {
                $groups['system']['options'][] = $option;
            } else {
                $groups['plugins']['options'][] = $option;
            }
        }

        return array_values(array_filter(
            $groups,
            fn($group) => !empty($group['options'])
        ));
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

            // No selection yet
            return null;
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
     * Get filtered, sorted, paginated log entries plus category metadata in one pass.
     *
     * @return array{entries: array, total: int, category: string, categoryOptions: array}
     */
    private function _getLogPageFromFile(string $filePath, string $level, string $category, string $search, string $sort, string $dir, int $page, int $limit): array
    {
        if (!file_exists($filePath)) {
            return [
                'entries' => [],
                'total' => 0,
                'category' => 'all',
                'categoryOptions' => [],
            ];
        }

        $logs = LoggingLibrary::getInstance()->logCache->getLogs($filePath)->all();

        if ($level !== 'all') {
            $logs = array_values(array_filter($logs, fn($log) => ($log['level'] ?? '') === $level));
        }

        if ($search) {
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

        // Apply sorting with stable parse-order tiebreaker (same-second entries
        // reverse correctly when dir=desc — see LogCacheService::sortLogs)
        $logs = LogCacheService::sortLogs($logs, $sort, $dir);

        $offset = max(0, ($page - 1) * $limit);
        $entries = array_slice($logs, $offset, $limit);

        foreach ($entries as $index => &$log) {
            $log['lineNumber'] = $offset + $index + 1;
        }
        unset($log);

        return [
            'entries' => $entries,
            'total' => $totalCount,
            'category' => $category,
            'categoryOptions' => $this->_buildCategoryOptions($categoryCounts),
        ];
    }

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
}
