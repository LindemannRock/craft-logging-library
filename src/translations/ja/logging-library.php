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
    'Inspect system logs, review plugin logging output, and centralize diagnostics from one control panel workspace.' => 'システムログの確認、プラグインのログ出力の確認、コントロールパネルのワークスペースからの診断の一元管理が行えます。',
    'Open All Logs' => 'すべてのログを開く',

    // Navigation
    'All Logs' => 'すべてのログ',
    'Logs' => 'ログ',
    'Settings' => '設定',
    'System Logs' => 'システムログ',
    'System' => 'システム',
    'General' => '一般',
    'Interface' => 'インターフェース',

    // Log levels
    'All Levels' => 'すべてのレベル',
    'Error' => 'エラー',
    'Warning' => '警告',
    'Info' => '情報',
    'Debug' => 'デバッグ',

    // Log sources
    'All Sources' => 'すべてのソース',
    'Web' => 'Web',
    'Console' => 'コンソール',
    'Queue' => 'Queue',
    'PHP Errors' => 'PHP エラー',
    'Other' => 'その他',

    // Filters
    'Select File' => 'ファイルを選択',
    'Select Date' => '日付を選択',
    'Search messages and context...' => 'メッセージとコンテキストを検索...',

    // Table
    'Time' => '時刻',
    'Level' => 'レベル',
    'Source' => 'ソース',
    'User' => 'ユーザー',
    'Message' => 'メッセージ',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'ログファイルが見つかりません。プラグインのアクティビティが発生するとログファイルが作成されます。',
    'No log entries found for the selected filters.' => '選択したフィルターに一致するログエントリが見つかりません。',

    // Pagination
    'entry' => 'エントリ',
    'entries' => 'エントリ',

    // Row detail
    'Context' => 'コンテキスト',
    'No context data available.' => 'コンテキストデータがありません。',

    // Sidebar
    'Current Level' => '現在のレベル',
    'Current log level' => '現在のログレベル',
    'Retention' => '保持期間',
    'days' => '日',
    'Available Logs' => '利用可能なログ',
    'file' => 'ファイル',
    'files' => 'ファイル',
    'Current File' => '現在のファイル',
    'Entries' => 'エントリ',
    'Download File' => 'ファイルをダウンロード',
    'Log Location' => 'ログの場所',

    // Common
    'Save Settings' => '設定を保存',

    // Controller messages
    'Settings saved.' => '設定を保存しました。',
    'Could not save settings.' => '設定を保存できませんでした。',

    // Validation messages
    'Value must be a whole number.' => '値は整数である必要があります。',

    // Settings: General
    'General Settings' => '一般設定',
    'Plugin Name' => 'プラグイン名',
    'The name of the plugin as it appears in the Control Panel menu' => 'コントロールパネルのメニューに表示されるプラグインの名前',
    'This is being overridden by the <code>pluginName</code> setting in <code>config/logging-library.php</code>.' => 'この設定は <code>config/logging-library.php</code> の <code>pluginName</code> 設定によって上書きされています。',
    'Force Enable Log Viewers' => 'ログビューアーを強制有効化',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'エッジ環境またはエフェメラル環境が検出された場合でも、ファイルベースのログビューアーを強制的に有効にします。これは Logging Library およびすべてのプラグインの専用 Logs セクションに影響します。',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'この設定は <code>config/logging-library.php</code> の <code>forceEnableLogViewer</code> 設定によって上書きされています。',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library がエッジ環境またはエフェメラル環境を検出したため、ファイルベースのログビューアーはスタンドアロンの <strong>すべてのログ</strong> ビューおよびすべてのプラグインの専用 <strong>ログ</strong> セクションで非表示になっています。このオーバーライドを有効にするまで、メインメニューのビューアーは使用できません。ホスティングプラットフォームのネイティブログを使用するか、永続ストレージが利用可能な場合はオーバーライドを有効にしてください。',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library がエッジ環境またはエフェメラル環境を検出しましたが、ファイルベースのログビューアーは強制的に有効化されています。このオーバーライドはスタンドアロンの <strong>すべてのログ</strong> ビューおよびすべてのプラグインの専用 <strong>ログ</strong> セクションに影響します。',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library はコントロールパネルのメインメニューに統合された <strong>すべてのログ</strong> ビューを追加します。個々のプラグインは引き続き独自の専用 <strong>ログ</strong> セクションを保持します。',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => '統合された <strong>すべてのログ</strong> ビューはコントロールパネルのメインメニューで非表示になっています。個々のプラグインは引き続き独自の専用 <strong>ログ</strong> セクションを保持します。',
    'Show Main Menu' => 'メインメニューを表示',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'ファイルベースのログビューアーが利用可能な場合に、コントロールパネルのメインナビゲーションに Logging Library を統合された すべてのログ ビューとして表示します。',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'この設定は <code>config/logging-library.php</code> の <code>showCpSection</code> 設定によって上書きされています。',

    // Settings: Interface
    'Interface Settings' => 'インターフェース設定',
    'Items Per Page' => '1 ページあたりの件数',
    'Number of log entries to display per page in the log viewers' => 'ログビューアーで 1 ページに表示するログエントリの件数',
    'This is being overridden by the <code>itemsPerPage</code> setting in <code>config/logging-library.php</code>.' => 'この設定は <code>config/logging-library.php</code> の <code>itemsPerPage</code> 設定によって上書きされています。',
];
