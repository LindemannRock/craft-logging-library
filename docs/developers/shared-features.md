# Shared Features

Logging Library is built on `lindemannrock/base`, the shared foundation used across LindemannRock plugins. Most of this is invisible in day-to-day use — it's what keeps naming, settings, and Control Panel behaviour consistent — but a couple of pieces are worth knowing about when you're working with the plugin in code or templates.

## Bootstrap

In its `init()`, Logging Library calls `PluginHelper::bootstrap()`. This single call wires up the pieces the base plugin provides:

- **Twig globals** — registers the `loggingLibraryHelper` variable for templates (see [Twig Globals](twig-globals.md))
- **Install experience** — the welcome screen shown on first install, with a call-to-action that opens **All Logs** (or **Settings** when no viewer is available)
- **Plugin-name resolution** — applies the display name from `config/logging-library.php` if one is set

Unlike a consumer plugin, Logging Library passes empty log-permission arrays to `bootstrap()` — it *provides* the logging infrastructure rather than consuming it, so it registers its own viewer routes and permissions directly.

## Settings traits

The [Settings](../feature-tour/settings.md) model composes several base traits so the plugin doesn't reinvent common settings behaviour:

| Trait | What it adds |
|-------|--------------|
| `SettingsPersistenceTrait` | Saves/loads settings to the `logginglibrary_settings` table |
| `SettingsConfigTrait` | Applies `config/logging-library.php` overrides and flags overridden fields |
| `PluginNameSettingsTrait` | The configurable **Plugin Name** field |
| `ItemsPerPageSettingsTrait` | The **Items Per Page** field (10–500) |
| `DateFormatSettingsTrait` | The **Time Format** / **Show Seconds** fields that cascade from base |
| `SettingsDisplayNameTrait` | The display-name helpers exposed in Twig |

These are why the same settings (name, items-per-page, timestamp format) look and behave the same way across every LindemannRock plugin.
