# Feature Tour

Logging Library is a shared infrastructure plugin that provides centralized logging for Craft CMS plugins. It gives every plugin that uses it dedicated daily log files, a built-in log viewer, and a standalone system log browser — all without duplicating code.

## What It Does

- **Dedicated Log Files** — each plugin gets its own daily log files (`plugin-handle-YYYY-MM-DD.log`) in `storage/logs/`
- **Built-in Log Viewer** — web interface for viewing, filtering, searching, and downloading logs from within each plugin's CP section
- **Standalone System Log Viewer** — browse all logs (plugin, Craft, PHP) from a single interface at **Logging Library → All Logs**
- **Control Panel Section** — access the standalone viewer directly from the main Control Panel navigation when the CP section is enabled
- **LoggingTrait** — drop-in trait that adds `logInfo()`, `logWarning()`, `logError()`, and `logDebug()` to any class
- **LoggingService** — static API for direct logging, log statistics, recent entries, and cleanup
- **High Performance Caching** — file-based cache with ArrayQuery handles 40,000+ entries instantly
- **Multi-Format Parsing** — automatically detects and parses plugin logs, Craft logs, and PHP error logs
- **Edge Detection** — auto-disables file-based log viewer on edge/CDN platforms like Servd
- **Permission-Gated Access** — granular permissions for viewing and downloading logs

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
| [Caching](caching.md) | How the performance cache works |
| [Edge Detection](edge-detection.md) | Hosting on edge/CDN platforms |
| [Integration Guide](integration-guide.md) | Full plugin setup with routes, nav, and permissions |
