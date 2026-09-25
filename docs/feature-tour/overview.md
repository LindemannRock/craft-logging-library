# Feature tour

Give every Craft CMS plugin a consistent place to write, inspect, and troubleshoot logs without rebuilding the same infrastructure. Logging Library provides dedicated daily log files, per-plugin viewers, and a standalone system log browser.

## What it does

- **Dedicated Log Files** — each plugin gets its own daily log files (`plugin-handle-YYYY-MM-DD.log`) in `storage/logs/`
- **Built-in Log Viewer** — web interface for viewing, filtering, searching, and downloading logs from within each plugin's CP section
- **Standalone System Log Viewer** — browse all logs (plugin, Craft, PHP) from a single interface at **Logging Library → All Logs**
- **Runtime Logs** — bounded Redis-list view of recent log activity, with a generic backend only for non-Redis Craft caches, at **Logging Library → Runtime Logs**
- **Control Panel Section** — access the standalone viewer directly from the main Control Panel navigation when the CP section is enabled
- **Control Panel Settings** — a [Settings area](settings.md) for runtime capture, file-viewer availability, the display name, menu visibility, page size, and timestamps, all overridable from `config/logging-library.php`
- **Setup Summary** — review capture, file availability, and configured storage using the shared setup screen; capture remains optional
- **LoggingTrait** — drop-in trait that adds `logInfo()`, `logWarning()`, `logError()`, and `logDebug()` to any class
- **LoggingService** — static API for direct logging, log statistics, recent entries, and cleanup
- **High Performance Caching** — indexed file-based cache for large log viewer pages, with ArrayQuery compatibility for API callers
- **Multi-Format Parsing** — automatically detects and parses plugin logs, Craft logs, and PHP error logs
- **Edge Detection** — hides file-based viewers when Craft reports ephemeral storage or `SERVD_PROJECT_SLUG` resolves to a non-empty Servd project slug
- **Permission-Gated Access** — granular permissions for viewing and downloading logs

Craft's ephemeral-host signal and the normalized Servd signal compose with OR behavior. Missing, blank, whitespace-only, null, or normalized false `SERVD_PROJECT_SLUG` values do not detect Servd; a genuine project slug does. On Craft-ephemeral hosts such as Craft Cloud, and on detected Servd projects, detection suppresses file-based viewer presentation only. Craft/Yii logging and dedicated Monolog targets continue unchanged, and the CP viewer does not import the host's centralized log feed. Use `forceEnableLogViewer` when persistent local log storage makes file viewing safe. For file-independent CP visibility, opt into [Runtime Logs](runtime-logs.md) in Settings or configuration — it remains disabled by default.

## How plugins use it

A plugin integrates Logging Library in three steps:

1. **Configure** — call `LoggingLibrary::configure()` in `init()` with your plugin handle and options
2. **Log** — use `LoggingTrait` methods or `LoggingService::log()` to write structured log entries
3. **View** — the log viewer is automatically available at your plugin's `/logs` URL

See the [Integration guide](integration-guide.md) for the complete setup walkthrough.

## Key pages

| Topic | What you'll learn |
|-------|-------------------|
| [Configuration options](configuration-options.md) | All parameters for `LoggingLibrary::configure()` |
| [LoggingTrait](logging-trait.md) | How to use the trait in plugins, services, and controllers |
| [LoggingService API](logging-service.md) | Direct logging, statistics, recent entries, and cleanup |
| [Log viewer](log-viewer.md) | The built-in web interface for browsing logs |
| [Standalone Viewer](standalone-viewer.md) | The system-wide log browser |
| [Runtime Logs](runtime-logs.md) | Recent activity from a bounded diagnostic store — no log files needed |
| [Settings](settings.md) | The Control Panel settings area and config-file overrides |
| [Caching](caching.md) | How the performance cache works |
| [Edge detection](edge-detection.md) | Hosting on edge/CDN platforms |
| [Integration guide](integration-guide.md) | Full plugin setup with routes, nav, and permissions |
