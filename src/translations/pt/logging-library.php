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
    'Show Main Menu' => 'Exibir menu principal',
    'Show Logging Library in the main Control Panel navigation. When disabled, All Logs remains accessible from plugin settings and direct URLs.' => 'Exibir Logging Library na navegação principal do painel de controle. Quando desativado, Todos os registros permanece acessível pelas configurações do plugin e URLs diretas.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Este valor está sendo substituído pela configuração <code>showCpSection</code> em <code>config/logging-library.php</code>.',

    // Settings: Interface
    'Interface Settings' => 'Configurações de interface',
    'Items Per Page' => 'Itens por página',
    'Number of log entries to display per page in the log viewers' => 'Número de entradas de registro a exibir por página nos visualizadores de registros',
    'This is being overridden by the <code>itemsPerPage</code> setting in <code>config/logging-library.php</code>.' => 'Este valor está sendo substituído pela configuração <code>itemsPerPage</code> em <code>config/logging-library.php</code>.',
];
