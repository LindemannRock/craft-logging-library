# Edge Detection

Logging Library can automatically detect edge/CDN hosting environments and disable the file-based log viewer where it may not work reliably.

## Why It Matters

Edge and CDN platforms use distributed, ephemeral storage. Local log files written on one node aren't accessible from another, and the file system may be restricted or unavailable. On these platforms:

- The built-in log viewer would show incomplete or empty results
- File I/O operations may be restricted
- The platform typically provides its own centralized log viewer with better filtering

## Supported Platforms

| Platform | Detection Method | Status |
|----------|-----------------|--------|
| [Servd.host](https://servd.host) | `SERVD_PROJECT_SLUG` environment variable | Verified |

Only verified platforms are included. Additional platforms will be added after real-world testing with Craft CMS deployments.

## How It Works

When `enableLogViewer` is not explicitly set in the `configure()` call, the library checks for known environment variables. If a match is found, `enableLogViewer` defaults to `false`.

When a file-based viewer is disabled, Logging Library hides the related CP navigation and controller actions return a 404 for direct viewer URLs. Logging still works normally — only the file-based web viewer is unavailable.

Logging itself still works normally — messages are routed through Craft's PSR-3 system and appear in the platform's native log dashboard.

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

You can also force-enable all file-based viewers globally from the Logging Library settings screen, or in `config/logging-library.php`:

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
$isCustomEdge = isset($_ENV['YOUR_PLATFORM_VAR']);

LoggingLibrary::configure([
    'pluginHandle' => $this->handle,
    'enableLogViewer' => !$isCustomEdge,
]);
```
