# Runtime Logs

See what your site just logged — even on hosting where log files don't stick around. Runtime Logs is a bounded view of recent log activity: Logging Library captures log messages as Craft dispatches them, keeps a rolling window in Redis or Craft's configured cache, and shows it in the Control Panel at **Logging Library → Runtime Logs**.

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

That's all it takes. On the next application start, Logging Library registers a log target that captures matching messages, and **Runtime Logs** appears in the Logging Library section of the Control Panel. Viewing requires the `loggingLibrary:viewAllLogs` permission — see [Permissions](../developers/permissions.md).

> [!IMPORTANT]
> Restart long-running queue workers after changing `runtimeLogStore` configuration. A target keeps the configuration snapshot from the application startup that created it.

> [!NOTE]
> Enabling the runtime store also makes the **Logging Library** CP section available on edge platforms where the file-based viewers are hidden — Runtime Logs works there precisely because it doesn't need log files.

## Browse runtime logs

Go to **Logging Library → Runtime Logs**. The view works like the [All Logs viewer](standalone-viewer.md):

- **Filter** by level (only levels you've enabled for capture are offered) and by source — entries are grouped under plugin display names, with Craft/framework categories listed individually
- **Search** across message, context, source, and user
- **Sort** any column: Timestamp, Level, Source, Request User, or Message
- **Expand a row** to read the full message and its context (a trace excerpt and memory usage, when Craft provides them)

The page **auto-refreshes** every few seconds (5 by default) and pauses while you have a row expanded, so entries don't shift under you mid-read. The sidebar shows the live state: the configured capture level, how many entries are held versus the configured maximum, and two storage details:

- **Runtime Store** shows the effective backend and state: **Redis database N**, **Redis (SELECT disabled)**, **Redis unavailable**, or **Craft cache**.
- **Runtime Location** keeps the primary display concise. Redis shows **Dedicated Redis key**; hover that value to inspect the exact application-namespaced Redis key in a technical tooltip. A generic non-Redis cache shows `craft.app.cache`.

The store text, location text, and Redis key tooltip all refresh with the table, so a Redis failure or recovery isn't hidden behind the status from the initial page load.

**Clear Runtime Logs** in the sidebar empties the store after a confirmation. The button only appears with the `loggingLibrary:clearCache` permission. Redis clearing issues an exact delete for Logging Library's application-namespaced Runtime Logs key; it never flushes a Redis database or scans/deletes wildcard keys. Log files, unrelated Redis data, and the [file viewer cache](caching.md) are untouched.

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
    'redis' => [
        // Omit to inherit Craft cache's Redis database.
        // Use null to disable SELECT for compatible cluster-style endpoints.
        // 'database' => '$LOGGING_LIBRARY_RUNTIME_REDIS_DB',
    ],
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
| `redis.database` | Optional Runtime Logs Redis database. Omit it to inherit Craft's Redis database, use a non-negative integer, use an environment reference such as `'$LOGGING_LIBRARY_RUNTIME_REDIS_DB'`, or set it explicitly to `null` to avoid `SELECT`. | Omitted |
| `privacy.includeUserId` | Record which logged-in user triggered each entry. Off by default so no user IDs are written to cache; when off, the Request User column shows **System**. | `false` |

Bounded capture options such as entry and payload limits are clamped. Redis database values use the stricter fail-closed rules below.

## Storage backends

Runtime Logs chooses its backend from Craft's configured cache:

- **Redis list backend:** when Craft cache is `yii\redis\Cache`, Redis is the sole Runtime Logs backend. Logging Library builds a separate Yii Redis connection with the same host or Unix socket, authentication, TLS context, timeouts, safe socket flags, retry interval, and command configuration. Two settings are deliberately not inherited: persistent transport identity is removed so Runtime Logs owns its socket independently, and Yii per-command retries are set to zero so an individual `LPUSH`, `LTRIM`, or `EXPIRE` can never be replayed outside a lost transaction. Each record is a separate list item, and each accepted batch uses one atomic `MULTI` → `LPUSH` → `LTRIM` → `EXPIRE` → `EXEC` transaction. Reads use `LRANGE`.
- **Generic Craft-cache backend:** only a genuinely non-Redis Craft cache uses the bounded whole-buffer value. Appends use a fail-fast mutex and never wait for a lock. This compatibility backend is suitable for best-effort diagnostics, but concurrent batches can be dropped under contention and non-shared caches remain instance-local.

An invalid Redis configuration, rejected `SELECT`, connection failure, or command failure marks Runtime Logs storage unavailable and drops the affected batch without affecting the request or job. It never switches to Craft's generic cache, writes the generic v1 Runtime key through Redis database `0`, merges two stores, or reads an older generic key as a fallback. A later operation may reconnect and retry the same authoritative Redis backend, but it never automatically replays an ambiguous transaction. On the non-Redis generic backend, a read failure aborts its append and a failed replacement write leaves the previous buffer intact.

### Choosing a Redis database

Use a literal database number when you have assigned one explicitly:

```php
'runtimeLogStore' => [
    'enabled' => true,
    'redis' => [
        'database' => 4,
    ],
],
```

Or resolve it through Craft's environment parser:

```php
'runtimeLogStore' => [
    'enabled' => true,
    'redis' => [
        'database' => '$LOGGING_LIBRARY_RUNTIME_REDIS_DB',
    ],
],
```

The resolution rules are deliberately strict:

- Omit `database` to inherit Craft cache's Redis database.
- Use a literal non-negative integer to select that database. Numeric strings are not accepted as literals.
- An environment reference must resolve to a non-negative integer. Missing, empty, non-integer, and negative values fail closed instead of silently selecting database `0`.
- Set `database => null` explicitly when a compatible cluster-style endpoint must not receive `SELECT`.
- If Redis rejects `SELECT`, Runtime Logs marks its Redis backend unavailable and does not continue in a different database or through Craft cache. The affected batch is dropped.
- Craft cache may use a persistent Redis connection, but Runtime Logs deliberately removes the persistent socket flag from its independently owned connection. This allows database selection without sharing, changing, or closing Craft cache's transport.

Logical Redis databases separate key namespaces and administrative operations. They do not isolate CPU, memory, network traffic, eviction policy, or contention on the Redis server. Logging Library never chooses “Craft database + 1” automatically because that database may already belong to another application service.

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

Restart every long-running queue worker after changing either safeguard or any other Runtime Logs option. Existing targets retain their startup configuration until their application process restarts.

### Checking availability in code

```php
use lindemannrock\logginglibrary\LoggingLibrary;

// Is the runtime store on?
LoggingLibrary::isRuntimeLogStoreEnabled(): bool;

// The normalized runtime config (defaults merged, limits applied)
LoggingLibrary::getRuntimeLogStoreConfig(): array;
```

## Limitations

- **It's a diagnostic window, not log history.** Entries expire with the TTL, roll off past `maxEntries`, and can disappear when Redis or Craft cache is cleared or evicts them. For full history, use log files or your hosting platform's log feed.
- **Multi-instance hosting needs a shared cache.** Each instance writes to its own cache unless your cache backend is shared (for example Redis). On load-balanced setups without a shared cache, you'll only see entries captured by the instance serving your CP request.
- **Capture is best-effort by design.** Runtime logging never breaks a request — if a Redis/cache operation fails or the non-Redis generic backend cannot acquire its mutex immediately, the affected batch is silently skipped.
- **It doesn't read hosted log feeds.** Like the file-based viewers, Runtime Logs shows only what this Craft install captured — it doesn't query Servd, Papertrail, Datadog, or any external logging service.
