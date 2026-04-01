# Integration Guide

Complete walkthrough for integrating Logging Library into a Craft CMS plugin — from initial setup through navigation, routes, and permissions.

## Step 1: Add Trait and Configure

In your plugin's main class:

```php
use craft\base\Plugin;
use lindemannrock\logginglibrary\traits\LoggingTrait;
use lindemannrock\logginglibrary\LoggingLibrary;

class YourPlugin extends Plugin
{
    use LoggingTrait;

    public function init(): void
    {
        parent::init();

        $settings = $this->getSettings();
        LoggingLibrary::configure([
            'pluginHandle' => $this->handle,
            'pluginName' => $settings->pluginName ?? $this->name,
            'logLevel' => 'info',
            'enableLogViewer' => true,
            'itemsPerPage' => $settings->itemsPerPage ?? 50,
            'viewSystemLogsPermissions' => ['yourPlugin:viewLogs'],
            'downloadSystemLogsPermissions' => ['yourPlugin:downloadLogs'],
        ]);
    }
}
```

> [!NOTE]
> When `enableLogViewer` is `true`, `configure()` automatically registers the CP routes for your plugin's log viewer (`your-plugin/logs`, `your-plugin/logs/system`, and `your-plugin/logs/system/download`). You do not need to register these routes manually.

> [!WARNING]
> Do not log messages inside `init()` — it runs on every request and will flood your log files.

## Step 2: Add Logs to CP Navigation

Use `LoggingLibrary::addLogsNav()` to add a "Logs" item to your plugin's sidebar:

```php
use lindemannrock\base\helpers\PluginHelper;

public function getCpNavItem(): ?array
{
    $item = parent::getCpNavItem();

    if (PluginHelper::isPluginEnabled('logging-library')) {
        $item = LoggingLibrary::addLogsNav($item, $this->handle, [
            'yourPlugin:viewLogs',
        ]);
    }

    return $item;
}
```

The third parameter is an array of permissions — the user needs any one of them to see the Logs nav item.

## Step 3: Register Permissions

Register view and download permissions for your plugin's logs:

```php
use craft\events\RegisterUserPermissionsEvent;
use craft\services\UserPermissions;

// Inside init()
Event::on(
    UserPermissions::class,
    UserPermissions::EVENT_REGISTER_PERMISSIONS,
    function(RegisterUserPermissionsEvent $event) {
        $event->permissions[] = [
            'heading' => $this->name,
            'permissions' => [
                'yourPlugin:viewLogs' => [
                    'label' => 'View logs',
                    'nested' => [
                        'yourPlugin:downloadLogs' => [
                            'label' => 'Download logs',
                        ],
                    ],
                ],
            ],
        ];
    }
);
```

## Step 4: Log Messages

In your services and controllers:

```php
// Using the trait (requires setLoggingHandle in services)
$this->logInfo('Export completed', ['count' => 42, 'format' => 'csv']);
$this->logWarning('Deprecated method called', ['method' => 'oldFunction']);
$this->logError('Database query failed', ['error' => $e->getMessage()]);
$this->logDebug('Processing step 3', ['data' => $result]);
```

Or using the static service directly:

```php
use lindemannrock\logginglibrary\services\LoggingService;

LoggingService::log('Custom message', 'info', 'your-plugin', ['key' => 'value']);
```

## Using PluginHelper::bootstrap()

If your plugin uses the base plugin's `PluginHelper::bootstrap()`, you can pass log-related options directly:

```php
PluginHelper::bootstrap($this, 'myHelper', ['myPlugin:viewLogs'], ['myPlugin:downloadLogs'], [
    'logMenu' => [
        'label' => 'Logs',
        'items' => [
            'system' => ['label' => 'System', 'url' => 'my-plugin/logs/system'],
            'activity' => ['label' => 'Activity', 'url' => 'my-plugin/logs/activity'],
        ],
    ],
]);
```

This is an alternative to calling `LoggingLibrary::addLogsNav()` manually — the base plugin handles the nav integration.

## Complete Example

See the full working example in the [Quickstart](../get-started/quickstart.md) or the README for a complete plugin class with all integration steps.
