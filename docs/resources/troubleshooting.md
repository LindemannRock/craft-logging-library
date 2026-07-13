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

1. Ensure `LoggingLibrary::configure()` is called for the plugin
2. Verify `configure()` runs during your plugin's `init()` before you try to access the log viewer
3. Confirm the route you are opening matches the same plugin handle passed as `pluginHandle`

**Why:** The controller extracts the plugin handle from the URL and looks up its config. If `configure()` wasn't called, or the URL handle does not match the configured handle, it can't find the config. When the viewer is enabled, Logging Library registers the CP routes automatically.

## Log viewer shows "Log viewer is disabled for this plugin"

1. Check if `enableLogViewer` is explicitly set to `false` in your `configure()` call
2. Check if you're running on an edge/CDN platform (Servd) — the viewer is auto-disabled

**Fix:** Set `'enableLogViewer' => true` for that plugin, or enable `forceEnableLogViewer` globally in Logging Library settings/config if persistent log storage is available. See [Edge Detection](../feature-tour/edge-detection.md).

## Servd shows an empty Select File dropdown

On Servd, Logging Library can only show files that exist in the current Craft `storage/logs/` path. Servd collects Craft logs centrally for its dashboard, but that hosted log feed is not imported into the Logging Library interface.

**Fix:** For recent activity in the CP, enable [Runtime Logs](../feature-tour/runtime-logs.md) — it captures log messages into Craft's cache as they happen, so it doesn't depend on files in `storage/logs/`. Use Servd's **Logs** page, or Servd's Papertrail/Datadog integrations, for the complete hosted log history. Only enable **Force Enable Log Viewers** if `storage/logs/` is backed by persistent shared storage. Without that, the dropdown may be empty, stale, or limited to whichever application instance handled the request.

## Runtime Logs is missing or empty

1. Confirm `runtimeLogStore.enabled` is `true` in `config/logging-library.php` — there is no Control Panel toggle
2. Check the user has `loggingLibrary:viewAllLogs` and that **Show Main Menu** is on in Logging Library settings
3. If the view is empty, trigger something that logs at a captured level (`error`, `warning`, or `info` by default) and let the page auto-refresh
4. Check your `levels`, `categories`, and `except` config — an entry has to match all three to be captured

**Why:** Runtime entries only exist in Craft's cache. They expire with the configured `ttl`, roll off past `maxEntries`, and disappear when the cache is cleared. On load-balanced hosting without a shared cache backend (such as Redis), each instance keeps its own store, so the CP may show only entries captured by the instance serving your request. See [Runtime Logs](../feature-tour/runtime-logs.md).

## Permission denied when viewing logs

1. Ensure the user has the required permission (e.g., `yourPlugin:viewLogs`)
2. Check that permissions are registered in your plugin's `EVENT_REGISTER_PERMISSIONS` handler
3. Grant the permission via **Settings → Users → User Groups → [Group] → [Plugin Name]**

**Why:** When `viewSystemLogsPermissions` is set, the controller requires the user to have at least one of the listed permissions. Admins are always allowed.

## Settings save shows a validation error

Numeric settings such as Items Per Page must be whole numbers within the allowed range. If a value is invalid, Logging Library keeps you on the same settings page and shows the field error inline.

When a setting is overridden in `config/logging-library.php`, the Control Panel field is skipped during save. Change the config file value instead.

## Cache not updating after new log entries

1. Check that the log file has actually changed — new entries increase the file size
2. Manually clear the cache: **Utilities → Clear Caches → Logging Library caches**

**Why:** The cache key includes the file size and modification time. If the file hasn't been modified since the last parse, the cached version is served. In rare cases, the OS may buffer writes — clearing the cache forces a re-parse.

## Undated log appears as Other or entries show Unknown

Undated source logs such as `freeform-email.log` should appear as their own source in the standalone All Logs viewer. If a file still appears under **Other** or its rows show `UNKNOWN`, refresh the log cache for that file from the sidebar.

**Why:** Older parser caches may have been built before undated source logs and bracketed ISO-8601 Monolog lines were recognized. Plugin updates that improve the parser invalidate old caches automatically on the next view, so this usually resolves itself after updating; the manual **Refresh Cache** button covers the remaining cases by forcing a re-read with the current parser.

## Duplicate log entries

1. Check if `LoggingLibrary::configure()` is being called multiple times for the same plugin handle
2. Verify you don't have both `LoggingTrait` logging and direct `Craft::info()` calls with the same category

**Why:** Each `configure()` call removes existing Monolog targets for that handle before creating a new one, but if something prevents cleanup (e.g., concurrent requests during init), duplicates can briefly appear.
