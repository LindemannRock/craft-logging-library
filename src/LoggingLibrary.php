<?php
/**
 * Logging Library for Craft CMS
 *
 * Main module for logging configuration and setup
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2025 LindemannRock
 */

namespace lindemannrock\logginglibrary;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\events\RegisterCacheOptionsEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterUserPermissionsEvent;
use craft\log\MonologTarget;
use craft\services\UserPermissions;
use craft\utilities\ClearCaches;
use craft\web\UrlManager;
use lindemannrock\base\helpers\CpNavHelper;
use lindemannrock\base\helpers\PluginHelper;
use lindemannrock\logginglibrary\models\Settings;
use lindemannrock\logginglibrary\services\LogCacheService;
use lindemannrock\logginglibrary\services\LogsViewService;
use Monolog\Formatter\LineFormatter;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Psr\Log\LogLevel;
use yii\base\Event;

/**
 * Logging Library Plugin
 * Provides centralized logging configuration for Craft CMS plugins
 *
 * @property-read LogCacheService $logCache
 * @property-read LogsViewService $logsView
 * @since 1.0.0
 */
class LoggingLibrary extends Plugin
{
    public const PERMISSION_VIEW_ALL_LOGS = 'loggingLibrary:viewAllLogs';
    public const PERMISSION_DOWNLOAD_ALL_LOGS = 'loggingLibrary:downloadAllLogs';
    public const PERMISSION_MANAGE_SETTINGS = 'loggingLibrary:manageSettings';

    /**
     * @var string Plugin schema version for migrations
     */
    public string $schemaVersion = '1.0.0';

    /**
     * @var bool Whether the plugin registers a control panel section
     */
    public bool $hasCpSection = true;

    /**
     * @var bool Whether the plugin exposes a control panel settings page
     */
    public bool $hasCpSettings = true;

    /**
     * @var array Registered plugin configurations
     */
    private static array $_pluginConfigs = [];

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();

        // Bootstrap the base plugin helper
        PluginHelper::bootstrap($this, 'loggingLibraryHelper', [], [], [
            'installExperience' => [
                'headline' => Craft::t('logging-library', 'Logging Library'),
                'body' => Craft::t('logging-library', 'Inspect system logs, review plugin logging output, and centralize diagnostics from one control panel workspace.'),
                'ctaLabel' => Craft::t('logging-library', 'Open All Logs'),
                'ctaUrl' => 'logging-library/logs/system',
                'redirectUri' => 'logging-library/logs/system',
                'confettiPreset' => 'surprise',
            ],
        ]);
        PluginHelper::applyPluginNameFromConfig($this);

        // Register services
        $this->setComponents([
            'logCache' => LogCacheService::class,
            'logsView' => LogsViewService::class,
        ]);

