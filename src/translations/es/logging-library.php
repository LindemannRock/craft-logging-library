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
    'Inspect system logs, review plugin logging output, and centralize diagnostics from one control panel workspace.' => 'Inspeccione los registros del sistema, revise la salida de registro de los plugins y centralice los diagnósticos desde un espacio de trabajo del panel de control.',
    'Open All Logs' => 'Abrir todos los registros',
    'Open Settings' => 'Abrir la configuración',

    // Navigation
    'File Logs' => 'Registros en archivos',
    'All Logs' => 'Todos los registros',
    'Runtime Logs' => 'Registros de tiempo de ejecución',
    'Logs' => 'Registros',
    'Settings' => 'Configuración',
    'System Logs' => 'Registros del sistema',
    'System' => 'Sistema',
    'Plugins' => 'Plugins',
    'General' => 'General',
    'Interface' => 'Interfaz',

    // Permissions
    'View all file logs' => 'Ver todos los registros en archivos',
    'Download all file logs' => 'Descargar todos los registros en archivos',
    'Clear file log cache' => 'Vaciar caché de registros en archivos',
    'View runtime logs' => 'Ver registros de tiempo de ejecución',
    'Manage settings' => 'Gestionar configuración',

    // Common
    '{displayName} caches' => 'Cachés de {displayName}',

    // Controller messages
    'Settings saved.' => 'Configuración guardada.',
    'Could not save settings.' => 'No se pudo guardar la configuración.',
    'Log cache refreshed.' => 'Caché del registro actualizada.',
    'Failed to refresh log cache.' => 'No se pudo actualizar la caché del registro.',
    'Recent runtime logs cleared.' => 'Registros recientes de tiempo de ejecución borrados.',
    'Unable to clear recent runtime logs.' => 'No se pudieron borrar los registros recientes de tiempo de ejecución.',
    'Plugin logging not configured' => 'Registros del plugin no configurados',
    'Log viewer is disabled for this plugin' => 'El visor de registros está desactivado para este plugin',
    'Log viewer is disabled for this environment' => 'El visor de registros está desactivado para este entorno',
    'Recent runtime logs are disabled' => 'Los registros recientes de tiempo de ejecución están desactivados',
    'Log file not found' => 'Archivo de registro no encontrado',
    'Unable to determine plugin handle from URL' => 'No se pudo determinar el identificador del plugin a partir de la URL',
    'User does not have permission to view logs' => 'El usuario no tiene permiso para ver los registros',

    // Settings: General
    'Show Logging Library in the main navigation. This does not enable or disable log capture.' => 'Mostrar Logging Library en la navegación principal. Esto no activa ni desactiva la captura de registros.',
    'General Settings' => 'Configuración general',
    'Force Enable Log Viewers' => 'Forzar activación de visores de registros',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Forzar la activación de visores de registros basados en archivos incluso cuando se detecta un entorno edge o efímero. Esto afecta a Logging Library y a la sección de Registros dedicada de cada plugin.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library detectó un entorno edge o efímero, por lo que los visores de registros basados en archivos están ocultos para la vista independiente <strong>Todos los registros</strong> y para la sección <strong>Registros</strong> dedicada de cada plugin. El visor del menú principal no está disponible hasta que active esta anulación. Utilice los registros nativos de su plataforma de alojamiento o active la anulación si hay almacenamiento persistente disponible.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library detectó un entorno edge o efímero, pero los visores de registros basados en archivos están siendo activados de forma forzada. Esta anulación afecta a la vista independiente <strong>Todos los registros</strong> y a la sección <strong>Registros</strong> dedicada de cada plugin.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library añade una vista consolidada <strong>Todos los registros</strong> al menú principal del panel de control. Los plugins individuales conservan sus propias secciones de <strong>Registros</strong> dedicadas.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'La vista consolidada <strong>Todos los registros</strong> está oculta en el menú principal del panel de control. Los plugins individuales conservan sus propias secciones de <strong>Registros</strong> dedicadas.',
    'Show Main Menu' => 'Mostrar menú principal',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Mostrar Logging Library en la navegación principal del panel de control como una vista consolidada Todos los registros cuando los visores de registros basados en archivos estén disponibles.',

    // Settings: File Logs
    'Force Enable File Log Viewers' => 'Forzar visores de registros en archivos',
    'An edge or ephemeral environment is detected. File viewers are hidden unless forced on; {runtimeLogs} remain available when enabled. Only force file viewers on if persistent log files are available.' => 'Se detecta un entorno edge o efímero. Los visores de archivos se ocultan salvo que se fuerce su activación; los {runtimeLogs} siguen disponibles cuando están activados. Fuerce los visores solo si dispone de archivos de registro persistentes.',
    'File viewers are available. Runtime Logs are optional and independent of file logging.' => 'Los visores de archivos están disponibles. Los registros de tiempo de ejecución son opcionales e independientes del registro en archivos.',

    // Settings: Runtime Logs
    'Min: {min}, Max: {max}' => 'Mín.: {min}, Máx.: {max}',
    'Uses the application cache configuration. The Redis database can be overridden in {file}. To verify capture, trigger a log message and check Runtime Logs.' => 'Utiliza la configuración de la caché de aplicación. La base de datos Redis se puede sobrescribir en {file}. Para verificar la captura, genere un mensaje de registro y consulte los registros de tiempo de ejecución.',
    'Enable Runtime Logs' => 'Activar registros de tiempo de ejecución',
    'Skip Console Requests' => 'Omitir solicitudes de consola',
    'Skip Queue Requests' => 'Omitir solicitudes de cola',
    'Retention (seconds)' => 'Retención (segundos)',
    'Maximum Entries' => 'Máximo de entradas',
    'Refresh Interval (seconds)' => 'Intervalo de actualización (segundos)',
    'Maximum Message Bytes' => 'Máximo de bytes del mensaje',
    'Maximum Context Bytes' => 'Máximo de bytes del contexto',
    'Captured Levels' => 'Niveles capturados',
    'Include Sources and Categories' => 'Incluir orígenes y categorías',
    'Exclude Sources and Categories' => 'Excluir orígenes y categorías',
    'Category: {pattern}' => 'Categoría: {pattern}',
    'Include Request User ID' => 'Incluir ID de usuario de la solicitud',
    'Advanced' => 'Avanzado',
    'Configured Storage' => 'Almacenamiento configurado',
    'Capture new messages in Runtime Logs. Changes apply to new requests; restart long-running workers to apply them. Turning this off does not delete existing logs.' => 'Capture nuevos mensajes en los registros de tiempo de ejecución. Los cambios se aplican a nuevas solicitudes; reinicie los procesos de larga duración para aplicarlos. Desactivar esta opción no elimina los registros existentes.',
    'Limits the message text kept for each new Runtime Logs entry, in bytes rather than characters. Longer messages are shortened. File logs are unaffected.' => 'Limita el texto del mensaje conservado para cada nueva entrada de los registros de tiempo de ejecución, en bytes en lugar de caracteres. Los mensajes más largos se acortan. Los registros en archivos no se ven afectados.',
    'Limits the extra data kept for each new Runtime Logs entry, such as error details and stack traces, in bytes after JSON encoding. Larger context is shortened. File logs are unaffected.' => 'Limita los datos adicionales conservados para cada nueva entrada de los registros de tiempo de ejecución, como detalles de errores y trazas de pila, en bytes después de la codificación JSON. El contexto de mayor tamaño se acorta. Los registros en archivos no se ven afectados.',
    'How often Runtime Logs refreshes automatically. Set to 0 to disable. Current: {duration}' => 'Frecuencia de actualización automática de los registros de tiempo de ejecución. Establezca 0 para desactivarla. Actual: {duration}',
    'Choose sources by name or type a category pattern such as {pattern} and press Enter. Leave empty to capture all sources. Changes affect new messages only.' => 'Elija los orígenes por nombre o introduzca un patrón de categoría como {pattern} y pulse Intro. Deje vacío para capturar todos los orígenes. Los cambios solo afectan a los mensajes nuevos.',
    'Choose sources to skip or type a category pattern and press Enter. Exclusions take precedence. Existing entries are not removed.' => 'Elija los orígenes que desea omitir o introduzca un patrón de categoría y pulse Intro. Las exclusiones tienen prioridad. Las entradas existentes no se eliminan.',
    'When on, Runtime Logs skips command-line requests. Turn off only when diagnosing console commands; file and hosted logs are unaffected.' => 'Cuando esta opción está activada, los registros de ejecución omiten las solicitudes de línea de comandos. Desactívela solo para diagnosticar comandos de consola; los registros en archivos y del alojamiento no se ven afectados.',
    'When on, Runtime Logs skips detected queue execution. To capture console queue workers, turn off both skip switches and restart the workers. Workers can generate large volumes of logs.' => 'Cuando esta opción está activada, los registros de ejecución omiten la ejecución de cola detectada. Para capturar los procesos de cola de la consola, desactive ambas opciones de omisión y reinicie los procesos. Los procesos pueden generar grandes volúmenes de registros.',
    'Adds the authenticated request user ID. Messages and context may still contain personal data regardless of this setting.' => 'Añade el ID del usuario autenticado de la solicitud. Los mensajes y el contexto pueden contener datos personales independientemente de esta configuración.',

    'Maximum age of runtime entries, in seconds. Current: {duration}' => 'Antigüedad máxima de las entradas de registros de ejecución, en segundos. Actual: {duration}',
    'Min: {min} ({minDuration}), Max: {max} ({maxDuration})' => 'Mín.: {min} ({minDuration}), Máx.: {max} ({maxDuration})',
    '{count} second' => '{count} segundo',
    '{count} seconds' => '{count} segundos',
    '{count} minute' => '{count} minuto',
    '{count} minutes' => '{count} minutos',
    '{count} hour' => '{count} hora',
    '{count} hours' => '{count} horas',
    '{count} day' => '{count} día',
    '{count} days' => '{count} días',

    // Settings: Interface
    'Interface Settings' => 'Configuración de interfaz',

    // Log levels
    'All Levels' => 'Todos los niveles',
    'Error' => 'Error',
    'Warning' => 'Advertencia',
    'Info' => 'Info',
    'Debug' => 'Debug',
    'Unknown' => 'Desconocido',

    // Log sources
    'All Sources' => 'Todos los orígenes',
    'Web' => 'Web',
    'Console' => 'Consola',
    'Queue' => 'Queue',
    'PHP Errors' => 'Errores PHP',
    'Other' => 'Otro',
    'DB Queries' => 'Consultas de DB',
    'DB Commands' => 'Comandos de DB',
    'DB Command::{method}' => 'Comando de DB::{method}',
    'DB Connection' => 'Conexión de DB',
    'DB Connection::{method}' => 'Conexión de DB::{method}',
    'Redis Commands' => 'Comandos de Redis',
    'Redis Connection' => 'Conexión de Redis',
    'Redis Connection::{method}' => 'Conexión de Redis::{method}',
    'URL Routing' => 'Enrutamiento de URL',
    'Web Request' => 'Solicitud web',
    'Session' => 'Sesión',
    'Template Rendering' => 'Renderizado de plantillas',
    'Modules' => 'Módulos',
    'Integration Service' => 'Servicio de integración',

    // Filters
    'Select File' => 'Seleccionar archivo',
    'Select Date' => 'Seleccionar fecha',
    'Search messages and context...' => 'Buscar en mensajes y contexto...',

    // Table
    'Time' => 'Hora',
    'Level' => 'Nivel',
    'Source' => 'Origen',
    'User' => 'Usuario',
    'Request User' => 'Usuario de la solicitud',
    'User #{id}' => 'Usuario #{id}',
    'Message' => 'Mensaje',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'No se encontraron archivos de registro. Los archivos de registro se crean cuando ocurren actividades del plugin.',
    'No recent runtime logs found. Runtime logs are short-lived and only appear after matching events are captured.' => 'No se encontraron registros recientes de tiempo de ejecución. Los registros de tiempo de ejecución son efímeros y solo aparecen después de capturar eventos coincidentes.',
    'No log entries found for the selected filters.' => 'No se encontraron entradas de registro para los filtros seleccionados.',

    // Pagination
    'entry' => 'entrada',
    'entries' => 'entradas',

    // Sidebar
    'Current Level' => 'Nivel actual',
    'Current log level' => 'Nivel de registro actual',
    'Retention' => 'Retención',
    'days' => 'días',
    'Available Logs' => 'Registros disponibles',
    'file' => 'archivo',
    'files' => 'archivos',
    'Current File' => 'Archivo actual',
    'Log entries' => 'Entradas de registro',
    'Refresh Cache' => 'Actualizar caché',
    'Clear runtime logs' => 'Borrar registros de tiempo de ejecución',
    'Clear recent runtime logs? This cannot be undone.' => '¿Borrar los registros recientes de tiempo de ejecución? Esta acción no se puede deshacer.',
    'Loading' => 'Cargando',
    'Download File' => 'Descargar archivo',
    'Log Location' => 'Ubicación del registro',
    'Runtime Store' => 'Almacén de tiempo de ejecución',
    'Craft cache' => 'caché de Craft',
    'Redis unavailable' => 'Redis no disponible',
    'Redis (SELECT disabled)' => 'Redis (SELECT desactivado)',
    'Redis database {database}' => 'Base de datos Redis {database}',
    'Runtime Location' => 'Ubicación de tiempo de ejecución',
    'Dedicated Redis key' => 'Clave Redis dedicada',
    'Recent runtime logs use a bounded diagnostic store and are not complete log history.' => 'Los registros recientes de tiempo de ejecución usan un almacén de diagnóstico limitado y no constituyen un historial completo de registros.',

    // Config overrides
    'This is being overridden by the <code>{setting}</code> setting in <code>config/logging-library.php</code>.' => 'Este valor está siendo anulado por la configuración <code>{setting}</code> en <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Este valor está siendo anulado por la configuración <code>forceEnableLogViewer</code> en <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Este valor está siendo anulado por la configuración <code>showCpSection</code> en <code>config/logging-library.php</code>.',
];
