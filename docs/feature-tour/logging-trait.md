# LoggingTrait

The `LoggingTrait` is a drop-in trait that adds structured logging methods to any class. It routes messages through Craft's PSR-3 logging system to your plugin's dedicated log file.

## How It Works

Add `use LoggingTrait` to any class — plugin main class, service, controller, or any component. The trait provides four protected methods that map to Craft's native logging:

| Method | Craft Method | When to Use |
|--------|-------------|-------------|
| `logInfo()` | `Craft::info()` | Normal operations, user actions, completions |
| `logWarning()` | `Craft::warning()` | Unexpected but handled situations |
| `logError()` | `Craft::error()` | Failures that prevent an operation |
| `logDebug()` | `Craft::debug()` | Internal state, variable dumps (requires `devMode`) |

## Method Signatures

All four methods share the same signature:

```php
protected function logInfo(string $message, array $params = []): void
protected function logWarning(string $message, array $params = []): void
protected function logError(string $message, array $params = []): void
protected function logDebug(string $message, array $params = []): void
```

## Usage in Plugin Classes

In a plugin's main class, the trait auto-detects `$this->handle` for routing:

```php
use lindemannrock\logginglibrary\traits\LoggingTrait;

class YourPlugin extends Plugin
{
    use LoggingTrait;

    public function someAction(): void
    {
        $this->logInfo('Action completed', ['items' => 42]);
    }
}
```

## Usage in Services and Controllers

For services, controllers, and other components, set the handle manually in `init()`:

```php
use craft\base\Component;
use lindemannrock\logginglibrary\traits\LoggingTrait;

class YourService extends Component
{
    use LoggingTrait;

    public function init(): void
    {
        parent::init();
        $this->setLoggingHandle('your-plugin');
    }

    public function processData(): void
    {
        $this->logInfo('Processing started');
        // ... your logic
        $this->logInfo('Processing completed', ['count' => $count]);
    }
}
```

## Handle Detection

The trait determines the plugin handle in this order:

1. Manually set via `$this->setLoggingHandle('your-plugin')`
2. Auto-detected from `$this->handle` (available in Plugin classes)
3. Fallback: derived from the class name in kebab-case

## Message Formatting

When you pass a `$params` array, the trait appends it as JSON:

```php
$this->logInfo('User exported data', ['userId' => 1, 'format' => 'csv']);
// Log: "User exported data | {"userId":1,"format":"csv"}"

$this->logError('Connection failed');
// Log: "Connection failed"
```

> [!TIP]
> Always use the `$params` array for variable data instead of string concatenation. Structured context is easier to search, filter, and parse with external log tools.

## Limitations

- Methods are **protected** — only available within the class that uses the trait, not from outside
- `logDebug()` only writes when Craft's `devMode` is enabled — in production, debug messages are silently ignored
- The trait does not create the Monolog target — you must also call `LoggingLibrary::configure()` in your plugin's `init()` for messages to reach the dedicated log file
