# Logging Library for Craft CMS

[![Latest Version](https://img.shields.io/packagist/v/lindemannrock/craft-logging-library.svg)](https://packagist.org/packages/lindemannrock/craft-logging-library)
[![Craft CMS](https://img.shields.io/badge/Craft%20CMS-5.0%2B-orange.svg)](https://craftcms.com/)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net/)
[![License](https://img.shields.io/packagist/l/lindemannrock/craft-logging-library.svg)](LICENSE)

A reusable logging library for Craft CMS plugins that provides consistent logging, dedicated log files, and a built-in log viewer interface.

## Beta Notice

This plugin is currently in active development and provided under the MIT License for testing purposes.

**Licensing is subject to change.** We are finalizing our licensing structure and some or all features may require a paid license when officially released on the Craft Plugin Store.

## Features

- **Dedicated Log Files** — each plugin gets its own daily log files in `storage/logs/`
- **Built-in Log Viewer** — web interface for viewing, filtering, searching, and downloading logs
- **Standalone System Log Viewer** — browse all Craft, plugin, and PHP logs from one interface
- **Control Panel Section** — access the standalone viewer from **Logging Library → All Logs** when the CP section is enabled
- **LoggingTrait** — drop-in trait with `logInfo()`, `logWarning()`, `logError()`, `logDebug()`
- **LoggingService API** — direct logging, log statistics, recent entries, and cleanup
- **High Performance Caching** — handle 40,000+ entries instantly with ArrayQuery and file-based caching
- **Multi-Format Parsing** — automatically detects plugin, Craft CMS, and PHP error log formats
- **Edge Detection** — auto-disables log viewer on edge/CDN platforms like Servd
- **Monolog Integration** — uses Craft 5's Monolog system with proper PSR-3 standards
- **Configurable** — customizable log levels, retention, permissions, and sidebar menus

## Requirements

- Craft CMS 5.0+
- PHP 8.2+

## Installation

### Via Composer

```bash
composer require lindemannrock/craft-logging-library
```

```bash
php craft plugin/install logging-library
```

### Using DDEV

```bash
ddev composer require lindemannrock/craft-logging-library
```

```bash
ddev craft plugin/install logging-library
```

## Documentation

Full documentation is available in the [docs](docs/) folder.

## Support

- **Issues**: [GitHub Issues](https://github.com/LindemannRock/craft-logging-library/issues)
- **Email**: [support@lindemannrock.com](mailto:support@lindemannrock.com)

## License

This plugin is licensed under the MIT License. See [LICENSE](LICENSE) for details.

---

Developed by [LindemannRock](https://lindemannrock.com)
