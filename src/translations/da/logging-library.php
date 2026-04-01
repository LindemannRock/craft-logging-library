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
    'Inspect system logs, review plugin logging output, and centralize diagnostics from one control panel workspace.' => 'Inspicér systemlogfiler, gennemgå plugin-logoutput og centraliser diagnostik fra ét arbejdsområde i kontrolpanelet.',
    'Open All Logs' => 'Åbn alle logfiler',

    // Navigation
    'All Logs' => 'Alle logfiler',
    'Logs' => 'Logfiler',
    'Settings' => 'Indstillinger',
    'System Logs' => 'Systemlogfiler',
    'System' => 'System',
    'General' => 'Generelt',
    'Interface' => 'Grænseflade',

    // Log levels
    'All Levels' => 'Alle niveauer',
    'Error' => 'Fejl',
    'Warning' => 'Advarsel',
    'Info' => 'Info',
    'Debug' => 'Debug',

    // Log sources
    'All Sources' => 'Alle kilder',
    'Web' => 'Web',
    'Console' => 'Konsol',
    'Queue' => 'Queue',
    'PHP Errors' => 'PHP-fejl',
    'Other' => 'Andet',

    // Filters
    'Select File' => 'Vælg fil',
    'Select Date' => 'Vælg dato',
    'Search messages and context...' => 'Søg i beskeder og kontekst...',

    // Table
    'Time' => 'Tid',
    'Level' => 'Niveau',
    'Source' => 'Kilde',
    'User' => 'Bruger',
    'Message' => 'Besked',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'Ingen logfiler fundet. Logfiler oprettes, når plugin-aktiviteter forekommer.',
    'No log entries found for the selected filters.' => 'Ingen logposter fundet for de valgte filtre.',

    // Pagination
    'entry' => 'post',
    'entries' => 'poster',

    // Row detail
    'Context' => 'Kontekst',
    'No context data available.' => 'Ingen kontekstdata tilgængelig.',

    // Sidebar
    'Current Level' => 'Nuværende niveau',
    'Current log level' => 'Nuværende logniveau',
    'Retention' => 'Opbevaring',
    'days' => 'dage',
    'Available Logs' => 'Tilgængelige logfiler',
    'file' => 'fil',
    'files' => 'filer',
    'Current File' => 'Nuværende fil',
    'Entries' => 'Poster',
    'Download File' => 'Download fil',
    'Log Location' => 'Logplacering',

    // Common
    'Save Settings' => 'Gem indstillinger',

    // Controller messages
    'Settings saved.' => 'Indstillinger gemt.',
    'Could not save settings.' => 'Indstillinger kunne ikke gemmes.',

    // Validation messages
    'Value must be a whole number.' => 'Værdien skal være et helt tal.',

    // Settings: General
    'General Settings' => 'Generelle indstillinger',
    'Plugin Name' => 'Plugin-navn',
    'The name of the plugin as it appears in the Control Panel menu' => 'Navnet på plugin-programmet, som det vises i kontrolpanelets menu',
    'This is being overridden by the <code>pluginName</code> setting in <code>config/logging-library.php</code>.' => 'Dette tilsidesættes af indstillingen <code>pluginName</code> i <code>config/logging-library.php</code>.',
    'Force Enable Log Viewers' => 'Tving aktivering af logvisere',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Tving aktivering af filbaserede logvisere, selv når et edge- eller flygtigt miljø registreres. Dette påvirker Logging Library og hvert plugins dedikerede Logfiler-sektion.',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Dette tilsidesættes af indstillingen <code>forceEnableLogViewer</code> i <code>config/logging-library.php</code>.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library registrerede et edge- eller flygtigt miljø, så filbaserede logvisere er skjult for den selvstændige <strong>Alle logfiler</strong>-visning og for hvert plugins dedikerede <strong>Logfiler</strong>-sektion. Hovedmenuviseren er ikke tilgængelig, før du aktiverer denne tilsidesættelse. Brug din hostingplatforms native logfiler, eller aktiver tilsidesættelsen, hvis vedvarende lagring er tilgængelig.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library registrerede et edge- eller flygtigt miljø, men filbaserede logvisere aktiveres med tvang. Denne tilsidesættelse påvirker den selvstændige <strong>Alle logfiler</strong>-visning og hvert plugins dedikerede <strong>Logfiler</strong>-sektion.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library tilføjer en samlet <strong>Alle logfiler</strong>-visning til kontrolpanelets hovedmenu. Individuelle plugins beholder stadig deres egne dedikerede <strong>Logfiler</strong>-sektioner.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Den samlede <strong>Alle logfiler</strong>-visning er skjult fra kontrolpanelets hovedmenu. Individuelle plugins beholder stadig deres egne dedikerede <strong>Logfiler</strong>-sektioner.',
    'Show Main Menu' => 'Vis hovedmenu',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Vis Logging Library i kontrolpanelets hovednavigation som en samlet Alle logfiler-visning, når filbaserede logvisere er tilgængelige.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Dette tilsidesættes af indstillingen <code>showCpSection</code> i <code>config/logging-library.php</code>.',

    // Settings: Interface
    'Interface Settings' => 'Grænsefladeindstillinger',
    'Items Per Page' => 'Elementer pr. side',
    'Number of log entries to display per page in the log viewers' => 'Antal logposter der skal vises pr. side i logviserne',
    'This is being overridden by the <code>itemsPerPage</code> setting in <code>config/logging-library.php</code>.' => 'Dette tilsidesættes af indstillingen <code>itemsPerPage</code> i <code>config/logging-library.php</code>.',
];
