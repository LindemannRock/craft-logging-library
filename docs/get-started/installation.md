# Installation & setup

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

## Post-install setup

Logging Library itself needs no additional Control Panel setup. Continue to the quickstart to connect it to a plugin and confirm your first dedicated log entry.

## Quick start

See [Quickstart](quickstart.md) for the fastest path from install to first result.
