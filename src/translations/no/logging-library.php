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
    'Inspect system logs, review plugin logging output, and centralize diagnostics from one control panel workspace.' => 'Inspiser systemlogger, gjennomgå plugin-loggutdata og sentraliser diagnostikk fra ett arbeidsområde i kontrollpanelet.',
    'Open All Logs' => 'Åpne alle logger',
    'Open Settings' => 'Åpne innstillinger',

    // Navigation
    'File Logs' => 'Fillogger',
    'All Logs' => 'Alle logger',
    'Runtime Logs' => 'Runtime-logger',
    'Logs' => 'Logger',
    'Settings' => 'Innstillinger',
    'System Logs' => 'Systemlogger',
    'System' => 'System',
    'Plugins' => 'Plugins',
    'General' => 'Generelt',
    'Interface' => 'Grensesnitt',

    // Permissions
    'View all file logs' => 'Vis alle fillogger',
    'Download all file logs' => 'Last ned alle fillogger',
    'Clear file log cache' => 'Fjern filloggenes cache',
    'View runtime logs' => 'Vis runtime-logger',
    'Manage settings' => 'Administrer innstillinger',

    // Common
    '{displayName} caches' => '{displayName}-cacher',

    // Controller messages
    'Settings saved.' => 'Innstillinger lagret.',
    'Could not save settings.' => 'Kunne ikke lagre innstillingene.',
    'Log cache refreshed.' => 'Logg-cache oppdatert.',
    'Failed to refresh log cache.' => 'Kunne ikke oppdatere logg-cache.',
    'Recent runtime logs cleared.' => 'Nylige runtime-logger er tømt.',
    'Unable to clear recent runtime logs.' => 'Nylige runtime-logger kunne ikke tømmes.',
    'Plugin logging not configured' => 'Plugin-logging er ikke konfigurert',
    'Log viewer is disabled for this plugin' => 'Loggviseren er deaktivert for dette pluginet',
    'Log viewer is disabled for this environment' => 'Loggviseren er deaktivert for dette miljøet',
    'Recent runtime logs are disabled' => 'Nylige runtime-logger er deaktivert',
    'Log file not found' => 'Loggfilen ble ikke funnet',
    'Unable to determine plugin handle from URL' => 'Plugin-handle kunne ikke bestemmes fra URL',
    'User does not have permission to view logs' => 'Brukeren har ikke tillatelse til å vise logger',

    // Settings: General
    'Show Logging Library in the main navigation. This does not enable or disable log capture.' => 'Vis Logging Library i hovednavigasjonen. Dette aktiverer eller deaktiverer ikke logginnsamling.',
    'General Settings' => 'Generelle innstillinger',
    'Force Enable Log Viewers' => 'Tving aktivering av loggvisere',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Tving aktivering av filbaserte loggvisere selv når et edge- eller flyktig miljø oppdages. Dette påvirker Logging Library og hvert plugins dedikerte Logger-seksjon.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library oppdaget et edge- eller flyktig miljø, så filbaserte loggvisere er skjult for den frittstående <strong>Alle logger</strong>-visningen og for hvert plugins dedikerte <strong>Logger</strong>-seksjon. Hovedmenyviseren er ikke tilgjengelig før du aktiverer denne overstyringen. Bruk din hostingplattforms innebygde logger, eller aktiver overstyringen hvis vedvarende lagring er tilgjengelig.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library oppdaget et edge- eller flyktig miljø, men filbaserte loggvisere aktiveres med tvang. Denne overstyringen påvirker den frittstående <strong>Alle logger</strong>-visningen og hvert plugins dedikerte <strong>Logger</strong>-seksjon.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library legger til en samlet <strong>Alle logger</strong>-visning i kontrollpanelets hovedmeny. Individuelle plugins beholder fortsatt sine egne dedikerte <strong>Logger</strong>-seksjoner.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Den samlede <strong>Alle logger</strong>-visningen er skjult fra kontrollpanelets hovedmeny. Individuelle plugins beholder fortsatt sine egne dedikerte <strong>Logger</strong>-seksjoner.',
    'Show Main Menu' => 'Vis hovedmeny',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Vis Logging Library i kontrollpanelets hovednavigasjon som en samlet Alle logger-visning når filbaserte loggvisere er tilgjengelige.',

    // Settings: File Logs
    'Force Enable File Log Viewers' => 'Tving aktivering av filloggvisere',
    'An edge or ephemeral environment is detected. File viewers are hidden unless forced on; {runtimeLogs} remain available when enabled. Only force file viewers on if persistent log files are available.' => 'Et edge- eller midlertidig miljø er oppdaget. Filvisere er skjult med mindre de tvinges på; {runtimeLogs} er fortsatt tilgjengelige når de er aktivert. Tving bare filvisere på hvis vedvarende loggfiler er tilgjengelige.',
    'File viewers are available. Runtime Logs are optional and independent of file logging.' => 'Filvisere er tilgjengelige. Runtime-logger er valgfrie og uavhengige av fillogging.',

    // Settings: Runtime Logs
    'Min: {min}, Max: {max}' => 'Min: {min}, Maks: {max}',
    'Uses the application cache configuration. The Redis database can be overridden in {file}. To verify capture, trigger a log message and check Runtime Logs.' => 'Bruker applikasjonscachens konfigurasjon. Redis-databasen kan overstyres i {file}. Generer en loggmelding og kontroller runtime-logger for å bekrefte innsamlingen.',
    'Enable Runtime Logs' => 'Aktiver runtime-logger',
    'Skip Console Requests' => 'Hopp over konsollforespørsler',
    'Skip Queue Requests' => 'Hopp over køforespørsler',
    'Retention (seconds)' => 'Oppbevaring (sekunder)',
    'Maximum Entries' => 'Maksimalt antall oppføringer',
    'Refresh Interval (seconds)' => 'Oppdateringsintervall (sekunder)',
    'Maximum Message Bytes' => 'Maksimalt antall byte per melding',
    'Maximum Context Bytes' => 'Maksimalt antall byte for kontekst',
    'Captured Levels' => 'Innsamlede nivåer',
    'Include Sources and Categories' => 'Inkluder kilder og kategorier',
    'Exclude Sources and Categories' => 'Ekskluder kilder og kategorier',
    'Category: {pattern}' => 'Kategori: {pattern}',
    'Include Request User ID' => 'Inkluder forespørselens bruker-ID',
    'Advanced' => 'Avansert',
    'Configured Storage' => 'Konfigurert lagring',
    'Capture new messages in Runtime Logs. Changes apply to new requests; restart long-running workers to apply them. Turning this off does not delete existing logs.' => 'Samle inn nye meldinger i runtime-logger. Endringer gjelder nye forespørsler; start langvarige arbeidsprosesser på nytt for å ta dem i bruk. Deaktivering sletter ikke eksisterende logger.',
    'Limits the message text kept for each new Runtime Logs entry, in bytes rather than characters. Longer messages are shortened. File logs are unaffected.' => 'Begrenser meldingsteksten som lagres for hver ny oppføring i runtime-logger, i byte i stedet for tegn. Lengre meldinger forkortes. Fillogger påvirkes ikke.',
    'Limits the extra data kept for each new Runtime Logs entry, such as error details and stack traces, in bytes after JSON encoding. Larger context is shortened. File logs are unaffected.' => 'Begrenser ekstra data som lagres for hver ny oppføring i runtime-logger, som feildetaljer og stakksporinger, i byte etter JSON-koding. Større kontekst forkortes. Fillogger påvirkes ikke.',
    'How often Runtime Logs refreshes automatically. Set to 0 to disable. Current: {duration}' => 'Hvor ofte runtime-logger oppdateres automatisk. Angi 0 for å deaktivere. Gjeldende: {duration}',
    'Choose sources by name or type a category pattern such as {pattern} and press Enter. Leave empty to capture all sources. Changes affect new messages only.' => 'Velg kilder etter navn eller angi et kategorimønster som {pattern}, og trykk på Enter. La feltet stå tomt for å samle inn alle kilder. Endringer gjelder bare nye meldinger.',
    'Choose sources to skip or type a category pattern and press Enter. Exclusions take precedence. Existing entries are not removed.' => 'Velg kilder som skal hoppes over, eller angi et kategorimønster og trykk på Enter. Ekskluderinger har forrang. Eksisterende oppføringer fjernes ikke.',
    'When on, Runtime Logs skips command-line requests. Turn off only when diagnosing console commands; file and hosted logs are unaffected.' => 'Når dette er aktivert, hopper runtime-logger over kommandolinjeforespørsler. Deaktiver bare ved diagnostikk av konsollkommandoer; fillogger og vertsleverandørens logger påvirkes ikke.',
    'When on, Runtime Logs skips detected queue execution. To capture console queue workers, turn off both skip switches and restart the workers. Workers can generate large volumes of logs.' => 'Når dette er aktivert, hopper runtime-logger over oppdaget køkjøring. For å samle inn logger fra konsollens køprosesser må du deaktivere begge hopp over-innstillingene og starte prosessene på nytt. Arbeidsprosesser kan generere store loggmengder.',
    'Adds the authenticated request user ID. Messages and context may still contain personal data regardless of this setting.' => 'Legger til ID for forespørselens autentiserte bruker. Meldinger og kontekst kan inneholde personopplysninger uavhengig av denne innstillingen.',

    'Maximum age of runtime entries, in seconds. Current: {duration}' => 'Maksimal alder for runtime-loggoppføringer i sekunder. Gjeldende: {duration}',
    'Min: {min} ({minDuration}), Max: {max} ({maxDuration})' => 'Min: {min} ({minDuration}), Maks: {max} ({maxDuration})',
    '{count} second' => '{count} sekund',
    '{count} seconds' => '{count} sekunder',
    '{count} minute' => '{count} minutt',
    '{count} minutes' => '{count} minutter',
    '{count} hour' => '{count} time',
    '{count} hours' => '{count} timer',
    '{count} day' => '{count} dag',
    '{count} days' => '{count} dager',

    // Settings: Interface
    'Interface Settings' => 'Grensesnittinnstillinger',

    // Log levels
    'All Levels' => 'Alle nivåer',
    'Error' => 'Feil',
    'Warning' => 'Advarsel',
    'Info' => 'Info',
    'Debug' => 'Debug',
    'Unknown' => 'Ukjent',

    // Log sources
    'All Sources' => 'Alle kilder',
    'Web' => 'Web',
    'Console' => 'Konsoll',
    'Queue' => 'Queue',
    'PHP Errors' => 'PHP-feil',
    'Other' => 'Annet',
    'DB Queries' => 'DB-spørringer',
    'DB Commands' => 'DB-kommandoer',
    'DB Command::{method}' => 'DB-kommando::{method}',
    'DB Connection' => 'DB-tilkobling',
    'DB Connection::{method}' => 'DB-tilkobling::{method}',
    'Redis Commands' => 'Redis-kommandoer',
    'Redis Connection' => 'Redis-tilkobling',
    'Redis Connection::{method}' => 'Redis-tilkobling::{method}',
    'URL Routing' => 'URL-ruting',
    'Web Request' => 'Webforespørsel',
    'Session' => 'Økt',
    'Template Rendering' => 'Malgjengivelse',
    'Modules' => 'Moduler',
    'Integration Service' => 'Integrasjonstjeneste',

    // Filters
    'Select File' => 'Velg fil',
    'Select Date' => 'Velg dato',
    'Search messages and context...' => 'Søk i meldinger og kontekst...',

    // Table
    'Time' => 'Tid',
    'Level' => 'Nivå',
    'Source' => 'Kilde',
    'User' => 'Bruker',
    'Request User' => 'Forespørselsbruker',
    'User #{id}' => 'Bruker #{id}',
    'Message' => 'Melding',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'Ingen loggfiler funnet. Loggfiler opprettes når plugin-aktiviteter oppstår.',
    'No recent runtime logs found. Runtime logs are short-lived and only appear after matching events are captured.' => 'Ingen nylige runtime-logger funnet. Runtime-logger er kortvarige og vises bare etter at samsvarende hendelser er fanget.',
    'No log entries found for the selected filters.' => 'Ingen loggoppføringer funnet for de valgte filtrene.',

    // Pagination
    'entry' => 'oppføring',
    'entries' => 'oppføringer',

    // Sidebar
    'Current Level' => 'Gjeldende nivå',
    'Current log level' => 'Gjeldende loggnivå',
    'Retention' => 'Oppbevaring',
    'days' => 'dager',
    'Available Logs' => 'Tilgjengelige logger',
    'file' => 'fil',
    'files' => 'filer',
    'Current File' => 'Gjeldende fil',
    'Log entries' => 'Loggoppføringer',
    'Refresh Cache' => 'Oppdater cache',
    'Clear runtime logs' => 'Tøm runtime-logger',
    'Clear recent runtime logs? This cannot be undone.' => 'Tøm nylige runtime-logger? Dette kan ikke angres.',
    'Loading' => 'Laster',
    'Download File' => 'Last ned fil',
    'Log Location' => 'Loggplassering',
    'Runtime Store' => 'Runtime-lager',
    'Craft cache' => 'Craft-cache',
    'Redis unavailable' => 'Redis ikke tilgjengelig',
    'Redis (SELECT disabled)' => 'Redis (SELECT deaktivert)',
    'Redis database {database}' => 'Redis-database {database}',
    'Runtime Location' => 'Runtime-plassering',
    'Dedicated Redis key' => 'Dedikert Redis-nøkkel',
    'Recent runtime logs use a bounded diagnostic store and are not complete log history.' => 'Nylige runtime-logger bruker et begrenset diagnostikklager og er ikke en fullstendig logghistorikk.',

    // Config overrides
    'This is being overridden by the <code>{setting}</code> setting in <code>config/logging-library.php</code>.' => 'Dette overstyres av innstillingen <code>{setting}</code> i <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Dette overstyres av innstillingen <code>forceEnableLogViewer</code> i <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Dette overstyres av innstillingen <code>showCpSection</code> i <code>config/logging-library.php</code>.',
];
