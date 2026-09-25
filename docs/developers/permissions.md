# Permissions

Give a support user access to recent runtime activity without exposing every log file, or let an administrator manage capture settings without reading logs. Logging Library separates file logs, runtime logs, and settings permissions.

Assign them under **Settings → Users → (group/user) → Permissions → Logging Library**. Administrators have full permission access; disabled viewers and hosting restrictions still apply.

## Choose the access needed

| CP permission | Handle | What it allows |
|---------------|--------|----------------|
| **View all file logs** | `loggingLibrary:viewAllLogs` | Open the standalone **All Logs** viewer |
| └─ Download all file logs | `loggingLibrary:downloadAllLogs` | Download raw files; also requires file viewing |
| └─ Clear file log cache | `loggingLibrary:clearCache` | Refresh a file's parsed cache and expose the Logging Library option in **Utilities → Clear Caches**; also requires file viewing |
| **View runtime logs** | `loggingLibrary:viewRuntimeLogs` | Open **Runtime Logs** and receive automatic updates |
| └─ Clear runtime logs | `loggingLibrary:clearRuntimeLogs` | Empty the runtime diagnostic window; also requires runtime viewing |
| **Manage settings** | `loggingLibrary:manageSettings` | Manage General, File Logs, Runtime Logs, and Interface settings |

For read-only runtime access, grant **View runtime logs** only. Add **Clear runtime logs** only if that role should discard the shared diagnostic window. Neither grants access to files or settings.

For read-only file access, grant **View all file logs** only. Downloads and manual cache refresh are separate choices. Refreshing the file cache does not delete log files or clear runtime entries. Automatic rebuilding during ordinary viewing is unchanged.

Craft's nested checkboxes do not automatically grant child permissions. The controller requires viewing as well as the download or clearing permission; hiding a button is not the security boundary. Craft separately governs access to its Clear Caches utility.

## Upgrading to 5.19.0

Run Craft's normal plugin migrations when deploying the update. Existing file-view and cache-clear handles are retained. The migration adds corresponding runtime grants:

- Existing **View all system logs** grants gain **View runtime logs**.
- Existing **Clear cache** grants gain **Clear runtime logs**. Clearing still requires runtime viewing, so a clear-only grant does not give someone access to logs.
- Direct user grants remain direct; group grants stay on the same groups. Group changes use Craft's project config, so deploy the resulting group configuration through your usual project-config workflow.

This preserves runtime access when viewing and clearing came from different groups or a mix of direct and group grants. It does not copy group permissions onto individual users. New installations and roles receive only permissions you assign. Log entries and plugin settings are untouched.

The migration is not automatically reversible: revoking grants later could remove permissions an administrator deliberately assigned after upgrading. Review permissions manually if downgrading.

## Navigation and direct routes

**All Logs** requires file viewing; **Runtime Logs** requires runtime viewing and enabled capture; **Settings** requires settings management. An inaccessible initial viewer page may redirect to another available section. Runtime data and clearing endpoints enforce their permissions independently.

**Show Main Menu** controls navigation visibility, not access rights. Hiding it does not revoke direct-route access or disable capture. Edge detection can suppress file viewers without affecting runtime access — see [Edge Detection](../feature-tour/edge-detection.md).

## Per-plugin viewers

An integrating plugin still owns its log-viewing and download permissions, passed to `LoggingLibrary::configure()` as `viewSystemLogsPermissions` and `downloadSystemLogsPermissions`. This split does not change those contracts: plugin-specific cache refresh continues to require that plugin's viewing permission, not Logging Library's centralized file-cache permission.

A typical integration registers `yourPlugin:viewLogs` with a nested `yourPlugin:downloadLogs`. Configured arrays use **any-of** checks. An empty array imposes no extra permission requirement for that operation on an authenticated user, so configure them deliberately. See the [integration guide](../feature-tour/integration-guide.md).

## Checking access in code

```twig
{% if currentUser.can('loggingLibrary:viewRuntimeLogs') %}
    {# Offer a Runtime Logs link when capture is enabled. #}
{% endif %}
```

```php
// A mutating action requires both permissions, not either one.
$this->requirePermission('loggingLibrary:viewRuntimeLogs');
$this->requirePermission('loggingLibrary:clearRuntimeLogs');
```
