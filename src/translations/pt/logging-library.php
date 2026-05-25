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
    'Inspect system logs, review plugin logging output, and centralize diagnostics from one control panel workspace.' => 'Inspecione os logs do sistema, reveja a saída de log dos plugins e centralize os diagnósticos a partir de um espaço de trabalho do Painel de Controlo.',
    'Open All Logs' => 'Abrir todos os logs',
    'Open Settings' => 'Abrir as definições',

    // Navigation
    'All Logs' => 'Todos os logs',
    'Logs' => 'Logs',
    'Settings' => 'Definições',
    'System Logs' => 'Logs do sistema',
    'System' => 'Sistema',
    'General' => 'Geral',
    'Interface' => 'Interface',

    // Permissions
    'View all system logs' => 'Ver todos os logs do sistema',
    'Download all system logs' => 'Transferir todos os logs do sistema',
    'Clear cache' => 'Limpar cache',
    'Manage settings' => 'Gerir definições',

    // Common
    'Save Settings' => 'Guardar definições',
    '{displayName} caches' => 'Caches de {displayName}',

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
    'User #{id}' => 'Utilizador #{id}',
    'Message' => 'Mensagem',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'Nenhum ficheiro de log encontrado. Os ficheiros de log são criados quando ocorrem atividades do plugin.',
    'No log entries found for the selected filters.' => 'Nenhuma entrada de log encontrada para os filtros selecionados.',

    // Pagination
    'entry' => 'entrada',
    'entries' => 'entradas',

    // Row detail
    'Context' => 'Contexto',
    'No context data available.' => 'Nenhum dado de contexto disponível.',

    // Sidebar
    'Current Level' => 'Nível atual',
    'Current log level' => 'Nível de log atual',
    'Retention' => 'Retenção',
    'days' => 'dias',
    'Available Logs' => 'Logs disponíveis',
    'file' => 'ficheiro',
    'files' => 'ficheiros',
    'Current File' => 'Ficheiro atual',
    'Entries' => 'Entradas',
    'Refresh Cache' => 'Atualizar cache',
    'Loading' => 'A carregar',
    'Download File' => 'Transferir ficheiro',
    'Log Location' => 'Localização do log',

    // Controller messages
    'Settings saved.' => 'Definições guardadas.',
    'Could not save settings.' => 'Não foi possível guardar as definições.',
    'Log cache refreshed.' => 'Cache do log atualizada.',
    'Failed to refresh log cache.' => 'Não foi possível atualizar a cache do log.',
    'Plugin logging not configured' => 'Log do plugin não configurado',
    'Log viewer is disabled for this plugin' => 'O visualizador de logs está desativado para este plugin',
    'Log viewer is disabled for this environment' => 'O visualizador de logs está desativado para este ambiente',
    'Log file not found' => 'Ficheiro de log não encontrado',
    'Unable to determine plugin handle from URL' => 'Não foi possível determinar o handle do plugin a partir da URL',
    'User does not have permission to view logs' => 'O utilizador não tem permissão para ver os logs',

    // Validation messages
    'Found {count, number} {count, plural, =1{error} other{errors}}' => '{count, number} {count, plural, =1{erro encontrado} other{erros encontrados}}',
    'Value must be a whole number.' => 'O valor deve ser um número inteiro.',

    // Settings: General
    'General Settings' => 'Definições gerais',
    'Force Enable Log Viewers' => 'Forçar ativação dos visualizadores de logs',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Forçar a ativação de visualizadores de logs baseados em ficheiros mesmo quando é detetado um ambiente edge ou efémero. Isto afeta o Logging Library e a secção de Logs dedicada de cada plugin.',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Isto está a ser substituído pela definição <code>forceEnableLogViewer</code> em <code>config/logging-library.php</code>.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'O Logging Library detetou um ambiente edge ou efémero, por isso os visualizadores de logs baseados em ficheiros estão ocultos na vista independente <strong>Todos os logs</strong> e na secção <strong>Logs</strong> dedicada de cada plugin. O visualizador do menu principal não está disponível até ativar esta substituição. Utilize os logs nativos da sua plataforma de alojamento, ou ative a substituição se o armazenamento persistente estiver disponível.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'O Logging Library detetou um ambiente edge ou efémero, mas os visualizadores de logs baseados em ficheiros estão a ser ativados de forma forçada. Esta substituição afeta a vista independente <strong>Todos os logs</strong> e a secção <strong>Logs</strong> dedicada de cada plugin.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'O Logging Library adiciona uma vista consolidada <strong>Todos os logs</strong> ao menu principal do Painel de Controlo. Os plugins individuais mantêm as suas próprias secções de <strong>Logs</strong> dedicadas.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'A vista consolidada <strong>Todos os logs</strong> está oculta no menu principal do Painel de Controlo. Os plugins individuais mantêm as suas próprias secções de <strong>Logs</strong> dedicadas.',
    'Show Main Menu' => 'Mostrar menu principal',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Mostrar o Logging Library na navegação principal do Painel de Controlo como uma vista consolidada Todos os logs quando os visualizadores de logs baseados em ficheiros estiverem disponíveis.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Isto está a ser substituído pela definição <code>showCpSection</code> em <code>config/logging-library.php</code>.',

    // Settings: Interface
    'Interface Settings' => 'Definições de interface',
];
