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

    // Navigation
    'All Logs' => 'Todos los registros',
    'Logs' => 'Registros',
    'Settings' => 'Configuración',
    'System Logs' => 'Registros del sistema',
    'System' => 'Sistema',
    'General' => 'General',
    'Interface' => 'Interfaz',

    // Log levels
    'All Levels' => 'Todos los niveles',
    'Error' => 'Error',
    'Warning' => 'Advertencia',
    'Info' => 'Info',
    'Debug' => 'Debug',

    // Log sources
    'All Sources' => 'Todas las fuentes',
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
    'Source' => 'Fuente',
    'User' => 'Usuario',
    'Message' => 'Mensaje',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'No se encontraron archivos de registro. Los archivos de registro se crean cuando ocurren actividades del plugin.',
    'No log entries found for the selected filters.' => 'No se encontraron entradas de registro para los filtros seleccionados.',

    // Pagination
    'entry' => 'entrada',
    'entries' => 'entradas',

    // Row detail
    'Context' => 'Contexto',
    'No context data available.' => 'No hay datos de contexto disponibles.',

    // Sidebar
    'Current Level' => 'Nivel actual',
    'Current log level' => 'Nivel de registro actual',
    'Retention' => 'Retención',
    'days' => 'días',
    'Available Logs' => 'Registros disponibles',
    'file' => 'archivo',
    'files' => 'archivos',
    'Current File' => 'Archivo actual',
    'Entries' => 'Entradas',
    'Download File' => 'Descargar archivo',
    'Log Location' => 'Ubicación del registro',

    // Common
    'Save Settings' => 'Guardar configuración',

    // Controller messages
    'Settings saved.' => 'Configuración guardada.',
    'Could not save settings.' => 'No se pudo guardar la configuración.',

    // Validation messages
    'Value must be a whole number.' => 'El valor debe ser un número entero.',

    // Settings: General
    'General Settings' => 'Configuración general',
    'Plugin Name' => 'Nombre del plugin',
    'The name of the plugin as it appears in the Control Panel menu' => 'El nombre del plugin tal como aparece en el menú del panel de control',
    'This is being overridden by the <code>pluginName</code> setting in <code>config/logging-library.php</code>.' => 'Este valor está siendo anulado por la configuración <code>pluginName</code> en <code>config/logging-library.php</code>.',
    'Show Main Menu' => 'Mostrar menú principal',
    'Show Logging Library in the main Control Panel navigation. When disabled, All Logs remains accessible from plugin settings and direct URLs.' => 'Mostrar Logging Library en la navegación principal del panel de control. Cuando está desactivado, Todos los registros sigue siendo accesible desde la configuración del plugin y las URL directas.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Este valor está siendo anulado por la configuración <code>showCpSection</code> en <code>config/logging-library.php</code>.',

    // Settings: Interface
    'Interface Settings' => 'Configuración de interfaz',
    'Items Per Page' => 'Elementos por página',
    'Number of log entries to display per page in the log viewers' => 'Número de entradas de registro que se mostrarán por página en los visores de registros',
    'This is being overridden by the <code>itemsPerPage</code> setting in <code>config/logging-library.php</code>.' => 'Este valor está siendo anulado por la configuración <code>itemsPerPage</code> en <code>config/logging-library.php</code>.',
];
