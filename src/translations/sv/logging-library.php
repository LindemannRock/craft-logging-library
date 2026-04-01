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

    // Navigation
    'All Logs' => 'Alla loggar',
    'Logs' => 'Loggar',
    'Settings' => 'Inställningar',
    'System Logs' => 'Systemloggar',
    'System' => 'System',
    'General' => 'Allmänt',
    'Interface' => 'Gränssnitt',

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
    'Message' => 'Meddelande',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'Inga loggfiler hittades. Loggfiler skapas när plugin-aktiviteter inträffar.',
    'No log entries found for the selected filters.' => 'Inga loggposter hittades för de valda filtren.',

    // Pagination
    'entry' => 'post',
    'entries' => 'poster',

    // Row detail
    'Context' => 'Kontext',
    'No context data available.' => 'Ingen kontextdata tillgänglig.',

    // Sidebar
    'Current Level' => 'Aktuell nivå',
    'Current log level' => 'Aktuell loggnivå',
    'Retention' => 'Lagring',
    'days' => 'dagar',
    'Available Logs' => 'Tillgängliga loggar',
    'file' => 'fil',
    'files' => 'filer',
    'Current File' => 'Aktuell fil',
    'Entries' => 'Poster',
    'Download File' => 'Ladda ner fil',
    'Log Location' => 'Loggplats',

    // Common
    'Save Settings' => 'Spara inställningar',

    // Controller messages
    'Settings saved.' => 'Inställningar sparade.',
    'Could not save settings.' => 'Det gick inte att spara inställningarna.',

    // Validation messages
    'Value must be a whole number.' => 'Värdet måste vara ett heltal.',

    // Settings: General
    'General Settings' => 'Allmänna inställningar',
    'Plugin Name' => 'Plugin-namn',
    'The name of the plugin as it appears in the Control Panel menu' => 'Namnet på plugin-programmet som det visas i kontrollpanelens meny',
    'This is being overridden by the <code>pluginName</code> setting in <code>config/logging-library.php</code>.' => 'Detta åsidosätts av inställningen <code>pluginName</code> i <code>config/logging-library.php</code>.',
    'Show Main Menu' => 'Visa huvudmeny',
    'Show Logging Library in the main Control Panel navigation. When disabled, All Logs remains accessible from plugin settings and direct URLs.' => 'Visa Logging Library i kontrollpanelens huvudnavigering. När det är inaktiverat förblir Alla loggar tillgängliga via plugin-inställningar och direkta URL:er.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Detta åsidosätts av inställningen <code>showCpSection</code> i <code>config/logging-library.php</code>.',

    // Settings: Interface
    'Interface Settings' => 'Gränssnittsinställningar',
    'Items Per Page' => 'Objekt per sida',
    'Number of log entries to display per page in the log viewers' => 'Antal loggposter som ska visas per sida i loggvisarna',
    'This is being overridden by the <code>itemsPerPage</code> setting in <code>config/logging-library.php</code>.' => 'Detta åsidosätts av inställningen <code>itemsPerPage</code> i <code>config/logging-library.php</code>.',
];
