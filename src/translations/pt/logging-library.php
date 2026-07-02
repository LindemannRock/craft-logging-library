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
    'Inspect system logs, review plugin logging output, and centralize diagnostics from one control panel workspace.' => 'Inspecione os registos do sistema, reveja a saída de registos dos plugins e centralize os diagnósticos a partir de um espaço de trabalho do Painel de Controlo.',
    'Open All Logs' => 'Abrir todos os registos',
    'Open Settings' => 'Abrir as definições',

    // Navigation
    'All Logs' => 'Todos os registos',
    'Runtime Logs' => 'Registos de runtime',
    'Logs' => 'Registos',
    'Settings' => 'Definições',
    'System Logs' => 'Registos do sistema',
    'System' => 'Sistema',
    'Plugins' => 'Plugins',
    'General' => 'Geral',
    'Interface' => 'Interface',

    // Permissions
    'View all system logs' => 'Ver todos os registos do sistema',
    'Download all system logs' => 'Descarregar todos os registos do sistema',
    'Clear cache' => 'Limpar cache',
    'Manage settings' => 'Gerir definições',

    // Common
    '{displayName} caches' => 'Caches de {displayName}',

    // Controller messages
    'Settings saved.' => 'Definições guardadas.',
    'Could not save settings.' => 'Não foi possível guardar as definições.',
    'Log cache refreshed.' => 'Cache do log atualizada.',
    'Failed to refresh log cache.' => 'Não foi possível atualizar a cache do log.',
    'Recent runtime logs cleared.' => 'Logs de runtime recentes limpos.',
    'Plugin logging not configured' => 'Registo do plugin não configurado',
    'Log viewer is disabled for this plugin' => 'O visualizador de registos está desativado para este plugin',
    'Log viewer is disabled for this environment' => 'O visualizador de registos está desativado para este ambiente',
    'Recent runtime logs are disabled' => 'Os registos de runtime recentes estão desativados',
    'Log file not found' => 'Ficheiro de log não encontrado',
    'Unable to determine plugin handle from URL' => 'Não foi possível determinar o handle do plugin a partir da URL',
    'User does not have permission to view logs' => 'O utilizador não tem permissão para ver os registos',

    // Settings: General
    'General Settings' => 'Definições gerais',
    'Force Enable Log Viewers' => 'Forçar ativação dos visualizadores de registos',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Forçar a ativação de visualizadores de registos baseados em ficheiros mesmo quando é detetado um ambiente edge ou efémero. Isto afeta o Logging Library e a secção de Registos dedicada de cada plugin.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'O Logging Library detetou um ambiente edge ou efémero, por isso os visualizadores de registos baseados em ficheiros estão ocultos na vista independente <strong>Todos os registos</strong> e na secção <strong>Registos</strong> dedicada de cada plugin. O visualizador do menu principal não está disponível até ativar esta substituição. Utilize os registos nativos da sua plataforma de alojamento, ou ative a substituição se o armazenamento persistente estiver disponível.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'O Logging Library detetou um ambiente edge ou efémero, mas os visualizadores de registos baseados em ficheiros estão a ser ativados de forma forçada. Esta substituição afeta a vista independente <strong>Todos os registos</strong> e a secção <strong>Registos</strong> dedicada de cada plugin.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'O Logging Library adiciona uma vista consolidada <strong>Todos os registos</strong> ao menu principal do Painel de Controlo. Os plugins individuais mantêm as suas próprias secções de <strong>Registos</strong> dedicadas.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'A vista consolidada <strong>Todos os registos</strong> está oculta no menu principal do Painel de Controlo. Os plugins individuais mantêm as suas próprias secções de <strong>Registos</strong> dedicadas.',
    'Show Main Menu' => 'Mostrar menu principal',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Mostrar o Logging Library na navegação principal do Painel de Controlo como uma vista consolidada Todos os registos quando os visualizadores de registos baseados em ficheiros estiverem disponíveis.',

    // Settings: Interface
    'Interface Settings' => 'Definições de interface',

    // Log levels
    'All Levels' => 'Todos os níveis',
    'Error' => 'Erro',
    'Warning' => 'Aviso',
    'Info' => 'Info',
    'Debug' => 'Debug',

    // Log sources
    'All Sources' => 'Todas as origens',
    'Web' => 'Web',
    'Console' => 'Console',
    'Queue' => 'Queue',
    'PHP Errors' => 'Erros PHP',
    'Other' => 'Outro',

    // Filters
    'Select File' => 'Selecionar ficheiro',
    'Select Date' => 'Selecionar data',
    'Search messages and context...' => 'Pesquisar mensagens e contexto...',

    // Table
    'Time' => 'Hora',
    'Level' => 'Nível',
    'Source' => 'Origem',
    'User' => 'Utilizador',
    'Request User' => 'Utilizador do pedido',
    'User #{id}' => 'Utilizador #{id}',
    'Message' => 'Mensagem',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'Nenhum ficheiro de log encontrado. Os ficheiros de log são criados quando ocorrem atividades do plugin.',
    'No recent runtime logs found. Runtime logs are short-lived and only appear after matching events are captured.' => 'Nenhum registo de runtime recente encontrado. Os registos de runtime são de curta duração e só aparecem depois de eventos correspondentes serem capturados.',
    'No log entries found for the selected filters.' => 'Nenhuma entrada de registo encontrada para os filtros selecionados.',

    // Pagination
    'entry' => 'entrada',
    'entries' => 'entradas',

    // Sidebar
    'Current Level' => 'Nível atual',
    'Current log level' => 'Nível de registo atual',
    'Retention' => 'Retenção',
    'days' => 'dias',
    'Available Logs' => 'Registos disponíveis',
    'file' => 'ficheiro',
    'files' => 'ficheiros',
    'Current File' => 'Ficheiro atual',
    'Log entries' => 'Entradas de registo',
    'Refresh Cache' => 'Atualizar cache',
    'Clear Runtime Logs' => 'Limpar logs de runtime',
    'Loading' => 'A carregar',
    'Download File' => 'Transferir ficheiro',
    'Log Location' => 'Localização do log',
    'Runtime Store' => 'Armazenamento de runtime',
    'Craft cache' => 'cache Craft',
    'Redis ({cache})' => 'Redis ({cache})',
    'Runtime Location' => 'Localização de runtime',
    'Recent runtime logs are stored in Craft cache and are intended for short-lived diagnostics, not complete log history.' => 'Os registos de runtime recentes são armazenados na cache Craft e destinam-se a diagnósticos de curta duração, não ao histórico completo de registos.',

    // Config overrides
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Isto está a ser substituído pela definição <code>forceEnableLogViewer</code> em <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Isto está a ser substituído pela definição <code>showCpSection</code> em <code>config/logging-library.php</code>.',
];
