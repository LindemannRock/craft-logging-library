# Settings

Choose which log views your site needs, then control runtime capture and presentation from **Logging Library → Settings**. Runtime capture is optional: installing or upgrading never turns it on automatically. Per-plugin file logging still uses [`LoggingLibrary::configure()`](configuration-options.md); these settings belong to Logging Library itself.

![Logging Library General settings page showing the Plugin Name field and the Show Main Menu toggle](../images/settings-general.webp)

## Where to find it

Go to **Logging Library → Settings**, or reach it through **Settings → Plugins → Logging Library**. The four tabs are **General**, **Runtime Logs**, **File Logs**, and **Interface**. All remain accessible regardless of edge detection or whether a viewer is enabled. Settings and Setup require `loggingLibrary:manageSettings`; viewing logs is a separate permission — see [Permissions](../developers/permissions.md).

> [!NOTE]
> With **Show Main Menu** on, navigation contains only sections the current user can access. A settings manager can reach Settings and Setup even with both viewers off. A viewer-only user gets no empty menu. Turning the main menu off does not disable capture.

## Setup

Open **Logging Library → Setup** for a summary of capture, file-viewer availability, and configured storage. It uses the shared LindemannRock setup layout. Disabled runtime capture is a valid choice, not an incomplete installation.

On an ephemeral host, Setup suggests considering Runtime Logs. It does not enable capture, change your cache, select a Redis database, or test connectivity. Confirm entries arrive in [Runtime Logs](runtime-logs.md) before relying on it; multiple instances need shared cache storage.

## General

| Setting | What it does | Default |
|---------|--------------|---------|
| **Plugin Name** | The display name shown for Logging Library in the Control Panel. | `Logging Library` |
| **Show Main Menu** | Show accessible Logging Library sections in the main navigation. Does not change capture or other plugins' **Logs** sections. | On |

## Runtime Logs

