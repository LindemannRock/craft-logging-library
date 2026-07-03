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
    'Open Settings' => '設定を開く',

    // Navigation
    'All Logs' => 'すべてのログ',
    'Runtime Logs' => 'ランタイムログ',
    'Logs' => 'ログ',
    'Settings' => '設定',
    'System Logs' => 'システムログ',
    'System' => 'システム',
    'Plugins' => 'プラグイン',
    'General' => '一般',
    'Interface' => 'インターフェース',

    // Permissions
    'View all system logs' => 'すべてのシステムログを表示する',
    'Download all system logs' => 'すべてのシステムログをダウンロードする',
    'Clear cache' => 'キャッシュを削除する',
    'Manage settings' => '設定を管理する',

    // Common
    '{displayName} caches' => '{displayName} のキャッシュ',

    // Controller messages
    'Settings saved.' => '設定を保存しました。',
    'Could not save settings.' => '設定を保存できませんでした。',
    'Log cache refreshed.' => 'ログキャッシュを更新しました。',
    'Failed to refresh log cache.' => 'ログキャッシュを更新できませんでした。',
    'Recent runtime logs cleared.' => '最近のランタイムログを削除しました。',
    'Unable to clear recent runtime logs.' => '最近のランタイムログを削除できません。',
    'Plugin logging not configured' => 'プラグインのログ機能が設定されていません',
    'Log viewer is disabled for this plugin' => 'このプラグインではログビューアーが無効になっています',
    'Log viewer is disabled for this environment' => 'この環境ではログビューアーが無効になっています',
    'Recent runtime logs are disabled' => '最近のランタイムログは無効になっています',
    'Log file not found' => 'ログファイルが見つかりません',
    'Unable to determine plugin handle from URL' => 'URL からプラグイン Handle を特定できません',
    'User does not have permission to view logs' => 'ユーザーにログを表示する権限がありません',

    // Settings: General
    'General Settings' => '一般設定',
    'Force Enable Log Viewers' => 'ログビューアーを強制有効化',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'エッジ環境またはエフェメラル環境が検出された場合でも、ファイルベースのログビューアーを強制的に有効にします。これは Logging Library およびすべてのプラグインの専用 Logs セクションに影響します。',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library がエッジ環境またはエフェメラル環境を検出したため、ファイルベースのログビューアーはスタンドアロンの <strong>すべてのログ</strong> ビューおよびすべてのプラグインの専用 <strong>ログ</strong> セクションで非表示になっています。このオーバーライドを有効にするまで、メインメニューのビューアーは使用できません。ホスティングプラットフォームのネイティブログを使用するか、永続ストレージが利用可能な場合はオーバーライドを有効にしてください。',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library がエッジ環境またはエフェメラル環境を検出しましたが、ファイルベースのログビューアーは強制的に有効化されています。このオーバーライドはスタンドアロンの <strong>すべてのログ</strong> ビューおよびすべてのプラグインの専用 <strong>ログ</strong> セクションに影響します。',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library はコントロールパネルのメインメニューに統合された <strong>すべてのログ</strong> ビューを追加します。個々のプラグインは引き続き独自の専用 <strong>ログ</strong> セクションを保持します。',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => '統合された <strong>すべてのログ</strong> ビューはコントロールパネルのメインメニューで非表示になっています。個々のプラグインは引き続き独自の専用 <strong>ログ</strong> セクションを保持します。',
    'Show Main Menu' => 'メインメニューを表示',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'ファイルベースのログビューアーが利用可能な場合に、コントロールパネルのメインナビゲーションに Logging Library を統合された すべてのログ ビューとして表示します。',

    // Settings: Interface
    'Interface Settings' => 'インターフェース設定',

    // Log levels
    'All Levels' => 'すべてのレベル',
    'Error' => 'エラー',
    'Warning' => '警告',
    'Info' => '情報',
    'Debug' => 'デバッグ',
    'Unknown' => '不明',

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
    'Request User' => 'リクエストユーザー',
    'User #{id}' => 'ユーザー #{id}',
    'Message' => 'メッセージ',

    // Table empty
    'No log files found. Log files are created when plugin activities occur.' => 'ログファイルが見つかりません。プラグインのアクティビティが発生するとログファイルが作成されます。',
    'No recent runtime logs found. Runtime logs are short-lived and only appear after matching events are captured.' => '最近のランタイムログが見つかりません。ランタイムログは短期間のみ保持され、一致するイベントが取得された後にのみ表示されます。',
    'No log entries found for the selected filters.' => '選択したフィルターに一致するログエントリが見つかりません。',

    // Pagination
    'entry' => 'エントリ',
    'entries' => 'エントリ',

    // Sidebar
    'Current Level' => '現在のレベル',
    'Current log level' => '現在のログレベル',
    'Retention' => '保持期間',
    'days' => '日',
    'Available Logs' => '利用可能なログ',
    'file' => 'ファイル',
    'files' => 'ファイル',
    'Current File' => '現在のファイル',
    'Log entries' => 'ログエントリ',
    'Refresh Cache' => 'キャッシュを更新',
    'Clear Runtime Logs' => 'ランタイムログを削除する',
    'Loading' => 'ロードしています',
    'Download File' => 'ファイルをダウンロード',
    'Log Location' => 'ログの場所',
    'Runtime Store' => 'ランタイムストア',
    'Craft cache' => 'Craft キャッシュ',
    'Redis ({cache})' => 'Redis（{cache}）',
    'Runtime Location' => 'ランタイムの場所',
    'Recent runtime logs are stored in Craft cache and are intended for short-lived diagnostics, not complete log history.' => '最近のランタイムログは Craft キャッシュに保存され、短期間の診断を目的としています。完全なログ履歴ではありません。',

    // Config overrides
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'この設定は <code>config/logging-library.php</code> の <code>forceEnableLogViewer</code> 設定によって上書きされています。',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'この設定は <code>config/logging-library.php</code> の <code>showCpSection</code> 設定によって上書きされています。',
];
