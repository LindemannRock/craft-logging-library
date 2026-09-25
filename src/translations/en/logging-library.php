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
    'Open Settings' => 'Open Settings',

    // Navigation
    'Setup' => 'Setup',
    'File Logs' => 'File Logs',
    'All Logs' => 'All Logs',
    'Runtime Logs' => 'Runtime Logs',
    'Logs' => 'Logs',
    'Settings' => 'Settings',
    'System Logs' => 'System Logs',
    'System' => 'System',
    'Plugins' => 'Plugins',
    'General' => 'General',
    'Interface' => 'Interface',

    // Permissions
    'View all system logs' => 'View all system logs',
    'Download all system logs' => 'Download all system logs',
    'Clear cache' => 'Clear cache',
    'Manage settings' => 'Manage settings',

    // Common
    '{displayName} caches' => '{displayName} caches',

    // Controller messages
    'Settings saved.' => 'Settings saved.',
    'Could not save settings.' => 'Could not save settings.',
    'Log cache refreshed.' => 'Log cache refreshed.',
    'Failed to refresh log cache.' => 'Failed to refresh log cache.',
    'Recent runtime logs cleared.' => 'Recent runtime logs cleared.',
    'Unable to clear recent runtime logs.' => 'Unable to clear recent runtime logs.',
    'Plugin logging not configured' => 'Plugin logging not configured',
    'Log viewer is disabled for this plugin' => 'Log viewer is disabled for this plugin',
    'Log viewer is disabled for this environment' => 'Log viewer is disabled for this environment',
    'Recent runtime logs are disabled' => 'Recent runtime logs are disabled',
    'Log file not found' => 'Log file not found',
    'Unable to determine plugin handle from URL' => 'Unable to determine plugin handle from URL',
    'User does not have permission to view logs' => 'User does not have permission to view logs',

    // Settings: General
    'Show Logging Library in the main navigation. This does not enable or disable log capture.' => 'Show Logging Library in the main navigation. This does not enable or disable log capture.',
    'General Settings' => 'General Settings',
    'Force Enable Log Viewers' => 'Force Enable Log Viewers',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.',
    'Show Main Menu' => 'Show Main Menu',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.',

    // Settings: File Logs
    'Force Enable File Log Viewers' => 'Force Enable File Log Viewers',
    'An edge or ephemeral environment is detected. File viewers are hidden unless forced on; Runtime Logs remain available when enabled. Only force file viewers on if persistent log files are available.' => 'An edge or ephemeral environment is detected. File viewers are hidden unless forced on; Runtime Logs remain available when enabled. Only force file viewers on if persistent log files are available.',
    'File viewers are available. Runtime Logs are optional and independent of file logging.' => 'File viewers are available. Runtime Logs are optional and independent of file logging.',

    // Settings: Runtime Logs
    'Min: {min}, Max: {max}' => 'Min: {min}, Max: {max}',
    'Uses the application cache configuration. The Redis database can be overridden in {file}. To verify capture, trigger a log message and check Runtime Logs.' => 'Uses the application cache configuration. The Redis database can be overridden in {file}. To verify capture, trigger a log message and check Runtime Logs.',
    'Enable Runtime Logs' => 'Enable Runtime Logs',
    'Skip Console Requests' => 'Skip Console Requests',
    'Skip Queue Requests' => 'Skip Queue Requests',
    'Retention (seconds)' => 'Retention (seconds)',
    'Maximum Entries' => 'Maximum Entries',
    'Refresh Interval (seconds)' => 'Refresh Interval (seconds)',
    'Maximum Message Bytes' => 'Maximum Message Bytes',
    'Maximum Context Bytes' => 'Maximum Context Bytes',
    'Captured Levels' => 'Captured Levels',
    'Include Categories' => 'Include Categories',
    'Exclude Categories' => 'Exclude Categories',
    'Include Request User ID' => 'Include Request User ID',
    'Advanced' => 'Advanced',
    'Configured Storage' => 'Configured Storage',
    'Storage follows the Craft cache configuration. Redis database selection is configuration-only. This is not a connection test; confirm capture in Runtime Logs.' => 'Storage follows the Craft cache configuration. Redis database selection is configuration-only. This is not a connection test; confirm capture in Runtime Logs.',
    'On multiple servers, use shared cache storage. Local file cache does not combine logs from other instances.' => 'On multiple servers, use shared cache storage. Local file cache does not combine logs from other instances.',
    'Capture changes apply to new requests. Restart long-running workers to load changed settings. Disabling capture does not clear stored logs.' => 'Capture changes apply to new requests. Restart long-running workers to load changed settings. Disabling capture does not clear stored logs.',
    'How often Runtime Logs refreshes automatically. Set to 0 to disable. Current: {duration}' => 'How often Runtime Logs refreshes automatically. Set to 0 to disable. Current: {duration}',
    'Choose which log categories to capture, not words in the message. Enter one category per line, such as {exact}, or use {prefix} to match categories starting with {start}. Leave empty to capture all categories.' => 'Choose which log categories to capture, not words in the message. Enter one category per line, such as {exact}, or use {prefix} to match categories starting with {start}. Leave empty to capture all categories.',
    'Skip these log categories even if included above. Enter one per line, such as {exact} or {prefix}. Leave empty to add no category exclusions.' => 'Skip these log categories even if included above. Enter one per line, such as {exact} or {prefix}. Leave empty to add no category exclusions.',
    'When on, Runtime Logs skips command-line requests. Turn off only when diagnosing console commands; file and hosted logs are unaffected.' => 'When on, Runtime Logs skips command-line requests. Turn off only when diagnosing console commands; file and hosted logs are unaffected.',
    'When on, Runtime Logs skips detected queue execution. To capture console queue workers, turn off both skip switches and restart the workers. Workers can generate large volumes of logs.' => 'When on, Runtime Logs skips detected queue execution. To capture console queue workers, turn off both skip switches and restart the workers. Workers can generate large volumes of logs.',
    'Adds the authenticated request user ID. Messages and context may still contain personal data regardless of this setting.' => 'Adds the authenticated request user ID. Messages and context may still contain personal data regardless of this setting.',

    'Maximum age of runtime entries, in seconds. Current: {duration}' => 'Maximum age of runtime entries, in seconds. Current: {duration}',
    'Min: {min} ({minDuration}), Max: {max} ({maxDuration})' => 'Min: {min} ({minDuration}), Max: {max} ({maxDuration})',
    '{count} second' => '{count} second',
    '{count} seconds' => '{count} seconds',
    '{count} minute' => '{count} minute',
    '{count} minutes' => '{count} minutes',
    '{count} hour' => '{count} hour',
    '{count} hours' => '{count} hours',
    '{count} day' => '{count} day',
    '{count} days' => '{count} days',

    // Settings: Interface
    'Interface Settings' => 'Interface Settings',

    // Setup
    'Choose the log views that suit this environment. Runtime capture is optional and remains off until enabled.' => 'Choose the log views that suit this environment. Runtime capture is optional and remains off until enabled.',
    'Consider Runtime Logs for recent diagnostics on ephemeral hosting. Confirm shared storage before relying on logs from multiple instances.' => 'Consider Runtime Logs for recent diagnostics on ephemeral hosting. Confirm shared storage before relying on logs from multiple instances.',

    // Log levels
    'All Levels' => 'All Levels',
    'Error' => 'Error',
    'Warning' => 'Warning',
    'Info' => 'Info',
    'Debug' => 'Debug',
    'Unknown' => 'Unknown',

    // Log sources
    'All Sources' => 'All Sources',
    'Web' => 'Web',
    'Console' => 'Console',
    'Queue' => 'Queue',
    'PHP Errors' => 'PHP Errors',
    'Other' => 'Other',
    'DB Queries' => 'DB Queries',
    'DB Commands' => 'DB Commands',
    'DB Command::{method}' => 'DB Command::{method}',
    'DB Connection' => 'DB Connection',
    'DB Connection::{method}' => 'DB Connection::{method}',
    'Redis Commands' => 'Redis Commands',
    'Redis Connection' => 'Redis Connection',
    'Redis Connection::{method}' => 'Redis Connection::{method}',
    'URL Routing' => 'URL Routing',
    'Web Request' => 'Web Request',
    'Session' => 'Session',
    'Template Rendering' => 'Template Rendering',
    'Modules' => 'Modules',
    'Integration Service' => 'Integration Service',

    // Filters
    'Select File' => 'Select File',
    'Select Date' => 'Select Date',
    'Search messages and context...' => 'Search messages and context...',

    // Table
    'Time' => 'Time',
    'Level' => 'Level',
    'Source' => 'Source',
    'User' => 'User',
    'Request User' => 'Request User',
    'User #{id}' => 'User #{id}',
    'Message' => 'Message',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'No log files found. Log files are created when plugin activities occur.',
    'No recent runtime logs found. Runtime logs are short-lived and only appear after matching events are captured.' => 'No recent runtime logs found. Runtime logs are short-lived and only appear after matching events are captured.',
    'No log entries found for the selected filters.' => 'No log entries found for the selected filters.',

    // Pagination
    'entry' => 'entry',
    'entries' => 'entries',

    // Sidebar
    'Current Level' => 'Current Level',
    'Current log level' => 'Current log level',
    'Retention' => 'Retention',
    'days' => 'days',
    'Available Logs' => 'Available Logs',
    'file' => 'file',
    'files' => 'files',
    'Current File' => 'Current File',
    'Log entries' => 'Log entries',
    'Refresh Cache' => 'Refresh Cache',
    'Clear Runtime Logs' => 'Clear Runtime Logs',
    'Clear recent runtime logs? This cannot be undone.' => 'Clear recent runtime logs? This cannot be undone.',
    'Loading' => 'Loading',
    'Download File' => 'Download File',
    'Log Location' => 'Log Location',
    'Runtime Store' => 'Runtime Store',
    'Craft cache' => 'Craft cache',
    'Redis unavailable' => 'Redis unavailable',
    'Redis (SELECT disabled)' => 'Redis (SELECT disabled)',
    'Redis database {database}' => 'Redis database {database}',
    'Runtime Location' => 'Runtime Location',
    'Dedicated Redis key' => 'Dedicated Redis key',
    'Recent runtime logs use a bounded diagnostic store and are not complete log history.' => 'Recent runtime logs use a bounded diagnostic store and are not complete log history.',

    // Config overrides
    'This is being overridden by the <code>{setting}</code> setting in <code>config/logging-library.php</code>.' => 'This is being overridden by the <code>{setting}</code> setting in <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.',
];
