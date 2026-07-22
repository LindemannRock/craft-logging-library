# Runtime Logs

See what your site just logged — even on hosting where log files don't stick around. Runtime Logs is a cache-backed view of recent log activity: Logging Library captures log messages as Craft dispatches them, keeps a bounded rolling window in Craft's cache, and shows it in the Control Panel at **Logging Library → Runtime Logs**.

Where the [file-based viewers](standalone-viewer.md) read what's on disk, Runtime Logs works without any log files at all — which is exactly what you need on [edge/ephemeral platforms](edge-detection.md) like Servd, where `storage/logs/` doesn't survive a redeploy.

## What you'll use it for

- **Diagnosing on ephemeral hosting** — see recent errors and warnings on Servd or similar platforms where the file-based viewers are empty or disabled
- **Watching a deploy or a fix land** — the view auto-refreshes, so you can trigger the failing action and watch the entries arrive
- **Quick triage without SSH** — check what just went wrong from the Control Panel before reaching for hosting dashboards or terminal access
- **Short-lived diagnostics** — a rolling window of the latest activity, deliberately separate from your full log history

## Turn it on

Runtime Logs is **off by default** and configured entirely from `config/logging-library.php` — there's no Control Panel toggle. Create the file (copy the sample from the plugin's `src/config.php`) and enable the store:

```php
<?php
return [
    '*' => [
        'runtimeLogStore' => [
            'enabled' => true,
            'skipConsoleRequests' => true,
            'skipQueueRequests' => true,
        ],
    ],
];
```

That's all it takes. On the next request, Logging Library registers a log target that captures matching messages, and **Runtime Logs** appears in the Logging Library section of the Control Panel. Viewing requires the `loggingLibrary:viewAllLogs` permission — see [Permissions](../developers/permissions.md).

> [!NOTE]
> Enabling the runtime store also makes the **Logging Library** CP section available on edge platforms where the file-based viewers are hidden — Runtime Logs works there precisely because it doesn't need log files.

## Browse runtime logs

Go to **Logging Library → Runtime Logs**. The view works like the [All Logs viewer](standalone-viewer.md):

- **Filter** by level (only levels you've enabled for capture are offered) and by source — entries are grouped under plugin display names, with Craft/framework categories listed individually
- **Search** across message, context, source, and user
- **Sort** any column: Timestamp, Level, Source, Request User, or Message
- **Expand a row** to read the full message and its context (a trace excerpt and memory usage, when Craft provides them)

The page **auto-refreshes** every few seconds (5 by default) and pauses while you have a row expanded, so entries don't shift under you mid-read. The sidebar shows the live state: the configured capture level, where the store lives (**Craft cache**, or **Redis** when that's your cache backend), and how many entries are held versus the configured maximum.

**Clear Runtime Logs** in the sidebar empties the store after a confirmation. The button only appears with the `loggingLibrary:clearCache` permission, and it clears only runtime entries — log files and the [file viewer cache](caching.md) are untouched.

Entries per page follows the standalone viewer's **Items Per Page** setting — see [Settings](settings.md).

## Configuration reference

All options live under the `runtimeLogStore` key in `config/logging-library.php`:

```php
'runtimeLogStore' => [
    'enabled' => false,
    'skipConsoleRequests' => true,
    'skipQueueRequests' => true,
    'ttl' => 86400,
    'maxEntries' => 1000,
    'refreshInterval' => 5,
    'maxMessageBytes' => 8000,
    'maxContextBytes' => 8000,
    'levels' => ['error', 'warning', 'info'],
    'categories' => [],
    'except' => [],
    'privacy' => [
        'includeUserId' => false,
    ],
],
```

| Option | What it does | Default |
|--------|--------------|---------|
| `enabled` | Turns the runtime store (and the **Runtime Logs** CP view) on. | `false` |
| `skipConsoleRequests` | Excludes runtime capture for console requests. This affects only Runtime Logs. | `true` |
| `skipQueueRequests` | Excludes runtime capture when Craft queue execution is detected. Queue exclusion applies to the current buffered export batch as a whole. | `true` |
| `ttl` | How long entries live, in seconds. Older entries are dropped from the view and the cache entry expires. | `86400` (24 hours) |
| `maxEntries` | Rolling window size — the newest N entries are kept (capped at 10,000). | `1000` |
| `refreshInterval` | Seconds between CP auto-refreshes. Set `0` to disable auto-refresh. | `5` |
| `maxMessageBytes` | Longer messages are truncated with `...` (capped at 65,536 bytes). | `8000` |
| `maxContextBytes` | Same truncation for the context payload. | `8000` |
| `levels` | Which levels to capture: any of `error`, `warning`, `info`, `trace` (`debug` is accepted as an alias for `trace`). The CP's Debug filter only appears when Craft's `devMode` is on. | `['error', 'warning', 'info']` |
| `categories` | Capture only these log categories (Yii wildcard patterns, e.g. `my-plugin*`). Empty means all. | `[]` |
| `except` | Never capture these categories. Translation-lookup noise (`yii\i18n\PhpMessageSource:*`) is always excluded. | `[]` |
| `privacy.includeUserId` | Record which logged-in user triggered each entry. Off by default so no user IDs are written to cache; when off, the Request User column shows **System**. | `false` |

Out-of-range values are clamped to their limits rather than rejected, so a typo can't break log capture.

## Console and queue safeguards

`skipConsoleRequests` and `skipQueueRequests` default to `true`. These are conservative safeguards that keep Runtime Logs cache work out of commands and queue jobs. Craft queue workers normally run as console requests, so capturing their runtime logs generally requires setting **both** options to `false`:

```php
'runtimeLogStore' => [
    'enabled' => true,
    'skipConsoleRequests' => false,
    'skipQueueRequests' => false,
],
```

Queue detection is applied to the current buffered runtime export batch. If one message signals queue execution, that whole batch is skipped, so nearby non-queue messages may also be absent from Runtime Logs. This is an intentional best-effort tradeoff: Runtime Logs avoids cache writes during detected queue execution rather than filtering and writing individual messages from the same batch.

These exclusions affect only Runtime Logs. Craft's file logs and hosted log feeds such as Servd's remain unchanged. For temporary diagnosis, disable the relevant exclusions, reproduce the issue, and then restore the defaults. Capturing command or queue traffic—especially debug-level output—can fill the bounded runtime buffer quickly and add cache traffic.

### Checking availability in code

```php
use lindemannrock\logginglibrary\LoggingLibrary;

// Is the runtime store on?
LoggingLibrary::isRuntimeLogStoreEnabled(): bool;

// The normalized runtime config (defaults merged, limits applied)
LoggingLibrary::getRuntimeLogStoreConfig(): array;
```

## Limitations

- **It's a diagnostic window, not log history.** Entries expire with the TTL, roll off past `maxEntries`, and disappear when Craft's cache is cleared. For full history, use log files or your hosting platform's log feed.
- **Multi-instance hosting needs a shared cache.** Each instance writes to its own cache unless your cache backend is shared (for example Redis). On load-balanced setups without a shared cache, you'll only see entries captured by the instance serving your CP request.
- **Capture is best-effort by design.** Runtime logging never breaks a request — if the cache write fails, the entry is silently skipped.
- **It doesn't read hosted log feeds.** Like the file-based viewers, Runtime Logs shows only what this Craft install captured — it doesn't query Servd, Papertrail, Datadog, or any external logging service.
