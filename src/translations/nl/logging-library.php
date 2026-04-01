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
    'Inspect system logs, review plugin logging output, and centralize diagnostics from one control panel workspace.' => 'Bekijk systeemlogboeken, controleer plugin-loguitvoer en centraliseer diagnostiek vanuit één werkruimte in het Control Panel.',
    'Open All Logs' => 'Alle logboeken openen',

    // Navigation
    'All Logs' => 'Alle logboeken',
    'Logs' => 'Logboeken',
    'Settings' => 'Instellingen',
    'System Logs' => 'Systeemlogboeken',
    'System' => 'Systeem',
    'General' => 'Algemeen',
    'Interface' => 'Interface',

    // Log levels
    'All Levels' => 'Alle niveaus',
    'Error' => 'Fout',
    'Warning' => 'Waarschuwing',
    'Info' => 'Info',
    'Debug' => 'Debug',

    // Log sources
    'All Sources' => 'Alle bronnen',
    'Web' => 'Web',
    'Console' => 'Console',
    'Queue' => 'Queue',
    'PHP Errors' => 'PHP-fouten',
    'Other' => 'Overig',

    // Filters
    'Select File' => 'Bestand selecteren',
    'Select Date' => 'Datum selecteren',
    'Search messages and context...' => 'Berichten en context doorzoeken...',

    // Table
    'Time' => 'Tijd',
    'Level' => 'Niveau',
    'Source' => 'Bron',
    'User' => 'Gebruiker',
    'Message' => 'Bericht',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'Geen logbestanden gevonden. Logbestanden worden aangemaakt wanneer plugin-activiteiten plaatsvinden.',
    'No log entries found for the selected filters.' => 'Geen logvermeldingen gevonden voor de geselecteerde filters.',

    // Pagination
    'entry' => 'vermelding',
    'entries' => 'vermeldingen',

    // Row detail
    'Context' => 'Context',
    'No context data available.' => 'Geen contextgegevens beschikbaar.',

    // Sidebar
    'Current Level' => 'Huidig niveau',
    'Current log level' => 'Huidig logniveau',
    'Retention' => 'Retentie',
    'days' => 'dagen',
    'Available Logs' => 'Beschikbare logboeken',
    'file' => 'bestand',
    'files' => 'bestanden',
    'Current File' => 'Huidig bestand',
    'Entries' => 'Vermeldingen',
    'Download File' => 'Bestand downloaden',
    'Log Location' => 'Loglocatie',

    // Common
    'Save Settings' => 'Instellingen opslaan',

    // Controller messages
    'Settings saved.' => 'Instellingen opgeslagen.',
    'Could not save settings.' => 'Instellingen konden niet worden opgeslagen.',

    // Validation messages
    'Value must be a whole number.' => 'De waarde moet een geheel getal zijn.',

    // Settings: General
    'General Settings' => 'Algemene instellingen',
    'Plugin Name' => 'Plugin-naam',
    'The name of the plugin as it appears in the Control Panel menu' => 'De naam van de plugin zoals die wordt weergegeven in het Control Panel-menu',
    'This is being overridden by the <code>pluginName</code> setting in <code>config/logging-library.php</code>.' => 'Dit wordt overschreven door de instelling <code>pluginName</code> in <code>config/logging-library.php</code>.',
    'Show Main Menu' => 'Hoofdmenu weergeven',
    'Show Logging Library in the main Control Panel navigation. When disabled, All Logs remains accessible from plugin settings and direct URLs.' => 'Logging Library weergeven in de hoofdnavigatie van het Control Panel. Wanneer uitgeschakeld, blijft Alle logboeken toegankelijk via de plugin-instellingen en directe URL\'s.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Dit wordt overschreven door de instelling <code>showCpSection</code> in <code>config/logging-library.php</code>.',

    // Settings: Interface
    'Interface Settings' => 'Interface-instellingen',
    'Items Per Page' => 'Items per pagina',
    'Number of log entries to display per page in the log viewers' => 'Aantal logvermeldingen dat per pagina wordt weergegeven in de logviewers',
    'This is being overridden by the <code>itemsPerPage</code> setting in <code>config/logging-library.php</code>.' => 'Dit wordt overschreven door de instelling <code>itemsPerPage</code> in <code>config/logging-library.php</code>.',
];
