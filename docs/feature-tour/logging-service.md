# LoggingService API

The `LoggingService` class provides static methods for direct logging, log statistics, recent entries, and log cleanup. Use it when you need logging outside of a class with `LoggingTrait`, or when you need log metadata like statistics.

## Direct Logging

Log a message for a specific plugin without using the trait:

```php
use lindemannrock\logginglibrary\services\LoggingService;

LoggingService::log('Custom message', 'info', 'your-plugin', [
    'userId' => 123,
    'action' => 'export',
]);
```

### Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$message` | `string` | (required) | The log message |
| `$level` | `string` | `'info'` | Log level: `'debug'`, `'info'`, `'warning'`, `'error'` |
| `$pluginHandle` | `string` | (required) | Plugin handle for routing to the correct log file |
| `$context` | `array` | `[]` | Additional context data, appended as JSON |

## Log Statistics

Get aggregate statistics for a plugin's log files:

```php
$stats = LoggingService::getLogStats('your-plugin');
```

Returns an array with:

| Key | Type | Description |
|-----|------|-------------|
| `totalFiles` | `int` | Number of log files |
| `totalSize` | `int` | Total size in bytes |
| `formattedSize` | `string` | Human-readable size (e.g., "2.4 MB") |
| `oldestDate` | `string\|null` | Oldest log file date (`YYYY-MM-DD`) |
| `newestDate` | `string\|null` | Newest log file date (`YYYY-MM-DD`) |
| `levels` | `array` | Breakdown by level: `['error' => 5, 'warning' => 12, 'info' => 230, 'debug' => 0]` |

## Recent Entries

Get the most recent log entries — useful for dashboard widgets:

```php
$recentErrors = LoggingService::getRecentEntries('your-plugin', 5, 'error');

foreach ($recentErrors as $entry) {
    echo $entry['timestamp'] . ': ' . $entry['message'];
}
```

### Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$pluginHandle` | `string` | (required) | Plugin handle |
| `$limit` | `int` | `10` | Maximum entries to return |
| `$level` | `string` | `'all'` | Filter by level, or `'all'` for all levels |

### Return Format

Each entry is an array:

| Key | Type | Description |
|-----|------|-------------|
| `timestamp` | `string` | Log timestamp |
| `user` | `string` | User identifier (e.g., `user:1`) |
| `level` | `string` | Log level |
| `category` | `string` | Plugin handle |
| `message` | `string` | Log message |
| `context` | `string\|null` | JSON context data |
| `lineNumber` | `int` | Line number in the log file |
| `raw` | `string` | Original unparsed log line |

## Log Cleanup

Remove log files older than a specified number of days:

```php
$deleted = LoggingService::cleanupOldLogs('your-plugin', 30);
// Returns: ['your-plugin-2025-12-01.log', 'your-plugin-2025-12-02.log']
```

### Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$pluginHandle` | `string` | (required) | Plugin handle |
| `$retentionDays` | `int` | `30` | Delete files older than this many days |

## Utility Methods

### Check if Logging is Configured

```php
if (LoggingService::isConfigured('your-plugin')) {
    // Plugin has called LoggingLibrary::configure()
}
```

### Get Effective Log Level

```php
$level = LoggingService::getLogLevel('your-plugin');
// Returns: 'info', 'debug', 'warning', 'error', or null if not configured
```

### Message Sanitization @since(5.9.0)

Log entries are one line each. If an attacker-controlled value reaches a log message with a raw newline in it, they could forge an extra log line — the classic log-injection attack ([CWE-117](https://cwe.mitre.org/data/definitions/117.html)). `sanitizeLogMessage()` neutralizes that by turning every `\r\n`, `\r`, or `\n` into the two-character literal `\n`, so the intent is still readable but the file always sees a single entry per emit:

```php
LoggingService::sanitizeLogMessage("line one\nline two");
// Returns: 'line one\nline two'  (literal backslash-n, not a real newline)
```

You rarely call this yourself — it runs inside the Monolog formatter, so **every** path that writes to a Logging Library channel is already protected, including third-party code that calls `Craft::info()` / `Craft::error()` directly. It's exposed publicly for the rare case where you need the same guarantee before handing a string to another system.
