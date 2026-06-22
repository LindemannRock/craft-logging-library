# Settings

Logging Library has its own settings area in the Control Panel for the things that aren't tied to a single plugin's integration — the display name, whether the consolidated viewer appears in the main menu, how many entries the standalone viewer shows per page, and how timestamps are formatted. Per-plugin logging behaviour (log level, retention, per-plugin permissions) is still configured in code with [`LoggingLibrary::configure()`](configuration-options.md); this page covers the library's *own* settings.

![Logging Library General settings page showing the Plugin Name field and the Show Main Menu toggle](images/settings-general.webp)

## Where to find it

Go to **Logging Library → Settings** in the Control Panel. Settings open on the **General** tab, with **Interface** as a second tab in the sidebar. Access requires the `loggingLibrary:manageSettings` permission (admins always have it) — see [Permissions](../developers/permissions.md).

> [!NOTE]
> When the standalone **All Logs** viewer isn't available (an edge/ephemeral environment with the override off, or **Show Main Menu** turned off), the main **Logging Library** menu item opens straight to Settings, and the **Interface** tab is hidden — its options only matter when there's a viewer to show.

## General

| Setting | What it does | Default |
|---------|--------------|---------|
| **Plugin Name** | The display name shown for Logging Library in the Control Panel. | `Logging Library` |
| **Show Main Menu** | Show Logging Library in the main Control Panel navigation as a consolidated **All Logs** view. Turn it off to hide the menu item while keeping each plugin's own **Logs** section. | On |
| **Force Enable Log Viewers** | Only shown when an edge/ephemeral environment is detected. Force-enables file-based log viewers — both the standalone **All Logs** view and every plugin's dedicated **Logs** section — even though edge detection would normally hide them. | Off |

**Force Enable Log Viewers** is the escape hatch for the [edge-detection](edge-detection.md) behaviour. On platforms with ephemeral storage, file-based viewers are hidden by default because logs don't survive a redeploy. If you've attached persistent storage at `storage/`, switch this on to bring the viewers back.

## Interface

The Interface tab only appears when a viewer is available (see the note above).

| Setting | What it does | Default |
|---------|--------------|---------|
| **Items Per Page** | How many entries the standalone **All Logs** viewer shows per page (10–500). | 100 |
| **Time Format** | 12-hour (AM/PM) or 24-hour clock for log timestamps. | Inherits base |
| **Show Seconds** | Whether timestamps include seconds. | Inherits base |

**Items Per Page** applies to the standalone viewer only. Each plugin's own viewer uses the `itemsPerPage` it passes to `configure()` (default 50) — see [Configuration Options](configuration-options.md).

**Time Format** and **Show Seconds** cascade from the base plugin. If they're set in `config/lindemannrock-base.php`, that value wins and the field is locked here. The remaining date settings (month format, date order, separator) are global — they live only in the base config, not on this page. See [Log Viewer → Adaptive Timestamps](log-viewer.md) for how timestamps render.

## Overriding settings from a config file

Every setting on these pages can be locked in code with a `config/logging-library.php` file. Copy the sample from the plugin's `src/config.php` and edit your copy:

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

        // Base-plugin time overrides (optional — leave out to inherit base)
        // 'timeFormat'  => '24',  // '12' or '24'
        // 'showSeconds' => false,
    ],
];
```

When a setting is present in this file, the matching Control Panel field is **disabled** and shows a notice that it's being overridden by `config/logging-library.php`. The full resolution order, highest priority first:

1. **Plugin config file** (`config/logging-library.php`) — environment-aware overrides
2. **Control Panel setting** — what's saved on these pages
3. **Base config file** (`config/lindemannrock-base.php`) — global default (time settings only)
4. **Hardcoded defaults** — the final fallback

Use the config file when you want a value pinned per environment (for example, forcing viewers on in one environment only) or kept out of the database; use the Control Panel for everything else.

## Where settings are stored

These settings persist in the plugin's own database table (`logginglibrary_settings`), not in Craft's project config. The table is created on install and kept current by the plugin's migrations, so existing sites pick up new settings automatically on update.
