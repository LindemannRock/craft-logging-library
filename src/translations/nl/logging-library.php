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
    'View all file logs' => 'Alle bestandslogboeken bekijken',
    'Download all file logs' => 'Alle bestandslogboeken downloaden',
    'Clear file log cache' => 'Cache van bestandslogboeken wissen',
    'View runtime logs' => 'Runtime-logboeken bekijken',
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
    'An edge or ephemeral environment is detected. File viewers are hidden unless forced on; {runtimeLogs} remain available when enabled. Only force file viewers on if persistent log files are available.' => 'Er is een edge- of tijdelijke omgeving gedetecteerd. Bestandsviewers zijn verborgen tenzij geforceerd ingeschakeld; {runtimeLogs} blijven beschikbaar wanneer ingeschakeld. Forceer bestandsviewers alleen als permanente logbestanden beschikbaar zijn.',
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
    'Include Sources and Categories' => 'Bronnen en categorieën opnemen',
    'Exclude Sources and Categories' => 'Bronnen en categorieën uitsluiten',
    'Category: {pattern}' => 'Categorie: {pattern}',
    'Include Request User ID' => 'Gebruikers-ID van verzoek opnemen',
    'Advanced' => 'Geavanceerd',
    'Configured Storage' => 'Geconfigureerde opslag',
    'Capture new messages in Runtime Logs. Changes apply to new requests; restart long-running workers to apply them. Turning this off does not delete existing logs.' => 'Leg nieuwe berichten vast in de runtime-logboeken. Wijzigingen gelden voor nieuwe verzoeken; herstart langdurige werkprocessen om ze toe te passen. Uitschakelen verwijdert geen bestaande logboeken.',
    'Limits the message text kept for each new Runtime Logs entry, in bytes rather than characters. Longer messages are shortened. File logs are unaffected.' => 'Beperkt de berichttekst die voor elke nieuwe vermelding in de runtime-logboeken wordt bewaard, in bytes in plaats van tekens. Langere berichten worden ingekort. Bestandslogboeken blijven ongewijzigd.',
    'Limits the extra data kept for each new Runtime Logs entry, such as error details and stack traces, in bytes after JSON encoding. Larger context is shortened. File logs are unaffected.' => 'Beperkt de extra gegevens die voor elke nieuwe vermelding in de runtime-logboeken worden bewaard, zoals foutdetails en stacktraces, in bytes na JSON-codering. Grotere context wordt ingekort. Bestandslogboeken blijven ongewijzigd.',
    'How often Runtime Logs refreshes automatically. Set to 0 to disable. Current: {duration}' => 'Hoe vaak de runtime-logboeken automatisch worden vernieuwd. Stel in op 0 om dit uit te schakelen. Huidig: {duration}',
    'Choose sources by name or type a category pattern such as {pattern} and press Enter. Leave empty to capture all sources. Changes affect new messages only.' => 'Kies bronnen op naam of voer een categoriepatroon in, zoals {pattern}, en druk op Enter. Laat leeg om alle bronnen vast te leggen. Wijzigingen gelden alleen voor nieuwe berichten.',
    'Choose sources to skip or type a category pattern and press Enter. Exclusions take precedence. Existing entries are not removed.' => 'Kies bronnen om over te slaan of voer een categoriepatroon in en druk op Enter. Uitsluitingen hebben voorrang. Bestaande vermeldingen worden niet verwijderd.',
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
    'Clear runtime logs' => 'Runtime-logboeken wissen',
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
