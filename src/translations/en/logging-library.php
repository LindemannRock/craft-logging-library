<?php
/**
 * Logging Library plugin for Craft CMS 5.x
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

return [
    // Plugin meta
    'Logging Library' => 'Logging Library',
    'Inspect system logs, review plugin logging output, and centralize diagnostics from one control panel workspace.' => 'Inspect system logs, review plugin logging output, and centralize diagnostics from one control panel workspace.',
    'Open All Logs' => 'Open All Logs',

    // Navigation
    'All Logs' => 'All Logs',
    'Logs' => 'Logs',
    'Settings' => 'Settings',
    'System Logs' => 'System Logs',
    'System' => 'System',
    'General' => 'General',
    'Interface' => 'Interface',

    // Log levels
    'All Levels' => 'All Levels',
    'Error' => 'Error',
    'Warning' => 'Warning',
    'Info' => 'Info',
    'Debug' => 'Debug',

    // Log sources
    'All Sources' => 'All Sources',
    'Web' => 'Web',
    'Console' => 'Console',
    'Queue' => 'Queue',
    'PHP Errors' => 'PHP Errors',
    'Other' => 'Other',

    // Filters
    'Select File' => 'Select File',
    'Select Date' => 'Select Date',
    'Search messages and context...' => 'Search messages and context...',

    // Table
    'Time' => 'Time',
    'Level' => 'Level',
    'Source' => 'Source',
    'User' => 'User',
    'Message' => 'Message',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'No log files found. Log files are created when plugin activities occur.',
    'No log entries found for the selected filters.' => 'No log entries found for the selected filters.',

    // Pagination
    'entry' => 'entry',
    'entries' => 'entries',

    // Row detail
    'Context' => 'Context',
    'No context data available.' => 'No context data available.',

    // Sidebar
    'Current Level' => 'Current Level',
    'Current log level' => 'Current log level',
    'Retention' => 'Retention',
    'days' => 'days',
    'Available Logs' => 'Available Logs',
    'file' => 'file',
    'files' => 'files',
    'Current File' => 'Current File',
    'Entries' => 'Entries',
    'Download File' => 'Download File',
    'Log Location' => 'Log Location',

    // Common
    'Save Settings' => 'Save Settings',

    // Controller messages
    'Settings saved.' => 'Settings saved.',
    'Could not save settings.' => 'Could not save settings.',

    // Validation messages
    'Value must be a whole number.' => 'Value must be a whole number.',

    // Settings: General
    'General Settings' => 'General Settings',
    'Plugin Name' => 'Plugin Name',
    'The name of the plugin as it appears in the Control Panel menu' => 'The name of the plugin as it appears in the Control Panel menu',
    'This is being overridden by the <code>pluginName</code> setting in <code>config/logging-library.php</code>.' => 'This is being overridden by the <code>pluginName</code> setting in <code>config/logging-library.php</code>.',
    'Force Enable Log Viewers' => 'Force Enable Log Viewers',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.',
    'Show Main Menu' => 'Show Main Menu',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.',

    // Settings: Interface
    'Interface Settings' => 'Interface Settings',
    'Items Per Page' => 'Items Per Page',
    'Number of log entries to display per page in the log viewers' => 'Number of log entries to display per page in the log viewers',
    'This is being overridden by the <code>itemsPerPage</code> setting in <code>config/logging-library.php</code>.' => 'This is being overridden by the <code>itemsPerPage</code> setting in <code>config/logging-library.php</code>.',
];
