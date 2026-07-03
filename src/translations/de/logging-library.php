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
    'Open Settings' => 'Einstellungen öffnen',

    // Navigation
    'All Logs' => 'Alle Protokolle',
    'Runtime Logs' => 'Laufzeitprotokolle',
    'Logs' => 'Protokolle',
    'Settings' => 'Einstellungen',
    'System Logs' => 'Systemprotokolle',
    'System' => 'System',
    'Plugins' => 'Plugins',
    'General' => 'Allgemein',
    'Interface' => 'Oberfläche',

    // Permissions
    'View all system logs' => 'Alle Systemprotokolle anzeigen',
    'Download all system logs' => 'Alle Systemprotokolle herunterladen',
    'Clear cache' => 'Cache leeren',
    'Manage settings' => 'Einstellungen verwalten',

    // Common
    '{displayName} caches' => '{displayName} Caches',

    // Controller messages
    'Settings saved.' => 'Einstellungen gespeichert.',
    'Could not save settings.' => 'Einstellungen konnten nicht gespeichert werden.',
    'Log cache refreshed.' => 'Protokoll-Cache aktualisiert.',
    'Failed to refresh log cache.' => 'Protokoll-Cache konnte nicht aktualisiert werden.',
    'Recent runtime logs cleared.' => 'Aktuelle Laufzeitprotokolle gelöscht.',
    'Unable to clear recent runtime logs.' => 'Aktuelle Laufzeitprotokolle konnten nicht gelöscht werden.',
    'Plugin logging not configured' => 'Plugin-Protokollierung nicht konfiguriert',
    'Log viewer is disabled for this plugin' => 'Protokoll-Viewer ist für dieses Plugin deaktiviert',
    'Log viewer is disabled for this environment' => 'Protokoll-Viewer ist für diese Umgebung deaktiviert',
    'Recent runtime logs are disabled' => 'Aktuelle Laufzeitprotokolle sind deaktiviert',
    'Log file not found' => 'Protokolldatei nicht gefunden',
    'Unable to determine plugin handle from URL' => 'Plugin-Handle konnte nicht aus URL ermittelt werden',
    'User does not have permission to view logs' => 'Der Benutzer hat keine Berechtigung, Protokolle anzuzeigen',

    // Settings: General
    'General Settings' => 'Allgemeine Einstellungen',
    'Force Enable Log Viewers' => 'Protokoll-Viewer erzwingen',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Dateibasierte Protokoll-Viewer erzwingen, auch wenn eine Edge- oder ephemere Umgebung erkannt wird. Dies betrifft Logging Library und den dedizierten Protokollbereich jedes Plugins.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library hat eine Edge- oder ephemere Umgebung erkannt, daher sind dateibasierte Protokoll-Viewer für die eigenständige <strong>Alle Protokolle</strong>-Ansicht und den dedizierten <strong>Protokolle</strong>-Bereich jedes Plugins ausgeblendet. Der Hauptmenü-Viewer ist erst verfügbar, wenn Sie diese Überschreibung aktivieren. Verwenden Sie die nativen Protokolle Ihrer Hosting-Plattform oder aktivieren Sie die Überschreibung, wenn persistenter Speicher verfügbar ist.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library hat eine Edge- oder ephemere Umgebung erkannt, aber dateibasierte Protokoll-Viewer werden erzwungen aktiviert. Diese Überschreibung betrifft die eigenständige <strong>Alle Protokolle</strong>-Ansicht und den dedizierten <strong>Protokolle</strong>-Bereich jedes Plugins.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library fügt dem Hauptmenü des Control Panels eine konsolidierte <strong>Alle Protokolle</strong>-Ansicht hinzu. Einzelne Plugins behalten weiterhin ihre eigenen dedizierten <strong>Protokolle</strong>-Bereiche.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Die konsolidierte <strong>Alle Protokolle</strong>-Ansicht ist im Hauptmenü des Control Panels ausgeblendet. Einzelne Plugins behalten weiterhin ihre eigenen dedizierten <strong>Protokolle</strong>-Bereiche.',
    'Show Main Menu' => 'Hauptmenü anzeigen',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Logging Library in der Hauptnavigation des Control Panels als konsolidierte Alle Protokolle-Ansicht anzeigen, wenn dateibasierte Protokoll-Viewer verfügbar sind.',

    // Settings: Interface
    'Interface Settings' => 'Oberflächen-Einstellungen',

    // Log levels
    'All Levels' => 'Alle Stufen',
    'Error' => 'Fehler',
    'Warning' => 'Warnung',
    'Info' => 'Info',
    'Debug' => 'Debug',
    'Unknown' => 'Unbekannt',

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
    'Request User' => 'Anfragebenutzer',
    'User #{id}' => 'Benutzer #{id}',
    'Message' => 'Nachricht',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'Keine Protokolldateien gefunden. Protokolldateien werden erstellt, wenn Plugin-Aktivitäten auftreten.',
    'No recent runtime logs found. Runtime logs are short-lived and only appear after matching events are captured.' => 'Keine aktuellen Laufzeitprotokolle gefunden. Laufzeitprotokolle sind kurzlebig und erscheinen nur, nachdem passende Ereignisse erfasst wurden.',
    'No log entries found for the selected filters.' => 'Keine Protokolleinträge für die ausgewählten Filter gefunden.',

    // Pagination
    'entry' => 'Eintrag',
    'entries' => 'Einträge',

    // Sidebar
    'Current Level' => 'Aktuelle Stufe',
    'Current log level' => 'Aktuelle Protokollierungsstufe',
    'Retention' => 'Aufbewahrung',
    'days' => 'Tage',
    'Available Logs' => 'Verfügbare Protokolle',
    'file' => 'Datei',
    'files' => 'Dateien',
    'Current File' => 'Aktuelle Datei',
    'Log entries' => 'Protokolleinträge',
    'Refresh Cache' => 'Cache aktualisieren',
    'Clear Runtime Logs' => 'Laufzeitprotokolle löschen',
    'Clear recent runtime logs? This cannot be undone.' => 'Aktuelle Laufzeitprotokolle löschen? Dies kann nicht rückgängig gemacht werden.',
    'Loading' => 'Wird geladen',
    'Download File' => 'Datei herunterladen',
    'Log Location' => 'Protokollspeicherort',
    'Runtime Store' => 'Laufzeitspeicher',
    'Craft cache' => 'Craft Cache',
    'Redis ({cache})' => 'Redis ({cache})',
    'Runtime Location' => 'Laufzeit-Speicherort',
    'Recent runtime logs are stored in Craft cache and are intended for short-lived diagnostics, not complete log history.' => 'Aktuelle Laufzeitprotokolle werden im Craft Cache gespeichert und sind für kurzlebige Diagnose gedacht, nicht für den vollständigen Protokollverlauf.',

    // Config overrides
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Dies wird durch die Einstellung <code>forceEnableLogViewer</code> in <code>config/logging-library.php</code> überschrieben.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Dies wird durch die Einstellung <code>showCpSection</code> in <code>config/logging-library.php</code> überschrieben.',
];
