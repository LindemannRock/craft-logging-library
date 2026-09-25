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

The installation welcome button opens **Logging Library → Settings**, starting on **General**. Use **Runtime Logs** for capture and configured storage, or **File Logs** for file-viewer availability. No mandatory setup step is required: runtime capture stays off unless you enable it under **Settings → Runtime Logs** or in configuration. Existing configuration overrides remain authoritative. Continue to the quickstart to connect a plugin and confirm your first log entry.

## Quick start

See [Quickstart](quickstart.md) for the fastest path from install to first result.
