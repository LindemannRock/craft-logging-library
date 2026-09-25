# Troubleshooting

Use these checks when a log destination, viewer, or Runtime Logs result does not match what you expect.

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
2. Check whether Craft reports an ephemeral host (including Craft Cloud) or its normalized `SERVD_PROJECT_SLUG` value resolves to a non-empty project slug — the signals compose with OR behavior and either one suppresses automatic file-viewer availability. Missing, blank, whitespace-only, null, and normalized false Servd values do not detect Servd

**Fix:** Set `'enableLogViewer' => true` for that plugin, or enable `forceEnableLogViewer` globally in Logging Library settings/config if persistent log storage is available. An explicit per-plugin `enableLogViewer` value keeps precedence over the automatic default. See [Edge Detection](../feature-tour/edge-detection.md).

**Why:** Viewer suppression only hides file-reading routes and navigation. It does not disable Craft/Yii logging, Logging Library's dedicated Monolog targets, stream logging, or hosted logging feeds.

## Logging Library is missing from the main navigation on an ephemeral host

1. Confirm **Show Main Menu** is on
2. Check whether file viewers are suppressed because Craft reports an ephemeral host or its normalized `SERVD_PROJECT_SLUG` value resolves to a non-empty project slug. Missing, blank, whitespace-only, null, and normalized false Servd values do not detect Servd
3. Check permissions: settings managers can access Settings; viewer-only users need an available viewer

**Fix:** If persistent shared storage backs `storage/logs/`, use **Force Enable File Log Viewers** to restore automatic file-viewer availability. Otherwise, enable [Runtime Logs](../feature-tour/runtime-logs.md) under **Settings → Runtime Logs** or in configuration. With both viewers off, a settings manager still sees Settings, but a viewer-only user has no available section.

## Servd shows an empty Select File dropdown

On Servd, Logging Library can only show files that exist in the current Craft `storage/logs/` path. Servd collects Craft logs centrally for its dashboard, but that hosted log feed is not imported into the Logging Library interface.

**Fix:** For recent activity in the CP, enable [Runtime Logs](../feature-tour/runtime-logs.md) — it captures log messages into a bounded Redis list, or a bounded generic value when Craft cache is non-Redis, so it doesn't depend on files in `storage/logs/`. Use Servd's **Logs** page, or Servd's Papertrail/Datadog integrations, for the complete hosted log history. Only enable **Force Enable File Log Viewers** if `storage/logs/` is backed by persistent shared storage. Without that, the dropdown may be empty, stale, or limited to whichever application instance handled the request.

## Runtime Logs is missing or empty

1. Confirm **Enable Runtime Logs** is on under **Settings → Runtime Logs**, or overridden to `true` by `runtimeLogStore.enabled` in configuration
2. Check the user has `loggingLibrary:viewRuntimeLogs` and that **Show Main Menu** is on in Logging Library settings
3. If the view is empty, trigger something that logs at a captured level (`error`, `warning`, or `info` by default) and let the page auto-refresh
4. Check effective capture levels and include/exclude categories in Settings — an entry has to match all three to be captured

**Why:** Runtime entries only exist in their selected diagnostic store. They expire with the configured `ttl`, roll off past `maxEntries`, and disappear when that store is cleared or evicts them. On load-balanced hosting without a shared cache backend (such as Redis), each instance keeps its own fallback store, so the CP may show only entries captured by the instance serving your request. The Runtime Logs sidebar reports the effective backend and Redis database. See [Runtime Logs](../feature-tour/runtime-logs.md).

## An excluded source still appears in Runtime Logs

1. Open **Settings → Runtime Logs → Advanced → Exclude Sources and Categories**
2. Search for the source and select the named result, such as **Vite**, **DB Connection**, **Code Editor**, or an installed plugin such as **Minify**
3. If you previously typed a display name as a raw category, remove the **Category: Vite** item and select **Vite** instead
4. Save, then trigger a new request and check its timestamp; restart long-running workers if they capture logs

