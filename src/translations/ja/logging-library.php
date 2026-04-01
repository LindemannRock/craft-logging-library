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
    'Show Main Menu' => 'メインメニューを表示',
    'Show Logging Library in the main Control Panel navigation. When disabled, All Logs remains accessible from plugin settings and direct URLs.' => 'コントロールパネルのメインナビゲーションに Logging Library を表示します。無効にした場合でも、すべてのログはプラグイン設定および直接 URL からアクセスできます。',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'この設定は <code>config/logging-library.php</code> の <code>showCpSection</code> 設定によって上書きされています。',

    // Settings: Interface
    'Interface Settings' => 'インターフェース設定',
    'Items Per Page' => '1 ページあたりの件数',
    'Number of log entries to display per page in the log viewers' => 'ログビューアーで 1 ページに表示するログエントリの件数',
    'This is being overridden by the <code>itemsPerPage</code> setting in <code>config/logging-library.php</code>.' => 'この設定は <code>config/logging-library.php</code> の <code>itemsPerPage</code> 設定によって上書きされています。',
];
