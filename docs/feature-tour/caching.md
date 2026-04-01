# Caching

Logging Library uses file-based caching to parse log files once and serve subsequent requests instantly. This is handled by the `LogCacheService`.

## How It Works

1. **First load** — the service reads and parses the entire log file line by line, building a structured array of entries
2. **Cache write** — the parsed array is JSON-encoded and written to a `.cache` file in `storage/runtime/logging-library/cache/logs/`
3. **Subsequent loads** — the service reads from the cache file instead of re-parsing the log
4. **Querying** — cached entries are loaded into an `ArrayQuery` instance, which provides SQL-like filtering (`WHERE`, `ORDER BY`, `LIMIT`, `OFFSET`)

## Cache Invalidation

The cache key is derived from `md5(filepath + filesize + mtime)`. This means:

- **Automatic invalidation** — when the log file grows (new entries written), the filesize changes and the old cache key no longer matches, triggering a re-parse
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

The caching layer handles large log files efficiently:

- **40,000+ entries** can be parsed, cached, and queried with no noticeable delay
- First-load parsing streams the file line by line to avoid loading the entire file into memory
- ArrayQuery filtering is performed in-memory on the cached data — no file I/O on subsequent page loads

## Cache Location

Cache files are stored at:

```
storage/runtime/logging-library/cache/logs/
```

Each cache file is named by its MD5 key with a `.cache` extension. You can safely delete these files — they will be regenerated on the next request.
