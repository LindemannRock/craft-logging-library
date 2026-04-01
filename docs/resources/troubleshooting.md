# Troubleshooting

## Log file is empty or not created

1. Verify Logging Library is installed and enabled: **Settings → Plugins → Logging Library**
2. Check that `LoggingLibrary::configure()` is called in your plugin's `init()` with the correct `pluginHandle`
3. Confirm you're calling a logging method (`logInfo()`, `Craft::info()`, etc.) with the plugin handle as the category
4. Check file permissions on `storage/logs/` — Craft needs write access

**Why:** The Monolog target is only created when `configure()` runs. If your plugin loads before Logging Library, the target won't exist yet. The base plugin's `PluginHelper::bootstrap()` handles load order automatically.

## Debug messages are not appearing

1. Check if `devMode` is enabled in your environment: **Settings → General → Dev Mode** or `CRAFT_DEV_MODE=true` in `.env`
2. Verify `logLevel` is set to `'debug'` in your `configure()` call

**Why:** Craft silently ignores `Craft::debug()` calls when `devMode` is `false`. This is by design — debug logging is suppressed in production for security and performance reasons.

## Log viewer shows "Plugin logging not configured"

1. Ensure `LoggingLibrary::configure()` is called with `'enableLogViewer' => true`
2. Verify `configure()` runs during your plugin's `init()` before you try to access the log viewer
3. Confirm the route you are opening matches the same plugin handle passed as `pluginHandle`

**Why:** The controller extracts the plugin handle from the URL and looks up its config. If `configure()` wasn't called, or the URL handle does not match the configured handle, it can't find the config. When `enableLogViewer` is true, Logging Library registers the CP routes automatically.

## Log viewer shows "Log viewer is disabled for this plugin"

1. Check if `enableLogViewer` is explicitly set to `false` in your `configure()` call
2. Check if you're running on an edge/CDN platform (Servd) — the viewer is auto-disabled

**Fix:** Set `'enableLogViewer' => true` explicitly to override edge detection. See [Edge Detection](../feature-tour/edge-detection.md).

## Permission denied when viewing logs

1. Ensure the user has the required permission (e.g., `yourPlugin:viewLogs`)
2. Check that permissions are registered in your plugin's `EVENT_REGISTER_PERMISSIONS` handler
3. Grant the permission via **Settings → Users → User Groups → [Group] → [Plugin Name]**

**Why:** When `viewSystemLogsPermissions` is set, the controller requires the user to have at least one of the listed permissions. Admins are always allowed.

## Cache not updating after new log entries

1. Check that the log file has actually changed — new entries increase the file size
2. Manually clear the cache: **Utilities → Clear Caches → Logging Library caches**

**Why:** The cache key includes the file size and modification time. If the file hasn't been modified since the last parse, the cached version is served. In rare cases, the OS may buffer writes — clearing the cache forces a re-parse.

## Duplicate log entries

1. Check if `LoggingLibrary::configure()` is being called multiple times for the same plugin handle
2. Verify you don't have both `LoggingTrait` logging and direct `Craft::info()` calls with the same category

**Why:** Each `configure()` call removes existing Monolog targets for that handle before creating a new one, but if something prevents cleanup (e.g., concurrent requests during init), duplicates can briefly appear.
