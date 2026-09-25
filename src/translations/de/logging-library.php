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
    'Setup' => 'Einrichtung',
    'File Logs' => 'Dateiprotokolle',
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
    'Show Logging Library in the main navigation. This does not enable or disable log capture.' => 'Logging Library in der Hauptnavigation anzeigen. Dies aktiviert oder deaktiviert die Protokollerfassung nicht.',
    'General Settings' => 'Allgemeine Einstellungen',
    'Force Enable Log Viewers' => 'Protokoll-Viewer erzwingen',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Dateibasierte Protokoll-Viewer erzwingen, auch wenn eine Edge- oder ephemere Umgebung erkannt wird. Dies betrifft Logging Library und den dedizierten Protokollbereich jedes Plugins.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library hat eine Edge- oder ephemere Umgebung erkannt, daher sind dateibasierte Protokoll-Viewer für die eigenständige <strong>Alle Protokolle</strong>-Ansicht und den dedizierten <strong>Protokolle</strong>-Bereich jedes Plugins ausgeblendet. Der Hauptmenü-Viewer ist erst verfügbar, wenn Sie diese Überschreibung aktivieren. Verwenden Sie die nativen Protokolle Ihrer Hosting-Plattform oder aktivieren Sie die Überschreibung, wenn persistenter Speicher verfügbar ist.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library hat eine Edge- oder ephemere Umgebung erkannt, aber dateibasierte Protokoll-Viewer werden erzwungen aktiviert. Diese Überschreibung betrifft die eigenständige <strong>Alle Protokolle</strong>-Ansicht und den dedizierten <strong>Protokolle</strong>-Bereich jedes Plugins.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library fügt dem Hauptmenü des Control Panels eine konsolidierte <strong>Alle Protokolle</strong>-Ansicht hinzu. Einzelne Plugins behalten weiterhin ihre eigenen dedizierten <strong>Protokolle</strong>-Bereiche.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Die konsolidierte <strong>Alle Protokolle</strong>-Ansicht ist im Hauptmenü des Control Panels ausgeblendet. Einzelne Plugins behalten weiterhin ihre eigenen dedizierten <strong>Protokolle</strong>-Bereiche.',
    'Show Main Menu' => 'Hauptmenü anzeigen',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Logging Library in der Hauptnavigation des Control Panels als konsolidierte Alle Protokolle-Ansicht anzeigen, wenn dateibasierte Protokoll-Viewer verfügbar sind.',

    // Settings: File Logs
    'Force Enable File Log Viewers' => 'Dateibasierte Protokoll-Viewer erzwingen',
    'An edge or ephemeral environment is detected. File viewers are hidden unless forced on; Runtime Logs remain available when enabled. Only force file viewers on if persistent log files are available.' => 'Eine Edge- oder kurzlebige Umgebung wurde erkannt. Datei-Viewer sind ausgeblendet, sofern sie nicht erzwungen werden; aktivierte Laufzeitprotokolle bleiben verfügbar. Erzwingen Sie Datei-Viewer nur, wenn persistente Protokolldateien verfügbar sind.',
    'File viewers are available. Runtime Logs are optional and independent of file logging.' => 'Datei-Viewer sind verfügbar. Laufzeitprotokolle sind optional und unabhängig von der Dateiprotokollierung.',

    // Settings: Runtime Logs
    'Min: {min}, Max: {max}' => 'Min.: {min}, Max.: {max}',
    'Uses the application cache configuration. The Redis database can be overridden in {file}. To verify capture, trigger a log message and check Runtime Logs.' => 'Verwendet die Konfiguration des Anwendungs-Caches. Die Redis-Datenbank kann in {file} überschrieben werden. Lösen Sie zur Prüfung der Erfassung eine Protokollmeldung aus und prüfen Sie die Laufzeitprotokolle.',
    'Enable Runtime Logs' => 'Laufzeitprotokolle aktivieren',
    'Skip Console Requests' => 'Konsolenanfragen überspringen',
    'Skip Queue Requests' => 'Warteschlangenanfragen überspringen',
    'Retention (seconds)' => 'Aufbewahrung (Sekunden)',
    'Maximum Entries' => 'Maximale Einträge',
    'Refresh Interval (seconds)' => 'Aktualisierungsintervall (Sekunden)',
    'Maximum Message Bytes' => 'Maximale Nachrichtenbytes',
    'Maximum Context Bytes' => 'Maximale Kontextbytes',
    'Captured Levels' => 'Erfasste Protokollstufen',
    'Include Categories' => 'Kategorien einschließen',
    'Exclude Categories' => 'Kategorien ausschließen',
    'Include Request User ID' => 'Benutzer-ID der Anfrage einschließen',
    'Advanced' => 'Erweitert',
    'Configured Storage' => 'Konfigurierter Speicher',
    'Storage follows the Craft cache configuration. Redis database selection is configuration-only. This is not a connection test; confirm capture in Runtime Logs.' => 'Der Speicher folgt der Craft Cache-Konfiguration. Die Redis-Datenbank wird ausschließlich über die Konfiguration ausgewählt. Dies ist kein Verbindungstest; prüfen Sie die Erfassung unter Laufzeitprotokolle.',
    'On multiple servers, use shared cache storage. Local file cache does not combine logs from other instances.' => 'Verwenden Sie bei mehreren Servern gemeinsamen Cache-Speicher. Ein lokaler Datei-Cache führt keine Protokolle anderer Instanzen zusammen.',
    'Capture changes apply to new requests. Restart long-running workers to load changed settings. Disabling capture does not clear stored logs.' => 'Änderungen an der Erfassung gelten für neue Anfragen. Starten Sie langlebige Worker neu, um geänderte Einstellungen zu laden. Das Deaktivieren der Erfassung löscht keine gespeicherten Protokolle.',
    'How often Runtime Logs refreshes automatically. Set to 0 to disable. Current: {duration}' => 'Wie oft die Laufzeitprotokolle automatisch aktualisiert werden. Zum Deaktivieren auf 0 setzen. Aktuell: {duration}',
    'Choose which log categories to capture, not words in the message. Enter one category per line, such as {exact}, or use {prefix} to match categories starting with {start}. Leave empty to capture all categories.' => 'Wählen Sie die zu erfassenden Protokollkategorien, nicht Wörter in der Meldung. Geben Sie eine Kategorie pro Zeile ein, etwa {exact}, oder verwenden Sie {prefix} für Kategorien, die mit {start} beginnen. Lassen Sie das Feld leer, um alle Kategorien zu erfassen.',
    'Skip these log categories even if included above. Enter one per line, such as {exact} or {prefix}. Leave empty to add no category exclusions.' => 'Überspringen Sie diese Protokollkategorien, auch wenn sie oben eingeschlossen sind. Geben Sie eine pro Zeile ein, etwa {exact} oder {prefix}. Lassen Sie das Feld leer, um keine Kategorieausschlüsse hinzuzufügen.',
    'When on, Runtime Logs skips command-line requests. Turn off only when diagnosing console commands; file and hosted logs are unaffected.' => 'Wenn aktiviert, überspringen Laufzeitprotokolle Befehlszeilenanfragen. Deaktivieren Sie diese Option nur zur Diagnose von Konsolenbefehlen; Datei- und Hosting-Protokolle bleiben unverändert.',
    'When on, Runtime Logs skips detected queue execution. To capture console queue workers, turn off both skip switches and restart the workers. Workers can generate large volumes of logs.' => 'Wenn aktiviert, überspringen Laufzeitprotokolle erkannte Warteschlangenausführungen. Um Konsolen-Worker der Warteschlange zu erfassen, deaktivieren Sie beide Überspringen-Schalter und starten Sie die Worker neu. Worker können große Protokollmengen erzeugen.',
    'Adds the authenticated request user ID. Messages and context may still contain personal data regardless of this setting.' => 'Fügt die ID des authentifizierten Anfragebenutzers hinzu. Nachrichten und Kontext können unabhängig von dieser Einstellung personenbezogene Daten enthalten.',

    'Maximum age of runtime entries, in seconds. Current: {duration}' => 'Höchstalter der Laufzeitprotokolleinträge in Sekunden. Aktuell: {duration}',
    'Min: {min} ({minDuration}), Max: {max} ({maxDuration})' => 'Min.: {min} ({minDuration}), Max.: {max} ({maxDuration})',
    '{count} second' => '{count} Sekunde',
    '{count} seconds' => '{count} Sekunden',
    '{count} minute' => '{count} Minute',
    '{count} minutes' => '{count} Minuten',
    '{count} hour' => '{count} Stunde',
    '{count} hours' => '{count} Stunden',
    '{count} day' => '{count} Tag',
    '{count} days' => '{count} Tage',

    // Settings: Interface
    'Interface Settings' => 'Oberflächen-Einstellungen',

    // Setup
    'Choose the log views that suit this environment. Runtime capture is optional and remains off until enabled.' => 'Wählen Sie die für diese Umgebung geeigneten Protokollansichten. Die Laufzeiterfassung ist optional und bleibt bis zur Aktivierung ausgeschaltet.',
    'Consider Runtime Logs for recent diagnostics on ephemeral hosting. Confirm shared storage before relying on logs from multiple instances.' => 'Erwägen Sie Laufzeitprotokolle für aktuelle Diagnosen auf kurzlebigem Hosting. Prüfen Sie den gemeinsamen Speicher, bevor Sie sich auf Protokolle mehrerer Instanzen verlassen.',

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
    'DB Queries' => 'DB-Abfragen',
    'DB Commands' => 'DB-Befehle',
    'DB Command::{method}' => 'DB-Befehl::{method}',
    'DB Connection' => 'DB-Verbindung',
    'DB Connection::{method}' => 'DB-Verbindung::{method}',
    'Redis Commands' => 'Redis-Befehle',
    'Redis Connection' => 'Redis-Verbindung',
    'Redis Connection::{method}' => 'Redis-Verbindung::{method}',
    'URL Routing' => 'URL-Routing',
    'Web Request' => 'Web-Anfrage',
    'Session' => 'Sitzung',
    'Template Rendering' => 'Template-Rendering',
    'Modules' => 'Module',
    'Integration Service' => 'Integrationsdienst',

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
    'Redis unavailable' => 'Redis nicht verfügbar',
    'Redis (SELECT disabled)' => 'Redis (SELECT deaktiviert)',
    'Redis database {database}' => 'Redis-Datenbank {database}',
    'Runtime Location' => 'Laufzeit-Speicherort',
    'Dedicated Redis key' => 'Dedizierter Redis-Schlüssel',
    'Recent runtime logs use a bounded diagnostic store and are not complete log history.' => 'Aktuelle Laufzeitprotokolle verwenden einen begrenzten Diagnosespeicher und stellen keinen vollständigen Protokollverlauf dar.',

    // Config overrides
    'This is being overridden by the <code>{setting}</code> setting in <code>config/logging-library.php</code>.' => 'Dies wird durch die Einstellung <code>{setting}</code> in <code>config/logging-library.php</code> überschrieben.',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Dies wird durch die Einstellung <code>forceEnableLogViewer</code> in <code>config/logging-library.php</code> überschrieben.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Dies wird durch die Einstellung <code>showCpSection</code> in <code>config/logging-library.php</code> überschrieben.',
];
