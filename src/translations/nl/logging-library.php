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
    'Open Settings' => 'Instellingen openen',

    // Navigation
    'All Logs' => 'Alle logboeken',
    'Runtime Logs' => 'Runtime-logboeken',
    'Logs' => 'Logboeken',
    'Settings' => 'Instellingen',
    'System Logs' => 'Systeemlogboeken',
    'System' => 'Systeem',
    'Plugins' => 'Plugins',
    'General' => 'Algemeen',
    'Interface' => 'Interface',

    // Permissions
    'View all system logs' => 'Alle systeemlogboeken bekijken',
    'Download all system logs' => 'Alle systeemlogboeken downloaden',
    'Clear cache' => 'Cache wissen',
    'Manage settings' => 'Instellingen beheren',

    // Common
    '{displayName} caches' => '{displayName} caches',

    // Controller messages
    'Settings saved.' => 'Instellingen opgeslagen.',
    'Could not save settings.' => 'Instellingen konden niet worden opgeslagen.',
    'Log cache refreshed.' => 'Logcache vernieuwd.',
    'Failed to refresh log cache.' => 'Logcache kon niet worden vernieuwd.',
    'Recent runtime logs cleared.' => 'Recente runtime-logboeken gewist.',
    'Unable to clear recent runtime logs.' => 'Kan recente runtime-logboeken niet wissen.',
    'Plugin logging not configured' => 'Plugin-logboeken niet geconfigureerd',
    'Log viewer is disabled for this plugin' => 'Logviewer is uitgeschakeld voor deze plugin',
    'Log viewer is disabled for this environment' => 'Logviewer is uitgeschakeld voor deze omgeving',
    'Recent runtime logs are disabled' => 'Recente runtime-logboeken zijn uitgeschakeld',
    'Log file not found' => 'Logbestand niet gevonden',
    'Unable to determine plugin handle from URL' => 'Kan plugin-handle niet bepalen uit URL',
    'User does not have permission to view logs' => 'De gebruiker heeft geen toestemming om logboeken te bekijken',

    // Settings: General
    'General Settings' => 'Algemene instellingen',
    'Force Enable Log Viewers' => 'Logviewers geforceerd inschakelen',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Bestandsgebaseerde logviewers geforceerd inschakelen, ook wanneer een edge- of vluchtige omgeving wordt gedetecteerd. Dit is van toepassing op Logging Library en het toegewezen loggedeelte van elke plugin.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library heeft een edge- of vluchtige omgeving gedetecteerd, waardoor bestandsgebaseerde logviewers zijn verborgen voor de zelfstandige <strong>Alle logboeken</strong>-weergave en het toegewezen <strong>Logboeken</strong>-gedeelte van elke plugin. De viewer in het hoofdmenu is niet beschikbaar totdat u deze overschrijving inschakelt. Gebruik de native logs van uw hostingplatform of schakel de overschrijving in als persistente opslag beschikbaar is.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library heeft een edge- of vluchtige omgeving gedetecteerd, maar bestandsgebaseerde logviewers worden geforceerd ingeschakeld. Deze overschrijving is van toepassing op de zelfstandige <strong>Alle logboeken</strong>-weergave en het toegewezen <strong>Logboeken</strong>-gedeelte van elke plugin.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library voegt een geconsolideerde <strong>Alle logboeken</strong>-weergave toe aan het hoofdmenu van het Control Panel. Afzonderlijke plugins behouden hun eigen toegewezen <strong>Logboeken</strong>-gedeelten.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'De geconsolideerde <strong>Alle logboeken</strong>-weergave is verborgen in het hoofdmenu van het Control Panel. Afzonderlijke plugins behouden hun eigen toegewezen <strong>Logboeken</strong>-gedeelten.',
    'Show Main Menu' => 'Hoofdmenu weergeven',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Logging Library weergeven in de hoofdnavigatie van het Control Panel als een geconsolideerde Alle logboeken-weergave wanneer bestandsgebaseerde logviewers beschikbaar zijn.',

    // Settings: Interface
    'Interface Settings' => 'Interface-instellingen',

    // Log levels
    'All Levels' => 'Alle niveaus',
    'Error' => 'Fout',
    'Warning' => 'Waarschuwing',
    'Info' => 'Info',
    'Debug' => 'Debug',
    'Unknown' => 'Onbekend',

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
    'Request User' => 'Aanvraaggebruiker',
    'User #{id}' => 'Gebruiker #{id}',
    'Message' => 'Bericht',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'Geen logbestanden gevonden. Logbestanden worden aangemaakt wanneer plugin-activiteiten plaatsvinden.',
    'No recent runtime logs found. Runtime logs are short-lived and only appear after matching events are captured.' => 'Geen recente runtime-logboeken gevonden. Runtime-logboeken zijn kortstondig en verschijnen alleen nadat overeenkomende gebeurtenissen zijn vastgelegd.',
    'No log entries found for the selected filters.' => 'Geen logvermeldingen gevonden voor de geselecteerde filters.',

    // Pagination
    'entry' => 'vermelding',
    'entries' => 'vermeldingen',

    // Sidebar
    'Current Level' => 'Huidig niveau',
    'Current log level' => 'Huidig logniveau',
    'Retention' => 'Retentie',
    'days' => 'dagen',
    'Available Logs' => 'Beschikbare logboeken',
    'file' => 'bestand',
    'files' => 'bestanden',
    'Current File' => 'Huidig bestand',
    'Log entries' => 'Logvermeldingen',
    'Refresh Cache' => 'Cache vernieuwen',
    'Clear Runtime Logs' => 'Runtime-logboeken wissen',
    'Loading' => 'Laden',
    'Download File' => 'Bestand downloaden',
    'Log Location' => 'Loglocatie',
    'Runtime Store' => 'Runtimeopslag',
    'Craft cache' => 'Craft-cache',
    'Redis ({cache})' => 'Redis ({cache})',
    'Runtime Location' => 'Runtimelocatie',
    'Recent runtime logs are stored in Craft cache and are intended for short-lived diagnostics, not complete log history.' => 'Recente runtime-logboeken worden opgeslagen in Craft-cache en zijn bedoeld voor kortstondige diagnose, niet voor volledige logboekgeschiedenis.',

    // Config overrides
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Dit wordt overschreven door de instelling <code>forceEnableLogViewer</code> in <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Dit wordt overschreven door de instelling <code>showCpSection</code> in <code>config/logging-library.php</code>.',
];
