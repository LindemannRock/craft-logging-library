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

        // Number of log entries shown per page in the full log viewer
        'itemsPerPage' => 50,

        // Show Logging Library in the main control panel navigation
        'showCpSection' => true,

        // Force-enable file-based log viewers even when an edge/ephemeral environment is detected
        'forceEnableLogViewer' => false,
    ],
];