Enable capture without creating a configuration file. Console/queue safeguards and request user IDs sit directly below **Enable Runtime Logs**, followed by capture levels, retention, entry count, and refresh interval. **Advanced** contains category filters and payload limits. See [Runtime Logs](runtime-logs.md#configuration-reference) for defaults and bounds.

Turning **Enable Runtime Logs** off hides the dependent fields without clearing their saved values. **Configured Storage** stays visible. Turn capture back on to review or change those preferences.

Each field shows its effective value. A matching nested `runtimeLogStore` option in configuration disables **only that field** and identifies the overriding key. Remove that option to manage it in the CP; the previously saved preference becomes effective again.

Category filters match the category attached to a log entry, not words in its message. Enter one category per line: `my-plugin` matches that exact category, while `yii\db\*` matches categories starting with `yii\db\`. An empty include list allows all categories; exclusions take priority, and an empty exclude list adds no exclusions of your own. Choose at least one capture level. Set refresh to `0` to disable automatic refresh.

**Retention (seconds)** defaults to `86400` (1 day). In the CP, enter between `1` second and `2592000` seconds (30 days); the readable preview updates as you type, and the tip shows both limits. This does not guarantee that entries survive for the whole duration: the entry limit, cache eviction, or clearing the store can remove them sooner. Explicit `runtimeLogStore.ttl` configuration overrides retain their existing behavior and are not shortened to the CP limit.

The **Configured Storage** info box sits above **Enable Runtime Logs** and stays visible when capture is off. It describes the backend derived from the application cache configuration and points to the configuration file for Redis database overrides. To verify capture, enable Runtime Logs, trigger a message at a captured level and category, then check that the new entry appears in the Runtime Logs viewer. There is no separate connection-test page. The capture-change notice below the toggle appears with the capture controls. Setup retains the fuller deployment and multi-server guidance.

> [!IMPORTANT]
> Changes apply to new requests. Restart long-running workers to load changed settings. Disabling capture does not clear stored entries; use **Clear Runtime Logs** while the viewer is enabled if you intend to remove them. Retention and entry limits take effect through subsequent store operations, not a settings-save cleanup job.

The other numeric runtime fields also show their accepted bounds in a **Tip**: Maximum Entries accepts `1–10000` (default `1000`), and Maximum Message Bytes and Maximum Context Bytes each accept `1–65536` bytes.

**Refresh Interval (seconds)** defaults to `5`. In the CP, enter `0` to disable automatic refresh or `1–3600` seconds (up to 1 hour). The live **Current** preview shows a readable duration as you type, or **Disabled** for `0`; the tip explains both limits. Like retention, explicit `runtimeLogStore.refreshInterval` configuration overrides retain their existing behavior. No saved values are rewritten automatically.

## File Logs

**Force Enable File Log Viewers** keeps the existing `forceEnableLogViewer` key and saved value. It defaults to off. The tab explains whether file viewers are available or suppressed.

**Force Enable File Log Viewers** is the escape hatch for [edge detection](edge-detection.md). Craft's `App::isEphemeral()` signal and Servd detection compose with OR behavior. Only enable it when persistent log files are available at `storage/logs/`. A plugin that explicitly configures `enableLogViewer` keeps that explicit value.

This setting does not import hosted logs from Craft Cloud, Servd, or any external logging platform. It only tells Logging Library to try reading local files again. It does not change Craft/Yii logging, dedicated Monolog targets, or log destinations.

## Interface

Interface settings remain available even when file viewers are hidden.

| Setting | What it does | Default |
|---------|--------------|---------|
| **Items Per Page** | How many entries the standalone **All Logs** and **Runtime Logs** viewers show per page (10–500). | 100 |
| **Time Format** | 12-hour (AM/PM) or 24-hour clock for log timestamps. | Inherits base |
| **Show Seconds** | Whether timestamps include seconds. | Inherits base |

**Items Per Page** applies to Logging Library's own views — the standalone **All Logs** viewer and [Runtime Logs](runtime-logs.md). Each plugin's own viewer uses the `itemsPerPage` it passes to `configure()` (default 50) — see [Configuration Options](configuration-options.md).

**Time Format** and **Show Seconds** cascade from the base plugin when their Control Panel fields are set to **Use global default**. Selecting a value here overrides the base default. Only the matching key in `config/logging-library.php` locks a field on this page. The remaining date settings (month format, date order, separator) are global — they live only in the base config, not on this page. See [Log viewer → Adaptive Timestamps](log-viewer.md) for how timestamps render.

## Overriding settings from a config file

Settings can be locked with `config/logging-library.php`. The sample in `src/config.php` lists the options, but copy only those you want to pin: every explicit option locks its matching field, even when it repeats a default. For example, this fuller configuration locks the named preferences while leaving other runtime fields editable:

```php
<?php
return [
    '*' => [
        'pluginName' => 'Logging Library',

        // Entries per page in the standalone viewer (10–500)
        'itemsPerPage' => 100,

        // Show Logging Library in the main CP navigation
        'showCpSection' => true,

        // Force-enable file-based viewers even on edge/ephemeral hosting
        'forceEnableLogViewer' => false,

        // Per-option overrides for the Runtime Logs settings
        // — see the Runtime Logs page for the full option reference
        'runtimeLogStore' => [
            'enabled' => false,
            'skipConsoleRequests' => true,
            'skipQueueRequests' => true,
            'redis' => [
                // 'database' => '$LOGGING_LIBRARY_RUNTIME_REDIS_DB',
            ],
        ],

        // Base-plugin time overrides (optional — leave out to inherit base)
        // 'timeFormat'  => '24',  // '12' or '24'
        // 'showSeconds' => false,
    ],
];
```

The `runtimeLogStore` block overrides individual [Runtime Logs](runtime-logs.md) preferences. Omitted options use saved preferences or defaults; the database's flat runtime column names are not public config keys. Capture remains independent of file-viewer detection. Only `redis.database` is configuration-only: omit it to inherit Craft's Redis database, choose an assigned non-negative database, resolve an environment reference, or explicitly disable `SELECT` with `null`. See the Runtime Logs page for strict resolution rules. Restart long-running workers after changing capture settings.

When a setting is present in this file, the matching Control Panel field is **disabled** and shows a notice that it's being overridden by `config/logging-library.php`. The full resolution order, highest priority first:

1. **Plugin config file** (`config/logging-library.php`) — environment-aware overrides
2. **Control Panel setting** — what's saved on these pages
3. **Base config file** (`config/lindemannrock-base.php`) — global default (time settings only)
4. **Hardcoded defaults** — the final fallback

Use the config file when you want a value pinned per environment (for example, forcing viewers on in one environment only) or kept out of the database; use the Control Panel for everything else.

## Where settings are stored

These settings persist in the plugin's own database table (`logginglibrary_settings`), not in Craft's project config. The table is created on install and kept current by the plugin's migrations, so existing sites pick up new settings automatically on update.

The 5.19.0 migration adds runtime preference columns with the existing conservative defaults. It preserves prior settings and does not copy environment-specific configuration into the database. Existing nested configuration keeps precedence unchanged. There is no log migration, cache move, Redis database change, or automatic capture activation. Apply the migration before using the new pages. Removing an override reveals the saved preference or default, not a copy of the removed value.
