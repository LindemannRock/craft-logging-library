# Feature Tour

Logging Library is a shared infrastructure plugin that provides centralized logging for Craft CMS plugins. It gives every plugin that uses it dedicated daily log files, a built-in log viewer, and a standalone system log browser — all without duplicating code.

## What It Does

- **Dedicated Log Files** — each plugin gets its own daily log files (`plugin-handle-YYYY-MM-DD.log`) in `storage/logs/`
- **Built-in Log Viewer** — web interface for viewing, filtering, searching, and downloading logs from within each plugin's CP section
- **Standalone System Log Viewer** — browse all logs (plugin, Craft, PHP) from a single interface at **Logging Library → All Logs**
- **Runtime Logs** — cache-backed view of recent log activity at **Logging Library → Runtime Logs**, built for edge/ephemeral hosting where log files don't persist
- **Control Panel Section** — access the standalone viewer directly from the main Control Panel navigation when the CP section is enabled
- **Control Panel Settings** — a [Settings area](settings.md) for the display name, menu visibility, entries-per-page, and timestamp format, all overridable from `config/logging-library.php`
- **LoggingTrait** — drop-in trait that adds `logInfo()`, `logWarning()`, `logError()`, and `logDebug()` to any class
- **LoggingService** — static API for direct logging, log statistics, recent entries, and cleanup
- **High Performance Caching** — indexed file-based cache for large log viewer pages, with ArrayQuery compatibility for API callers
- **Multi-Format Parsing** — automatically detects and parses plugin logs, Craft logs, and PHP error logs
- **Edge Detection** — auto-disables file-based log viewer on edge/CDN platforms like Servd
- **Permission-Gated Access** — granular permissions for viewing and downloading logs

On edge platforms such as Servd, that support means safe detection and normal Craft log emission. It does not mean the CP viewer imports the host's centralized log feed; the file-based viewers still read local files from Craft's `storage/logs/` path. For CP visibility on those platforms, enable [Runtime Logs](runtime-logs.md) — it captures recent activity in Craft's cache instead of reading files.

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
| [Runtime Logs](runtime-logs.md) | Recent log activity from cache — no log files needed |
| [Settings](settings.md) | The Control Panel settings area and config-file overrides |
| [Caching](caching.md) | How the performance cache works |
| [Edge Detection](edge-detection.md) | Hosting on edge/CDN platforms |
| [Integration Guide](integration-guide.md) | Full plugin setup with routes, nav, and permissions |
