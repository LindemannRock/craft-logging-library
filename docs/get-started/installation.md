# Installation & Setup

> [!NOTE]
> Logging Library is in active development and not yet available on the Craft Plugin Store. Install via Composer for now.

## Composer

Add the package to your project using Composer and the command line.

1. Open your terminal and go to your Craft project:

```bash
cd /path/to/project
```

2. Then tell Composer to require the plugin, and Craft to install it:

```bash title="Composer"
composer require lindemannrock/craft-logging-library && php craft plugin/install logging-library
```

```bash title="DDEV"
ddev composer require lindemannrock/craft-logging-library && ddev craft plugin/install logging-library
```

## Quick Start

See [Quickstart](quickstart.md) for the fastest path from install to first result.
