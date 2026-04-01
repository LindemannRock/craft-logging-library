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
    'Inspect system logs, review plugin logging output, and centralize diagnostics from one control panel workspace.' => 'Inspecione os registros do sistema, revise a saída de registro dos plugins e centralize os diagnósticos a partir de um espaço de trabalho do painel de controle.',
    'Open All Logs' => 'Abrir todos os registros',

    // Navigation
    'All Logs' => 'Todos os registros',
    'Logs' => 'Registros',
    'Settings' => 'Configurações',
    'System Logs' => 'Registros do sistema',
    'System' => 'Sistema',
    'General' => 'Geral',
    'Interface' => 'Interface',

    // Log levels
    'All Levels' => 'Todos os níveis',
    'Error' => 'Erro',
    'Warning' => 'Aviso',
    'Info' => 'Info',
    'Debug' => 'Debug',

    // Log sources
    'All Sources' => 'Todas as fontes',
    'Web' => 'Web',
    'Console' => 'Console',
    'Queue' => 'Queue',
    'PHP Errors' => 'Erros PHP',
    'Other' => 'Outro',

    // Filters
    'Select File' => 'Selecionar arquivo',
    'Select Date' => 'Selecionar data',
    'Search messages and context...' => 'Pesquisar mensagens e contexto...',

    // Table
    'Time' => 'Hora',
    'Level' => 'Nível',
    'Source' => 'Fonte',
    'User' => 'Usuário',
    'Message' => 'Mensagem',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'Nenhum arquivo de registro encontrado. Os arquivos de registro são criados quando ocorrem atividades do plugin.',
    'No log entries found for the selected filters.' => 'Nenhuma entrada de registro encontrada para os filtros selecionados.',

    // Pagination
    'entry' => 'entrada',
    'entries' => 'entradas',

    // Row detail
    'Context' => 'Contexto',
    'No context data available.' => 'Nenhum dado de contexto disponível.',

    // Sidebar
    'Current Level' => 'Nível atual',
    'Current log level' => 'Nível de registro atual',
    'Retention' => 'Retenção',
    'days' => 'dias',
    'Available Logs' => 'Registros disponíveis',
    'file' => 'arquivo',
    'files' => 'arquivos',
    'Current File' => 'Arquivo atual',
    'Entries' => 'Entradas',
    'Download File' => 'Baixar arquivo',
    'Log Location' => 'Localização do registro',

    // Common
    'Save Settings' => 'Salvar configurações',

    // Controller messages
    'Settings saved.' => 'Configurações salvas.',
    'Could not save settings.' => 'Não foi possível salvar as configurações.',

    // Validation messages
    'Value must be a whole number.' => 'O valor deve ser um número inteiro.',

    // Settings: General
    'General Settings' => 'Configurações gerais',
    'Plugin Name' => 'Nome do plugin',
    'The name of the plugin as it appears in the Control Panel menu' => 'O nome do plugin como aparece no menu do painel de controle',
    'This is being overridden by the <code>pluginName</code> setting in <code>config/logging-library.php</code>.' => 'Este valor está sendo substituído pela configuração <code>pluginName</code> em <code>config/logging-library.php</code>.',
    'Force Enable Log Viewers' => 'Forçar habilitação de visualizadores de registros',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Forçar a habilitação de visualizadores de registros baseados em arquivos mesmo quando um ambiente edge ou efêmero é detectado. Isso afeta o Logging Library e a seção de Registros dedicada de cada plugin.',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Este valor está sendo substituído pela configuração <code>forceEnableLogViewer</code> em <code>config/logging-library.php</code>.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'O Logging Library detectou um ambiente edge ou efêmero, portanto os visualizadores de registros baseados em arquivos estão ocultos na vista independente <strong>Todos os registros</strong> e na seção <strong>Registros</strong> dedicada de cada plugin. O visualizador do menu principal não está disponível até que você habilite essa substituição. Use os registros nativos da sua plataforma de hospedagem ou habilite a substituição se o armazenamento persistente estiver disponível.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'O Logging Library detectou um ambiente edge ou efêmero, mas os visualizadores de registros baseados em arquivos estão sendo habilitados de forma forçada. Essa substituição afeta a vista independente <strong>Todos os registros</strong> e a seção <strong>Registros</strong> dedicada de cada plugin.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'O Logging Library adiciona uma vista consolidada <strong>Todos os registros</strong> ao menu principal do painel de controle. Os plugins individuais ainda mantêm suas próprias seções de <strong>Registros</strong> dedicadas.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'A vista consolidada <strong>Todos os registros</strong> está oculta no menu principal do painel de controle. Os plugins individuais ainda mantêm suas próprias seções de <strong>Registros</strong> dedicadas.',
    'Show Main Menu' => 'Exibir menu principal',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Exibir Logging Library na navegação principal do painel de controle como uma vista consolidada Todos os registros quando os visualizadores de registros baseados em arquivos estiverem disponíveis.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Este valor está sendo substituído pela configuração <code>showCpSection</code> em <code>config/logging-library.php</code>.',

    // Settings: Interface
    'Interface Settings' => 'Configurações de interface',
    'Items Per Page' => 'Itens por página',
    'Number of log entries to display per page in the log viewers' => 'Número de entradas de registro a exibir por página nos visualizadores de registros',
    'This is being overridden by the <code>itemsPerPage</code> setting in <code>config/logging-library.php</code>.' => 'Este valor está sendo substituído pela configuração <code>itemsPerPage</code> em <code>config/logging-library.php</code>.',
];
