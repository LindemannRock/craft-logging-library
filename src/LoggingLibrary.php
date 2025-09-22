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
     */
    public static function configure(array $config): void
    {
        $handle = $config['pluginHandle'] ?? null;
        if (!$handle) {
            throw new \InvalidArgumentException('Plugin handle is required for logging configuration');
        }

        // Skip if already configured (lightweight guard)
        if (isset(self::$_pluginConfigs[$handle])) {
            return;
        }

        // Validate required config
        $config = array_merge([
            'pluginName' => ucfirst($handle),
            'logLevel' => 'info', // Options: 'debug', 'info', 'warning', 'error'
            'retention' => 30,
            'maxFileSize' => 10240, // 10MB
            'enableLogViewer' => true,
            'permissions' => [],
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
        // Skip if already configured
        if (isset(self::$_configuredTargets[$handle])) {
            return;
        }

        // Skip if target already exists in Craft's log dispatcher
        if (isset(Craft::$app->getLog()->targets[$handle])) {
            self::$_configuredTargets[$handle] = true;
            return;
        }

        // Create a MonologTarget following the exact PutYourLightsOn pattern
        $target = new MonologTarget([
            'name' => $handle,
            'categories' => [$handle],
            'level' => self::_mapLogLevel($config['logLevel']),
            'logContext' => false,
            'allowLineBreaks' => false,
            'formatter' => new LineFormatter(
                format: "%datetime% [%extra.user%][%level_name%][%channel%] %message% %context%\n",
                dateFormat: 'Y-m-d H:i:s',
            ),
        ]);

        // Add the target to the log dispatcher (following the exact pattern from the article)
        Craft::getLogger()->dispatcher->targets[] = $target;

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
     * Map string log level to PSR-3 LogLevel constant
     */
    private static function _mapLogLevel(string $level): string
    {
        return match ($level) {
            'debug' => LogLevel::DEBUG,
            'info' => LogLevel::INFO,
            'warning' => LogLevel::WARNING,
            'error' => LogLevel::ERROR,
            default => LogLevel::INFO
        };
    }
}