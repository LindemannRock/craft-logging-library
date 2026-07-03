<?php
/**
 * Logging Library for Craft CMS
 *
 * Generic controller for viewing plugin logs
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2025-2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\controllers;

use Craft;
use craft\web\Controller;
use lindemannrock\base\helpers\CpNavHelper;
use lindemannrock\logginglibrary\LoggingLibrary;
use lindemannrock\logginglibrary\models\Settings;
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
                $sections = LoggingLibrary::getInstance()->getCpSections($settings);
                $route = CpNavHelper::firstAccessibleRoute($user, $settings, $sections);
                if ($route && $route !== 'logging-library') {
                    return $this->redirect($route);
                }

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

        // ---- Param parsing + allowlist validation -------------------------
        // Every parameter that controls filtering or sorting goes through an
        // explicit allowlist. Off-list values snap back to the default. The
        // service layer also validates `sort`/`category` defensively, but the
        // controller is the primary gate.

        $level = (string) $request->getParam('level', 'all');
        $validLevels = ['all', 'error', 'warning', 'info', 'debug', 'unknown'];
        if (!in_array($level, $validLevels, true)) {
            $level = 'all';
        }

        // `category` and `source` have dynamic value spaces (built from the log
        // files actually present on disk). `category` is validated by
        // LogCacheService::getLogPage() against the indexed counts; `source` is
        // validated below once $sources has been built. Both default to 'all'.
        $category = (string) $request->getParam('category', 'all');
        $source = (string) $request->getParam('source', 'all');

        // 64-char defensive clamp on free-text search. Keeps a runaway payload
        // (URL of any length) from reaching the LIKE comparison.
        $search = trim((string) $request->getParam('search', ''));
        if (mb_strlen($search) > 64) {
            $search = mb_substr($search, 0, 64);
        }

        $validSortFields = ['timestamp', 'level', 'category', 'user', 'message'];
        $sort = (string) $request->getParam('sort', 'timestamp');
        if (!in_array($sort, $validSortFields, true)) {
            $sort = 'timestamp';
        }
        $dir = strtolower((string) $request->getParam('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $page = max(1, (int) $request->getParam('page', 1));
        $limit = max(1, (int) $limit);

        // Get available log files
        if ($isStandalone) {
            $allLogFiles = LoggingLibrary::getAllLogFiles();

            // Extract unique sources for filter dropdown
            $sources = $this->_extractSources($allLogFiles);
            $sourceGroups = $this->_buildSourceGroups($allLogFiles, $sources);

            // Snap unknown source values back to 'all'. $sources only has keys
            // for sources actually present on disk this request, so this also
            // handles stale URL params pointing at sources that no longer exist.
            if (!isset($sources[$source])) {
                $source = 'all';
            }

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
        } else {
            $logEntries = [];
            $totalEntries = 0;
            $categoryOptions = [];
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
            'levels' => $this->_logLevelLabels(),
            'logConfig' => $config,
        ]);
    }

    /**
     * Display recent cache-backed runtime logs.
     *
     * @return Response
     * @since 5.14.0
     */
    public function actionRuntime(): Response
    {
        $user = Craft::$app->getUser();
        $settings = LoggingLibrary::getInstance()->getSettings();

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

        if (!LoggingLibrary::isRuntimeLogStoreEnabled()) {
            throw new NotFoundHttpException(Craft::t('logging-library', 'Recent runtime logs are disabled'));
        }

        $context = $this->_runtimeLogContext($settings instanceof Settings ? $settings : null);
        $logPage = $context['logPage'];

        $totalEntries = $logPage['total'];
        $totalPages = $totalEntries > 0 ? ceil($totalEntries / $context['limit']) : 0;

        return $this->renderTemplate('logging-library/logs/index', [
            'pluginHandle' => 'logging-library',
            'pluginName' => $settings instanceof Settings ? $settings->getFullName() : LoggingLibrary::getInstance()->name,
            'isStandalone' => true,
            'isRuntime' => true,
            'logMenuItems' => null,
            'logMenuLabel' => null,
            'logFiles' => [],
            'selectedFile' => null,
            'sources' => [],
            'sourceGroups' => [],
            'categoryOptions' => $logPage['categoryOptions'],
            'logEntries' => $logPage['entries'],
            'canDownload' => false,
            'filters' => [
                'level' => $context['level'],
                'category' => $logPage['category'],
                'source' => 'all',
                'search' => $context['search'],
                'sort' => $context['sort'],
                'dir' => $context['dir'],
                'page' => $context['page'],
            ],
            'pagination' => [
                'total' => $totalEntries,
                'perPage' => $context['limit'],
                'currentPage' => $context['page'],
                'totalPages' => $totalPages,
            ],
            'levels' => $this->_logLevelLabels(),
            'availableLevels' => $context['runtimeLevels'],
            'runtimeCurrentLevel' => $context['runtimeCurrentLevel'],
            'runtimeMaxEntries' => $context['runtimeMaxEntries'],
            'runtimeRefreshInterval' => $context['runtimeRefreshInterval'],
            'runtimeStoredTotal' => $context['runtimeStoredTotal'],
            'runtimeUsesRedisCache' => Craft::$app->getCache() instanceof \yii\redis\Cache,
            'canClearRuntimeLogs' => $user->checkPermission(LoggingLibrary::PERMISSION_CLEAR_CACHE),
            'logConfig' => null,
        ]);
    }

    /**
     * JSON endpoint backing the runtime log viewer's AJAX auto-refresh.
     *
     * @return Response
     * @since 5.14.0
     */
    public function actionRuntimeData(): Response
    {
        $this->requireAcceptsJson();
        $this->_checkPermissions([LoggingLibrary::PERMISSION_VIEW_ALL_LOGS]);

        if (!LoggingLibrary::isRuntimeLogStoreEnabled()) {
            throw new NotFoundHttpException(Craft::t('logging-library', 'Recent runtime logs are disabled'));
        }

        $settings = LoggingLibrary::getInstance()->getSettings();
        $context = $this->_runtimeLogContext($settings instanceof Settings ? $settings : null);
        $logPage = $context['logPage'];
        $totalEntries = $logPage['total'];
        $totalPages = $totalEntries > 0 ? ceil($totalEntries / $context['limit']) : 0;

        $rowsHtml = '';
        foreach ($logPage['entries'] as $index => $entry) {
            $rowsHtml .= Craft::$app->getView()->renderTemplate('logging-library/logs/_runtime-row', [
                'item' => $entry,
                'levels' => $this->_logLevelLabels(),
                'rowIndex' => $index + 1,
                'colspan' => 5,
            ]);
        }

        if ($rowsHtml === '') {
            $rowsHtml = Craft::$app->getView()->renderTemplate('logging-library/logs/_runtime-empty-row', [
                'message' => $context['runtimeStoredTotal'] > 0
                    ? Craft::t('logging-library', 'No log entries found for the selected filters.')
                    : Craft::t('logging-library', 'No recent runtime logs found. Runtime logs are short-lived and only appear after matching events are captured.'),
                'colspan' => 5,
            ]);
        }

        return $this->asJson([
            'success' => true,
            'rowsHtml' => $rowsHtml,
            'totalCount' => $totalEntries,
            'pagination' => [
                'page' => $context['page'],
                'limit' => $context['limit'],
                'totalCount' => $totalEntries,
                'totalPages' => $totalPages,
            ],
            'refresh' => [
                'enabled' => $context['runtimeRefreshInterval'] > 0,
            ],
        ]);
    }

    /**
     * Clear the cache-backed recent runtime log store.
     *
     * @return Response
     * @since 5.14.0
     */
    public function actionClearRuntime(): Response
    {
        $this->requirePostRequest();
        $this->_checkPermissions([LoggingLibrary::PERMISSION_VIEW_ALL_LOGS]);
        $this->_checkPermissions([LoggingLibrary::PERMISSION_CLEAR_CACHE]);

        if (!LoggingLibrary::isRuntimeLogStoreEnabled()) {
            throw new NotFoundHttpException(Craft::t('logging-library', 'Recent runtime logs are disabled'));
        }

        LoggingLibrary::getInstance()->runtimeLogStore->clear();

        Craft::$app->getSession()->setNotice(Craft::t('logging-library', 'Recent runtime logs cleared.'));

        return $this->redirectToPostedUrl(null, 'logging-library/logs/runtime');
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
        $entryCount = LoggingLibrary::getInstance()->logCache->getLogEntryCount($logPath);

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
            $isSystem = isset($systemSources[$source]);
            $option = [
                'value' => $source,
                'label' => $sources[$source] ?? ucwords(str_replace('-', ' ', $source)),
                'colorKey' => $isSystem ? $source : 'plugin',
                'extraParams' => ['file' => '', 'category' => 'all'],
            ];

            if ($isSystem) {
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
     * Build the filtered runtime log context used by both the page and AJAX refresh endpoint.
     */
    private function _runtimeLogContext(?Settings $settings): array
    {
        $request = Craft::$app->getRequest();
        $runtimeConfig = LoggingLibrary::getRuntimeLogStoreConfig();
        $runtimeLevels = $this->_runtimeLevels($runtimeConfig);

        $level = (string)$request->getParam('level', 'all');
        $validLevels = array_merge(['all'], $runtimeLevels);
        if (!in_array($level, $validLevels, true)) {
            $level = 'all';
        }

        $category = (string)$request->getParam('category', 'all');

        $search = trim((string)$request->getParam('search', ''));
        if (mb_strlen($search) > 64) {
            $search = mb_substr($search, 0, 64);
        }

        $validSortFields = ['timestamp', 'level', 'category', 'user', 'message'];
        $sort = (string)$request->getParam('sort', 'timestamp');
        if (!in_array($sort, $validSortFields, true)) {
            $sort = 'timestamp';
        }

        $dir = strtolower((string)$request->getParam('dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $page = max(1, (int)$request->getParam('page', 1));
        $limit = max(1, $settings instanceof Settings ? $settings->itemsPerPage : 50);

        $ttl = max(1, (int)($runtimeConfig['ttl'] ?? 86400));

        $logPage = LoggingLibrary::getInstance()->runtimeLogStore->getLogPage(
            $level,
            $category,
            $search,
            $sort,
            $dir,
            $page,
            $limit,
            $ttl
        );

        return [
            'runtimeConfig' => $runtimeConfig,
            'runtimeLevels' => $runtimeLevels,
            'runtimeCurrentLevel' => $this->_runtimeCurrentLevel($runtimeLevels),
            'runtimeMaxEntries' => max(1, (int)($runtimeConfig['maxEntries'] ?? 1000)),
            'runtimeRefreshInterval' => max(0, (int)($runtimeConfig['refreshInterval'] ?? 5)),
            'runtimeStoredTotal' => $logPage['storedTotal'],
            'level' => $level,
            'category' => $logPage['category'],
            'search' => $search,
            'sort' => $sort,
            'dir' => $dir,
            'page' => $page,
            'limit' => $limit,
            'logPage' => $logPage,
        ];
    }

    /**
     * Runtime levels configured for the CP filter.
     */
    private function _runtimeLevels(array $runtimeConfig): array
    {
        $runtimeLevels = array_values(array_filter(array_map(
            static fn(string $level): string => $level === 'trace' ? 'debug' : $level,
            (array)($runtimeConfig['levels'] ?? [])
        ), static fn(string $level): bool => in_array($level, ['error', 'warning', 'info', 'debug'], true)));

        if (!Craft::$app->getConfig()->getGeneral()->devMode) {
            $runtimeLevels = array_values(array_filter($runtimeLevels, static fn(string $level): bool => $level !== 'debug'));
        }

        return array_values(array_unique($runtimeLevels));
    }

    /**
     * Most verbose runtime level enabled by configuration.
     */
    private function _runtimeCurrentLevel(array $runtimeLevels): string
    {
        foreach (['debug', 'info', 'warning', 'error'] as $level) {
            if (in_array($level, $runtimeLevels, true)) {
                return $level;
            }
        }

        return 'error';
    }

    /**
     * Viewer labels for canonical log levels.
     */
    private function _logLevelLabels(): array
    {
        return [
            'all' => Craft::t('logging-library', 'All Levels'),
            'error' => Craft::t('logging-library', 'Error'),
            'warning' => Craft::t('logging-library', 'Warning'),
            'info' => Craft::t('logging-library', 'Info'),
            'debug' => Craft::t('logging-library', 'Debug'),
        ];
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

        return LoggingLibrary::getInstance()->logCache->getLogPage(
            $filePath,
            $level,
            $category,
            $search,
            $sort,
            $dir,
            $page,
            $limit
        );
    }
}
