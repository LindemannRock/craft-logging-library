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
    'View all system logs' => 'Ver todos los registros del sistema',
    'Download all system logs' => 'Descargar todos los registros del sistema',
    'Clear cache' => 'Vaciar caché',
    'Manage settings' => 'Gestionar configuración',

    // Common
    '{displayName} caches' => 'Cachés de {displayName}',

    // Controller messages
    'Settings saved.' => 'Configuración guardada.',
    'Could not save settings.' => 'No se pudo guardar la configuración.',
    'Log cache refreshed.' => 'Caché del registro actualizada.',
    'Failed to refresh log cache.' => 'No se pudo actualizar la caché del registro.',
    'Recent runtime logs cleared.' => 'Registros recientes de tiempo de ejecución borrados.',
    'Plugin logging not configured' => 'Registros del plugin no configurados',
    'Log viewer is disabled for this plugin' => 'El visor de registros está desactivado para este plugin',
    'Log viewer is disabled for this environment' => 'El visor de registros está desactivado para este entorno',
    'Recent runtime logs are disabled' => 'Los registros recientes de tiempo de ejecución están desactivados',
    'Log file not found' => 'Archivo de registro no encontrado',
    'Unable to determine plugin handle from URL' => 'No se pudo determinar el identificador del plugin a partir de la URL',
    'User does not have permission to view logs' => 'El usuario no tiene permiso para ver los registros',

    // Settings: General
    'General Settings' => 'Configuración general',
    'Force Enable Log Viewers' => 'Forzar activación de visores de registros',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Forzar la activación de visores de registros basados en archivos incluso cuando se detecta un entorno edge o efímero. Esto afecta a Logging Library y a la sección de Registros dedicada de cada plugin.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library detectó un entorno edge o efímero, por lo que los visores de registros basados en archivos están ocultos para la vista independiente <strong>Todos los registros</strong> y para la sección <strong>Registros</strong> dedicada de cada plugin. El visor del menú principal no está disponible hasta que active esta anulación. Utilice los registros nativos de su plataforma de alojamiento o active la anulación si hay almacenamiento persistente disponible.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library detectó un entorno edge o efímero, pero los visores de registros basados en archivos están siendo activados de forma forzada. Esta anulación afecta a la vista independiente <strong>Todos los registros</strong> y a la sección <strong>Registros</strong> dedicada de cada plugin.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library añade una vista consolidada <strong>Todos los registros</strong> al menú principal del panel de control. Los plugins individuales conservan sus propias secciones de <strong>Registros</strong> dedicadas.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'La vista consolidada <strong>Todos los registros</strong> está oculta en el menú principal del panel de control. Los plugins individuales conservan sus propias secciones de <strong>Registros</strong> dedicadas.',
    'Show Main Menu' => 'Mostrar menú principal',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Mostrar Logging Library en la navegación principal del panel de control como una vista consolidada Todos los registros cuando los visores de registros basados en archivos estén disponibles.',

    // Settings: Interface
    'Interface Settings' => 'Configuración de interfaz',

    // Log levels
    'All Levels' => 'Todos los niveles',
    'Error' => 'Error',
    'Warning' => 'Advertencia',
    'Info' => 'Info',
    'Debug' => 'Debug',

    // Log sources
    'All Sources' => 'Todos los orígenes',
    'Web' => 'Web',
    'Console' => 'Consola',
    'Queue' => 'Queue',
    'PHP Errors' => 'Errores PHP',
    'Other' => 'Otro',

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
    'Clear Runtime Logs' => 'Borrar registros de tiempo de ejecución',
    'Loading' => 'Cargando',
    'Download File' => 'Descargar archivo',
    'Log Location' => 'Ubicación del registro',
    'Runtime Store' => 'Almacén de tiempo de ejecución',
    'Craft cache' => 'caché de Craft',
    'Redis ({cache})' => 'Redis ({cache})',
    'Runtime Location' => 'Ubicación de tiempo de ejecución',
    'Recent runtime logs are stored in Craft cache and are intended for short-lived diagnostics, not complete log history.' => 'Los registros recientes de tiempo de ejecución se almacenan en la caché de Craft y están pensados para diagnósticos efímeros, no para el historial completo de registros.',

    // Config overrides
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Este valor está siendo anulado por la configuración <code>forceEnableLogViewer</code> en <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Este valor está siendo anulado por la configuración <code>showCpSection</code> en <code>config/logging-library.php</code>.',
];
