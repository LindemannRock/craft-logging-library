<?php
/**
 * Logging Library config.php
 *
 * This file exists only as a template for Logging Library settings.
 * It does nothing on its own.
 *
 * Don't edit this file, instead copy it to 'craft/config' as 'logging-library.php'
 * and make your changes there to override default settings.
 *
 * @since 5.8.0
 */

return [
    '*' => [
        // Plugin display name shown in the control panel
        'pluginName' => 'Logging Library',

        // Number of log entries shown per page in the full log viewer (10–500)
        'itemsPerPage' => 50,

        // Show this plugin in the main control panel navigation
        'showCpSection' => true,

        // Force-enable file-based log viewers even when an edge/ephemeral environment is detected
        'forceEnableLogViewer' => false,

        // ========================================
        // BASE PLUGIN OVERRIDES (optional)
        // ========================================
        // These settings override lindemannrock-base defaults for this plugin only.
        // Resolution chain (high → low priority):
        //   1. Plugin config file (this file)              — env overrides
        //   2. Plugin CP setting                           — user-set in the control panel
        //   3. Base config file (config/lindemannrock-base.php) — global default
        //   4. Hardcoded defaults                          — final fallback
        // Leave commented out to fall through to layers 2-4.

        // Date/time formatting overrides
        // Only timeFormat and showSeconds affect Logging Library's UI (the log viewer's
        // timestamp column). The other DateFormatSettingsTrait properties exist on the
        // model but are not surfaced in this plugin's CP or persisted to DB.
        // 'timeFormat'  => '24',      // '12' (AM/PM) or '24' (military)
        // 'showSeconds' => false,
    ],
];
