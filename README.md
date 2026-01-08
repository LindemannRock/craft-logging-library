# Craft Logging Library

[![Latest Version](https://img.shields.io/packagist/v/lindemannrock/craft-logging-library.svg)](https://packagist.org/packages/lindemannrock/craft-logging-library)
[![Craft CMS](https://img.shields.io/badge/Craft%20CMS-5.0+-orange.svg)](https://craftcms.com/)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net/)
[![License](https://img.shields.io/packagist/l/lindemannrock/craft-logging-library.svg)](LICENSE)

A reusable logging library for Craft CMS plugins that provides consistent logging, dedicated log files, and a built-in log viewer interface.

## ⚠️ Beta Notice

This plugin is currently in active development and provided under the MIT License for testing purposes.

**Licensing is subject to change.** We are finalizing our licensing structure and some or all features may require a paid license when officially released on the Craft Plugin Store. Some plugins may remain free, others may offer free and Pro editions, or be fully commercial.

If you are using this plugin, please be aware that future versions may have different licensing terms.

## Features

- **Standalone System Log Viewer**: View all Craft CMS, plugin, and PHP logs from one interface
- **High Performance Caching**: Handle 40,000+ entries instantly with ArrayQuery and file-based caching
- **Dedicated Log Files**: Each plugin gets its own daily log files (`plugin-handle-YYYY-MM-DD.log`)
- **Built-in Log Viewer**: Web interface for viewing, filtering, and downloading logs
- **Multi-Format Support**: Automatically parses plugin, Craft CMS, and PHP error logs
- **User Context**: Automatically includes user information in log entries (`[user:1]`)
- **Multi-Plugin Safe**: Proper filtering prevents conflicts between multiple plugins
- **Monolog Integration**: Uses Craft 5's Monolog system with proper PSR-3 standards
- **Easy Integration**: Just add trait and configure - no complex setup
- **Easy Navigation**: Simple method to add "Logs" section to plugin CP nav
- **Configurable**: Customizable log levels, retention, permissions

## Installation

### Via Composer

```bash
cd /path/to/project
```

```bash
composer require lindemannrock/craft-logging-library
```

### Using DDEV

```bash
cd /path/to/project
```

```bash
ddev composer require lindemannrock/craft-logging-library
```

### Via Control Panel

In the Control Panel, go to Settings → Plugins and click "Install" for Logging Library.

## Quick Start

### 1. Add the Trait to Your Plugin

```php
<?php
namespace yourvendor\yourplugin;

use craft\base\Plugin;
use lindemannrock\logginglibrary\traits\LoggingTrait;
use lindemannrock\logginglibrary\LoggingLibrary;

class YourPlugin extends Plugin
{
    use LoggingTrait;

    public function init(): void
    {
        parent::init();

        // Configure logging for this plugin
        $settings = $this->getSettings();
        LoggingLibrary::configure([
            'pluginHandle' => $this->handle,                        // Required: 'your-plugin'
            'pluginName' => $settings->pluginName ?? $this->name,   // Optional: Use config or default name
            'logLevel' => 'info',                                   // Optional: debug|info|warning|error
            'enableLogViewer' => true,                              // Optional: Enable web log viewer
            'itemsPerPage' => $settings->itemsPerPage ?? 50,        // Optional: Entries per page in log viewer
            'viewPermissions' => ['yourPlugin:viewLogs'],           // Optional: Permissions to view logs
            'downloadPermissions' => ['yourPlugin:downloadLogs'],   // Optional: Permissions to download logs
        ]);

        // DO NOT log in init() - it's called on every request and floods logs
        // Only log meaningful events in controllers and services
    }
}
```

### 2. Use Logging Methods

```php
// In any class that uses the LoggingTrait
$this->logInfo('User logged in', ['userId' => 123]);
$this->logWarning('Deprecated method called', ['method' => 'oldFunction']);
$this->logError('Database connection failed', ['error' => $exception->getMessage()]);
$this->logDebug('Processing step completed', ['step' => 5, 'data' => $result]);
```

### 3. Add Routes for Log Viewer

```php
// In your plugin's init() method, register CP routes
// Don't forget these imports at the top of your plugin class:
// use craft\events\RegisterUrlRulesEvent;
// use craft\web\UrlManager;

Event::on(
    UrlManager::class,
    UrlManager::EVENT_REGISTER_CP_URL_RULES,
    function (RegisterUrlRulesEvent $event) {
        // Route logs to logging-library controller
        $event->rules['your-plugin/logs'] = 'logging-library/logs/index';
        $event->rules['your-plugin/logs/download'] = 'logging-library/logs/download';
    }
);
```

### 4. Add Logs to CP Navigation

```php
public function getCpNavItem(): ?array
{
    $item = parent::getCpNavItem();

    // Add logs section using logging library (only if installed and enabled)
    if (Craft::$app->getPlugins()->isPluginInstalled('logging-library') &&
        Craft::$app->getPlugins()->isPluginEnabled('logging-library')) {
        $item = LoggingLibrary::addLogsNav($item, $this->handle, [
            'yourPlugin:viewLogs'
        ]);
    }

    return $item;
}
```

## Standalone Mode: View All System Logs

In addition to plugin-specific log viewing, the Logging Library includes a **standalone mode** that allows you to view **all system logs** from a single interface.

### Features

- **Universal Log Viewer**: Access all logs in `/storage/logs` from one place
- **Multi-Format Support**: Automatically parses plugin logs, Craft CMS logs (web, console, queue), and PHP error logs
- **Source Filtering**: Filter by log source (Web, Console, Queue, PHP Errors, or specific plugins)
- **High Performance Caching**: Handle 40,000+ entries instantly with file-based caching and ArrayQuery
- **File-Based Navigation**: Select specific log files with size indicators
- **Smart Detection**: Automatically detects log format and parses accordingly

### Accessing Standalone Mode

Once the Logging Library plugin is installed, navigate to:

1. **Control Panel** → **Logging Library** → **All Logs**
2. Or directly via URL: `/admin/logging-library/logs`

### Using the Standalone Viewer

**Filter by Source:**
- **All Sources**: View logs from all sources combined
- **Web**: Craft CMS web request logs
- **Console**: Craft CMS console/CLI logs
- **Queue**: Craft CMS queue job logs
- **PHP Errors**: PHP error log
- **[Plugin Name]**: Logs from specific plugins using the logging library

**Filter by Level:**
- All Levels, Error, Warning, Info, Debug (same as plugin mode)

**Search:**
- Full-text search across messages and context data

**File Selection:**
- Select specific log files by name
- Shows file size for each log
- Download full file from sidebar

### Log Format Detection

The standalone viewer automatically detects and parses three log formats:

1. **Plugin Logs** (from logging-library):
   ```
   2025-11-01 14:30:25 [user:1][INFO][plugin-handle] Message | {"context":"data"}
   ```

2. **Craft CMS Logs**:
   ```
   2025-11-01 14:30:25 [web.INFO] [yii\db\Connection::open] Message {"memory":962008}
   ```

3. **PHP Error Logs**:
   ```
   [01-Nov-2025 14:30:25 UTC] PHP Warning: message in /path/file.php on line 123
   ```

### How It Works

The standalone viewer:
1. Scans all files in `/storage/logs/`
2. Groups them by source (web, console, queue, php-errors, plugins)
3. Detects the log format of each line automatically
4. **Parses and caches** structured data in `storage/runtime/logging-library/cache/logs/`
5. Uses **ArrayQuery** for instant filtering, sorting, and pagination

### Performance & Caching

The logging library uses intelligent caching to handle large log files efficiently:

**First Load:**
- Parses entire log file once
- Stores structured data in cache file
- Cache key: `md5(filepath + filesize)`

**Subsequent Loads:**
- Reads from cache (instant)
- ArrayQuery provides SQL-like filtering (WHERE, ORDER BY, LIMIT, OFFSET)
- No file re-parsing needed

**Cache Invalidation:**
- Automatic when file size changes (new logs written)
- Manual via Utilities → Clear Caches → "Logging Library Cache"

**Result:** Handle 40,000+ entries with no performance issues, just like Timber!

### Benefits

- **One Interface**: No need to SSH into server or open multiple plugin log viewers
- **Centralized Debugging**: See all system activity in one place
- **High Performance**: File-based caching handles any size log file instantly
- **Context Aware**: Automatically understands different log formats
- **Timeline View**: See plugin logs alongside Craft system logs
- **No Limits**: View all entries from files of any size

## Configuration Options

```php
$settings = $this->getSettings();
LoggingLibrary::configure([
    'pluginHandle' => 'your-plugin',                     // Required: Plugin handle
    'pluginName' => $settings->pluginName ?? 'Default',  // Plugin display name (from config/settings or default)
    'logLevel' => 'info',                                // debug|info|warning|error
    'retention' => 30,                                   // Days to keep log files
    'maxFileSize' => 10240,                              // Max file size in KB (10MB)
    'enableLogViewer' => true,                           // Enable web interface
    'itemsPerPage' => $settings->itemsPerPage ?? 50,     // Entries per page in log viewer
    'viewPermissions' => ['yourPlugin:viewLogs'],        // Permissions required to view logs
    'downloadPermissions' => ['yourPlugin:downloadLogs'], // Permissions required to download logs
]);
```

**Note**: For `pluginName`, use `$settings->pluginName ?? $this->name` to respect custom plugin names set in your config file (e.g., `config/your-plugin.php`). This allows users to customize the plugin name displayed in the log viewer.

## LoggingTrait API Reference

The `LoggingTrait` provides four protected methods for logging. These methods are automatically available in any class that uses the trait.

### Available Methods

```php
/**
 * Log an informational message
 * @param string $message The log message
 * @param array $params Optional context data (encoded as JSON)
 */
protected function logInfo(string $message, array $params = []): void

/**
 * Log a warning message
 * @param string $message The log message
 * @param array $params Optional context data (encoded as JSON)
 */
protected function logWarning(string $message, array $params = []): void

/**
 * Log an error message
 * @param string $message The log message
 * @param array $params Optional context data (encoded as JSON)
 */
protected function logError(string $message, array $params = []): void

/**
 * Log a debug message (only works when devMode is enabled)
 * @param string $message The log message
 * @param array $params Optional context data (encoded as JSON)
 */
protected function logDebug(string $message, array $params = []): void
```

### Message Formatting

The trait automatically formats messages with JSON context:

```php
$this->logInfo('User action', ['userId' => 123, 'action' => 'export']);
// Results in: "User action | {"userId":123,"action":"export"}"
```

If no context is provided, only the message is logged:

```php
$this->logError('Database connection failed');
// Results in: "Database connection failed"
```

**Note**: The logging library automatically filters out Yii2's internal context fields (`trace`, `memory`, `category`) to keep logs clean and readable. Only your custom context data will appear in the log output.

### Plugin Handle Detection

The trait automatically detects your plugin handle from:
1. `$this->handle` property (automatically available in Plugin classes)
2. Manual override via `setLoggingHandle()` (for Services and other Components)

**Automatic Detection (Plugin Classes):**
```php
class YourPlugin extends Plugin
{
    use LoggingTrait;

    public function init(): void
    {
        parent::init();
        // $this->handle is automatically used
        // DO NOT log here - init() is called on every request
    }

    public function someAction(): void
    {
        // Log meaningful events in your methods
        $this->logInfo('User performed action', ['action' => 'export']);
    }
}
```

**Manual Handle (Services & Components):**
```php
use craft\base\Component;
use lindemannrock\logginglibrary\traits\LoggingTrait;

class YourService extends Component
{
    use LoggingTrait;

    public function init(): void
    {
        parent::init();
        $this->setLoggingHandle('your-plugin');  // Set handle manually
    }

    public function processData(): void
    {
        $this->logInfo('Processing started');
        // ... your logic
    }
}
```

### Internal Implementation

The trait uses Craft's native PSR-3 logging methods:
- `logInfo()` → `Craft::info()`
- `logWarning()` → `Craft::warning()`
- `logError()` → `Craft::error()`
- `logDebug()` → `Craft::debug()`

All messages are automatically routed to your plugin's dedicated log file via the Monolog target configured by `LoggingLibrary::configure()`.

**Important Notes:**
- These are **protected methods** - only available within the class that uses the trait
- The `logDebug()` method only works when Craft's `devMode` is enabled
- Context arrays are automatically JSON-encoded with UTF-8 support
- User information is automatically included in log entries when available

## Advanced Usage

### Direct Logging (Without Trait)

```php
use lindemannrock\logginglibrary\services\LoggingService;

// Log directly
LoggingService::log('Custom message', 'info', 'your-plugin', [
    'userId' => 123,
    'action' => 'export'
]);
```

### Log Statistics

```php
use lindemannrock\logginglibrary\services\LoggingService;

$stats = LoggingService::getLogStats('your-plugin');
// Returns: totalFiles, totalSize, oldestDate, newestDate, levels breakdown
```

### Recent Entries (for Dashboards)

```php
$recentErrors = LoggingService::getRecentEntries('your-plugin', 5, 'error');
foreach ($recentErrors as $entry) {
    echo $entry['timestamp'] . ': ' . $entry['message'];
}
```

### Log Cleanup

```php
// Clean up logs older than 30 days
$deleted = LoggingService::cleanupOldLogs('your-plugin', 30);
```

## Log File Format

Log files are stored as: `storage/logs/your-plugin-YYYY-MM-DD.log`

Format: `timestamp [user:id][level][plugin-handle] message context`

Example:
```
2025-01-15 14:30:25 [user:1][INFO][your-plugin] User exported translations {"count":45,"format":"csv"}
2025-01-15 14:30:30 [][ERROR][your-plugin] Export failed {"error":"File not writable"}
```

## Log Levels

- **debug**: Detailed debugging information (requires `devMode` to be enabled)
- **info**: General informational messages
- **warning**: Warning conditions
- **error**: Error conditions

**Note**: Debug level logging only works when Craft's `devMode` is enabled. In production environments with `devMode=false`, debug messages are ignored for security reasons. If debug level is set in configuration when `devMode` is false, it will automatically fall back to 'info' level to prevent server errors.

## Web Log Viewer

When `enableLogViewer` is true **and** you've configured the routes (step 3 above), logs are available at `your-plugin/logs` with:

- **Date Filter**: Select specific log file by date
- **Level Filter**: Filter by error, warning, info, debug (automatically filtered based on configured `logLevel`)
- **Search**: Full-text search across messages and context
- **Pagination**: Handle large log files efficiently
- **Download**: Download raw log files
- **Context Expansion**: Click to view JSON context data

**Note**: The Level Filter dropdown automatically adjusts to only show log levels that are at or above your configured `logLevel`. For example, if `logLevel` is set to 'info', only Error, Warning, and Info options will appear. Debug level is always hidden unless `devMode` is enabled.

## Edge/CDN Hosting Environments

When deploying on edge networks and CDN-based hosting platforms, the logging library can automatically detect these environments and disable the built-in log viewer since edge servers typically don't have persistent local storage.

### Automatic Edge Detection

The logging library automatically detects edge/CDN hosting environments and disables file-based logging where it may not work.

**Currently Supported Platforms**:
- **Servd.host**: Detects `SERVD_PROJECT_SLUG` environment variable ✅ *Verified*

**Platform Detection Notes**:
- Only verified platforms are included to avoid accidentally disabling logging
- Additional platforms will be added after real-world testing with Craft CMS deployments
- Manual override is always available if needed

### Why Edge Detection Matters

**Technical Reasons**:
- Edge servers use distributed, ephemeral storage
- Local log files aren't accessible across the CDN network
- File system operations can be restricted or unavailable
- Better performance without local file I/O operations

**User Experience**:
- Most edge platforms provide superior centralized log viewing
- Eliminates redundant log viewer interfaces in Craft CP
- Logs still work normally via Craft's PSR-3 system
- Appear in the platform's native dashboard with advanced filtering

### Manual Configuration

**Explicit Control**:
```php
LoggingLibrary::configure([
    'pluginHandle' => 'your-plugin',
    'enableLogViewer' => false, // Explicitly disable for any hosting
    // ... other options
]);
```

**Environment-Based Control**:
```php
LoggingLibrary::configure([
    'pluginHandle' => 'your-plugin',
    'enableLogViewer' => !($_ENV['CUSTOM_EDGE_PLATFORM'] ?? false),
    // ... other options
]);
```

### Basic Usage (Automatic Detection)

**Simple Configuration** (Recommended):
```php
// In your plugin's init() method
$settings = $this->getSettings();
LoggingLibrary::configure([
    'pluginHandle' => $this->handle,
    'pluginName' => $settings->pluginName ?? $this->name,
    'logLevel' => 'info',
    // enableLogViewer automatically disabled on Servd
    // ... other options
]);
```

**Manual Override** (if needed):
```php
// Force enable/disable log viewer regardless of environment
LoggingLibrary::configure([
    'pluginHandle' => $this->handle,
    'enableLogViewer' => false, // Explicitly disable
    // ... other options
]);
```

**Custom Edge Detection** (for unsupported platforms):
```php
// Add your own platform detection
$isCustomEdge = isset($_ENV['YOUR_PLATFORM_VAR']);

LoggingLibrary::configure([
    'pluginHandle' => $this->handle,
    'enableLogViewer' => !$isCustomEdge,
    // ... other options
]);
```

## Permissions Integration

```php
// In your plugin's registerPermissions event
$event->permissions[] = [
    'heading' => 'Your Plugin',
    'permissions' => [
        'yourPlugin:viewLogs' => [
            'label' => 'View logs',
            'nested' => [
                'yourPlugin:downloadLogs' => [
                    'label' => 'Download logs',
                ],
            ],
        ],
    ],
];
```

## Best Practices

1. **DO NOT Log in init()** ⚠️:
   - The `init()` method is called on **every request** (every page load, AJAX call, etc.)
   - Logging there will **flood your logs** with duplicate entries
   - Only log meaningful events in controllers, services, and other methods

   ```php
   // ❌ BAD - Causes log flooding
   public function init(): void
   {
       parent::init();
       $this->logInfo('Plugin initialized');  // Called on EVERY request!
   }

   // ✅ GOOD - Log actual user actions
   public function actionExport(): Response
   {
       $this->logInfo('Export started', ['userId' => $userId]);
       // ... export logic
       $this->logInfo('Export completed', ['count' => $count]);
   }
   ```

2. **Use Appropriate Levels**:
   - `debug`: Internal state, variable dumps
   - `info`: Normal operations, user actions
   - `warning`: Unexpected but handled situations
   - `error`: Actual errors that prevent operation

3. **Always Use Context Arrays (Not Inline Concatenation)**:

   Use the second parameter for variable data, not string concatenation:

   ```php
   // ❌ BAD - Concatenating variables into message
   $this->logError('Export failed: ' . $e->getMessage());
   $this->logInfo('Processing ' . $count . ' items');
   $this->logWarning('Invalid user: ' . $userId);

   // ✅ GOOD - Use context array for variables
   $this->logError('Export failed', ['error' => $e->getMessage()]);
   $this->logInfo('Processing items', ['count' => $count]);
   $this->logWarning('Invalid user', ['userId' => $userId]);
   ```

   **Why Context Arrays Are Better:**
   - Structured data for log analysis tools
   - Easier to search and filter in log viewer
   - Consistent formatting across all logs
   - Automatic JSON encoding with UTF-8 support
   - Better for external logging services (Datadog, etc.)

   **Rich Context Example:**
   ```php
   $this->logInfo('Translation exported', [
       'userId' => Craft::$app->getUser()->getId(),
       'count' => count($translations),
       'format' => $exportFormat,
       'fileSize' => filesize($exportFile),
       'duration' => microtime(true) - $startTime
   ]);
   ```

4. **Performance Considerations**:
   - Use `debug` level sparingly in production
   - Avoid logging in tight loops
   - Consider log file size and retention

5. **Security**:
   - Never log passwords or sensitive data
   - Be careful with user input in log messages
   - Use appropriate permissions for log access

## Complete Plugin Example

```php
<?php
namespace yourvendor\yourplugin;

use craft\base\Plugin;
use craft\events\RegisterUserPermissionsEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\services\UserPermissions;
use craft\web\UrlManager;
use lindemannrock\logginglibrary\traits\LoggingTrait;
use lindemannrock\logginglibrary\LoggingLibrary;
use yii\base\Event;

class YourPlugin extends Plugin
{
    use LoggingTrait;

    public function init(): void
    {
        parent::init();

        // Configure logging
        $settings = $this->getSettings();
        LoggingLibrary::configure([
            'pluginHandle' => $this->handle,
            'pluginName' => $settings->pluginName ?? $this->name,
            'logLevel' => 'info',
            'enableLogViewer' => true,
            'itemsPerPage' => $settings->itemsPerPage ?? 50,
            'viewPermissions' => ['yourPlugin:viewLogs'],
            'downloadPermissions' => ['yourPlugin:downloadLogs'],
        ]);

        // Register CP routes for log viewer
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['your-plugin/logs'] = 'logging-library/logs/index';
                $event->rules['your-plugin/logs/download'] = 'logging-library/logs/download';
            }
        );

        // Register permissions
        Event::on(UserPermissions::class, UserPermissions::EVENT_REGISTER_PERMISSIONS,
            function (RegisterUserPermissionsEvent $event) {
                $event->permissions[] = [
                    'heading' => $this->name,
                    'permissions' => [
                        'yourPlugin:viewLogs' => [
                            'label' => 'View logs',
                            'nested' => [
                                'yourPlugin:downloadLogs' => [
                                    'label' => 'Download logs',
                                ],
                            ],
                        ],
                    ],
                ];
            }
        );

        // DO NOT log in init() - it's called on every request
    }

    public function getCpNavItem(): ?array
    {
        $item = parent::getCpNavItem();

        // Add logs section using logging library (only if installed and enabled)
        if (Craft::$app->getPlugins()->isPluginInstalled('logging-library') &&
            Craft::$app->getPlugins()->isPluginEnabled('logging-library')) {
            $item = LoggingLibrary::addLogsNav($item, $this->handle, ['yourPlugin:viewLogs']);
        }

        return $item;
    }
}
```

**Example Controller with Logging:**

```php
use craft\web\Controller;
use lindemannrock\logginglibrary\traits\LoggingTrait;

class ExportController extends Controller
{
    use LoggingTrait;

    public function init(): void
    {
        parent::init();
        $this->setLoggingHandle('your-plugin');
    }

    public function actionExport(): Response
    {
        // ✅ Log meaningful user actions
        $this->logInfo('Export started', ['userId' => Craft::$app->getUser()->getId()]);

        try {
            // Your export logic
            $count = $this->performExport();
            $this->logInfo('Export completed successfully', ['count' => $count]);

            return $this->asJson(['success' => true, 'count' => $count]);
        } catch (\Exception $e) {
            $this->logError('Export failed', ['error' => $e->getMessage()]);

            return $this->asJson(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
```

## Requirements

- Craft CMS 5.0 or greater
- PHP 8.2 or greater

## Support

- **Documentation**: [https://github.com/LindemannRock/craft-logging-library](https://github.com/LindemannRock/craft-logging-library)
- **Issues**: [https://github.com/LindemannRock/craft-logging-library/issues](https://github.com/LindemannRock/craft-logging-library/issues)
- **Email**: [support@lindemannrock.com](mailto:support@lindemannrock.com)

## License

This library is licensed under the MIT License. See [LICENSE](LICENSE) for details.

---

Developed by [LindemannRock](https://lindemannrock.com)
