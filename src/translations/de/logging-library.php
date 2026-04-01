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
    'Show Main Menu' => 'Hauptmenü anzeigen',
    'Show Logging Library in the main Control Panel navigation. When disabled, All Logs remains accessible from plugin settings and direct URLs.' => 'Logging Library in der Hauptnavigation des Control Panels anzeigen. Wenn deaktiviert, ist „Alle Protokolle" weiterhin über die Plugin-Einstellungen und direkte URLs erreichbar.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Dies wird durch die Einstellung <code>showCpSection</code> in <code>config/logging-library.php</code> überschrieben.',

    // Settings: Interface
    'Interface Settings' => 'Oberflächeneinstellungen',
    'Items Per Page' => 'Einträge pro Seite',
    'Number of log entries to display per page in the log viewers' => 'Anzahl der Protokolleinträge, die pro Seite in den Protokoll-Ansichten angezeigt werden',
    'This is being overridden by the <code>itemsPerPage</code> setting in <code>config/logging-library.php</code>.' => 'Dies wird durch die Einstellung <code>itemsPerPage</code> in <code>config/logging-library.php</code> überschrieben.',
];
