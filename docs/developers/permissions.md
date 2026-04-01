# Permissions

Logging Library registers permissions for the standalone log viewer. Individual plugins register their own log-viewing permissions separately.

## Permission Structure

### Standalone Viewer

| Permission | Description |
|------------|-------------|
| **`loggingLibrary:viewAllLogs`** | Parent — access the standalone "All Logs" viewer |
| └─ `loggingLibrary:downloadAllLogs` | Download log files from the standalone viewer |

These permissions control access to the centralized viewer at **Logging Library → All Logs** when the CP section is enabled. Admins always have access regardless of permission settings.

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

The library checks permissions at two levels:

1. **Navigation** — `getCpNavItem()` hides the nav item unless the user is admin or has `loggingLibrary:viewAllLogs`
2. **Controller** — `LogsController` checks `viewSystemLogsPermissions` before rendering and `downloadSystemLogsPermissions` before allowing file downloads

When the permissions array is empty (no permissions specified), any logged-in user can access that feature.
