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

    // Navigation
    'All Logs' => 'Alle logger',
    'Logs' => 'Logger',
    'Settings' => 'Innstillinger',
    'System Logs' => 'Systemlogger',
    'System' => 'System',
    'General' => 'Generelt',
    'Interface' => 'Grensesnitt',

    // Log levels
    'All Levels' => 'Alle nivåer',
    'Error' => 'Feil',
    'Warning' => 'Advarsel',
    'Info' => 'Info',
    'Debug' => 'Debug',

    // Log sources
    'All Sources' => 'Alle kilder',
    'Web' => 'Web',
    'Console' => 'Konsoll',
    'Queue' => 'Queue',
    'PHP Errors' => 'PHP-feil',
    'Other' => 'Annet',

    // Filters
    'Select File' => 'Velg fil',
    'Select Date' => 'Velg dato',
    'Search messages and context...' => 'Søk i meldinger og kontekst...',

    // Table
    'Time' => 'Tid',
    'Level' => 'Nivå',
    'Source' => 'Kilde',
    'User' => 'Bruker',
    'Message' => 'Melding',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'Ingen loggfiler funnet. Loggfiler opprettes når plugin-aktiviteter oppstår.',
    'No log entries found for the selected filters.' => 'Ingen loggoppføringer funnet for de valgte filtrene.',

    // Pagination
    'entry' => 'oppføring',
    'entries' => 'oppføringer',

    // Row detail
    'Context' => 'Kontekst',
    'No context data available.' => 'Ingen kontekstdata tilgjengelig.',

    // Sidebar
    'Current Level' => 'Gjeldende nivå',
    'Current log level' => 'Gjeldende loggnivå',
    'Retention' => 'Oppbevaring',
    'days' => 'dager',
    'Available Logs' => 'Tilgjengelige logger',
    'file' => 'fil',
    'files' => 'filer',
    'Current File' => 'Gjeldende fil',
    'Entries' => 'Oppføringer',
    'Download File' => 'Last ned fil',
    'Log Location' => 'Loggplassering',

    // Common
    'Save Settings' => 'Lagre innstillinger',

    // Controller messages
    'Settings saved.' => 'Innstillinger lagret.',
    'Could not save settings.' => 'Kunne ikke lagre innstillinger.',

    // Validation messages
    'Value must be a whole number.' => 'Verdien må være et heltall.',

    // Settings: General
    'General Settings' => 'Generelle innstillinger',
    'Plugin Name' => 'Plugin-navn',
    'The name of the plugin as it appears in the Control Panel menu' => 'Navnet på plugin-programmet slik det vises i kontrollpanelets meny',
    'This is being overridden by the <code>pluginName</code> setting in <code>config/logging-library.php</code>.' => 'Dette overstyres av innstillingen <code>pluginName</code> i <code>config/logging-library.php</code>.',
    'Force Enable Log Viewers' => 'Tving aktivering av loggvisere',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Tving aktivering av filbaserte loggvisere selv når et edge- eller flyktig miljø oppdages. Dette påvirker Logging Library og hvert plugins dedikerte Logger-seksjon.',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Dette overstyres av innstillingen <code>forceEnableLogViewer</code> i <code>config/logging-library.php</code>.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library oppdaget et edge- eller flyktig miljø, så filbaserte loggvisere er skjult for den frittstående <strong>Alle logger</strong>-visningen og for hvert plugins dedikerte <strong>Logger</strong>-seksjon. Hovedmenyviseren er ikke tilgjengelig før du aktiverer denne overstyringen. Bruk din hostingplattforms innebygde logger, eller aktiver overstyringen hvis vedvarende lagring er tilgjengelig.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library oppdaget et edge- eller flyktig miljø, men filbaserte loggvisere aktiveres med tvang. Denne overstyringen påvirker den frittstående <strong>Alle logger</strong>-visningen og hvert plugins dedikerte <strong>Logger</strong>-seksjon.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library legger til en samlet <strong>Alle logger</strong>-visning i kontrollpanelets hovedmeny. Individuelle plugins beholder fortsatt sine egne dedikerte <strong>Logger</strong>-seksjoner.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Den samlede <strong>Alle logger</strong>-visningen er skjult fra kontrollpanelets hovedmeny. Individuelle plugins beholder fortsatt sine egne dedikerte <strong>Logger</strong>-seksjoner.',
    'Show Main Menu' => 'Vis hovedmeny',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Vis Logging Library i kontrollpanelets hovednavigasjon som en samlet Alle logger-visning når filbaserte loggvisere er tilgjengelige.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Dette overstyres av innstillingen <code>showCpSection</code> i <code>config/logging-library.php</code>.',

    // Settings: Interface
    'Interface Settings' => 'Grensesnittinnstillinger',
    'Items Per Page' => 'Elementer per side',
    'Number of log entries to display per page in the log viewers' => 'Antall loggoppføringer som skal vises per side i loggviserne',
    'This is being overridden by the <code>itemsPerPage</code> setting in <code>config/logging-library.php</code>.' => 'Dette overstyres av innstillingen <code>itemsPerPage</code> i <code>config/logging-library.php</code>.',
];
