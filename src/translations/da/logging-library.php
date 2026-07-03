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
    'View all system logs' => 'Vis alle systemlogfiler',
    'Download all system logs' => 'Download alle systemlogfiler',
    'Clear cache' => 'Ryd cache',
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
    'General Settings' => 'Generelle indstillinger',
    'Force Enable Log Viewers' => 'Tving aktivering af logvisere',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Tving aktivering af filbaserede logvisere, selv når et edge- eller flygtigt miljø registreres. Dette påvirker Logging Library og hvert plugins dedikerede Logfiler-sektion.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library registrerede et edge- eller flygtigt miljø, så filbaserede logvisere er skjult for den selvstændige <strong>Alle logfiler</strong>-visning og for hvert plugins dedikerede <strong>Logfiler</strong>-sektion. Hovedmenuviseren er ikke tilgængelig, før du aktiverer denne tilsidesættelse. Brug din hostingplatforms native logfiler, eller aktiver tilsidesættelsen, hvis vedvarende lagring er tilgængelig.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library registrerede et edge- eller flygtigt miljø, men filbaserede logvisere aktiveres med tvang. Denne tilsidesættelse påvirker den selvstændige <strong>Alle logfiler</strong>-visning og hvert plugins dedikerede <strong>Logfiler</strong>-sektion.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library tilføjer en samlet <strong>Alle logfiler</strong>-visning til kontrolpanelets hovedmenu. Individuelle plugins beholder stadig deres egne dedikerede <strong>Logfiler</strong>-sektioner.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Den samlede <strong>Alle logfiler</strong>-visning er skjult fra kontrolpanelets hovedmenu. Individuelle plugins beholder stadig deres egne dedikerede <strong>Logfiler</strong>-sektioner.',
    'Show Main Menu' => 'Vis hovedmenu',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Vis Logging Library i kontrolpanelets hovednavigation som en samlet Alle logfiler-visning, når filbaserede logvisere er tilgængelige.',

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
    'Clear Runtime Logs' => 'Ryd runtime-logge',
    'Loading' => 'Indlæser',
    'Download File' => 'Download fil',
    'Log Location' => 'Logplacering',
    'Runtime Store' => 'Runtime-lager',
    'Craft cache' => 'Craft-cache',
    'Redis ({cache})' => 'Redis ({cache})',
    'Runtime Location' => 'Runtime-placering',
    'Recent runtime logs are stored in Craft cache and are intended for short-lived diagnostics, not complete log history.' => 'Seneste runtime-logfiler lagres i Craft-cache og er beregnet til kortlivede diagnosticeringer, ikke komplet loghistorik.',

    // Config overrides
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Dette tilsidesættes af indstillingen <code>forceEnableLogViewer</code> i <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Dette tilsidesættes af indstillingen <code>showCpSection</code> i <code>config/logging-library.php</code>.',
];