        // Register CP routes for all plugins using the logging library
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function(RegisterUrlRulesEvent $event) {
                $event->rules['logging-library'] = 'logging-library/logs/index';
                $event->rules['logging-library/logs'] = 'logging-library/logs/index';
                $event->rules['logging-library/settings'] = 'logging-library/settings/index';
                $event->rules['logging-library/settings/general'] = 'logging-library/settings/general';
                $event->rules['logging-library/settings/interface'] = 'logging-library/settings/interface';
                $event->rules['logging-library/settings/save'] = 'logging-library/settings/save';
                $event->rules['logging-library/logs/system'] = 'logging-library/logs/index';
                $event->rules['logging-library/logs/system/download'] = 'logging-library/logs/download';
            }
        );

        // Register cache clearing options
        Event::on(
            ClearCaches::class,
            ClearCaches::EVENT_REGISTER_CACHE_OPTIONS,
            function(RegisterCacheOptionsEvent $event) {
                $event->options[] = [
                    'key' => 'logging-library-cache',
                    'label' => Craft::t('app', 'Logging Library caches'),
                    'action' => [$this->logCache, 'invalidateCaches'],
                ];
            }
        );

        // Register permissions
        $this->_registerPermissions();
    }

    /**
     * @inheritdoc
     */
    public function getCpNavItem(): ?array
    {
        $settings = $this->getSettings();
        if (!($settings instanceof Settings) || !$settings->showCpSection) {
            return null;
        }

        $item = parent::getCpNavItem();
        $user = Craft::$app->getUser();

        if ($item) {
            $item['label'] = $settings->getFullName();

            $sections = $this->getCpSections($settings);
            $item['subnav'] = CpNavHelper::buildSubnav($user, $settings, $sections);

            if (empty($item['subnav'])) {
                return null;
            }
        }

        return $item;
    }

    /**
     * Get CP sections for nav + default route resolution.
     */
    public function getCpSections(Settings $settings): array
    {
        return [
            [
                'key' => 'all-logs',
                'label' => Craft::t('logging-library', 'All Logs'),
                'url' => 'logging-library/logs',
                'permissionsAll' => [self::PERMISSION_VIEW_ALL_LOGS],
            ],
            [
                'key' => 'settings',
                'label' => Craft::t('logging-library', 'Settings'),
                'url' => 'logging-library/settings',
                'permissionsAll' => [self::PERMISSION_MANAGE_SETTINGS],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function setSettings(array|Model $settings): void
    {
        // No-op: settings come from loadFromDatabase() in createSettingsModel()
    }

    /**
     * @inheritdoc
     */
    protected function createSettingsModel(): ?Model
    {
        try {
            return Settings::loadFromDatabase();
        } catch (\Throwable $e) {
            Craft::warning('Could not load Logging Library settings from database: ' . $e->getMessage(), __METHOD__);
            return new Settings();
        }
    }

    /**
     * @inheritdoc
     */
    public function getSettings(): ?Model
    {
        $settings = parent::getSettings();

        if ($settings instanceof Settings) {
            $config = Craft::$app->getConfig()->getConfigFromFile('logging-library');
            if (!empty($config) && is_array($config)) {
                foreach ($config as $key => $value) {
                    if (property_exists($settings, $key)) {
                        $settings->$key = $value;
                    }
                }
            }
        }

        return $settings;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsResponse(): mixed
    {
        return Craft::$app->controller->redirect('logging-library/settings');
    }

    /**
     * Configure logging for a plugin
     *
     * IMPORTANT: Debug level logging only works when Craft's DEV_MODE is true.
     * When DEV_MODE=false (production/staging), Craft::debug() calls are ignored
     * for security reasons. Only INFO, WARNING, and ERROR levels will work.
     *
     * @param array $config Plugin configuration array
     */
    public static function configure(array $config): void
    {
        $handle = $config['pluginHandle'] ?? null;
        if (!$handle) {
            throw new \InvalidArgumentException('Plugin handle is required for logging configuration');
        }

        // Check if we need to reconfigure logging (expensive operation)
        $existingConfig = self::$_pluginConfigs[$handle] ?? null;
        $needsLoggingSetup = !$existingConfig || $existingConfig['logLevel'] !== ($config['logLevel'] ?? 'info');

        // Detect edge/CDN hosting environments
        $isEdgeEnvironment = self::_detectEdgeEnvironment();

        // Validate required config
        $config = array_merge([
            'pluginName' => ucfirst($handle),
            'logLevel' => 'info', // Options: 'debug', 'info', 'warning', 'error'
            'retention' => 30,
            'maxFileSize' => 10240, // 10MB
            'enableLogViewer' => !$isEdgeEnvironment, // Auto-disable on edge platforms
            'viewSystemLogsPermissions' => [], // Permissions required to view system logs
            'downloadSystemLogsPermissions' => [], // Permissions required to download system logs
            'itemsPerPage' => 50, // Default entries per page in log viewer
        ], $config);

        // Always store the latest config (permissions may have changed)
        self::$_pluginConfigs[$handle] = $config;

        // Only configure logging if needed (expensive operation)
        if ($needsLoggingSetup) {
            self::_configureLogging($handle, $config);
        }

        // Register routes if log viewer is enabled (idempotent)
        if ($config['enableLogViewer']) {
            self::_registerRoutes($handle);
        }
    }

    /**
     * Get configuration for a plugin
     *
     * @param string $handle Plugin handle
     * @return array|null Plugin configuration or null if not found
     */
    public static function getConfig(string $handle): ?array
    {
        return self::$_pluginConfigs[$handle] ?? null;
    }

    /**
     * Get all registered plugin configurations
     *
     * @return array All plugin configurations
     */
    public static function getAllConfigs(): array
    {
        return self::$_pluginConfigs;
    }

    /**
     * Configure dedicated logging for a plugin
     */
    private static function _configureLogging(string $handle, array $config): void
    {
        // CRITICAL: Exclude our category from the global monologTargetConfig
        // This prevents global targets from filtering our messages
        $logComponent = Craft::$app->getLog();

        // Get current monolog config or initialize it
        $monologConfig = $logComponent->monologTargetConfig ?? [];
        $monologConfig['except'] = $monologConfig['except'] ?? [];

        // Add our handle to the except list if not already there
        if (!in_array($handle, $monologConfig['except'])) {
            $monologConfig['except'][] = $handle;
            $logComponent->monologTargetConfig = $monologConfig;
        }

        // Remove ALL existing targets for this handle from dispatcher
        $dispatcher = Craft::getLogger()->dispatcher;
        $targetsToRemove = [];
        foreach ($dispatcher->targets as $key => $target) {
            if ($target instanceof MonologTarget &&
                !empty($target->categories) &&
                in_array($handle, $target->categories)) {
                $targetsToRemove[] = $key;
            }
        }
        foreach ($targetsToRemove as $key) {
            unset($dispatcher->targets[$key]);
        }

        // Reset the array keys after removal
        $dispatcher->targets = array_values($dispatcher->targets);

        // Create a MonologTarget following the exact PutYourLightsOn pattern

        // Map string level to LogLevel constant
        $levelMap = [
            'debug' => LogLevel::DEBUG,
            'info' => LogLevel::INFO,
            'notice' => LogLevel::NOTICE,
            'warning' => LogLevel::WARNING,
            'error' => LogLevel::ERROR,
            'critical' => LogLevel::CRITICAL,
            'alert' => LogLevel::ALERT,
            'emergency' => LogLevel::EMERGENCY,
        ];

        $logLevelConstant = $levelMap[$config['logLevel']] ?? LogLevel::INFO;

        // Create a custom processor to add user info and clean up context
        $contextProcessor = new class() implements ProcessorInterface {
            public function __invoke(LogRecord $record): LogRecord
            {
                // Add user info
                $user = Craft::$app->getUser()->getIdentity();
                $record->extra['user'] = $user ? 'user:' . $user->id : '';

                // Remove Yii2's automatic context fields (trace, memory, category)
                // Keep only user-provided context
                $context = $record->context;
                unset($context['trace'], $context['memory'], $context['category']);

                return $record->with(context: $context);
            }
        };

        // Create a custom formatter that only shows context when not empty
        $formatter = new class() extends LineFormatter {
            public function __construct()
            {
                parent::__construct(
                    format: null,
                    dateFormat: 'Y-m-d H:i:s',
                    allowInlineLineBreaks: true,
                );
            }

            public function format(LogRecord $record): string
            {
                $output = sprintf(
                    "%s [%s][%s][%s] %s",
                    $record->datetime->format($this->dateFormat),
                    $record->extra['user'] ?? '',
                    $record->level->getName(),
                    $record->channel,
                    $record->message
                );

                // Only add context if it's not empty
                if (!empty($record->context)) {
                    $output .= ' ' . $this->toJson($record->context, true);
                }

                return $output . "\n";
            }
        };

        $target = new MonologTarget([
            'name' => $handle,
            'categories' => [$handle],
            'level' => $logLevelConstant,  // Use PSR-3 LogLevel constant
            'logContext' => false,
            'allowLineBreaks' => false,
            'processor' => $contextProcessor,
            'formatter' => $formatter,
        ]);


        // Add the target to the log dispatcher
        $dispatcher = Craft::getLogger()->dispatcher;
        $dispatcher->targets[] = $target;

        // Initialize the target immediately
        $target->init();
    }

    /**
     * Register CP routes for log viewer
     */
    private static function _registerRoutes(string $handle): void
    {
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function(RegisterUrlRulesEvent $event) use ($handle) {
                // Logs routes
                $event->rules[$handle . '/logs'] = 'logging-library/logs/index';
                $event->rules[$handle . '/logs/system'] = 'logging-library/logs/index';
                $event->rules[$handle . '/logs/system/download'] = 'logging-library/logs/download';
            }
        );
    }

    /**
     * Add logs section to plugin's CP nav item
     *
     * @param array $navItem The plugin's CP nav item
     * @param string $handle Plugin handle
     * @param array $permissions Permissions required to view logs
     * @return array Modified nav item with logs subnav
     * @since 1.7.0
     */
    public static function addLogsNav(array $navItem, string $handle, array $permissions = []): array
    {
        $config = self::getConfig($handle);
        if (!$config) {
            return $navItem;
        }

        $systemLogsEnabled = $config['enableLogViewer'] ?? false;
        $logMenuItems = $config['logMenuItems'] ?? [];

        // Check if there are non-system log types (e.g., activity logs)
        $hasOtherLogTypes = !empty(array_filter(
            array_keys($logMenuItems),
            fn($key) => $key !== 'system'
        ));

        // Show logs nav if system logs enabled OR other log types exist
        if (!$systemLogsEnabled && !$hasOtherLogTypes) {
            return $navItem;
        }

        // Check permissions if specified (user needs ANY of the permissions)
        if (!empty($permissions)) {
            $hasPermission = false;
            foreach ($permissions as $permission) {
                if (Craft::$app->getUser()->checkPermission($permission)) {
                    $hasPermission = true;
                    break;
                }
            }
            if (!$hasPermission) {
                return $navItem;
            }
        }

        $navItem['subnav'] = $navItem['subnav'] ?? [];
        $navItem['subnav']['logs'] = [
            'label' => $config['logMenuLabel'] ?? Craft::t($handle, 'Logs'),
            'url' => $handle . '/logs', // Base URL - controller handles redirect
        ];

        return $navItem;
    }

    /**
     * Get available log files for a plugin
     *
     * @param string $handle Plugin handle
     * @return array Array of log file information
     */
    public static function getLogFiles(string $handle): array
    {
        $logPath = Craft::$app->getPath()->getLogPath();
        $files = [];

        if (is_dir($logPath)) {
            $pattern = $logPath . '/' . $handle . '-*.log';
            $logFiles = glob($pattern);

            foreach ($logFiles as $file) {
                if (preg_match('/' . preg_quote($handle, '/') . '-(\d{4}-\d{2}-\d{2})\.log$/', basename($file), $matches)) {
                    $date = $matches[1];
                    $files[$date] = [
                        'date' => $date,
                        'size' => filesize($file),
                        'formattedSize' => Craft::$app->getFormatter()->asShortSize(filesize($file)),
                        'lastModified' => filemtime($file),
                        'path' => $file,
                    ];
                }
            }
        }

        // Sort by date descending
        krsort($files);

        return array_values($files);
    }

    /**
     * Get all log files from storage/logs directory (for standalone viewer)
     *
     * @return array Array of log file information grouped by source
     * @since 5.0.0
     */
    public static function getAllLogFiles(): array
    {
        $logPath = Craft::$app->getPath()->getLogPath();
        $files = [];

        if (!is_dir($logPath)) {
            return [];
        }

        $allLogFiles = glob($logPath . '/*.log*');

        foreach ($allLogFiles as $file) {
            $basename = basename($file);
            $size = filesize($file);
            $lastModified = filemtime($file);

            // Skip empty or very small files
            if ($size < 10) {
                continue;
            }

            $fileInfo = [
                'filename' => $basename,
                'size' => $size,
                'formattedSize' => Craft::$app->getFormatter()->asShortSize($size),
                'lastModified' => $lastModified,
                'path' => $file,
            ];

            // Plugin logs: {plugin-handle}-{YYYY-MM-DD}.log
            if (preg_match('/^([a-z0-9\-]+)-(\d{4}-\d{2}-\d{2})\.log$/', $basename, $matches)) {
                $pluginHandle = $matches[1];
                $date = $matches[2];
                $fileInfo['source'] = $pluginHandle;
                $fileInfo['type'] = 'plugin';
                $fileInfo['date'] = $date;
                $fileInfo['category'] = $pluginHandle;
            }
            // Craft web logs: web-{YYYY-MM-DD}.log or web.log
            elseif (preg_match('/^web(-(\d{4}-\d{2}-\d{2}))?\.log(\.\d+)?$/', $basename, $matches)) {
                $date = $matches[2] ?? null;
                $rotation = $matches[3] ?? null;
                $fileInfo['source'] = 'web';
                $fileInfo['type'] = 'craft';
                $fileInfo['date'] = $date ?: 'current';
                $fileInfo['category'] = 'web';
                if ($rotation) {
                    $fileInfo['rotation'] = ltrim($rotation, '.');
                }
            }
            // Console logs: console-{YYYY-MM-DD}.log
            elseif (preg_match('/^console(-(\d{4}-\d{2}-\d{2}))?\.log$/', $basename, $matches)) {
                $date = $matches[2] ?? null;
                $fileInfo['source'] = 'console';
                $fileInfo['type'] = 'craft';
                $fileInfo['date'] = $date ?: 'current';
                $fileInfo['category'] = 'console';
            }
            // Queue logs: queue-{YYYY-MM-DD}.log
            elseif (preg_match('/^queue(-(\d{4}-\d{2}-\d{2}))?\.log$/', $basename, $matches)) {
                $date = $matches[2] ?? null;
                $fileInfo['source'] = 'queue';
                $fileInfo['type'] = 'craft';
                $fileInfo['date'] = $date ?: 'current';
                $fileInfo['category'] = 'queue';
            }
            // PHP errors: phperrors.log
            elseif ($basename === 'phperrors.log') {
                $fileInfo['source'] = 'php-errors';
                $fileInfo['type'] = 'php';
                $fileInfo['date'] = 'current';
                $fileInfo['category'] = 'php-errors';
            }
            // Other/unknown logs
            else {
                $fileInfo['source'] = 'other';
                $fileInfo['type'] = 'unknown';
                $fileInfo['date'] = 'unknown';
                $fileInfo['category'] = 'other';
            }

            $files[] = $fileInfo;
        }

        // Sort by last modified date descending (newest first)
        usort($files, function($a, $b) {
            return $b['lastModified'] - $a['lastModified'];
        });

        return $files;
    }

    /**
     * Detect log format from a log line
     *
     * @param string $line A line from the log file
     * @return string Format type: 'plugin', 'craft', 'php', or 'unknown'
     * @since 5.0.0
     */
    public static function detectLogFormat(string $line): string
    {
        if (empty($line)) {
            return 'unknown';
        }

        // Plugin format: YYYY-MM-DD HH:MM:SS [user][LEVEL][category] message
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\s+\[.*?\]\[.*?\]\[.*?\]/', $line)) {
            return 'plugin';
        }

        // Craft format: YYYY-MM-DD HH:MM:SS [category.LEVEL] [class.name] message
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\s+\[[a-z]+\.[A-Z]+\]/', $line)) {
            return 'craft';
        }

        // PHP error format: [DD-MMM-YYYY HH:MM:SS Timezone] PHP Error Type:
        if (preg_match('/^\[\d{2}-[A-Za-z]{3}-\d{4} \d{2}:\d{2}:\d{2}/', $line)) {
            return 'php';
        }

        return 'unknown';
    }

    /**
     * Detect edge/CDN hosting environments
     *
     * @return bool True if running on an edge platform where file logging may not work
     */
    private static function _detectEdgeEnvironment(): bool
    {
        return
            isset($_ENV['SERVD_PROJECT_SLUG']);             // Servd.host - VERIFIED and tested
            // TODO: Add other platforms after testing actual deployments with Craft CMS
    }

    /**
     * Register user permissions for standalone log viewer
     */
    private function _registerPermissions(): void
    {
        Event::on(
            UserPermissions::class,
            UserPermissions::EVENT_REGISTER_PERMISSIONS,
            function(RegisterUserPermissionsEvent $event) {
                $event->permissions[] = [
                    'heading' => $this->name,
                    'permissions' => [
                        self::PERMISSION_VIEW_ALL_LOGS => [
                            'label' => 'View all system logs',
                            'nested' => [
                                self::PERMISSION_DOWNLOAD_ALL_LOGS => [
                                    'label' => 'Download all system logs',
                                ],
                            ],
                        ],
                        self::PERMISSION_MANAGE_SETTINGS => [
                            'label' => 'Manage settings',
                        ],
                    ],
                ];
            }
        );
    }
}
