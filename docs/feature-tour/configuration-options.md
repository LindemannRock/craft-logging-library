# Configuration Options

These options are passed to `LoggingLibrary::configure()` as an associative array during your plugin's `init()` method. They control per-plugin logging behavior in code. Standalone Logging Library plugin settings such as the plugin name, items per page, and CP section visibility are managed separately in the Logging Library settings UI/config.

## Configuration Reference

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `pluginHandle` | `string` | (required) | Your plugin's handle — used as the log file prefix and Monolog category |
| `pluginName` | `string` | ucfirst of handle | Display name shown in the log viewer header |
| `logLevel` | `string` | `'info'` | Minimum log level: `'debug'`, `'info'`, `'warning'`, `'error'` |
| `retention` | `int` | `30` | Days to keep log files when using `LoggingService::cleanupOldLogs()` |
| `maxFileSize` | `int` | `10240` | Maximum log file size in KB (10 MB default) |
| `enableLogViewer` | `bool` | auto | Enable the web log viewer. Auto-disabled on detected edge platforms |
| `viewSystemLogsPermissions` | `array` | `[]` | Permission strings required to view system logs. Empty = no restriction |
| `downloadSystemLogsPermissions` | `array` | `[]` | Permission strings required to download log files. Empty = download hidden |
| `itemsPerPage` | `int` | `50` | Number of log entries per page in the viewer |
| `logMenuItems` | `array` | `null` | Custom sidebar menu items for multi-section log pages |
| `logMenuLabel` | `string` | `'Logs'` | Aria-label for the sidebar log menu |

## Basic Example

```php
LoggingLibrary::configure([
    'pluginHandle' => $this->handle,
    'pluginName' => $this->name,
    'logLevel' => 'info',
    'enableLogViewer' => true,
    'viewSystemLogsPermissions' => ['yourPlugin:viewLogs'],
    'downloadSystemLogsPermissions' => ['yourPlugin:downloadLogs'],
]);
```

## Log Levels

The `logLevel` setting controls the minimum severity written to log files:

| Level | Use For |
|-------|---------|
| `debug` | Detailed internal state, variable dumps. Only works when Craft's `devMode` is enabled |
| `info` | Normal operations — user actions, successful completions |
| `warning` | Unexpected but handled situations — deprecated methods, missing optional config |
| `error` | Actual failures that prevent an operation from completing |

> [!WARNING]
> Debug-level logging only works when Craft's `devMode` is `true`. In production, `Craft::debug()` calls are silently ignored regardless of your `logLevel` setting.

## Sidebar Menu

When you provide two or more items in `logMenuItems`, a left sidebar appears on the logs page — useful for plugins with multiple log types:

```php
LoggingLibrary::configure([
    'pluginHandle' => $this->handle,
    'logMenuItems' => [
        'system' => ['label' => 'System', 'url' => 'my-plugin/logs/system'],
        'activity' => ['label' => 'Activity', 'url' => 'my-plugin/logs/activity'],
    ],
]);
```

The sidebar only renders when there are 2+ items. Single-item menus are hidden automatically.

## Edge Platform Override

The `enableLogViewer` option defaults to `true` unless an edge platform is detected. You can override this explicitly:

```php
LoggingLibrary::configure([
    'pluginHandle' => $this->handle,
    'enableLogViewer' => false, // Force disable regardless of platform
]);
```

See [Edge Detection](edge-detection.md) for details on supported platforms.
