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
    'Inspect system logs, review plugin logging output, and centralize diagnostics from one control panel workspace.' => 'Ispeziona i log di sistema, esamina l\'output di registrazione dei plugin e centralizza la diagnostica da un unico spazio di lavoro nel pannello di controllo.',
    'Open All Logs' => 'Apri tutti i log',

    // Navigation
    'All Logs' => 'Tutti i log',
    'Logs' => 'Log',
    'Settings' => 'Impostazioni',
    'System Logs' => 'Log di sistema',
    'System' => 'Sistema',
    'General' => 'Generale',
    'Interface' => 'Interfaccia',

    // Log levels
    'All Levels' => 'Tutti i livelli',
    'Error' => 'Errore',
    'Warning' => 'Avviso',
    'Info' => 'Info',
    'Debug' => 'Debug',

    // Log sources
    'All Sources' => 'Tutte le fonti',
    'Web' => 'Web',
    'Console' => 'Console',
    'Queue' => 'Queue',
    'PHP Errors' => 'Errori PHP',
    'Other' => 'Altro',

    // Filters
    'Select File' => 'Seleziona file',
    'Select Date' => 'Seleziona data',
    'Search messages and context...' => 'Cerca messaggi e contesto...',

    // Table
    'Time' => 'Ora',
    'Level' => 'Livello',
    'Source' => 'Fonte',
    'User' => 'Utente',
    'Message' => 'Messaggio',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'Nessun file di log trovato. I file di log vengono creati quando si verificano attività del plugin.',
    'No log entries found for the selected filters.' => 'Nessuna voce di log trovata per i filtri selezionati.',

    // Pagination
    'entry' => 'voce',
    'entries' => 'voci',

    // Row detail
    'Context' => 'Contesto',
    'No context data available.' => 'Nessun dato di contesto disponibile.',

    // Sidebar
    'Current Level' => 'Livello attuale',
    'Current log level' => 'Livello di log attuale',
    'Retention' => 'Conservazione',
    'days' => 'giorni',
    'Available Logs' => 'Log disponibili',
    'file' => 'file',
    'files' => 'file',
    'Current File' => 'File attuale',
    'Entries' => 'Voci',
    'Download File' => 'Scarica file',
    'Log Location' => 'Posizione del log',

    // Common
    'Save Settings' => 'Salva impostazioni',

    // Controller messages
    'Settings saved.' => 'Impostazioni salvate.',
    'Could not save settings.' => 'Impossibile salvare le impostazioni.',

    // Validation messages
    'Found {count, number} {count, plural, =1{error} other{errors}}' => '{count, number} {count, plural, =1{errore trovato} other{errori trovati}}',
    'Value must be a whole number.' => 'Il valore deve essere un numero intero.',

    // Settings: General
    'General Settings' => 'Impostazioni generali',
    'Plugin Name' => 'Nome del plugin',
    'The name of the plugin as it appears in the Control Panel menu' => 'Il nome del plugin come appare nel menu del pannello di controllo',
    'This is being overridden by the <code>pluginName</code> setting in <code>config/logging-library.php</code>.' => 'Questa impostazione viene sovrascritta dall\'impostazione <code>pluginName</code> in <code>config/logging-library.php</code>.',
    'Force Enable Log Viewers' => 'Forza abilitazione visualizzatori di log',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Forza l\'abilitazione dei visualizzatori di log basati su file anche quando viene rilevato un ambiente edge o effimero. Questo influisce su Logging Library e sulla sezione Log dedicata di ogni plugin.',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Questa impostazione viene sovrascritta dall\'impostazione <code>forceEnableLogViewer</code> in <code>config/logging-library.php</code>.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library ha rilevato un ambiente edge o effimero, quindi i visualizzatori di log basati su file sono nascosti per la vista autonoma <strong>Tutti i log</strong> e per la sezione <strong>Log</strong> dedicata di ogni plugin. Il visualizzatore del menu principale non è disponibile fino a quando non si abilita questo override. Utilizza i log nativi della tua piattaforma di hosting oppure abilita l\'override se è disponibile archiviazione persistente.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library ha rilevato un ambiente edge o effimero, ma i visualizzatori di log basati su file vengono abilitati forzatamente. Questo override influisce sulla vista autonoma <strong>Tutti i log</strong> e sulla sezione <strong>Log</strong> dedicata di ogni plugin.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library aggiunge una vista consolidata <strong>Tutti i log</strong> al menu principale del pannello di controllo. I singoli plugin mantengono ancora le proprie sezioni <strong>Log</strong> dedicate.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'La vista consolidata <strong>Tutti i log</strong> è nascosta nel menu principale del pannello di controllo. I singoli plugin mantengono ancora le proprie sezioni <strong>Log</strong> dedicate.',
    'Show Main Menu' => 'Mostra menu principale',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Mostra Logging Library nella navigazione principale del pannello di controllo come vista consolidata Tutti i log quando i visualizzatori di log basati su file sono disponibili.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Questa impostazione viene sovrascritta dall\'impostazione <code>showCpSection</code> in <code>config/logging-library.php</code>.',

    // Settings: Interface
    'Interface Settings' => 'Impostazioni dell\'interfaccia',
    'Items Per Page' => 'Elementi per pagina',
    'Number of log entries to display per page in the log viewers' => 'Numero di voci di log da visualizzare per pagina nei visualizzatori di log',
    'This is being overridden by the <code>itemsPerPage</code> setting in <code>config/logging-library.php</code>.' => 'Questa impostazione viene sovrascritta dall\'impostazione <code>itemsPerPage</code> in <code>config/logging-library.php</code>.',
];
