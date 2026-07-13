# Permissions

Logging Library registers four permissions — two for its own log views (the standalone All Logs viewer and Runtime Logs), one for clearing its caches, and one for managing its settings. Individual plugins register their own log-viewing permissions separately.

All four appear in the Control Panel under **Settings → Users → (group/user) → Permissions → Logging Library**. Admins always have full access regardless of permission settings.

## Permission Structure

### Standalone Viewer

| Permission | Description |
|------------|-------------|
| **`loggingLibrary:viewAllLogs`** | Parent — access the standalone "All Logs" viewer and the **Runtime Logs** view |
| └─ `loggingLibrary:downloadAllLogs` | Download log files from the standalone viewer |

These control access to the centralized viewer at **Logging Library → All Logs** when the CP section is enabled. The same `viewAllLogs` permission also gates the [Runtime Logs](../feature-tour/runtime-logs.md) view when the runtime log store is enabled.

### Caches & Settings

| Permission | Description |
|------------|-------------|
| **`loggingLibrary:clearCache`** | Show the **Logging Library caches** option under **Utilities → Clear Caches** and allow clearing it; also shows the **Clear Runtime Logs** button in the [Runtime Logs](../feature-tour/runtime-logs.md) view |
| **`loggingLibrary:manageSettings`** | Access the Logging Library settings pages (**General** and **Interface**) and the **Settings** subnav item |

These two are top-level permissions — they are not nested under `viewAllLogs`. A user can manage settings without being able to read logs, and vice versa.

### Per-Plugin Permissions

Each plugin that integrates Logging Library registers its own permissions. These are not defined by the library — they are passed to `LoggingLibrary::configure()` as `viewSystemLogsPermissions` and `downloadSystemLogsPermissions`.

A typical plugin registers:

| Permission | Description |
|------------|-------------|
| **`yourPlugin:viewLogs`** | Parent — view the plugin's log viewer |
| └─ `yourPlugin:downloadLogs` | Download log files from the plugin's viewer |

## Checking Permissions

In Twig:

```twig
{% if currentUser.can('loggingLibrary:viewAllLogs') %}
    {# User can access standalone viewer #}
{% endif %}
```

In PHP:

```php
if (Craft::$app->getUser()->checkPermission('loggingLibrary:viewAllLogs')) {
    // User can access standalone viewer
}

// In a controller
$this->requirePermission('loggingLibrary:viewAllLogs');
```

## Nested Permission Pattern

Craft's nested permissions are a UI convenience — the parent permission does not automatically grant child permissions.

- **"View" permissions** control read access and CP subnav visibility
- **"Download" permissions** control whether the download button appears

To give a user read-only access, grant `loggingLibrary:viewAllLogs` only. For full access including downloads, also grant `loggingLibrary:downloadAllLogs`.

## How Permissions Are Checked

The library checks permissions in several places:

1. **Navigation** — the **All Logs** and **Runtime Logs** subnav items are hidden unless the user is admin or has `loggingLibrary:viewAllLogs`; the **Settings** subnav requires `loggingLibrary:manageSettings`
2. **Log controller** — `LogsController` checks `viewSystemLogsPermissions` before rendering and `downloadSystemLogsPermissions` before allowing file downloads
3. **Settings controller** — `SettingsController` requires `loggingLibrary:manageSettings` for every action
4. **Utilities** — the **Logging Library caches** entry only registers under **Utilities → Clear Caches** when the user has `loggingLibrary:clearCache`

When a per-plugin permissions array is empty (no permissions specified), any logged-in user can access that plugin's viewer.