**Why:** The viewer groups raw categories under readable source names. The picker translates a named selection into matching category patterns, but custom categories and configuration arrays match raw strings, case-sensitively. **DB Connection** matches `yii\db\Connection::open`; it does not exclude the separate **DB Queries** or **DB Commands** sources. Existing entries are not removed when a capture filter changes; let them expire or clear them only if you no longer need them.

## Removed capture filters return after saving

Reload the Runtime Logs settings page after updating, remove the unwanted source or category selections, and save again. Both Include and Exclude support removing individual items or clearing the whole list. A configuration override locks the corresponding field; remove `runtimeLogStore.includeCategories` or `runtimeLogStore.excludeCategories` from configuration if you want to manage that list in the CP.

**Why:** A picker bug allowed removed chips to leave stale values in the submitted form, so saving restored the earlier selections. Picker values are now encoded for safe removal and decoded back into ordinary category patterns before saving. This does not clear existing runtime logs.

## Runtime Logs shows Redis unavailable

When Craft cache is Yii Redis, Redis is the only authoritative Runtime Logs backend. The sidebar reports **Redis unavailable** if its database configuration is invalid, `SELECT` is rejected, the independent connection cannot be established, or a Redis operation fails.

1. Confirm Craft cache is configured as `yii\redis\Cache`
2. If `runtimeLogStore.redis.database` references an environment variable, confirm it exists and contains a non-negative integer
3. If you supplied a literal value, use an integer such as `4`, not the string `'4'`
4. Confirm the endpoint supports the configured Redis database; a rejected `SELECT` fails closed
5. Restart long-running web and queue processes after correcting configuration

The affected batch is dropped fail-soft. Logging Library does not switch to Craft cache, write a generic Runtime key in database `0`, or make entries alternate between storage families. A later operation can reconnect to the same authoritative Redis backend. The sidebar's **Runtime Store** value, concise **Runtime Location** value, and exact-key tooltip update during AJAX refresh, so recovery or continued unavailability is visible without reloading the page.

An explicit `'database' => null` sends no `SELECT` and is intended only for compatible cluster-style endpoints. Logical Redis databases provide namespace and administrative separation, not separate CPU, memory, network, eviction, or server contention.

## Runtime Logs shows object reconstruction text for a structured message

Older Logging Library versions exported a `samdark\log\PsrMessage` object as PHP reconstruction text, which could make the message begin with content resembling `unserialize(...)` and bury its structured context inside that text.

**Fix:** Update to Logging Library 5.18.1 or later, then reproduce the event. New entries show `PsrMessage::getMessage()` as the message and `getContext()` in the separate context field. Existing cached Runtime entries are not rewritten; let them expire under the configured `ttl`, roll off the bounded store, or use **Clear runtime logs** if you have both `loggingLibrary:viewRuntimeLogs` and `loggingLibrary:clearRuntimeLogs` and no longer need the current diagnostic window.

**Why:** Runtime Logs stores bounded diagnostic snapshots. Updating normalization changes newly captured records only; it does not migrate, evaluate, or deserialize records already in Redis or Craft cache.

## Runtime Logs uses Craft cache

This is expected only when Craft's configured cache is genuinely non-Redis. The generic backend keeps one bounded value in that cache and uses a zero-wait mutex. A busy mutex drops the current batch rather than delaying the request. Cache read failures do not replace the existing value, and failed writes leave the previous buffer intact.

With a local or otherwise non-shared cache, each application instance has a separate Runtime Logs buffer. Use a shared cache when the CP must see entries captured by multiple instances.

## Console or queue entries are missing from Runtime Logs

1. Check **Skip Console Requests** and **Skip Queue Requests** under **Settings → Runtime Logs**, directly below **Enable Runtime Logs**, including any config overrides; both default to on
2. Remember that Craft queue workers normally run as console requests, so changing only the queue option may still leave worker entries excluded
3. Check the configured `levels`, especially before enabling debug-level capture for a busy command or worker

**Fix:** For temporary diagnosis, disable both safeguards, reproduce the issue, and then restore them to `true`:

```php
'runtimeLogStore' => [
    'enabled' => true,
    'skipConsoleRequests' => false,
    'skipQueueRequests' => false,
],
```

