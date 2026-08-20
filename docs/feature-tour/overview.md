# Feature Tour

Logging Library is a shared infrastructure plugin that provides centralized logging for Craft CMS plugins. It gives every plugin that uses it dedicated daily log files, a built-in log viewer, and a standalone system log browser — all without duplicating code.

## What It Does

- **Dedicated Log Files** — each plugin gets its own daily log files (`plugin-handle-YYYY-MM-DD.log`) in `storage/logs/`
- **Built-in Log Viewer** — web interface for viewing, filtering, searching, and downloading logs from within each plugin's CP section
- **Standalone System Log Viewer** — browse all logs (plugin, Craft, PHP) from a single interface at **Logging Library → All Logs**
- **Runtime Logs** — bounded Redis-list view of recent log activity, with a generic backend only for non-Redis Craft caches, at **Logging Library → Runtime Logs**
- **Control Panel Section** — access the standalone viewer directly from the main Control Panel navigation when the CP section is enabled
- **Control Panel Settings** — a [Settings area](settings.md) for the display name, menu visibility, entries-per-page, and timestamp format, all overridable from `config/logging-library.php`
- **LoggingTrait** — drop-in trait that adds `logInfo()`, `logWarning()`, `logError()`, and `logDebug()` to any class
- **LoggingService** — static API for direct logging, log statistics, recent entries, and cleanup
- **High Performance Caching** — indexed file-based cache for large log viewer pages, with ArrayQuery compatibility for API callers
- **Multi-Format Parsing** — automatically detects and parses plugin logs, Craft logs, and PHP error logs
- **Edge Detection** — hides file-based viewers when Craft reports ephemeral storage or `SERVD_PROJECT_SLUG` resolves to a non-empty Servd project slug
- **Permission-Gated Access** — granular permissions for viewing and downloading logs

Craft's ephemeral-host signal and the normalized Servd signal compose with OR behavior. Missing, blank, whitespace-only, null, or normalized false `SERVD_PROJECT_SLUG` values do not detect Servd; a genuine project slug does. On Craft-ephemeral hosts such as Craft Cloud, and on detected Servd projects, detection suppresses file-based viewer presentation only. Craft/Yii logging and dedicated Monolog targets continue unchanged, and the CP viewer does not import the host's centralized log feed. Use `forceEnableLogViewer` when persistent local log storage makes file viewing safe. For file-independent CP visibility, opt into [Runtime Logs](runtime-logs.md) separately in configuration — it remains config-only and disabled by default.

## How Plugins Use It

A plugin integrates Logging Library in three steps:

1. **Configure** — call `LoggingLibrary::configure()` in `init()` with your plugin handle and options
2. **Log** — use `LoggingTrait` methods or `LoggingService::log()` to write structured log entries
3. **View** — the log viewer is automatically available at your plugin's `/logs` URL

See [Integration Guide](integration-guide.md) for the complete setup walkthrough.

## Key Pages

| Topic | What You'll Learn |
|-------|-------------------|
| [Configuration Options](configuration-options.md) | All parameters for `LoggingLibrary::configure()` |
| [LoggingTrait](logging-trait.md) | How to use the trait in plugins, services, and controllers |
| [LoggingService API](logging-service.md) | Direct logging, statistics, recent entries, and cleanup |
| [Log Viewer](log-viewer.md) | The built-in web interface for browsing logs |
| [Standalone Viewer](standalone-viewer.md) | The system-wide log browser |
| [Runtime Logs](runtime-logs.md) | Recent activity from a bounded diagnostic store — no log files needed |
| [Settings](settings.md) | The Control Panel settings area and config-file overrides |
| [Caching](caching.md) | How the performance cache works |
| [Edge Detection](edge-detection.md) | Hosting on edge/CDN platforms |
| [Integration Guide](integration-guide.md) | Full plugin setup with routes, nav, and permissions |
