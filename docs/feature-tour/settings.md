# Settings

Choose which log views your site needs, then control runtime capture and presentation from **Logging Library → Settings**. Runtime capture is optional: installing or upgrading never turns it on automatically. Per-plugin file logging still uses [`LoggingLibrary::configure()`](configuration-options.md); these settings belong to Logging Library itself.

![Logging Library General settings page showing the Plugin Name field and the Show Main Menu toggle](../images/settings-general.webp)

## Where to find it

Go to **Logging Library → Settings**, or reach it through **Settings → Plugins → Logging Library**. The four tabs are **General**, **File Logs**, **Runtime Logs**, and **Interface**. All remain accessible regardless of edge detection or whether a viewer is enabled. Settings requires `loggingLibrary:manageSettings`; viewing logs is a separate permission — see [Permissions](../developers/permissions.md).

> [!NOTE]
> With **Show Main Menu** on, navigation contains only sections the current user can access. A settings manager can reach Settings even with both viewers off. A viewer-only user gets no empty menu. Turning the main menu off does not disable capture.

The installation welcome button opens these settings on **General**. Runtime capture remains optional; use the **Runtime Logs** tab when you want recent diagnostics, or **File Logs** to review file-viewer availability.

## General

| Setting | What it does | Default |
|---------|--------------|---------|
| **Plugin Name** | The display name shown for Logging Library in the Control Panel. | `Logging Library` |
| **Show Main Menu** | Show accessible Logging Library sections in the main navigation. Does not change capture or other plugins' **Logs** sections. | On |

## File Logs

**Force Enable File Log Viewers** keeps the existing `forceEnableLogViewer` key and saved value. It defaults to off. The tab explains whether file viewers are available or suppressed. On an edge or ephemeral host, the notice links **Runtime Logs** directly to its settings tab so you can review or enable capture.

**Force Enable File Log Viewers** is the escape hatch for [edge detection](edge-detection.md). Craft's `App::isEphemeral()` signal and Servd detection compose with OR behavior. Only enable it when persistent log files are available at `storage/logs/`. A plugin that explicitly configures `enableLogViewer` keeps that explicit value.

This setting does not import hosted logs from Craft Cloud, Servd, or any external logging platform. It only tells Logging Library to try reading local files again. It does not change Craft/Yii logging, dedicated Monolog targets, or log destinations.

## Runtime Logs

Enable capture without creating a configuration file. Console/queue safeguards and request user IDs sit directly below **Enable Runtime Logs**, followed by capture levels, retention, entry count, and refresh interval. **Advanced** contains category filters and payload limits. See [Runtime Logs](runtime-logs.md#configuration-reference) for defaults and bounds.

Turning **Enable Runtime Logs** off hides the dependent fields without clearing their saved values. Its help text stays visible beneath the label: changes apply to new requests, long-running workers need restarting, and turning capture off does not delete stored logs. **Configured Storage** also stays visible. Turn capture back on to review or change those preferences.

Each field shows its effective value. A matching nested `runtimeLogStore` option in configuration disables **only that field** and identifies the overriding key. Remove that option to manage it in the CP; the previously saved preference becomes effective again.

**Include Sources and Categories** and **Exclude Sources and Categories** let you search for a source by its name in the viewer, such as **Vite**, **DB Connection**, or **Code Editor**. Select a result to add it. The dropdown also shows the underlying category patterns, so you can see what the choice covers. Installed plugins and known framework sources are available even before they have logged a message.

For a custom category, type its exact name or a trailing-wildcard pattern and press Enter. For example, `my-plugin` matches that exact category; `yii\db\*` matches categories starting with `yii\db\`. Matching is case-sensitive and checks the category, not words in the message. You can combine named sources and custom patterns. Leave Include empty to allow all sources; exclusions take precedence. Remove a selected item to stop including or excluding it.

Named choices save ordinary category patterns, not translated display names. Configuration uses `runtimeLogStore.includeCategories` and `runtimeLogStore.excludeCategories`; each explicit key locks its matching field, even when its value is `[]`. Previously typed names remain literal custom categories, shown as **Category: Vite**, for example; remove that item and select the named **Vite** source if you meant the plugin. A partial custom pattern stays partial rather than silently expanding to a whole source. Remove individual items or all items, then save: an empty Include list allows all sources, while an empty Exclude list removes your exclusions. Filter changes affect new messages only and do not remove stored entries.

Choose at least one capture level. Set refresh to `0` to disable automatic refresh.

**Retention (seconds)** defaults to `86400` (1 day). In the CP, enter between `1` second and `2592000` seconds (30 days); the readable preview updates as you type, and the tip shows both limits. This does not guarantee that entries survive for the whole duration: the entry limit, cache eviction, or clearing the store can remove them sooner. Explicit `runtimeLogStore.ttl` configuration overrides retain their existing behavior and are not shortened to the CP limit.

The **Configured Storage** info box sits above **Enable Runtime Logs** and stays visible when capture is off. It describes the backend derived from the application cache configuration and points to the configuration file for Redis database overrides. To verify capture, enable Runtime Logs, trigger a message at a captured level and category, then check that the new entry appears in the Runtime Logs viewer. There is no separate connection-test page. Multiple instances need shared cache storage; see [Runtime Logs](runtime-logs.md#storage-backends) for deployment guidance.

> [!IMPORTANT]
> Changes apply to new requests. Restart long-running workers to load changed settings. Disabling capture does not clear stored entries; use **Clear runtime logs** while the viewer is enabled if you intend to remove them. That action requires separate runtime viewing and clearing [permissions](../developers/permissions.md). Retention and entry limits take effect through subsequent store operations, not a settings-save cleanup job.

The other numeric runtime fields also show their accepted bounds in a **Tip**: Maximum Entries accepts `1–10000` (default `1000`), and Maximum Message Bytes and Maximum Context Bytes each accept `1–65536` bytes (default `8000` each).

**Maximum Message Bytes** limits how much of each new entry's main message is kept. **Maximum Context Bytes** separately limits its attached details, such as error data and stack traces, after JSON encoding. Both limits count bytes, not characters. Larger content is shortened and marked with `...`; the entry is still captured, but some diagnostic detail is lost. The marker is added after the byte cut. These limits apply only to new Runtime Logs entries, not existing entries or file logs. Keep the defaults unless you need to reduce stored detail or retain longer messages.

**Refresh Interval (seconds)** defaults to `5`. In the CP, enter `0` to disable automatic refresh or `1–3600` seconds (up to 1 hour). The live **Current** preview shows a readable duration as you type, or **Disabled** for `0`; the tip explains both limits. Like retention, explicit `runtimeLogStore.refreshInterval` configuration overrides retain their existing behavior. No saved values are rewritten automatically.

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

The 5.19.0 migrations add runtime preferences and rename the filter columns to `runtimeIncludeCategories` and `runtimeExcludeCategories`, preserving any already-saved filter lists. They do not copy environment-specific configuration into the database. Update existing config keys from `categories` / `except` to `includeCategories` / `excludeCategories` inside `runtimeLogStore`; there are no legacy aliases. Per-option configuration precedence is unchanged. There is no log migration, cache move, Redis database change, or automatic capture activation. Apply the migrations before using the new pages. Removing an override reveals the saved preference or default, not a copy of the removed value.
