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
    'View all file logs' => 'Visualizza tutti i log su file',
    'Download all file logs' => 'Scarica tutti i log su file',
    'Clear file log cache' => 'Svuota cache dei log su file',
    'View runtime logs' => 'Visualizza log runtime',
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
    'An edge or ephemeral environment is detected. File viewers are hidden unless forced on; {runtimeLogs} remain available when enabled. Only force file viewers on if persistent log files are available.' => 'È stato rilevato un ambiente edge o temporaneo. I visualizzatori di file sono nascosti salvo attivazione forzata; i {runtimeLogs} restano disponibili quando attivati. Forzare i visualizzatori solo se sono disponibili file di log persistenti.',
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
    'Include Sources and Categories' => 'Includi sorgenti e categorie',
    'Exclude Sources and Categories' => 'Escludi sorgenti e categorie',
    'Category: {pattern}' => 'Categoria: {pattern}',
    'Include Request User ID' => 'Includi ID utente della richiesta',
    'Advanced' => 'Avanzate',
    'Configured Storage' => 'Archiviazione configurata',
    'Capture new messages in Runtime Logs. Changes apply to new requests; restart long-running workers to apply them. Turning this off does not delete existing logs.' => 'Acquisire nuovi messaggi nei log runtime. Le modifiche si applicano alle nuove richieste; riavviare i processi di lunga durata per applicarle. Disattivare questa opzione non elimina i log esistenti.',
    'Limits the message text kept for each new Runtime Logs entry, in bytes rather than characters. Longer messages are shortened. File logs are unaffected.' => 'Limita il testo del messaggio conservato per ogni nuova voce nei log runtime, in byte anziché in caratteri. I messaggi più lunghi vengono abbreviati. I log su file non vengono modificati.',
    'Limits the extra data kept for each new Runtime Logs entry, such as error details and stack traces, in bytes after JSON encoding. Larger context is shortened. File logs are unaffected.' => 'Limita i dati aggiuntivi conservati per ogni nuova voce nei log runtime, come i dettagli degli errori e le tracce dello stack, in byte dopo la codifica JSON. Il contesto più grande viene abbreviato. I log su file non vengono modificati.',
    'How often Runtime Logs refreshes automatically. Set to 0 to disable. Current: {duration}' => 'Frequenza di aggiornamento automatico dei log runtime. Impostare 0 per disattivarlo. Attuale: {duration}',
    'Choose sources by name or type a category pattern such as {pattern} and press Enter. Leave empty to capture all sources. Changes affect new messages only.' => 'Scegliere le sorgenti per nome oppure inserire un modello di categoria come {pattern} e premere Invio. Lasciare vuoto per acquisire tutte le sorgenti. Le modifiche riguardano solo i nuovi messaggi.',
    'Choose sources to skip or type a category pattern and press Enter. Exclusions take precedence. Existing entries are not removed.' => 'Scegliere le sorgenti da ignorare oppure inserire un modello di categoria e premere Invio. Le esclusioni hanno la precedenza. Le voci esistenti non vengono rimosse.',
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
    'Clear runtime logs' => 'Cancella log runtime',
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
