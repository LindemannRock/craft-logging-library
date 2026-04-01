# Log Viewer

The built-in log viewer provides a web interface for browsing, filtering, searching, and downloading log files — directly within each plugin's Control Panel section.

## How It Works

When a plugin calls `LoggingLibrary::configure()` with `'enableLogViewer' => true`, the log viewer becomes available at `your-plugin/logs/system`. CP routes are registered automatically — no manual route registration is needed. The viewer reads parsed log entries from the [cache](caching.md) and renders them in a paginated table.

## Features

- **Date Selection** — pick a specific log file by date from the available files
- **Level Filtering** — filter by Error, Warning, Info, or Debug
- **Full-Text Search** — search across messages and context data
- **Sorting** — sort by timestamp, level, user, category, or message
- **Pagination** — configurable entries per page (default: 50)
- **Download** — download the raw log file (permission-gated)
- **Context Expansion** — click to view JSON context data inline
- **Smart Columns** — columns with no variance (e.g., all entries from the same user) are automatically hidden

## Enabling the Viewer

Two things are required for the log viewer to work:

### 1. Enable in Configuration

```php
LoggingLibrary::configure([
    'pluginHandle' => $this->handle,
    'enableLogViewer' => true,
    // ...
]);
```

When `enableLogViewer` is `true`, `configure()` automatically registers the CP routes for your plugin's log viewer — no manual route registration needed.

### 2. Set Permissions (Optional)

```php
LoggingLibrary::configure([
    'pluginHandle' => $this->handle,
    'viewSystemLogsPermissions' => ['yourPlugin:viewLogs'],
    'downloadSystemLogsPermissions' => ['yourPlugin:downloadLogs'],
]);
```

When `viewSystemLogsPermissions` is empty, any logged-in user can view logs. When `downloadSystemLogsPermissions` is empty, the download button is hidden.

## Viewer Filters

| Filter | Options | Description |
|--------|---------|-------------|
| Level | All Levels, Error, Warning, Info, Debug | Filter entries by log level |
| Search | Free text | Case-insensitive search across message and context |
| Sort | timestamp, level, user, category, message | Column to sort by |
| Direction | asc, desc | Sort direction (default: desc — newest first) |

## Log File Format

Plugin log files follow this format:

```
2025-01-15 14:30:25 [user:1][INFO][your-plugin] User exported translations | {"count":45,"format":"csv"}
2025-01-15 14:30:30 [][ERROR][your-plugin] Export failed | {"error":"File not writable"}
```

| Field | Description |
|-------|-------------|
| Timestamp | `YYYY-MM-DD HH:MM:SS` |
| User | `user:{id}` or empty for system/anonymous |
| Level | `DEBUG`, `INFO`, `WARNING`, `ERROR` |
| Category | Plugin handle |
| Message | The log message |
| Context | Optional JSON data (appended after the message) |

## Limitations

- The viewer reads from file-based logs only — it does not query a database
- Large files (100 MB+) may take longer on first load before the cache is built
- On edge/CDN platforms, the viewer is automatically disabled (see [Edge Detection](edge-detection.md))
