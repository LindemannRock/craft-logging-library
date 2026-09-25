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
    'Setup' => 'Setup',
    'File Logs' => 'Bestandslogboeken',
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
    'Show Logging Library in the main navigation. This does not enable or disable log capture.' => 'Logging Library in de hoofdnavigatie weergeven. Dit schakelt het vastleggen van logboeken niet in of uit.',
    'General Settings' => 'Algemene instellingen',
    'Force Enable Log Viewers' => 'Logviewers geforceerd inschakelen',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Bestandsgebaseerde logviewers geforceerd inschakelen, ook wanneer een edge- of vluchtige omgeving wordt gedetecteerd. Dit is van toepassing op Logging Library en het toegewezen loggedeelte van elke plugin.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library heeft een edge- of vluchtige omgeving gedetecteerd, waardoor bestandsgebaseerde logviewers zijn verborgen voor de zelfstandige <strong>Alle logboeken</strong>-weergave en het toegewezen <strong>Logboeken</strong>-gedeelte van elke plugin. De viewer in het hoofdmenu is niet beschikbaar totdat u deze overschrijving inschakelt. Gebruik de native logs van uw hostingplatform of schakel de overschrijving in als persistente opslag beschikbaar is.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library heeft een edge- of vluchtige omgeving gedetecteerd, maar bestandsgebaseerde logviewers worden geforceerd ingeschakeld. Deze overschrijving is van toepassing op de zelfstandige <strong>Alle logboeken</strong>-weergave en het toegewezen <strong>Logboeken</strong>-gedeelte van elke plugin.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library voegt een geconsolideerde <strong>Alle logboeken</strong>-weergave toe aan het hoofdmenu van het Control Panel. Afzonderlijke plugins behouden hun eigen toegewezen <strong>Logboeken</strong>-gedeelten.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'De geconsolideerde <strong>Alle logboeken</strong>-weergave is verborgen in het hoofdmenu van het Control Panel. Afzonderlijke plugins behouden hun eigen toegewezen <strong>Logboeken</strong>-gedeelten.',
    'Show Main Menu' => 'Hoofdmenu weergeven',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Logging Library weergeven in de hoofdnavigatie van het Control Panel als een geconsolideerde Alle logboeken-weergave wanneer bestandsgebaseerde logviewers beschikbaar zijn.',

    // Settings: File Logs
    'Force Enable File Log Viewers' => 'Bestandslogviewers geforceerd inschakelen',
    'An edge or ephemeral environment is detected. File viewers are hidden unless forced on; Runtime Logs remain available when enabled. Only force file viewers on if persistent log files are available.' => 'Er is een edge- of tijdelijke omgeving gedetecteerd. Bestandsviewers zijn verborgen tenzij geforceerd ingeschakeld; runtime-logboeken blijven beschikbaar wanneer ingeschakeld. Forceer bestandsviewers alleen als permanente logbestanden beschikbaar zijn.',
    'File viewers are available. Runtime Logs are optional and independent of file logging.' => 'Bestandsviewers zijn beschikbaar. Runtime-logboeken zijn optioneel en onafhankelijk van bestandsregistratie.',

    // Settings: Runtime Logs
    'Min: {min}, Max: {max}' => 'Min: {min}, Max: {max}',
    'Uses the application cache configuration. The Redis database can be overridden in {file}. To verify capture, trigger a log message and check Runtime Logs.' => 'Gebruikt de configuratie van de applicatiecache. De Redis-database kan worden overschreven in {file}. Genereer een logbericht en controleer de Runtime-logboeken om de vastlegging te verifiëren.',
    'Enable Runtime Logs' => 'Runtime-logboeken inschakelen',
    'Skip Console Requests' => 'Consoleverzoeken overslaan',
    'Skip Queue Requests' => 'Wachtrijverzoeken overslaan',
    'Retention (seconds)' => 'Bewaartermijn (seconden)',
    'Maximum Entries' => 'Maximumaantal vermeldingen',
    'Refresh Interval (seconds)' => 'Verversingsinterval (seconden)',
    'Maximum Message Bytes' => 'Maximale berichtbytes',
    'Maximum Context Bytes' => 'Maximale contextbytes',
    'Captured Levels' => 'Vastgelegde niveaus',
    'Include Categories' => 'Categorieën opnemen',
    'Exclude Categories' => 'Categorieën uitsluiten',
    'Include Request User ID' => 'Gebruikers-ID van verzoek opnemen',
    'Advanced' => 'Geavanceerd',
    'Configured Storage' => 'Geconfigureerde opslag',
    'Storage follows the Craft cache configuration. Redis database selection is configuration-only. This is not a connection test; confirm capture in Runtime Logs.' => 'De opslag volgt de Craft-cacheconfiguratie. De Redis-database wordt alleen via configuratie gekozen. Dit is geen verbindingstest; controleer de vastlegging in Runtime-logboeken.',
    'On multiple servers, use shared cache storage. Local file cache does not combine logs from other instances.' => 'Gebruik gedeelde cacheopslag bij meerdere servers. Lokale bestandscache combineert geen logboeken van andere instanties.',
    'Capture changes apply to new requests. Restart long-running workers to load changed settings. Disabling capture does not clear stored logs.' => 'Wijzigingen in de vastlegging gelden voor nieuwe verzoeken. Herstart langdurige werkprocessen om gewijzigde instellingen te laden. Uitschakelen van de vastlegging wist geen opgeslagen logboeken.',
    'How often Runtime Logs refreshes automatically. Set to 0 to disable. Current: {duration}' => 'Hoe vaak de runtime-logboeken automatisch worden vernieuwd. Stel in op 0 om dit uit te schakelen. Huidig: {duration}',
    'Choose which log categories to capture, not words in the message. Enter one category per line, such as {exact}, or use {prefix} to match categories starting with {start}. Leave empty to capture all categories.' => 'Kies welke logboekcategorieën u wilt vastleggen, niet woorden in het bericht. Voer één categorie per regel in, zoals {exact}, of gebruik {prefix} voor categorieën die beginnen met {start}. Laat leeg om alle categorieën vast te leggen.',
    'Skip these log categories even if included above. Enter one per line, such as {exact} or {prefix}. Leave empty to add no category exclusions.' => 'Sla deze logboekcategorieën over, ook als ze hierboven zijn opgenomen. Voer er één per regel in, zoals {exact} of {prefix}. Laat leeg om geen categorie-uitsluitingen toe te voegen.',
    'When on, Runtime Logs skips command-line requests. Turn off only when diagnosing console commands; file and hosted logs are unaffected.' => 'Wanneer ingeschakeld, slaan runtimelogboeken opdrachtregelaanvragen over. Schakel dit alleen uit om consoleopdrachten te onderzoeken; bestandslogboeken en gehoste logboeken blijven ongewijzigd.',
    'When on, Runtime Logs skips detected queue execution. To capture console queue workers, turn off both skip switches and restart the workers. Workers can generate large volumes of logs.' => 'Wanneer ingeschakeld, slaan runtimelogboeken gedetecteerde wachtrijuitvoering over. Schakel beide overslaanschakelaars uit en herstart de werkprocessen om consolewerkprocessen voor de wachtrij vast te leggen. Werkprocessen kunnen grote hoeveelheden logboeken genereren.',
    'Adds the authenticated request user ID. Messages and context may still contain personal data regardless of this setting.' => 'Voegt de ID van de aangemelde verzoekgebruiker toe. Berichten en context kunnen ongeacht deze instelling persoonsgegevens bevatten.',

    'Maximum age of runtime entries, in seconds. Current: {duration}' => 'Maximale leeftijd van runtimelogboekvermeldingen, in seconden. Huidig: {duration}',
    'Min: {min} ({minDuration}), Max: {max} ({maxDuration})' => 'Min: {min} ({minDuration}), Max: {max} ({maxDuration})',
    '{count} second' => '{count} seconde',
    '{count} seconds' => '{count} seconden',
    '{count} minute' => '{count} minuut',
    '{count} minutes' => '{count} minuten',
    '{count} hour' => '{count} uur',
    '{count} hours' => '{count} uur',
    '{count} day' => '{count} dag',
    '{count} days' => '{count} dagen',

    // Settings: Interface
    'Interface Settings' => 'Interface-instellingen',

    // Setup
    'Choose the log views that suit this environment. Runtime capture is optional and remains off until enabled.' => 'Kies de logboekweergaven die bij deze omgeving passen. Runtime-vastlegging is optioneel en blijft uitgeschakeld totdat u deze inschakelt.',
    'Consider Runtime Logs for recent diagnostics on ephemeral hosting. Confirm shared storage before relying on logs from multiple instances.' => 'Overweeg Runtime-logboeken voor recente diagnoses op tijdelijke hosting. Controleer gedeelde opslag voordat u vertrouwt op logboeken van meerdere instanties.',

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
    'DB Queries' => 'DB-query\'s',
    'DB Commands' => 'DB-opdrachten',
    'DB Command::{method}' => 'DB-opdracht::{method}',
    'DB Connection' => 'DB-verbinding',
    'DB Connection::{method}' => 'DB-verbinding::{method}',
    'Redis Commands' => 'Redis-opdrachten',
    'Redis Connection' => 'Redis-verbinding',
    'Redis Connection::{method}' => 'Redis-verbinding::{method}',
    'URL Routing' => 'URL-routering',
    'Web Request' => 'Webverzoek',
    'Session' => 'Sessie',
    'Template Rendering' => 'Sjabloonweergave',
    'Modules' => 'Modules',
    'Integration Service' => 'Integratieservice',

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
    'Clear recent runtime logs? This cannot be undone.' => 'Recente runtime-logboeken wissen? Dit kan niet ongedaan worden gemaakt.',
    'Loading' => 'Laden',
    'Download File' => 'Bestand downloaden',
    'Log Location' => 'Loglocatie',
    'Runtime Store' => 'Runtimeopslag',
    'Craft cache' => 'Craft-cache',
    'Redis unavailable' => 'Redis niet beschikbaar',
    'Redis (SELECT disabled)' => 'Redis (SELECT uitgeschakeld)',
    'Redis database {database}' => 'Redis-database {database}',
    'Runtime Location' => 'Runtimelocatie',
    'Dedicated Redis key' => 'Toegewezen Redis-sleutel',
    'Recent runtime logs use a bounded diagnostic store and are not complete log history.' => 'Recente runtime-logboeken gebruiken een begrensde diagnostische opslag en vormen geen volledige logboekgeschiedenis.',

    // Config overrides
    'This is being overridden by the <code>{setting}</code> setting in <code>config/logging-library.php</code>.' => 'Dit wordt overschreven door de instelling <code>{setting}</code> in <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Dit wordt overschreven door de instelling <code>forceEnableLogViewer</code> in <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Dit wordt overschreven door de instelling <code>showCpSection</code> in <code>config/logging-library.php</code>.',
];