**Why:** The defaults avoid Runtime Logs cache work inside commands and queue jobs. Queue detection skips the current buffered runtime export batch as a whole, so nearby non-queue messages in that batch may also be absent. These options affect only Runtime Logs; Craft file logs and hosted feeds such as Servd's continue unchanged. Capturing console or queue traffic, especially debug output, can fill the bounded runtime buffer quickly and add cache traffic.

Restart long-running queue workers after changing these options. A running worker keeps the Runtime Logs target configuration captured when its application started.

## Long user names appear shortened

Hover the User or Request User value to see the full name or email address. The table uses an ellipsis when it cannot fit the value, preventing it from overlapping the message. This is display-only: stored logs and user values are unchanged, including after Runtime Logs refreshes.

## Permission denied when viewing logs

1. Ensure the user has the required permission (e.g., `yourPlugin:viewLogs`)
2. Check that permissions are registered in your plugin's `EVENT_REGISTER_PERMISSIONS` handler
3. Grant the permission via **Settings → Users → User Groups → [Group] → [Plugin Name]**

**Why:** When `viewSystemLogsPermissions` is set, the controller requires the user to have at least one of the listed permissions. Admins are always allowed.

## A clearing action or Runtime Logs is unavailable after upgrading

Before changing permissions, check which operation is missing: **View runtime logs** is independent of file viewing, and **Clear runtime logs** requires runtime viewing as well. For the standalone file viewer, **Refresh Cache** requires both **View all file logs** and **Clear file log cache**. If access changed after upgrading to 5.19.0, confirm the plugin migrations and resulting group project config have been applied; see [Permissions](../developers/permissions.md#upgrading-to-5190).

## Settings save shows a validation error

Numeric settings such as Items Per Page must be whole numbers within the allowed range. If a value is invalid, Logging Library keeps you on the same settings page and shows the field error inline.

When a setting is overridden in `config/logging-library.php`, the Control Panel field is skipped during save. Change the config file value instead.

## Runtime settings are locked or changes do not take effect

Each explicit nested option in `config/logging-library.php` locks only its matching field. Copying the full sample pins all listed options. Remove the specific override to manage that value in the CP; the saved preference or default becomes effective again. Redis database selection remains configuration-only.

Changes affect new requests. Restart long-running workers after saving capture settings. **Configured Storage** on Settings does not test connectivity: trigger a matching log message and check the Runtime Logs viewer. Turning capture off does not clear existing entries, and disabling user IDs does not redact personal data already present in messages or context.

## Cache not updating after new log entries

1. Check that the log file has actually changed — new entries increase the file size
2. Manually clear the cache: **Utilities → Clear Caches → Logging Library caches**

**Why:** The cache key includes the file size and modification time. If the file hasn't been modified since the last parse, the cached version is served. In rare cases, the OS may buffer writes — clearing the cache forces a re-parse.

## Undated log appears as Other or entries show Unknown

Undated source logs such as `freeform-email.log` should appear as their own source in the standalone All Logs viewer. If a file still appears under **Other** or its rows show `UNKNOWN`, refresh the log cache for that file from the sidebar.

**Why:** Older parser caches may have been built before undated source logs and bracketed ISO-8601 Monolog lines were recognized. Plugin updates that improve the parser invalidate old caches automatically on the next view, so this usually resolves itself after updating; the manual **Refresh Cache** button covers the remaining cases by forcing a re-read with the current parser.

## Plugin entries also appear in Craft's global log

If a plugin entry appears once in its dedicated destination and once in Craft's global log, first update to Logging Library 5.18.1 or later. Then confirm the category passed to Craft matches the exact `pluginHandle` used by `LoggingLibrary::configure()` and reproduce the event with a new message.

Logging Library keeps one dedicated target per configured handle, even when configuration runs again. It also excludes that exact handle from Craft's current default targets and from the template used to build future defaults. Other categories continue to reach Craft's global destination, and existing target exclusions, handlers, levels, and formatting remain in place.

If two copies still appear in the same dedicated destination, check whether the application emits the event twice—for example, once through `LoggingTrait` and once through a direct `Craft::info()` call. Updating target routing does not remove entries already written to log files or hosted feeds.
