# Craft Logging Library

A reusable logging library for Craft CMS plugins that provides consistent logging, dedicated log files, and a built-in log viewer interface.

## Features

- **Dedicated Log Files**: Each plugin gets its own daily log files (`plugin-handle-YYYY-MM-DD.log`)
- **Built-in Log Viewer**: Web interface for viewing, filtering, and downloading logs
- **Monolog Integration**: Uses Craft 5's Monolog system with proper PSR-3 standards
- **Easy Integration**: Just add trait and configure - no complex setup
- **Auto Navigation**: Automatically adds "Logs" section to plugin CP nav
- **Configurable**: Customizable log levels, retention, permissions

## Installation

### Via Composer (Development)

Until published on Packagist, install directly from the repository:

```bash
cd /path/to/project
composer config repositories.craft-logging-library vcs https://github.com/LindemannRock/craft-logging-library
composer require lindemannrock/logging-library:dev-main
```

### Via Composer (Production - Coming Soon)

Once published on Packagist:

```bash
cd /path/to/project
composer require lindemannrock/logging-library
```

## Quick Start

### 1. Add the Trait to Your Plugin

```php
<?php
namespace yourvendor\yourplugin;

use craft\base\Plugin;
use lindemannrock\logginglibrary\traits\LoggingTrait;
use lindemannrock\logginglibrary\LoggingModule;

class YourPlugin extends Plugin
{
    use LoggingTrait;

    public function init(): void
    {
        parent::init();

        // Configure logging for this plugin
        LoggingModule::configure([
            'pluginHandle' => $this->handle,           // Required: 'your-plugin'
            'pluginName' => $this->name,               // Optional: 'Your Plugin'
            'logLevel' => 'info',                      // Optional: debug|info|warning|error
            'enableLogViewer' => true,                 // Optional: Enable web log viewer
            'permissions' => ['yourPlugin:viewLogs'],  // Optional: Required permissions
        ]);

        // Start logging!
        $this->logInfo('Plugin initialized successfully');
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

### 3. Add Logs to CP Navigation

```php
public function getCpNavItem(): ?array
{
    $item = parent::getCpNavItem();

    // Add logs section with permission check
    return LoggingModule::addLogsNav($item, $this->handle, [
        'yourPlugin:viewLogs'
    ]);
}
```

## Configuration Options

```php
LoggingModule::configure([
    'pluginHandle' => 'your-plugin',        // Required: Plugin handle
    'pluginName' => 'Your Plugin',          // Plugin display name
    'logLevel' => 'info',                   // debug|info|warning|error
    'retention' => 30,                      // Days to keep log files
    'maxFileSize' => 10240,                 // Max file size in KB (10MB)
    'enableLogViewer' => true,              // Enable web interface
    'permissions' => [                      // Required permissions for log access
        'yourPlugin:viewLogs',
        'yourPlugin:editSettings'
    ],
]);
```

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

Format: `timestamp [user:id][level][plugin-handle] message | context`

Example:
```
2025-01-15 14:30:25 [user:1][INFO][your-plugin] User exported translations | {"count":45,"format":"csv"}
2025-01-15 14:30:30 [user:1][ERROR][your-plugin] Export failed | {"error":"File not writable"}
```

## Log Levels

- **debug**: Detailed debugging information
- **info**: General informational messages
- **warning**: Warning conditions
- **error**: Error conditions

## Web Log Viewer

When `enableLogViewer` is true, logs are available at `your-plugin/logs` with:

- **Date Filter**: Select specific log file by date
- **Level Filter**: Filter by error, warning, info, debug
- **Search**: Full-text search across messages and context
- **Pagination**: Handle large log files efficiently
- **Download**: Download raw log files
- **Context Expansion**: Click to view JSON context data

## Permissions Integration

```php
// In your plugin's registerPermissions event
$event->permissions[] = [
    'heading' => 'Your Plugin',
    'permissions' => [
        'yourPlugin:viewLogs' => [
            'label' => 'View logs',
        ],
    ],
];
```

## Best Practices

1. **Use Appropriate Levels**:
   - `debug`: Internal state, variable dumps
   - `info`: Normal operations, user actions
   - `warning`: Unexpected but handled situations
   - `error`: Actual errors that prevent operation

2. **Include Context**:
   ```php
   $this->logInfo('Translation exported', [
       'userId' => Craft::$app->getUser()->getId(),
       'count' => count($translations),
       'format' => $exportFormat,
       'fileSize' => filesize($exportFile)
   ]);
   ```

3. **Performance Considerations**:
   - Use `debug` level sparingly in production
   - Avoid logging in tight loops
   - Consider log file size and retention

4. **Security**:
   - Never log passwords or sensitive data
   - Be careful with user input in log messages
   - Use appropriate permissions for log access

## Complete Plugin Example

```php
<?php
namespace yourvendor\yourplugin;

use craft\base\Plugin;
use craft\events\RegisterUserPermissionsEvent;
use craft\services\UserPermissions;
use lindemannrock\logginglibrary\traits\LoggingTrait;
use lindemannrock\logginglibrary\LoggingModule;
use yii\base\Event;

class YourPlugin extends Plugin
{
    use LoggingTrait;

    public function init(): void
    {
        parent::init();

        // Configure logging
        LoggingModule::configure([
            'pluginHandle' => $this->handle,
            'pluginName' => $this->name,
            'logLevel' => 'info',
            'enableLogViewer' => true,
            'permissions' => ['yourPlugin:viewLogs'],
        ]);

        // Register permissions
        Event::on(UserPermissions::class, UserPermissions::EVENT_REGISTER_PERMISSIONS,
            function (RegisterUserPermissionsEvent $event) {
                $event->permissions[] = [
                    'heading' => $this->name,
                    'permissions' => [
                        'yourPlugin:viewLogs' => ['label' => 'View logs'],
                    ],
                ];
            }
        );

        $this->logInfo('Plugin initialized');
    }

    public function getCpNavItem(): ?array
    {
        $item = parent::getCpNavItem();
        return LoggingModule::addLogsNav($item, $this->handle, ['yourPlugin:viewLogs']);
    }

    // Your plugin methods can now use logging
    public function exportData(): bool
    {
        $this->logInfo('Starting data export');

        try {
            // Your export logic
            $count = $this->performExport();
            $this->logInfo('Export completed successfully', ['count' => $count]);
            return true;
        } catch (\Exception $e) {
            $this->logError('Export failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
```

## Requirements

- Craft CMS 5.0+
- PHP 8.1+

## Support

- **Documentation**: [https://github.com/LindemannRock/craft-logging-library](https://github.com/LindemannRock/craft-logging-library)
- **Issues**: [https://github.com/LindemannRock/craft-logging-library/issues](https://github.com/LindemannRock/craft-logging-library/issues)
- **Email**: [support@lindemannrock.com](mailto:support@lindemannrock.com)

## License

This library is licensed under the MIT License. See [LICENSE](LICENSE) for details.

---

Developed by [LindemannRock](https://lindemannrock.com)
