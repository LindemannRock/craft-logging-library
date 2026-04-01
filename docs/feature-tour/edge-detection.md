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

Logging itself still works normally — messages are routed through Craft's PSR-3 system and appear in the platform's native log dashboard.

## Manual Override

You can always override the auto-detection:

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

## Custom Platform Detection

For platforms not yet supported, add your own detection:

```php
$isCustomEdge = isset($_ENV['YOUR_PLATFORM_VAR']);

LoggingLibrary::configure([
    'pluginHandle' => $this->handle,
    'enableLogViewer' => !$isCustomEdge,
]);
```
