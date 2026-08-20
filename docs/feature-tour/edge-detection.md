# Edge Detection

Logging Library automatically hides file-based log viewers when Craft reports ephemeral storage or Servd identifies the project. This keeps unavailable local files out of the Control Panel without changing log emission.

## Why It Matters

Edge and CDN platforms use distributed, ephemeral storage. Local log files written on one node aren't accessible from another, and the file system may be restricted or unavailable. On these platforms:

- The built-in log viewer would show incomplete or empty results
- File I/O operations may be restricted
- The platform typically provides its own centralized log viewer with better filtering

## Supported signals

| Host signal | Detection method | Behaviour |
|-------------|------------------|-----------|
| Craft ephemeral storage, including Craft Cloud | Craft's `App::isEphemeral()`, backed by `CRAFT_EPHEMERAL` | Hides file-based viewers when Craft normalizes the value to `true` |
| [Servd.host](https://servd.host) | Craft-normalized `SERVD_PROJECT_SLUG` resolving to a non-empty project slug | Preserves the existing Servd viewer suppression |

The two signals are combined with OR: either one is enough. Servd is detected when `SERVD_PROJECT_SLUG` resolves to a non-empty project slug. Missing, blank, whitespace-only, null, or normalized false values do not enable Servd detection; a genuine Servd project slug does. `CRAFT_EPHEMERAL=false` does not cancel a valid Servd slug. Craft owns the boolean normalization for `CRAFT_EPHEMERAL`; boolean/string `true` values enable that signal, while false, blank, absent, whitespace-only, and invalid values do not.

## How It Works

When `enableLogViewer` is not explicitly set in a plugin's `configure()` call, the detected-host result becomes its default. A match makes `enableLogViewer` default to `false`; an explicit per-plugin `true` or `false` still wins.

The global **Force Enable Log Viewers** setting restores automatic file-viewer availability for both signals. It remains an escape hatch, not a replacement for explicit per-plugin configuration: an explicit `enableLogViewer` value keeps precedence.

When file viewers are suppressed, Logging Library removes their navigation and does not register plugin-specific file-viewer routes through `configure()`. The standalone routes still exist so an authorized request to the Logging Library root can resolve safely: it redirects to the first accessible Runtime Logs or Settings route, or returns 404 when no destination is available. That redirect is not a visible main-navigation item. The main item is absent when **Show Main Menu** is off, and also when both file viewers and Runtime Logs are unavailable.

Logging itself keeps working normally. Craft/Yii logging, Craft's default targets, each configured plugin's dedicated Monolog target, target exclusions, stream logging, and hosted logging feeds are unchanged. This correction controls file-viewer presentation, not log emission or destination configuration.

[Runtime Logs](runtime-logs.md) are independent. They remain disabled unless `runtimeLogStore.enabled` is set in `config/logging-library.php`. When enabled, Runtime Logs can be the only visible log view on an ephemeral host because they do not read log files.

## Hosted log feeds

Logging Library does not query or import Craft Cloud, Servd, or third-party hosted log feeds. The built-in CP viewer only reads files that exist in the current Craft `storage/logs/` path. Servd also adds its own Craft log target and collects logs centrally for the **Logs** page in the Servd dashboard; the viewer-suppression decision does not remove or replace that target.

That distinction matters when you enable **Force Enable Log Viewers**. The override only re-enables local file reading. It does not connect to Servd, Papertrail, Datadog, or any other external log source. On Servd without persistent shared storage for `storage/logs/`, the file selector may be empty or show only partial logs from the current instance while the complete logs are still available in Servd.

For CP log visibility on these platforms, [Runtime Logs](runtime-logs.md) is usually the better answer than force-enabling file viewers. Enable it explicitly in configuration; Logging Library never enables it automatically just because the host is ephemeral.

## Manual Override

You can override auto-detection per plugin:

```php
// Force disable (any platform)
LoggingLibrary::configure([
    'pluginHandle' => $this->handle,
    'enableLogViewer' => false,
]);

// Force enable (override edge detection)
LoggingLibrary::configure([
    'pluginHandle' => $this->handle,
    'enableLogViewer' => true,
]);
```

You can also force-enable all file-based viewers globally from the [Logging Library settings screen](settings.md), or in `config/logging-library.php`:

```php
return [
    '*' => [
        'forceEnableLogViewer' => true,
    ],
];
```

Use the global override only when the environment has persistent storage available for `storage/logs/`.

## Custom Platform Detection

For platforms not yet supported, add your own detection:

```php
use craft\helpers\App;

$isCustomEdge = App::env('YOUR_PLATFORM_VAR') !== null;

LoggingLibrary::configure([
    'pluginHandle' => $this->handle,
    'enableLogViewer' => !$isCustomEdge,
]);
```

## Checking Availability in Code

Logging Library exposes static helpers so you can branch on the current environment without re-implementing the detection logic:

```php
use lindemannrock\logginglibrary\LoggingLibrary;

// Did edge/ephemeral detection match this environment?
LoggingLibrary::isEdgeEnvironmentDetected(): bool;

// Is the global "Force Enable Log Viewers" override on?
LoggingLibrary::isForceEnableLogViewer(): bool;

// Net result — should file-based viewers be shown at all?
// (true when not an edge environment, or when the override is on)
LoggingLibrary::areLogViewersAvailable(): bool;
```

For example, only surface a "View logs" link when a viewer will actually be available:

```php
if (LoggingLibrary::areLogViewersAvailable()) {
    // safe to link to the file-based viewer
}
```
