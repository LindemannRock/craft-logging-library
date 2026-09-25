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
    'File Logs' => 'Filloggar',
    'All Logs' => 'Alla loggar',
    'Runtime Logs' => 'Runtime-loggar',
    'Logs' => 'Loggar',
    'Settings' => 'Inställningar',
    'System Logs' => 'Systemloggar',
    'System' => 'System',
    'Plugins' => 'Plugins',
    'General' => 'Allmänt',
    'Interface' => 'Gränssnitt',

    // Permissions
    'View all file logs' => 'Visa alla filloggar',
    'Download all file logs' => 'Ladda ner alla filloggar',
    'Clear file log cache' => 'Rensa filloggarnas cache',
    'View runtime logs' => 'Visa runtime-loggar',
    'Manage settings' => 'Hantera inställningar',

    // Common
    '{displayName} caches' => '{displayName}-cacher',

    // Controller messages
    'Settings saved.' => 'Inställningar sparade.',
    'Could not save settings.' => 'Det gick inte att spara inställningarna.',
    'Log cache refreshed.' => 'Loggcachen uppdaterades.',
    'Failed to refresh log cache.' => 'Det gick inte att uppdatera loggcachen.',
    'Recent runtime logs cleared.' => 'Senaste runtime-loggarna rensades.',
    'Unable to clear recent runtime logs.' => 'Det gick inte att rensa de senaste runtime-loggarna.',
    'Plugin logging not configured' => 'Plugin-loggning är inte konfigurerad',
    'Log viewer is disabled for this plugin' => 'Loggvisaren är inaktiverad för detta plugin',
    'Log viewer is disabled for this environment' => 'Loggvisaren är inaktiverad för denna miljö',
    'Recent runtime logs are disabled' => 'Senaste runtime-loggar är inaktiverade',
    'Log file not found' => 'Loggfilen hittades inte',
    'Unable to determine plugin handle from URL' => 'Det gick inte att fastställa plugin-handle från URL',
    'User does not have permission to view logs' => 'Användaren har inte behörighet att visa loggar',

    // Settings: General
    'Show Logging Library in the main navigation. This does not enable or disable log capture.' => 'Visa Logging Library i huvudnavigeringen. Detta aktiverar eller inaktiverar inte logginsamling.',
    'General Settings' => 'Allmänna inställningar',
    'Force Enable Log Viewers' => 'Tvinga aktivering av loggvisare',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Tvinga aktivering av filbaserade loggvisare även när en edge- eller tillfällig miljö identifieras. Detta påverkar Logging Library och varje plugins dedikerade Loggar-avsnitt.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library identifierade en edge- eller tillfällig miljö, vilket innebär att filbaserade loggvisare är dolda för den fristående <strong>Alla loggar</strong>-vyn och för varje plugins dedikerade <strong>Loggar</strong>-avsnitt. Huvudmenyvisaren är inte tillgänglig förrän du aktiverar denna åsidosättning. Använd din hostingplattforms inbyggda loggar, eller aktivera åsidosättningen om beständig lagring är tillgänglig.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library identifierade en edge- eller tillfällig miljö, men filbaserade loggvisare aktiveras tvångsvis. Denna åsidosättning påverkar den fristående <strong>Alla loggar</strong>-vyn och varje plugins dedikerade <strong>Loggar</strong>-avsnitt.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library lägger till en samlad <strong>Alla loggar</strong>-vy i kontrollpanelens huvudmeny. Enskilda plugins behåller fortfarande sina egna dedikerade <strong>Loggar</strong>-avsnitt.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Den samlade <strong>Alla loggar</strong>-vyn är dold från kontrollpanelens huvudmeny. Enskilda plugins behåller fortfarande sina egna dedikerade <strong>Loggar</strong>-avsnitt.',
    'Show Main Menu' => 'Visa huvudmeny',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Visa Logging Library i kontrollpanelens huvudnavigering som en samlad Alla loggar-vy när filbaserade loggvisare är tillgängliga.',

    // Settings: File Logs
    'Force Enable File Log Viewers' => 'Tvinga aktivering av filloggvisare',
    'An edge or ephemeral environment is detected. File viewers are hidden unless forced on; {runtimeLogs} remain available when enabled. Only force file viewers on if persistent log files are available.' => 'En edge- eller tillfällig miljö har upptäckts. Filvisare är dolda om de inte tvingas på; {runtimeLogs} är fortsatt tillgängliga när de är aktiverade. Tvinga bara på filvisare om beständiga loggfiler finns.',
    'File viewers are available. Runtime Logs are optional and independent of file logging.' => 'Filvisare är tillgängliga. Runtime-loggar är valfria och oberoende av filloggning.',

    // Settings: Runtime Logs
    'Min: {min}, Max: {max}' => 'Min: {min}, Max: {max}',
    'Uses the application cache configuration. The Redis database can be overridden in {file}. To verify capture, trigger a log message and check Runtime Logs.' => 'Använder applikationscachens konfiguration. Redis-databasen kan åsidosättas i {file}. Generera ett loggmeddelande och kontrollera runtime-loggar för att verifiera insamlingen.',
    'Enable Runtime Logs' => 'Aktivera runtime-loggar',
    'Skip Console Requests' => 'Hoppa över konsolbegäranden',
    'Skip Queue Requests' => 'Hoppa över köbegäranden',
    'Retention (seconds)' => 'Lagringstid (sekunder)',
    'Maximum Entries' => 'Maximalt antal poster',
    'Refresh Interval (seconds)' => 'Uppdateringsintervall (sekunder)',
    'Maximum Message Bytes' => 'Maximalt antal byte per meddelande',
    'Maximum Context Bytes' => 'Maximalt antal byte för kontext',
    'Captured Levels' => 'Insamlade nivåer',
    'Include Sources and Categories' => 'Inkludera källor och kategorier',
    'Exclude Sources and Categories' => 'Exkludera källor och kategorier',
    'Category: {pattern}' => 'Kategori: {pattern}',
    'Include Request User ID' => 'Inkludera begärandens användar-ID',
    'Advanced' => 'Avancerat',
    'Configured Storage' => 'Konfigurerad lagring',
    'Capture new messages in Runtime Logs. Changes apply to new requests; restart long-running workers to apply them. Turning this off does not delete existing logs.' => 'Samla in nya meddelanden i runtime-loggar. Ändringar gäller nya begäranden; starta om långvariga arbetsprocesser för att tillämpa dem. Befintliga loggar tas inte bort när detta inaktiveras.',
    'Limits the message text kept for each new Runtime Logs entry, in bytes rather than characters. Longer messages are shortened. File logs are unaffected.' => 'Begränsar meddelandetexten som sparas för varje ny post i runtime-loggar, i byte i stället för tecken. Längre meddelanden kortas ned. Filloggar påverkas inte.',
    'Limits the extra data kept for each new Runtime Logs entry, such as error details and stack traces, in bytes after JSON encoding. Larger context is shortened. File logs are unaffected.' => 'Begränsar extra data som sparas för varje ny post i runtime-loggar, som feldetaljer och stackspårningar, i byte efter JSON-kodning. Större kontext kortas ned. Filloggar påverkas inte.',
    'How often Runtime Logs refreshes automatically. Set to 0 to disable. Current: {duration}' => 'Hur ofta runtime-loggar uppdateras automatiskt. Ange 0 för att inaktivera. Aktuellt: {duration}',
    'Choose sources by name or type a category pattern such as {pattern} and press Enter. Leave empty to capture all sources. Changes affect new messages only.' => 'Välj källor efter namn eller ange ett kategorimönster som {pattern} och tryck på Enter. Lämna tomt för att samla in alla källor. Ändringar gäller endast nya meddelanden.',
    'Choose sources to skip or type a category pattern and press Enter. Exclusions take precedence. Existing entries are not removed.' => 'Välj källor att hoppa över eller ange ett kategorimönster och tryck på Enter. Exkluderingar har företräde. Befintliga poster tas inte bort.',
    'When on, Runtime Logs skips command-line requests. Turn off only when diagnosing console commands; file and hosted logs are unaffected.' => 'När detta är aktiverat hoppar runtimeloggar över kommandoradsförfrågningar. Inaktivera endast vid diagnostik av konsolkommandon; filloggar och värdtjänstens loggar påverkas inte.',
    'When on, Runtime Logs skips detected queue execution. To capture console queue workers, turn off both skip switches and restart the workers. Workers can generate large volumes of logs.' => 'När detta är aktiverat hoppar runtimeloggar över upptäckt kökörning. För att samla in loggar från konsolens köarbetare, inaktivera båda hoppa över-reglagen och starta om arbetsprocesserna. Arbetsprocesser kan generera stora loggmängder.',
    'Adds the authenticated request user ID. Messages and context may still contain personal data regardless of this setting.' => 'Lägger till den autentiserade begärandeanvändarens ID. Meddelanden och kontext kan innehålla personuppgifter oavsett denna inställning.',

    'Maximum age of runtime entries, in seconds. Current: {duration}' => 'Maximal ålder för runtimeloggposter, i sekunder. Aktuellt: {duration}',
    'Min: {min} ({minDuration}), Max: {max} ({maxDuration})' => 'Min: {min} ({minDuration}), Max: {max} ({maxDuration})',
    '{count} second' => '{count} sekund',
    '{count} seconds' => '{count} sekunder',
    '{count} minute' => '{count} minut',
    '{count} minutes' => '{count} minuter',
    '{count} hour' => '{count} timme',
    '{count} hours' => '{count} timmar',
    '{count} day' => '{count} dag',
    '{count} days' => '{count} dagar',

    // Settings: Interface
    'Interface Settings' => 'Gränssnittsinställningar',

    // Log levels
    'All Levels' => 'Alla nivåer',
    'Error' => 'Fel',
    'Warning' => 'Varning',
    'Info' => 'Info',
    'Debug' => 'Debug',
    'Unknown' => 'Okänt',

    // Log sources
    'All Sources' => 'Alla källor',
    'Web' => 'Web',
    'Console' => 'Konsol',
    'Queue' => 'Queue',
    'PHP Errors' => 'PHP-fel',
    'Other' => 'Övrigt',
    'DB Queries' => 'DB-frågor',
    'DB Commands' => 'DB-kommandon',
    'DB Command::{method}' => 'DB-kommando::{method}',
    'DB Connection' => 'DB-anslutning',
    'DB Connection::{method}' => 'DB-anslutning::{method}',
    'Redis Commands' => 'Redis-kommandon',
    'Redis Connection' => 'Redis-anslutning',
    'Redis Connection::{method}' => 'Redis-anslutning::{method}',
    'URL Routing' => 'URL-routning',
    'Web Request' => 'Webbförfrågan',
    'Session' => 'Session',
    'Template Rendering' => 'Mallrendering',
    'Modules' => 'Moduler',
    'Integration Service' => 'Integrationstjänst',

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
    'Clear runtime logs' => 'Rensa runtime-loggar',
    'Clear recent runtime logs? This cannot be undone.' => 'Rensa de senaste runtime-loggarna? Detta kan inte ångras.',
    'Loading' => 'Läser in',
    'Download File' => 'Ladda ner fil',
    'Log Location' => 'Loggplats',
    'Runtime Store' => 'Runtime-lagring',
    'Craft cache' => 'Craft-cache',
    'Redis unavailable' => 'Redis inte tillgängligt',
    'Redis (SELECT disabled)' => 'Redis (SELECT inaktiverat)',
    'Redis database {database}' => 'Redis-databas {database}',
    'Runtime Location' => 'Runtime-plats',
    'Dedicated Redis key' => 'Dedikerad Redis-nyckel',
    'Recent runtime logs use a bounded diagnostic store and are not complete log history.' => 'Senaste runtime-loggar använder en begränsad diagnostiklagring och är inte en fullständig logghistorik.',

    // Config overrides
    'This is being overridden by the <code>{setting}</code> setting in <code>config/logging-library.php</code>.' => 'Detta åsidosätts av inställningen <code>{setting}</code> i <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Detta åsidosätts av inställningen <code>forceEnableLogViewer</code> i <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Detta åsidosätts av inställningen <code>showCpSection</code> i <code>config/logging-library.php</code>.',
];
