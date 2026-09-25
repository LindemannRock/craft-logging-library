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
    'Open Settings' => 'Apri le impostazioni',

    // Navigation
    'Setup' => 'Configurazione',
    'File Logs' => 'Log su file',
    'All Logs' => 'Tutti i log',
    'Runtime Logs' => 'Log runtime',
    'Logs' => 'Log',
    'Settings' => 'Impostazioni',
    'System Logs' => 'Log di sistema',
    'System' => 'Sistema',
    'Plugins' => 'Plugin',
    'General' => 'Generale',
    'Interface' => 'Interfaccia',

    // Permissions
    'View all system logs' => 'Visualizza tutti i log di sistema',
    'Download all system logs' => 'Scarica tutti i log di sistema',
    'Clear cache' => 'Svuota cache',
    'Manage settings' => 'Gestisci impostazioni',

    // Common
    '{displayName} caches' => 'Cache di {displayName}',

    // Controller messages
    'Settings saved.' => 'Impostazioni salvate.',
    'Could not save settings.' => 'Impossibile salvare le impostazioni.',
    'Log cache refreshed.' => 'Cache dei log aggiornata.',
    'Failed to refresh log cache.' => 'Impossibile aggiornare la cache dei log.',
    'Recent runtime logs cleared.' => 'Log runtime recenti cancellati.',
    'Unable to clear recent runtime logs.' => 'Impossibile cancellare i log runtime recenti.',
    'Plugin logging not configured' => 'Log del plugin non configurati',
    'Log viewer is disabled for this plugin' => 'Il visualizzatore di log è disabilitato per questo plugin',
    'Log viewer is disabled for this environment' => 'Il visualizzatore di log è disabilitato per questo ambiente',
    'Recent runtime logs are disabled' => 'I log di runtime recenti sono disabilitati',
    'Log file not found' => 'File di log non trovato',
    'Unable to determine plugin handle from URL' => 'Impossibile determinare l\'handle del plugin dall\'URL',
    'User does not have permission to view logs' => 'L\'utente non dispone dell\'autorizzazione per visualizzare i log',

    // Settings: General
    'Show Logging Library in the main navigation. This does not enable or disable log capture.' => 'Mostra Logging Library nella navigazione principale. Questo non attiva né disattiva l\'acquisizione dei log.',
    'General Settings' => 'Impostazioni generali',
    'Force Enable Log Viewers' => 'Forza abilitazione visualizzatori di log',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Forza l\'abilitazione dei visualizzatori di log basati su file anche quando viene rilevato un ambiente edge o effimero. Questo influisce su Logging Library e sulla sezione Log dedicata di ogni plugin.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library ha rilevato un ambiente edge o effimero, quindi i visualizzatori di log basati su file sono nascosti per la vista autonoma <strong>Tutti i log</strong> e per la sezione <strong>Log</strong> dedicata di ogni plugin. Il visualizzatore del menu principale non è disponibile fino a quando non si abilita questo override. Utilizza i log nativi della tua piattaforma di hosting oppure abilita l\'override se è disponibile archiviazione persistente.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library ha rilevato un ambiente edge o effimero, ma i visualizzatori di log basati su file vengono abilitati forzatamente. Questo override influisce sulla vista autonoma <strong>Tutti i log</strong> e sulla sezione <strong>Log</strong> dedicata di ogni plugin.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library aggiunge una vista consolidata <strong>Tutti i log</strong> al menu principale del pannello di controllo. I singoli plugin mantengono ancora le proprie sezioni <strong>Log</strong> dedicate.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'La vista consolidata <strong>Tutti i log</strong> è nascosta nel menu principale del pannello di controllo. I singoli plugin mantengono ancora le proprie sezioni <strong>Log</strong> dedicate.',
    'Show Main Menu' => 'Mostra menu principale',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Mostra Logging Library nella navigazione principale del pannello di controllo come vista consolidata Tutti i log quando i visualizzatori di log basati su file sono disponibili.',

    // Settings: File Logs
    'Force Enable File Log Viewers' => 'Forza attivazione visualizzatori di log su file',
    'An edge or ephemeral environment is detected. File viewers are hidden unless forced on; Runtime Logs remain available when enabled. Only force file viewers on if persistent log files are available.' => 'È stato rilevato un ambiente edge o temporaneo. I visualizzatori di file sono nascosti salvo attivazione forzata; i log runtime restano disponibili quando attivati. Forzare i visualizzatori solo se sono disponibili file di log persistenti.',
    'File viewers are available. Runtime Logs are optional and independent of file logging.' => 'I visualizzatori di file sono disponibili. I log runtime sono facoltativi e indipendenti dalla registrazione su file.',

    // Settings: Runtime Logs
    'Min: {min}, Max: {max}' => 'Min: {min}, Max: {max}',
    'Uses the application cache configuration. The Redis database can be overridden in {file}. To verify capture, trigger a log message and check Runtime Logs.' => 'Utilizza la configurazione della cache dell’applicazione. Il database Redis può essere sovrascritto in {file}. Per verificare l’acquisizione, generare un messaggio di log e controllare i log runtime.',
    'Enable Runtime Logs' => 'Attiva log runtime',
    'Skip Console Requests' => 'Ignora richieste console',
    'Skip Queue Requests' => 'Ignora richieste della coda',
    'Retention (seconds)' => 'Conservazione (secondi)',
    'Maximum Entries' => 'Numero massimo di voci',
    'Refresh Interval (seconds)' => 'Intervallo di aggiornamento (secondi)',
    'Maximum Message Bytes' => 'Byte massimi del messaggio',
    'Maximum Context Bytes' => 'Byte massimi del contesto',
    'Captured Levels' => 'Livelli acquisiti',
    'Include Categories' => 'Includi categorie',
    'Exclude Categories' => 'Escludi categorie',
    'Include Request User ID' => 'Includi ID utente della richiesta',
    'Advanced' => 'Avanzate',
    'Configured Storage' => 'Archiviazione configurata',
    'Storage follows the Craft cache configuration. Redis database selection is configuration-only. This is not a connection test; confirm capture in Runtime Logs.' => 'L\'archiviazione segue la configurazione della cache Craft. Il database Redis si seleziona solo tramite configurazione. Questo non è un test di connessione; verificare l\'acquisizione nei log runtime.',
    'On multiple servers, use shared cache storage. Local file cache does not combine logs from other instances.' => 'Su più server, usare una cache condivisa. La cache di file locale non combina i log di altre istanze.',
    'Capture changes apply to new requests. Restart long-running workers to load changed settings. Disabling capture does not clear stored logs.' => 'Le modifiche all\'acquisizione si applicano alle nuove richieste. Riavviare i processi di lunga durata per caricare le impostazioni modificate. Disattivare l\'acquisizione non elimina i log archiviati.',
    'How often Runtime Logs refreshes automatically. Set to 0 to disable. Current: {duration}' => 'Frequenza di aggiornamento automatico dei log runtime. Impostare 0 per disattivarlo. Attuale: {duration}',
    'Choose which log categories to capture, not words in the message. Enter one category per line, such as {exact}, or use {prefix} to match categories starting with {start}. Leave empty to capture all categories.' => 'Scegliere le categorie di log da acquisire, non parole del messaggio. Inserire una categoria per riga, come {exact}, oppure usare {prefix} per le categorie che iniziano con {start}. Lasciare vuoto per acquisire tutte le categorie.',
    'Skip these log categories even if included above. Enter one per line, such as {exact} or {prefix}. Leave empty to add no category exclusions.' => 'Ignorare queste categorie di log anche se incluse sopra. Inserirne una per riga, come {exact} o {prefix}. Lasciare vuoto per non aggiungere esclusioni di categorie.',
    'When on, Runtime Logs skips command-line requests. Turn off only when diagnosing console commands; file and hosted logs are unaffected.' => 'Quando l’opzione è attiva, i log di runtime ignorano le richieste da riga di comando. Disattivarla solo per diagnosticare i comandi della console; i log su file e dell’hosting restano invariati.',
    'When on, Runtime Logs skips detected queue execution. To capture console queue workers, turn off both skip switches and restart the workers. Workers can generate large volumes of logs.' => 'Quando l’opzione è attiva, i log di runtime ignorano l’esecuzione della coda rilevata. Per acquisire i processi della coda della console, disattivare entrambe le opzioni di esclusione e riavviare i processi. I processi possono generare grandi volumi di log.',
    'Adds the authenticated request user ID. Messages and context may still contain personal data regardless of this setting.' => 'Aggiunge l\'ID dell\'utente autenticato della richiesta. Messaggi e contesto possono contenere dati personali indipendentemente da questa impostazione.',

    'Maximum age of runtime entries, in seconds. Current: {duration}' => 'Età massima delle voci dei log di runtime, in secondi. Attuale: {duration}',
    'Min: {min} ({minDuration}), Max: {max} ({maxDuration})' => 'Min: {min} ({minDuration}), Max: {max} ({maxDuration})',
    '{count} second' => '{count} secondo',
    '{count} seconds' => '{count} secondi',
    '{count} minute' => '{count} minuto',
    '{count} minutes' => '{count} minuti',
    '{count} hour' => '{count} ora',
    '{count} hours' => '{count} ore',
    '{count} day' => '{count} giorno',
    '{count} days' => '{count} giorni',

    // Settings: Interface
    'Interface Settings' => 'Impostazioni interfaccia',

    // Setup
    'Choose the log views that suit this environment. Runtime capture is optional and remains off until enabled.' => 'Scegliere le viste dei log adatte a questo ambiente. L\'acquisizione runtime è facoltativa e resta disattivata fino all\'attivazione.',
    'Consider Runtime Logs for recent diagnostics on ephemeral hosting. Confirm shared storage before relying on logs from multiple instances.' => 'Valutare i log runtime per la diagnostica recente su hosting temporaneo. Verificare l\'archiviazione condivisa prima di affidarsi ai log di più istanze.',

    // Log levels
    'All Levels' => 'Tutti i livelli',
    'Error' => 'Errore',
    'Warning' => 'Avviso',
    'Info' => 'Info',
    'Debug' => 'Debug',
    'Unknown' => 'Sconosciuto',

    // Log sources
    'All Sources' => 'Tutte le sorgenti',
    'Web' => 'Web',
    'Console' => 'Console',
    'Queue' => 'Queue',
    'PHP Errors' => 'Errori PHP',
    'Other' => 'Altro',
    'DB Queries' => 'Query DB',
    'DB Commands' => 'Comandi DB',
    'DB Command::{method}' => 'Comando DB::{method}',
    'DB Connection' => 'Connessione DB',
    'DB Connection::{method}' => 'Connessione DB::{method}',
    'Redis Commands' => 'Comandi Redis',
    'Redis Connection' => 'Connessione Redis',
    'Redis Connection::{method}' => 'Connessione Redis::{method}',
    'URL Routing' => 'Routing URL',
    'Web Request' => 'Richiesta web',
    'Session' => 'Sessione',
    'Template Rendering' => 'Rendering dei template',
    'Modules' => 'Moduli',
    'Integration Service' => 'Servizio di integrazione',

    // Filters
    'Select File' => 'Seleziona file',
    'Select Date' => 'Seleziona data',
    'Search messages and context...' => 'Cerca messaggi e contesto...',

    // Table
    'Time' => 'Ora',
    'Level' => 'Livello',
    'Source' => 'Sorgente',
    'User' => 'Utente',
    'Request User' => 'Utente della richiesta',
    'User #{id}' => 'Utente #{id}',
    'Message' => 'Messaggio',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'Nessun file di log trovato. I file di log vengono creati quando si verificano attività del plugin.',
    'No recent runtime logs found. Runtime logs are short-lived and only appear after matching events are captured.' => 'Nessun log di runtime recente trovato. I log di runtime sono di breve durata e appaiono solo dopo l\'acquisizione di eventi corrispondenti.',
    'No log entries found for the selected filters.' => 'Nessuna voce di log trovata per i filtri selezionati.',

    // Pagination
    'entry' => 'voce',
    'entries' => 'voci',

    // Sidebar
    'Current Level' => 'Livello attuale',
    'Current log level' => 'Livello di log attuale',
    'Retention' => 'Conservazione',
    'days' => 'giorni',
    'Available Logs' => 'Log disponibili',
    'file' => 'file',
    'files' => 'file',
    'Current File' => 'File attuale',
    'Log entries' => 'Voci di log',
    'Refresh Cache' => 'Aggiorna cache',
    'Clear Runtime Logs' => 'Cancella log runtime',
    'Clear recent runtime logs? This cannot be undone.' => 'Cancellare i log runtime recenti? Questa azione non può essere annullata.',
    'Loading' => 'Caricamento',
    'Download File' => 'Scarica file',
    'Log Location' => 'Posizione del log',
    'Runtime Store' => 'Archivio runtime',
    'Craft cache' => 'cache Craft',
    'Redis unavailable' => 'Redis non disponibile',
    'Redis (SELECT disabled)' => 'Redis (SELECT disabilitato)',
    'Redis database {database}' => 'Database Redis {database}',
    'Runtime Location' => 'Posizione runtime',
    'Dedicated Redis key' => 'Chiave Redis dedicata',
    'Recent runtime logs use a bounded diagnostic store and are not complete log history.' => 'I log di runtime recenti usano un archivio diagnostico limitato e non costituiscono una cronologia completa dei log.',

    // Config overrides
    'This is being overridden by the <code>{setting}</code> setting in <code>config/logging-library.php</code>.' => 'Questa impostazione viene sovrascritta dall\'impostazione <code>{setting}</code> in <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Questa impostazione viene sovrascritta dall\'impostazione <code>forceEnableLogViewer</code> in <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Questa impostazione viene sovrascritta dall\'impostazione <code>showCpSection</code> in <code>config/logging-library.php</code>.',
];
