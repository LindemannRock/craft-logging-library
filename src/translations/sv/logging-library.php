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
    'Inspect system logs, review plugin logging output, and centralize diagnostics from one control panel workspace.' => 'Inspektera systemloggar, granska plugin-loggutdata och centralisera diagnostik från en arbetsyta i kontrollpanelen.',
    'Open All Logs' => 'Öppna alla loggar',
    'Open Settings' => 'Öppna inställningar',

    // Navigation
    'All Logs' => 'Alla loggar',
    'Runtime Logs' => 'Runtime-loggar',
    'Logs' => 'Loggar',
    'Settings' => 'Inställningar',
    'System Logs' => 'Systemloggar',
    'System' => 'System',
    'General' => 'Allmänt',
    'Interface' => 'Gränssnitt',

    // Permissions
    'View all system logs' => 'Visa alla systemloggar',
    'Download all system logs' => 'Ladda ner alla systemloggar',
    'Clear cache' => 'Rensa cache',
    'Manage settings' => 'Hantera inställningar',

    // Common
    '{displayName} caches' => '{displayName}-cacher',

    // Controller messages
    'Settings saved.' => 'Inställningar sparade.',
    'Could not save settings.' => 'Det gick inte att spara inställningarna.',
    'Log cache refreshed.' => 'Loggcachen uppdaterades.',
    'Failed to refresh log cache.' => 'Det gick inte att uppdatera loggcachen.',
    'Recent runtime logs cleared.' => 'Senaste runtime-loggarna rensades.',
    'Plugin logging not configured' => 'Plugin-loggning är inte konfigurerad',
    'Log viewer is disabled for this plugin' => 'Loggvisaren är inaktiverad för detta plugin',
    'Log viewer is disabled for this environment' => 'Loggvisaren är inaktiverad för denna miljö',
    'Recent runtime logs are disabled' => 'Senaste runtime-loggar är inaktiverade',
    'Log file not found' => 'Loggfilen hittades inte',
    'Unable to determine plugin handle from URL' => 'Det gick inte att fastställa plugin-handle från URL',
    'User does not have permission to view logs' => 'Användaren har inte behörighet att visa loggar',

    // Validation messages
    'Found {count, number} {count, plural, =1{error} other{errors}}' => '{count, number} {count, plural, =1{fel} other{fel}} hittades',

    // Settings: General
    'General Settings' => 'Allmänna inställningar',
    'Force Enable Log Viewers' => 'Tvinga aktivering av loggvisare',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Tvinga aktivering av filbaserade loggvisare även när en edge- eller tillfällig miljö identifieras. Detta påverkar Logging Library och varje plugins dedikerade Loggar-avsnitt.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library identifierade en edge- eller tillfällig miljö, vilket innebär att filbaserade loggvisare är dolda för den fristående <strong>Alla loggar</strong>-vyn och för varje plugins dedikerade <strong>Loggar</strong>-avsnitt. Huvudmenyvisaren är inte tillgänglig förrän du aktiverar denna åsidosättning. Använd din hostingplattforms inbyggda loggar, eller aktivera åsidosättningen om beständig lagring är tillgänglig.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library identifierade en edge- eller tillfällig miljö, men filbaserade loggvisare aktiveras tvångsvis. Denna åsidosättning påverkar den fristående <strong>Alla loggar</strong>-vyn och varje plugins dedikerade <strong>Loggar</strong>-avsnitt.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library lägger till en samlad <strong>Alla loggar</strong>-vy i kontrollpanelens huvudmeny. Enskilda plugins behåller fortfarande sina egna dedikerade <strong>Loggar</strong>-avsnitt.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Den samlade <strong>Alla loggar</strong>-vyn är dold från kontrollpanelens huvudmeny. Enskilda plugins behåller fortfarande sina egna dedikerade <strong>Loggar</strong>-avsnitt.',
    'Show Main Menu' => 'Visa huvudmeny',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Visa Logging Library i kontrollpanelens huvudnavigering som en samlad Alla loggar-vy när filbaserade loggvisare är tillgängliga.',

    // Settings: Interface
    'Interface Settings' => 'Gränssnittsinställningar',

    // Log levels
    'All Levels' => 'Alla nivåer',
    'Error' => 'Fel',
    'Warning' => 'Varning',
    'Info' => 'Info',
    'Debug' => 'Debug',

    // Log sources
    'All Sources' => 'Alla källor',
    'Web' => 'Web',
    'Console' => 'Konsol',
    'Queue' => 'Queue',
    'PHP Errors' => 'PHP-fel',
    'Other' => 'Övrigt',

    // Filters
    'Select File' => 'Välj fil',
    'Select Date' => 'Välj datum',
    'Search messages and context...' => 'Sök meddelanden och kontext...',

    // Table
    'Time' => 'Tid',
    'Level' => 'Nivå',
    'Source' => 'Källa',
    'User' => 'Användare',
    'Request User' => 'Begärande användare',
    'User #{id}' => 'Användare #{id}',
    'Message' => 'Meddelande',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'Inga loggfiler hittades. Loggfiler skapas när plugin-aktiviteter inträffar.',
    'No recent runtime logs found. Runtime logs are short-lived and only appear after matching events are captured.' => 'Inga senaste runtime-loggar hittades. Runtime-loggar är kortlivade och visas bara efter att matchande händelser har fångats.',
    'No log entries found for the selected filters.' => 'Inga loggposter hittades för de valda filtren.',

    // Pagination
    'entry' => 'post',
    'entries' => 'poster',

    // Sidebar
    'Current Level' => 'Aktuell nivå',
    'Current log level' => 'Aktuell loggnivå',
    'Retention' => 'Lagring',
    'days' => 'dagar',
    'Available Logs' => 'Tillgängliga loggar',
    'file' => 'fil',
    'files' => 'filer',
    'Current File' => 'Aktuell fil',
    'Log entries' => 'Loggposter',
    'Refresh Cache' => 'Uppdatera cache',
    'Clear Runtime Logs' => 'Rensa runtime-loggar',
    'Loading' => 'Läser in',
    'Download File' => 'Ladda ner fil',
    'Log Location' => 'Loggplats',
    'Runtime Store' => 'Runtime-lagring',
    'Craft cache' => 'Craft-cache',
    'Runtime Location' => 'Runtime-plats',
    'Recent runtime logs are stored in Craft cache and are intended for short-lived diagnostics, not complete log history.' => 'Senaste runtime-loggar lagras i Craft-cache och är avsedda för kortlivad diagnostik, inte fullständig logghistorik.',

    // Config overrides
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Detta åsidosätts av inställningen <code>forceEnableLogViewer</code> i <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Detta åsidosätts av inställningen <code>showCpSection</code> i <code>config/logging-library.php</code>.',
];
