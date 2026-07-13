# Caching

Logging Library uses file-based caching to parse log files once and serve subsequent requests efficiently. This is handled by the `LogCacheService`.

This page covers the parsed-log-entry cache behind the file-based viewers. Two smaller caches exist alongside it: the log **file listing** is cached in Craft's cache for 5 seconds (so a file created a moment ago can take up to 5 seconds to appear in the file selector), and [Runtime Logs](runtime-logs.md) entries are stored in Craft's cache entirely — they never touch the caches described here.

## How It Works

1. **First CP load** — the service streams the selected log file line by line and writes parsed entries into an indexed SQLite cache
2. **Indexed queries** — the log viewer applies level/category/search filters, sorting, counts, and pagination in SQLite
3. **Subsequent CP loads** — the viewer reads only the requested page from the indexed cache instead of loading the full log into PHP memory
4. **ArrayQuery compatibility** — the public `getLogs()` API still provides the legacy JSON/ArrayQuery cache for callers that need the full parsed array

The indexed cache requires PHP's PDO SQLite driver. If PDO SQLite is unavailable, the viewer falls back to the legacy JSON/ArrayQuery cache so the interface still works, but large files will use more PHP memory.

## Cache Invalidation

The cache key is derived from `md5(parser version + filepath + filesize + mtime)`. This means:

- **Automatic invalidation on file changes** — when the log file grows (new entries written), the filesize changes and the old cache key no longer matches, triggering a re-parse
- **Automatic invalidation on plugin updates** — when an update improves the log parser, its internal version string changes, so every file is re-parsed with the current parser on next view — no manual cache clearing needed
- **Manual invalidation** — clear all caches via **Utilities → Clear Caches → Logging Library caches** in the Control Panel

## Cache Statistics

The `LogCacheService` provides a `getCacheStats()` method that returns:

| Key | Type | Description |
|-----|------|-------------|
| `totalFiles` | `int` | Number of cached files |
| `totalSize` | `int` | Total cache size in bytes |
| `formattedSize` | `string` | Human-readable size |
| `files` | `array` | Details per cache file (name, size, modified time) |

## Performance

The CP log viewer is optimized for large files:

- First-load parsing streams the file line by line to avoid loading the entire raw log into memory
- Filtering, sorting, category counts, and pagination run against the indexed cache
- Page requests load only the rows needed for the current page
- Entry counts use `getLogEntryCount()` @since(5.14.0), which counts in the indexed cache without materializing the parsed entries
- The legacy `getLogs()` ArrayQuery API loads the full parsed log and should not be used for very large files

## Cache Location

Cache files are stored at:

```
storage/runtime/logging-library/cache/logs/
```

Indexed CP-viewer cache files use the `.sqlite` extension. Legacy ArrayQuery cache files use the `.cache` extension. You can safely delete these files — they will be regenerated on the next request.
