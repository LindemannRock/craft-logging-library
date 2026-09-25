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
    'Open Settings' => 'Åbn indstillinger',

    // Navigation
    'File Logs' => 'Fillogge',
    'All Logs' => 'Alle logfiler',
    'Runtime Logs' => 'Runtime-logge',
    'Logs' => 'Logfiler',
    'Settings' => 'Indstillinger',
    'System Logs' => 'Systemlogfiler',
    'System' => 'System',
    'Plugins' => 'Plugins',
    'General' => 'Generelt',
    'Interface' => 'Brugerflade',

    // Permissions
    'View all file logs' => 'Vis alle fillogge',
    'Download all file logs' => 'Download alle fillogge',
    'Clear file log cache' => 'Ryd filloggenes cache',
    'View runtime logs' => 'Vis runtime-logge',
    'Manage settings' => 'Administrer indstillinger',

    // Common
    '{displayName} caches' => '{displayName}-caches',

    // Controller messages
    'Settings saved.' => 'Indstillinger gemt.',
    'Could not save settings.' => 'Kunne ikke gemme indstillingerne.',
    'Log cache refreshed.' => 'Log-cache opdateret.',
    'Failed to refresh log cache.' => 'Log-cache kunne ikke opdateres.',
    'Recent runtime logs cleared.' => 'Seneste runtime-logge ryddet.',
    'Unable to clear recent runtime logs.' => 'De seneste runtime-logge kunne ikke ryddes.',
    'Plugin logging not configured' => 'Plugin-logning er ikke konfigureret',
    'Log viewer is disabled for this plugin' => 'Logviseren er deaktiveret for dette plugin',
    'Log viewer is disabled for this environment' => 'Logviseren er deaktiveret for dette miljø',
    'Recent runtime logs are disabled' => 'Seneste runtime-logfiler er deaktiveret',
    'Log file not found' => 'Logfilen blev ikke fundet',
    'Unable to determine plugin handle from URL' => 'Plugin-handle kunne ikke bestemmes ud fra URL',
    'User does not have permission to view logs' => 'Brugeren har ikke tilladelse til at vise logfiler',

    // Settings: General
    'Show Logging Library in the main navigation. This does not enable or disable log capture.' => 'Vis Logging Library i hovednavigationen. Dette aktiverer eller deaktiverer ikke logindsamling.',
    'General Settings' => 'Generelle indstillinger',
    'Force Enable Log Viewers' => 'Tving aktivering af logvisere',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Tving aktivering af filbaserede logvisere, selv når et edge- eller flygtigt miljø registreres. Dette påvirker Logging Library og hvert plugins dedikerede Logfiler-sektion.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library registrerede et edge- eller flygtigt miljø, så filbaserede logvisere er skjult for den selvstændige <strong>Alle logfiler</strong>-visning og for hvert plugins dedikerede <strong>Logfiler</strong>-sektion. Hovedmenuviseren er ikke tilgængelig, før du aktiverer denne tilsidesættelse. Brug din hostingplatforms native logfiler, eller aktiver tilsidesættelsen, hvis vedvarende lagring er tilgængelig.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library registrerede et edge- eller flygtigt miljø, men filbaserede logvisere aktiveres med tvang. Denne tilsidesættelse påvirker den selvstændige <strong>Alle logfiler</strong>-visning og hvert plugins dedikerede <strong>Logfiler</strong>-sektion.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library tilføjer en samlet <strong>Alle logfiler</strong>-visning til kontrolpanelets hovedmenu. Individuelle plugins beholder stadig deres egne dedikerede <strong>Logfiler</strong>-sektioner.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Den samlede <strong>Alle logfiler</strong>-visning er skjult fra kontrolpanelets hovedmenu. Individuelle plugins beholder stadig deres egne dedikerede <strong>Logfiler</strong>-sektioner.',
    'Show Main Menu' => 'Vis hovedmenu',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Vis Logging Library i kontrolpanelets hovednavigation som en samlet Alle logfiler-visning, når filbaserede logvisere er tilgængelige.',

    // Settings: File Logs
    'Force Enable File Log Viewers' => 'Tving aktivering af fillogvisere',
    'An edge or ephemeral environment is detected. File viewers are hidden unless forced on; {runtimeLogs} remain available when enabled. Only force file viewers on if persistent log files are available.' => 'Et edge- eller midlertidigt miljø er registreret. Filvisere er skjult, medmindre de tvinges til; {runtimeLogs} er fortsat tilgængelige, når de er aktiveret. Tving kun filvisere til, hvis der findes vedvarende logfiler.',
    'File viewers are available. Runtime Logs are optional and independent of file logging.' => 'Filvisere er tilgængelige. Runtime-logge er valgfrie og uafhængige af fillogning.',

    // Settings: Runtime Logs
    'Min: {min}, Max: {max}' => 'Min: {min}, Maks: {max}',
    'Uses the application cache configuration. The Redis database can be overridden in {file}. To verify capture, trigger a log message and check Runtime Logs.' => 'Bruger applikationscachens konfiguration. Redis-databasen kan tilsidesættes i {file}. Generer en logbesked, og kontrollér runtime-logge for at bekræfte indsamlingen.',
    'Enable Runtime Logs' => 'Aktiver runtime-logge',
    'Skip Console Requests' => 'Spring konsolanmodninger over',
    'Skip Queue Requests' => 'Spring køanmodninger over',
    'Retention (seconds)' => 'Opbevaring (sekunder)',
    'Maximum Entries' => 'Maksimalt antal poster',
    'Refresh Interval (seconds)' => 'Opdateringsinterval (sekunder)',
    'Maximum Message Bytes' => 'Maksimalt antal bytes pr. besked',
    'Maximum Context Bytes' => 'Maksimalt antal bytes for kontekst',
    'Captured Levels' => 'Indsamlede niveauer',
    'Include Sources and Categories' => 'Medtag kilder og kategorier',
    'Exclude Sources and Categories' => 'Udelad kilder og kategorier',
    'Category: {pattern}' => 'Kategori: {pattern}',
    'Include Request User ID' => 'Medtag anmodningens bruger-ID',
    'Advanced' => 'Avanceret',
    'Configured Storage' => 'Konfigureret lagring',
    'Capture new messages in Runtime Logs. Changes apply to new requests; restart long-running workers to apply them. Turning this off does not delete existing logs.' => 'Indsaml nye beskeder i runtime-logge. Ændringer gælder nye anmodninger; genstart langvarige arbejdsprocesser for at anvende dem. Deaktivering sletter ikke eksisterende logge.',
    'Limits the message text kept for each new Runtime Logs entry, in bytes rather than characters. Longer messages are shortened. File logs are unaffected.' => 'Begrænser den beskedtekst, der gemmes for hver ny post i runtime-logge, i bytes frem for tegn. Længere beskeder forkortes. Fillogge påvirkes ikke.',
    'Limits the extra data kept for each new Runtime Logs entry, such as error details and stack traces, in bytes after JSON encoding. Larger context is shortened. File logs are unaffected.' => 'Begrænser de ekstra data, der gemmes for hver ny post i runtime-logge, såsom fejloplysninger og stakspor, i bytes efter JSON-kodning. Større kontekst forkortes. Fillogge påvirkes ikke.',
    'How often Runtime Logs refreshes automatically. Set to 0 to disable. Current: {duration}' => 'Hvor ofte runtime-logge opdateres automatisk. Angiv 0 for at deaktivere. Aktuelt: {duration}',
    'Choose sources by name or type a category pattern such as {pattern} and press Enter. Leave empty to capture all sources. Changes affect new messages only.' => 'Vælg kilder efter navn, eller angiv et kategorimønster som {pattern}, og tryk på Enter. Lad feltet være tomt for at indsamle alle kilder. Ændringer gælder kun nye beskeder.',
    'Choose sources to skip or type a category pattern and press Enter. Exclusions take precedence. Existing entries are not removed.' => 'Vælg kilder, der skal springes over, eller angiv et kategorimønster, og tryk på Enter. Udeladelser har forrang. Eksisterende poster fjernes ikke.',
    'When on, Runtime Logs skips command-line requests. Turn off only when diagnosing console commands; file and hosted logs are unaffected.' => 'Når dette er aktiveret, springer runtime-logge kommandolinjeanmodninger over. Deaktiver kun ved diagnosticering af konsolkommandoer; fillogge og hostinglogge påvirkes ikke.',
    'When on, Runtime Logs skips detected queue execution. To capture console queue workers, turn off both skip switches and restart the workers. Workers can generate large volumes of logs.' => 'Når dette er aktiveret, springer runtime-logge registreret køudførelse over. For at indsamle logge fra konsollens køprocesser skal du deaktivere begge spring over-indstillinger og genstarte processerne. Arbejdsprocesser kan generere store logmængder.',
    'Adds the authenticated request user ID. Messages and context may still contain personal data regardless of this setting.' => 'Tilføjer ID for anmodningens godkendte bruger. Beskeder og kontekst kan indeholde personoplysninger uanset denne indstilling.',

    'Maximum age of runtime entries, in seconds. Current: {duration}' => 'Maksimal alder for runtime-logposter i sekunder. Aktuelt: {duration}',
    'Min: {min} ({minDuration}), Max: {max} ({maxDuration})' => 'Min: {min} ({minDuration}), Maks: {max} ({maxDuration})',
    '{count} second' => '{count} sekund',
    '{count} seconds' => '{count} sekunder',
    '{count} minute' => '{count} minut',
    '{count} minutes' => '{count} minutter',
    '{count} hour' => '{count} time',
    '{count} hours' => '{count} timer',
    '{count} day' => '{count} dag',
    '{count} days' => '{count} dage',

    // Settings: Interface
    'Interface Settings' => 'Brugerflade-indstillinger',

    // Log levels
    'All Levels' => 'Alle niveauer',
    'Error' => 'Fejl',
    'Warning' => 'Advarsel',
    'Info' => 'Info',
    'Debug' => 'Debug',
    'Unknown' => 'Ukendt',

    // Log sources
    'All Sources' => 'Alle kilder',
    'Web' => 'Web',
    'Console' => 'Konsol',
    'Queue' => 'Queue',
    'PHP Errors' => 'PHP-fejl',
    'Other' => 'Andet',
    'DB Queries' => 'DB-forespørgsler',
    'DB Commands' => 'DB-kommandoer',
    'DB Command::{method}' => 'DB-kommando::{method}',
    'DB Connection' => 'DB-forbindelse',
    'DB Connection::{method}' => 'DB-forbindelse::{method}',
    'Redis Commands' => 'Redis-kommandoer',
    'Redis Connection' => 'Redis-forbindelse',
    'Redis Connection::{method}' => 'Redis-forbindelse::{method}',
    'URL Routing' => 'URL-routing',
    'Web Request' => 'Webanmodning',
    'Session' => 'Session',
    'Template Rendering' => 'Skabelongengivelse',
    'Modules' => 'Moduler',
    'Integration Service' => 'Integrationstjeneste',

    // Filters
    'Select File' => 'Vælg fil',
    'Select Date' => 'Vælg dato',
    'Search messages and context...' => 'Søg i beskeder og kontekst...',

    // Table
    'Time' => 'Tid',
    'Level' => 'Niveau',
    'Source' => 'Kilde',
    'User' => 'Bruger',
    'Request User' => 'Anmodningsbruger',
    'User #{id}' => 'Bruger #{id}',
    'Message' => 'Besked',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'Ingen logfiler fundet. Logfiler oprettes, når plugin-aktiviteter forekommer.',
    'No recent runtime logs found. Runtime logs are short-lived and only appear after matching events are captured.' => 'Ingen seneste runtime-logfiler fundet. Runtime-logfiler er kortlivede og vises kun, når matchende hændelser er registreret.',
    'No log entries found for the selected filters.' => 'Ingen logposter fundet for de valgte filtre.',

    // Pagination
    'entry' => 'post',
    'entries' => 'poster',

    // Sidebar
    'Current Level' => 'Nuværende niveau',
    'Current log level' => 'Nuværende logniveau',
    'Retention' => 'Opbevaring',
    'days' => 'dage',
    'Available Logs' => 'Tilgængelige logfiler',
    'file' => 'fil',
    'files' => 'filer',
    'Current File' => 'Nuværende fil',
    'Log entries' => 'Logposter',
    'Refresh Cache' => 'Opdater cache',
    'Clear runtime logs' => 'Ryd runtime-logge',
    'Clear recent runtime logs? This cannot be undone.' => 'Ryd de seneste runtime-logge? Dette kan ikke fortrydes.',
    'Loading' => 'Indlæser',
    'Download File' => 'Download fil',
    'Log Location' => 'Logplacering',
    'Runtime Store' => 'Runtime-lager',
    'Craft cache' => 'Craft-cache',
    'Redis unavailable' => 'Redis ikke tilgængelig',
    'Redis (SELECT disabled)' => 'Redis (SELECT deaktiveret)',
    'Redis database {database}' => 'Redis-database {database}',
    'Runtime Location' => 'Runtime-placering',
    'Dedicated Redis key' => 'Dedikeret Redis-nøgle',
    'Recent runtime logs use a bounded diagnostic store and are not complete log history.' => 'Seneste runtime-logfiler bruger et begrænset diagnosticeringslager og er ikke en komplet loghistorik.',

    // Config overrides
    'This is being overridden by the <code>{setting}</code> setting in <code>config/logging-library.php</code>.' => 'Dette tilsidesættes af indstillingen <code>{setting}</code> i <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Dette tilsidesættes af indstillingen <code>forceEnableLogViewer</code> i <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Dette tilsidesættes af indstillingen <code>showCpSection</code> i <code>config/logging-library.php</code>.',
];
