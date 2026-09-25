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
    'File Logs' => 'Registos em ficheiros',
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
    'View all file logs' => 'Ver todos os registos em ficheiros',
    'Download all file logs' => 'Descarregar todos os registos em ficheiros',
    'Clear file log cache' => 'Limpar cache dos registos em ficheiros',
    'View runtime logs' => 'Ver registos de runtime',
    'Manage settings' => 'Gerir definições',

    // Common
    '{displayName} caches' => 'Caches de {displayName}',

    // Controller messages
    'Settings saved.' => 'Definições guardadas.',
    'Could not save settings.' => 'Não foi possível guardar as definições.',
    'Log cache refreshed.' => 'Cache do log atualizada.',
    'Failed to refresh log cache.' => 'Não foi possível atualizar a cache do log.',
    'Recent runtime logs cleared.' => 'Registos de runtime recentes limpos.',
    'Unable to clear recent runtime logs.' => 'Não foi possível limpar os registos de runtime recentes.',
    'Plugin logging not configured' => 'Registo do plugin não configurado',
    'Log viewer is disabled for this plugin' => 'O visualizador de registos está desativado para este plugin',
    'Log viewer is disabled for this environment' => 'O visualizador de registos está desativado para este ambiente',
    'Recent runtime logs are disabled' => 'Os registos de runtime recentes estão desativados',
    'Log file not found' => 'Ficheiro de log não encontrado',
    'Unable to determine plugin handle from URL' => 'Não foi possível determinar o handle do plugin a partir da URL',
    'User does not have permission to view logs' => 'O utilizador não tem permissão para ver os registos',

    // Settings: General
    'Show Logging Library in the main navigation. This does not enable or disable log capture.' => 'Mostrar Logging Library na navegação principal. Isto não ativa nem desativa a captura de registos.',
    'General Settings' => 'Definições gerais',
    'Force Enable Log Viewers' => 'Forçar ativação dos visualizadores de registos',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'Forçar a ativação de visualizadores de registos baseados em ficheiros mesmo quando é detetado um ambiente edge ou efémero. Isto afeta o Logging Library e a secção de Registos dedicada de cada plugin.',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'O Logging Library detetou um ambiente edge ou efémero, por isso os visualizadores de registos baseados em ficheiros estão ocultos na vista independente <strong>Todos os registos</strong> e na secção <strong>Registos</strong> dedicada de cada plugin. O visualizador do menu principal não está disponível até ativar esta substituição. Utilize os registos nativos da sua plataforma de alojamento, ou ative a substituição se o armazenamento persistente estiver disponível.',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'O Logging Library detetou um ambiente edge ou efémero, mas os visualizadores de registos baseados em ficheiros estão a ser ativados de forma forçada. Esta substituição afeta a vista independente <strong>Todos os registos</strong> e a secção <strong>Registos</strong> dedicada de cada plugin.',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'O Logging Library adiciona uma vista consolidada <strong>Todos os registos</strong> ao menu principal do Painel de Controlo. Os plugins individuais mantêm as suas próprias secções de <strong>Registos</strong> dedicadas.',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'A vista consolidada <strong>Todos os registos</strong> está oculta no menu principal do Painel de Controlo. Os plugins individuais mantêm as suas próprias secções de <strong>Registos</strong> dedicadas.',
    'Show Main Menu' => 'Mostrar menu principal',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'Mostrar o Logging Library na navegação principal do Painel de Controlo como uma vista consolidada Todos os registos quando os visualizadores de registos baseados em ficheiros estiverem disponíveis.',

    // Settings: File Logs
    'Force Enable File Log Viewers' => 'Forçar ativação dos visualizadores de registos em ficheiros',
    'An edge or ephemeral environment is detected. File viewers are hidden unless forced on; {runtimeLogs} remain available when enabled. Only force file viewers on if persistent log files are available.' => 'Foi detetado um ambiente edge ou efémero. Os visualizadores de ficheiros ficam ocultos salvo ativação forçada; os {runtimeLogs} continuam disponíveis quando ativados. Force os visualizadores apenas se existirem ficheiros de registos persistentes.',
    'File viewers are available. Runtime Logs are optional and independent of file logging.' => 'Os visualizadores de ficheiros estão disponíveis. Os registos de runtime são opcionais e independentes do registo em ficheiros.',

    // Settings: Runtime Logs
    'Min: {min}, Max: {max}' => 'Mín.: {min}, Máx.: {max}',
    'Uses the application cache configuration. The Redis database can be overridden in {file}. To verify capture, trigger a log message and check Runtime Logs.' => 'Utiliza a configuração da cache da aplicação. A base de dados Redis pode ser substituída em {file}. Para verificar a captura, gere uma mensagem de registo e consulte os registos de runtime.',
    'Enable Runtime Logs' => 'Ativar registos de runtime',
    'Skip Console Requests' => 'Ignorar pedidos da consola',
    'Skip Queue Requests' => 'Ignorar pedidos da fila',
    'Retention (seconds)' => 'Retenção (segundos)',
    'Maximum Entries' => 'Máximo de entradas',
    'Refresh Interval (seconds)' => 'Intervalo de atualização (segundos)',
    'Maximum Message Bytes' => 'Máximo de bytes da mensagem',
    'Maximum Context Bytes' => 'Máximo de bytes do contexto',
    'Captured Levels' => 'Níveis capturados',
    'Include Sources and Categories' => 'Incluir origens e categorias',
    'Exclude Sources and Categories' => 'Excluir origens e categorias',
    'Category: {pattern}' => 'Categoria: {pattern}',
    'Include Request User ID' => 'Incluir ID do utilizador do pedido',
    'Advanced' => 'Avançado',
    'Configured Storage' => 'Armazenamento configurado',
    'Capture new messages in Runtime Logs. Changes apply to new requests; restart long-running workers to apply them. Turning this off does not delete existing logs.' => 'Capture novas mensagens nos registos de runtime. As alterações aplicam-se a novos pedidos; reinicie os processos de longa duração para as aplicar. Desativar esta opção não elimina os registos existentes.',
    'Limits the message text kept for each new Runtime Logs entry, in bytes rather than characters. Longer messages are shortened. File logs are unaffected.' => 'Limita o texto da mensagem guardado para cada nova entrada dos registos de runtime, em bytes e não em caracteres. As mensagens mais longas são encurtadas. Os registos em ficheiros não são afetados.',
    'Limits the extra data kept for each new Runtime Logs entry, such as error details and stack traces, in bytes after JSON encoding. Larger context is shortened. File logs are unaffected.' => 'Limita os dados adicionais guardados para cada nova entrada dos registos de runtime, como detalhes de erros e rastreios da pilha, em bytes após a codificação JSON. O contexto de maior dimensão é encurtado. Os registos em ficheiros não são afetados.',
    'How often Runtime Logs refreshes automatically. Set to 0 to disable. Current: {duration}' => 'Frequência de atualização automática dos registos de runtime. Defina 0 para desativar. Atual: {duration}',
    'Choose sources by name or type a category pattern such as {pattern} and press Enter. Leave empty to capture all sources. Changes affect new messages only.' => 'Escolha as origens pelo nome ou introduza um padrão de categoria como {pattern} e prima Enter. Deixe vazio para capturar todas as origens. As alterações afetam apenas as mensagens novas.',
    'Choose sources to skip or type a category pattern and press Enter. Exclusions take precedence. Existing entries are not removed.' => 'Escolha as origens a ignorar ou introduza um padrão de categoria e prima Enter. As exclusões têm prioridade. As entradas existentes não são removidas.',
    'When on, Runtime Logs skips command-line requests. Turn off only when diagnosing console commands; file and hosted logs are unaffected.' => 'Quando esta opção está ativada, os registos de runtime ignoram os pedidos da linha de comandos. Desative-a apenas para diagnosticar comandos da consola; os registos em ficheiros e do alojamento não são afetados.',
    'When on, Runtime Logs skips detected queue execution. To capture console queue workers, turn off both skip switches and restart the workers. Workers can generate large volumes of logs.' => 'Quando esta opção está ativada, os registos de runtime ignoram a execução da fila detetada. Para capturar os processos da fila da consola, desative ambas as opções de exclusão e reinicie os processos. Os processos podem gerar grandes volumes de registos.',
    'Adds the authenticated request user ID. Messages and context may still contain personal data regardless of this setting.' => 'Adiciona o ID do utilizador autenticado do pedido. As mensagens e o contexto podem conter dados pessoais independentemente desta definição.',

    'Maximum age of runtime entries, in seconds. Current: {duration}' => 'Idade máxima das entradas dos registos de runtime, em segundos. Atual: {duration}',
    'Min: {min} ({minDuration}), Max: {max} ({maxDuration})' => 'Mín.: {min} ({minDuration}), Máx.: {max} ({maxDuration})',
    '{count} second' => '{count} segundo',
    '{count} seconds' => '{count} segundos',
    '{count} minute' => '{count} minuto',
    '{count} minutes' => '{count} minutos',
    '{count} hour' => '{count} hora',
    '{count} hours' => '{count} horas',
    '{count} day' => '{count} dia',
    '{count} days' => '{count} dias',

    // Settings: Interface
    'Interface Settings' => 'Definições de interface',

    // Log levels
    'All Levels' => 'Todos os níveis',
    'Error' => 'Erro',
    'Warning' => 'Aviso',
    'Info' => 'Info',
    'Debug' => 'Debug',
    'Unknown' => 'Desconhecido',

    // Log sources
    'All Sources' => 'Todas as origens',
    'Web' => 'Web',
    'Console' => 'Console',
    'Queue' => 'Queue',
    'PHP Errors' => 'Erros PHP',
    'Other' => 'Outro',
    'DB Queries' => 'Consultas DB',
    'DB Commands' => 'Comandos DB',
    'DB Command::{method}' => 'Comando DB::{method}',
    'DB Connection' => 'Ligação DB',
    'DB Connection::{method}' => 'Ligação DB::{method}',
    'Redis Commands' => 'Comandos Redis',
    'Redis Connection' => 'Ligação Redis',
    'Redis Connection::{method}' => 'Ligação Redis::{method}',
    'URL Routing' => 'Encaminhamento de URL',
    'Web Request' => 'Pedido Web',
    'Session' => 'Sessão',
    'Template Rendering' => 'Renderização de templates',
    'Modules' => 'Módulos',
    'Integration Service' => 'Serviço de integração',

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
    'Clear runtime logs' => 'Limpar registos de runtime',
    'Clear recent runtime logs? This cannot be undone.' => 'Limpar os registos de runtime recentes? Esta ação não pode ser anulada.',
    'Loading' => 'A carregar',
    'Download File' => 'Transferir ficheiro',
    'Log Location' => 'Localização do log',
    'Runtime Store' => 'Armazenamento de runtime',
    'Craft cache' => 'cache Craft',
    'Redis unavailable' => 'Redis indisponível',
    'Redis (SELECT disabled)' => 'Redis (SELECT desativado)',
    'Redis database {database}' => 'Base de dados Redis {database}',
    'Runtime Location' => 'Localização de runtime',
    'Dedicated Redis key' => 'Chave Redis dedicada',
    'Recent runtime logs use a bounded diagnostic store and are not complete log history.' => 'Os registos de runtime recentes usam um armazenamento de diagnóstico limitado e não constituem um histórico completo de registos.',

    // Config overrides
    'This is being overridden by the <code>{setting}</code> setting in <code>config/logging-library.php</code>.' => 'Isto está a ser substituído pela definição <code>{setting}</code> em <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'Isto está a ser substituído pela definição <code>forceEnableLogViewer</code> em <code>config/logging-library.php</code>.',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'Isto está a ser substituído pela definição <code>showCpSection</code> em <code>config/logging-library.php</code>.',
];
