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
    'Inspect system logs, review plugin logging output, and centralize diagnostics from one control panel workspace.' => 'Systemprotokolle einsehen, Plugin-Protokollausgaben überprüfen und Diagnosen über einen zentralen Arbeitsbereich im Control Panel zentralisieren.',
    'Open All Logs' => 'Alle Protokolle öffnen',

    // Navigation
    'All Logs' => 'Alle Protokolle',
    'Logs' => 'Protokolle',
    'Settings' => 'Einstellungen',
    'System Logs' => 'Systemprotokolle',
    'System' => 'System',
    'General' => 'Allgemein',
    'Interface' => 'Oberfläche',

    // Log levels
    'All Levels' => 'Alle Stufen',
    'Error' => 'Fehler',
    'Warning' => 'Warnung',
    'Info' => 'Info',
    'Debug' => 'Debug',

    // Log sources
    'All Sources' => 'Alle Quellen',
    'Web' => 'Web',
    'Console' => 'Konsole',
    'Queue' => 'Queue',
    'PHP Errors' => 'PHP-Fehler',
    'Other' => 'Sonstige',

    // Filters
    'Select File' => 'Datei auswählen',
    'Select Date' => 'Datum auswählen',
    'Search messages and context...' => 'Nachrichten und Kontext durchsuchen...',

    // Table
    'Time' => 'Zeit',
    'Level' => 'Stufe',
    'Source' => 'Quelle',
    'User' => 'Benutzer',
    'Message' => 'Nachricht',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'Keine Protokolldateien gefunden. Protokolldateien werden erstellt, wenn Plugin-Aktivitäten auftreten.',
    'No log entries found for the selected filters.' => 'Keine Protokolleinträge für die ausgewählten Filter gefunden.',

    // Pagination
    'entry' => 'Eintrag',
    'entries' => 'Einträge',

    // Row detail
    'Context' => 'Kontext',
    'No context data available.' => 'Keine Kontextdaten verfügbar.',

    // Sidebar
    'Current Level' => 'Aktuelle Stufe',
    'Current log level' => 'Aktuelle Protokollierungsstufe',
    'Retention' => 'Aufbewahrung',
    'days' => 'Tage',
    'Available Logs' => 'Verfügbare Protokolle',
    'file' => 'Datei',
    'files' => 'Dateien',
    'Current File' => 'Aktuelle Datei',
    'Entries' => 'Einträge',
    'Download File' => 'Datei herunterladen',
    'Log Location' => 'Protokollspeicherort',

    // Common
    'Save Settings' => 'Einstellungen speichern',

    // Controller messages
    'Settings saved.' => 'Einstellungen gespeichert.',
    'Could not save settings.' => 'Einstellungen konnten nicht gespeichert werden.',

    // Validation messages
    'Value must be a whole number.' => 'Der Wert muss eine ganze Zahl sein.',

    // Settings: General
    'General Settings' => 'Allgemeine Einstellungen',
    'Plugin Name' => 'Plugin-Name',
    'The name of the plugin as it appears in the Control Panel menu' => 'Der Name des Plugins, wie er im Control-Panel-Menü angezeigt wird',
    'This is being overridden by the <code>pluginName</code> setting in <code>config/logging-library.php</code>.' => 'Dies wird durch die Einstellung <code>pluginName</code> in <code>config/logging-library.php</code> überschrieben.',
    'Force Enable Log Viewers' => 'Protokoll-Viewer erzwingen',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Dateibasierte Protokoll-Viewer erzwingen, auch wenn eine Edge- oder ephemere Umgebung erkannt wird. Dies betrifft Logging Library und den dedizierten Protokollbereich jedes Plugins.',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Dies wird durch die Einstellung <code>forceEnableLogViewer</code> in <code>config/logging-library.php</code> überschrieben.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library hat eine Edge- oder ephemere Umgebung erkannt, daher sind dateibasierte Protokoll-Viewer für die eigenständige <strong>Alle Protokolle</strong>-Ansicht und den dedizierten <strong>Protokolle</strong>-Bereich jedes Plugins ausgeblendet. Der Hauptmenü-Viewer ist erst verfügbar, wenn Sie diese Überschreibung aktivieren. Verwenden Sie die nativen Protokolle Ihrer Hosting-Plattform oder aktivieren Sie die Überschreibung, wenn persistenter Speicher verfügbar ist.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library hat eine Edge- oder ephemere Umgebung erkannt, aber dateibasierte Protokoll-Viewer werden erzwungen aktiviert. Diese Überschreibung betrifft die eigenständige <strong>Alle Protokolle</strong>-Ansicht und den dedizierten <strong>Protokolle</strong>-Bereich jedes Plugins.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library fügt dem Hauptmenü des Control Panels eine konsolidierte <strong>Alle Protokolle</strong>-Ansicht hinzu. Einzelne Plugins behalten weiterhin ihre eigenen dedizierten <strong>Protokolle</strong>-Bereiche.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Die konsolidierte <strong>Alle Protokolle</strong>-Ansicht ist im Hauptmenü des Control Panels ausgeblendet. Einzelne Plugins behalten weiterhin ihre eigenen dedizierten <strong>Protokolle</strong>-Bereiche.',
    'Show Main Menu' => 'Hauptmenü anzeigen',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Logging Library in der Hauptnavigation des Control Panels als konsolidierte Alle Protokolle-Ansicht anzeigen, wenn dateibasierte Protokoll-Viewer verfügbar sind.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Dies wird durch die Einstellung <code>showCpSection</code> in <code>config/logging-library.php</code> überschrieben.',

    // Settings: Interface
    'Interface Settings' => 'Oberflächeneinstellungen',
    'Items Per Page' => 'Einträge pro Seite',
    'Number of log entries to display per page in the log viewers' => 'Anzahl der Protokolleinträge, die pro Seite in den Protokoll-Ansichten angezeigt werden',
    'This is being overridden by the <code>itemsPerPage</code> setting in <code>config/logging-library.php</code>.' => 'Dies wird durch die Einstellung <code>itemsPerPage</code> in <code>config/logging-library.php</code> überschrieben.',
];
