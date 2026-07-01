![Logging Library](docs/images/hero.webp)

# Logging Library for Craft CMS

[![Latest Version](https://img.shields.io/packagist/v/lindemannrock/craft-logging-library.svg)](https://packagist.org/packages/lindemannrock/craft-logging-library)
[![Craft CMS](https://img.shields.io/badge/Craft%20CMS-5.10%2B-orange.svg)](https://craftcms.com/)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net/)
[![License](https://img.shields.io/packagist/l/lindemannrock/craft-logging-library.svg)](LICENSE)

A reusable logging library for Craft CMS plugins that provides consistent logging, dedicated log files, and a built-in log viewer interface.

## Features

- **Dedicated Log Files** — each plugin gets its own daily log files in `storage/logs/`
- **Built-in Log Viewer** — web interface for viewing, filtering, searching, and downloading logs
- **Standalone System Log Viewer** — browse all Craft, plugin, and PHP logs from one interface
- **Control Panel Section** — access the standalone viewer from **Logging Library → All Logs** when the CP section is enabled
- **Control Panel Settings** — display name, menu visibility, entries-per-page, and timestamp format, all overridable from `config/logging-library.php`
- **LoggingTrait** — drop-in trait with `logInfo()`, `logWarning()`, `logError()`, `logDebug()`
- **LoggingService API** — direct logging, log statistics, recent entries, and cleanup
- **High Performance Caching** — indexed file-based cache for large log viewer pages, with ArrayQuery compatibility for API callers
- **Multi-Format Parsing** — automatically detects plugin, Craft CMS, and PHP error log formats
- **Edge Detection** — auto-disables the file-based log viewer on edge/CDN platforms like Servd
- **Monolog Integration** — uses Craft 5's Monolog system with proper PSR-3 standards
- **Configurable** — customizable log levels, retention, permissions, and sidebar menus

On platforms like Servd, Logging Library does not import the host's centralized log feed into Craft. The CP viewer reads local `storage/logs/` files only; Servd-collected logs remain available in the Servd dashboard and any connected external logging service.

## Requirements

- Craft CMS 5.10+
- PHP 8.2+

## Installation

### Composer

```bash
composer require lindemannrock/craft-logging-library && php craft plugin/install logging-library
```

### DDEV

```bash
ddev composer require lindemannrock/craft-logging-library && ddev craft plugin/install logging-library
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
