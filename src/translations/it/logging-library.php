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
    'Value must be a whole number.' => 'Il valore deve essere un numero intero.',

    // Settings: General
    'General Settings' => 'Impostazioni generali',
    'Plugin Name' => 'Nome del plugin',
    'The name of the plugin as it appears in the Control Panel menu' => 'Il nome del plugin come appare nel menu del pannello di controllo',
    'This is being overridden by the <code>pluginName</code> setting in <code>config/logging-library.php</code>.' => 'Questa impostazione viene sovrascritta dall\'impostazione <code>pluginName</code> in <code>config/logging-library.php</code>.',
    'Show Main Menu' => 'Mostra menu principale',
    'Show Logging Library in the main Control Panel navigation. When disabled, All Logs remains accessible from plugin settings and direct URLs.' => 'Mostra Logging Library nella navigazione principale del pannello di controllo. Se disabilitata, Tutti i log rimane accessibile dalle impostazioni del plugin e dagli URL diretti.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Questa impostazione viene sovrascritta dall\'impostazione <code>showCpSection</code> in <code>config/logging-library.php</code>.',

    // Settings: Interface
    'Interface Settings' => 'Impostazioni dell\'interfaccia',
    'Items Per Page' => 'Elementi per pagina',
    'Number of log entries to display per page in the log viewers' => 'Numero di voci di log da visualizzare per pagina nei visualizzatori di log',
    'This is being overridden by the <code>itemsPerPage</code> setting in <code>config/logging-library.php</code>.' => 'Questa impostazione viene sovrascritta dall\'impostazione <code>itemsPerPage</code> in <code>config/logging-library.php</code>.',
];
