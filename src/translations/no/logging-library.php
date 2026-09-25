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
    'Setup' => 'Oppsett',
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
    'View all system logs' => 'Vis alle systemlogger',
    'Download all system logs' => 'Last ned alle systemlogger',
    'Clear cache' => 'Fjern cache',
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
    'An edge or ephemeral environment is detected. File viewers are hidden unless forced on; Runtime Logs remain available when enabled. Only force file viewers on if persistent log files are available.' => 'Et edge- eller midlertidig miljø er oppdaget. Filvisere er skjult med mindre de tvinges på; runtime-logger er fortsatt tilgjengelige når de er aktivert. Tving bare filvisere på hvis vedvarende loggfiler er tilgjengelige.',
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
    'Include Categories' => 'Inkluder kategorier',
    'Exclude Categories' => 'Ekskluder kategorier',
    'Include Request User ID' => 'Inkluder forespørselens bruker-ID',
    'Advanced' => 'Avansert',
    'Configured Storage' => 'Konfigurert lagring',
    'Storage follows the Craft cache configuration. Redis database selection is configuration-only. This is not a connection test; confirm capture in Runtime Logs.' => 'Lagringen følger Crafts cachekonfigurasjon. Redis-databasen velges bare via konfigurasjon. Dette er ikke en tilkoblingstest; bekreft innsamling i runtime-logger.',
    'On multiple servers, use shared cache storage. Local file cache does not combine logs from other instances.' => 'Bruk delt cachelagring på flere servere. Lokal filcache kombinerer ikke logger fra andre instanser.',
    'Capture changes apply to new requests. Restart long-running workers to load changed settings. Disabling capture does not clear stored logs.' => 'Innsamlingsendringer gjelder nye forespørsler. Start langvarige arbeidsprosesser på nytt for å laste endrede innstillinger. Deaktivering av innsamling fjerner ikke lagrede logger.',
    'How often Runtime Logs refreshes automatically. Set to 0 to disable. Current: {duration}' => 'Hvor ofte runtime-logger oppdateres automatisk. Angi 0 for å deaktivere. Gjeldende: {duration}',
    'Choose which log categories to capture, not words in the message. Enter one category per line, such as {exact}, or use {prefix} to match categories starting with {start}. Leave empty to capture all categories.' => 'Velg hvilke loggkategorier som skal samles inn, ikke ord i meldingen. Angi én kategori per linje, for eksempel {exact}, eller bruk {prefix} for kategorier som begynner med {start}. La feltet stå tomt for å samle inn alle kategorier.',
    'Skip these log categories even if included above. Enter one per line, such as {exact} or {prefix}. Leave empty to add no category exclusions.' => 'Hopp over disse loggkategoriene selv om de er inkludert ovenfor. Angi én per linje, for eksempel {exact} eller {prefix}. La feltet stå tomt for ikke å legge til kategoriekskluderinger.',
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

    // Setup
    'Choose the log views that suit this environment. Runtime capture is optional and remains off until enabled.' => 'Velg loggvisningene som passer til dette miljøet. Runtime-innsamling er valgfri og forblir avslått til den aktiveres.',
    'Consider Runtime Logs for recent diagnostics on ephemeral hosting. Confirm shared storage before relying on logs from multiple instances.' => 'Vurder runtime-logger for nyere diagnostikk på midlertidig hosting. Bekreft delt lagring før du stoler på logger fra flere instanser.',

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
    'Clear Runtime Logs' => 'Tøm runtime-logger',
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
