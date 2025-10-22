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
use craft\base\Plugin;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use yii\base\Event;
use yii\base\Module;
use craft\log\MonologTarget;
use Monolog\Formatter\LineFormatter;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Psr\Log\LogLevel;

/**
 * Logging Library Plugin
 * Provides centralized logging configuration for Craft CMS plugins
 */
class LoggingLibrary extends \craft\base\Plugin
{
    /**
     * @var array Registered plugin configurations
     */
    private static array $_pluginConfigs = [];

    /**
     * @var array Already configured log targets to prevent duplicates
     */
    private static array $_configuredTargets = [];

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();


        // Register CP routes for all plugins using the logging library
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['logging-library/logs'] = 'logging-library/logs/index';
                $event->rules['logging-library/logs/download'] = 'logging-library/logs/download';
            }
        );
    }

    /**
     * Configure logging for a plugin
     *
     * IMPORTANT: Debug level logging only works when Craft's DEV_MODE is true.
     * When DEV_MODE=false (production/staging), Craft::debug() calls are ignored
     * for security reasons. Only INFO, WARNING, and ERROR levels will work.
     */
    public static function configure(array $config): void
    {

        $handle = $config['pluginHandle'] ?? null;
        if (!$handle) {
            throw new \InvalidArgumentException('Plugin handle is required for logging configuration');
        }

        // Allow reconfiguration if log level changed, but skip if exact same config
        $existingConfig = self::$_pluginConfigs[$handle] ?? null;
        if ($existingConfig && $existingConfig['logLevel'] === $config['logLevel']) {
            return;
        }


        // Detect edge/CDN hosting environments
        $isEdgeEnvironment = self::_detectEdgeEnvironment();

        // Validate required config
        $config = array_merge([
            'pluginName' => ucfirst($handle),
            'logLevel' => 'info', // Options: 'debug', 'info', 'warning', 'error'
            'retention' => 30,
            'maxFileSize' => 10240, // 10MB
            'enableLogViewer' => !$isEdgeEnvironment, // Auto-disable on edge platforms
            'permissions' => [],
            'itemsPerPage' => 50, // Default entries per page in log viewer
        ], $config);

        self::$_pluginConfigs[$handle] = $config;

        // Configure logging immediately (heavy operation, but guarded)
        self::_configureLogging($handle, $config);

        // Register routes if log viewer is enabled
        if ($config['enableLogViewer']) {
            self::_registerRoutes($handle);
        }
    }

    /**
     * Get configuration for a plugin
     */
    public static function getConfig(string $handle): ?array
    {
        return self::$_pluginConfigs[$handle] ?? null;
    }

    /**
     * Get all registered plugin configurations
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

        // Create a custom processor to add user info
        $userProcessor = new class implements ProcessorInterface {
            public function __invoke(LogRecord $record): LogRecord
            {
                $user = Craft::$app->getUser()->getIdentity();
                $record->extra['user'] = $user ? 'user:' . $user->id : '';
                return $record;
            }
        };

        $target = new MonologTarget([
            'name' => $handle,
            'categories' => [$handle],
            'level' => $logLevelConstant,  // Use PSR-3 LogLevel constant
            'logContext' => false,
            'allowLineBreaks' => false,
            'processor' => $userProcessor,
            'formatter' => new LineFormatter(
                format: "%datetime% [%extra.user%][%level_name%][%channel%] %message% %context%\n",
                dateFormat: 'Y-m-d H:i:s',
                allowInlineLineBreaks: true,
            ),
        ]);


        // Add the target to the log dispatcher
        $dispatcher = Craft::getLogger()->dispatcher;
        $dispatcher->targets[] = $target;

        // Initialize the target immediately
        $target->init();

        // Mark as configured
        self::$_configuredTargets[$handle] = true;

    }

    /**
     * Register CP routes for log viewer
     */
    private static function _registerRoutes(string $handle): void
    {
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) use ($handle) {
                // Logs routes
                $event->rules[$handle . '/logs'] = 'logging-library/logs/index';
                $event->rules[$handle . '/logs/download'] = 'logging-library/logs/download';
            }
        );
    }

    /**
     * Add logs section to plugin's CP nav item
     */
    public static function addLogsNav(array $navItem, string $handle, array $permissions = []): array
    {
        $config = self::getConfig($handle);
        if (!$config || !$config['enableLogViewer']) {
            return $navItem;
        }

        // Check permissions if specified
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
            'label' => 'Logs',
            'url' => $handle . '/logs',
            'match' => $handle . '/logs*', // Match all logs pages
        ];

        return $navItem;
    }

    /**
     * Get available log files for a plugin
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

}