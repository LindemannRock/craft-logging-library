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
    'Setup' => 'セットアップ',
    'File Logs' => 'ファイルログ',
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
    'Show Logging Library in the main navigation. This does not enable or disable log capture.' => 'メインナビゲーションに Logging Library を表示します。ログのキャプチャの有効・無効は変更しません。',
    'General Settings' => '一般設定',
    'Force Enable Log Viewers' => 'ログビューアーを強制有効化',
    'Force-enable file-based log viewers even when an edge or ephemeral environment is detected. This affects Logging Library and every plugin&apos;s dedicated Logs section.' => 'エッジ環境またはエフェメラル環境が検出された場合でも、ファイルベースのログビューアーを強制的に有効にします。これは Logging Library およびすべてのプラグインの専用 Logs セクションに影響します。',
    'Logging Library detected an edge or ephemeral environment, so file-based log viewers are hidden for the standalone <strong>All Logs</strong> view and for every plugin&apos;s dedicated <strong>Logs</strong> section. The main menu viewer is unavailable until you enable this override. Use your hosting platform&apos;s native logs, or enable the override if persistent storage is available.' => 'Logging Library がエッジ環境またはエフェメラル環境を検出したため、ファイルベースのログビューアーはスタンドアロンの <strong>すべてのログ</strong> ビューおよびすべてのプラグインの専用 <strong>ログ</strong> セクションで非表示になっています。このオーバーライドを有効にするまで、メインメニューのビューアーは使用できません。ホスティングプラットフォームのネイティブログを使用するか、永続ストレージが利用可能な場合はオーバーライドを有効にしてください。',
    'Logging Library detected an edge or ephemeral environment, but file-based log viewers are being force-enabled. This override affects the standalone <strong>All Logs</strong> view and every plugin&apos;s dedicated <strong>Logs</strong> section.' => 'Logging Library がエッジ環境またはエフェメラル環境を検出しましたが、ファイルベースのログビューアーは強制的に有効化されています。このオーバーライドはスタンドアロンの <strong>すべてのログ</strong> ビューおよびすべてのプラグインの専用 <strong>ログ</strong> セクションに影響します。',
    'Logging Library adds a consolidated <strong>All Logs</strong> view to the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => 'Logging Library はコントロールパネルのメインメニューに統合された <strong>すべてのログ</strong> ビューを追加します。個々のプラグインは引き続き独自の専用 <strong>ログ</strong> セクションを保持します。',
    'The consolidated <strong>All Logs</strong> view is hidden from the main Control Panel menu. Individual plugins still keep their own dedicated <strong>Logs</strong> sections.' => '統合された <strong>すべてのログ</strong> ビューはコントロールパネルのメインメニューで非表示になっています。個々のプラグインは引き続き独自の専用 <strong>ログ</strong> セクションを保持します。',
    'Show Main Menu' => 'メインメニューを表示',
    'Show Logging Library in the main Control Panel navigation as a consolidated All Logs view when file-based log viewers are available.' => 'ファイルベースのログビューアーが利用可能な場合に、コントロールパネルのメインナビゲーションに Logging Library を統合された すべてのログ ビューとして表示します。',

    // Settings: File Logs
    'Force Enable File Log Viewers' => 'ファイルログビューアーを強制的に有効にする',
    'An edge or ephemeral environment is detected. File viewers are hidden unless forced on; Runtime Logs remain available when enabled. Only force file viewers on if persistent log files are available.' => 'エッジ環境または一時的な環境が検出されました。ファイルビューアーは強制的に有効にしない限り非表示になります。有効なランタイムログは引き続き利用できます。永続的なログファイルが利用できる場合のみ、ファイルビューアーを強制的に有効にしてください。',
    'File viewers are available. Runtime Logs are optional and independent of file logging.' => 'ファイルビューアーが利用できます。ランタイムログは任意であり、ファイルへのログ記録とは独立しています。',

    // Settings: Runtime Logs
    'Min: {min}, Max: {max}' => '最小: {min}、最大: {max}',
    'Uses the application cache configuration. The Redis database can be overridden in {file}. To verify capture, trigger a log message and check Runtime Logs.' => 'アプリケーションキャッシュの設定を使用します。Redis データベースは {file} で上書きできます。キャプチャを確認するには、ログメッセージを発生させてランタイムログを確認してください。',
    'Enable Runtime Logs' => 'ランタイムログを有効にする',
    'Skip Console Requests' => 'コンソールリクエストをスキップする',
    'Skip Queue Requests' => 'キューリクエストをスキップする',
    'Retention (seconds)' => '保持期間（秒）',
    'Maximum Entries' => '最大エントリー数',
    'Refresh Interval (seconds)' => '更新間隔（秒）',
    'Maximum Message Bytes' => 'メッセージの最大バイト数',
    'Maximum Context Bytes' => 'コンテキストの最大バイト数',
    'Captured Levels' => 'キャプチャするレベル',
    'Include Categories' => '含めるカテゴリ',
    'Exclude Categories' => '除外するカテゴリ',
    'Include Request User ID' => 'リクエストユーザー ID を含める',
    'Advanced' => '詳細設定',
    'Configured Storage' => '設定されたストレージ',
    'Storage follows the Craft cache configuration. Redis database selection is configuration-only. This is not a connection test; confirm capture in Runtime Logs.' => 'ストレージは Craft のキャッシュ設定に従います。Redis データベースは設定ファイルでのみ選択できます。これは接続テストではありません。ランタイムログでキャプチャを確認してください。',
    'On multiple servers, use shared cache storage. Local file cache does not combine logs from other instances.' => '複数のサーバーでは共有キャッシュストレージを使用してください。ローカルファイルキャッシュは他のインスタンスのログを統合しません。',
    'Capture changes apply to new requests. Restart long-running workers to load changed settings. Disabling capture does not clear stored logs.' => 'キャプチャの変更は新しいリクエストに適用されます。変更した設定を読み込むには、長時間実行されるワーカーを再起動してください。キャプチャを無効にしても保存済みのログは削除されません。',
    'How often Runtime Logs refreshes automatically. Set to 0 to disable. Current: {duration}' => 'ランタイムログが自動更新される頻度です。無効にするには 0 に設定してください。現在: {duration}',
    'Choose which log categories to capture, not words in the message. Enter one category per line, such as {exact}, or use {prefix} to match categories starting with {start}. Leave empty to capture all categories.' => 'メッセージ内の単語ではなく、キャプチャするログカテゴリを選択してください。{exact} のように 1 行に 1 つのカテゴリを入力するか、{prefix} を使用して {start} で始まるカテゴリに一致させます。すべてのカテゴリをキャプチャするには空欄にしてください。',
    'Skip these log categories even if included above. Enter one per line, such as {exact} or {prefix}. Leave empty to add no category exclusions.' => '上記で含めた場合でも、これらのログカテゴリを除外します。{exact} や {prefix} のように 1 行に 1 つ入力してください。カテゴリの除外を追加しない場合は空欄にしてください。',
    'When on, Runtime Logs skips command-line requests. Turn off only when diagnosing console commands; file and hosted logs are unaffected.' => '有効にすると、ランタイムログはコマンドラインのリクエストを除外します。コンソールコマンドの診断時のみ無効にしてください。ファイルログとホスティングのログには影響しません。',
    'When on, Runtime Logs skips detected queue execution. To capture console queue workers, turn off both skip switches and restart the workers. Workers can generate large volumes of logs.' => '有効にすると、ランタイムログは検出されたキュー実行を除外します。コンソールのキューワーカーをキャプチャするには、両方の除外スイッチを無効にしてワーカーを再起動してください。ワーカーは大量のログを生成する場合があります。',
    'Adds the authenticated request user ID. Messages and context may still contain personal data regardless of this setting.' => 'リクエストの認証済みユーザー ID を追加します。この設定に関係なく、メッセージとコンテキストには個人データが含まれる場合があります。',

    'Maximum age of runtime entries, in seconds. Current: {duration}' => 'ランタイムログのエントリーの最大保持期間（秒単位）です。現在: {duration}',
    'Min: {min} ({minDuration}), Max: {max} ({maxDuration})' => '最小: {min}（{minDuration}）、最大: {max}（{maxDuration}）',
    '{count} second' => '{count} 秒',
    '{count} seconds' => '{count} 秒',
    '{count} minute' => '{count} 分',
    '{count} minutes' => '{count} 分',
    '{count} hour' => '{count} 時間',
    '{count} hours' => '{count} 時間',
    '{count} day' => '{count} 日',
    '{count} days' => '{count} 日',

    // Settings: Interface
    'Interface Settings' => 'インターフェース設定',

    // Setup
    'Choose the log views that suit this environment. Runtime capture is optional and remains off until enabled.' => 'この環境に適したログ表示を選択してください。ランタイムキャプチャは任意であり、有効にするまで無効のままです。',
    'Consider Runtime Logs for recent diagnostics on ephemeral hosting. Confirm shared storage before relying on logs from multiple instances.' => '一時的なホスティングでの最近の診断にはランタイムログをご検討ください。複数のインスタンスのログを利用する前に、共有ストレージを確認してください。',

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
    'DB Queries' => 'DB クエリ',
    'DB Commands' => 'DB コマンド',
    'DB Command::{method}' => 'DB コマンド::{method}',
    'DB Connection' => 'DB 接続',
    'DB Connection::{method}' => 'DB 接続::{method}',
    'Redis Commands' => 'Redis コマンド',
    'Redis Connection' => 'Redis 接続',
    'Redis Connection::{method}' => 'Redis 接続::{method}',
    'URL Routing' => 'URL ルーティング',
    'Web Request' => 'Web リクエスト',
    'Session' => 'セッション',
    'Template Rendering' => 'テンプレートレンダリング',
    'Modules' => 'モジュール',
    'Integration Service' => 'インテグレーションサービス',

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
    'Clear recent runtime logs? This cannot be undone.' => '最近のランタイムログを削除しますか？この操作は取り消せません。',
    'Loading' => 'ロードしています',
    'Download File' => 'ファイルをダウンロード',
    'Log Location' => 'ログの場所',
    'Runtime Store' => 'ランタイムストア',
    'Craft cache' => 'Craft キャッシュ',
    'Redis unavailable' => 'Redis 利用不可',
    'Redis (SELECT disabled)' => 'Redis（SELECT 無効）',
    'Redis database {database}' => 'Redis データベース {database}',
    'Runtime Location' => 'ランタイムの場所',
    'Dedicated Redis key' => '専用 Redis キー',
    'Recent runtime logs use a bounded diagnostic store and are not complete log history.' => '最近のランタイムログには上限のある診断ストアが使用され、完全なログ履歴ではありません。',

    // Config overrides
    'This is being overridden by the <code>{setting}</code> setting in <code>config/logging-library.php</code>.' => 'この設定は <code>config/logging-library.php</code> の <code>{setting}</code> 設定によって上書きされています。',
    'This is being overridden by the <code>forceEnableLogViewer</code> setting in <code>config/logging-library.php</code>.' => 'この設定は <code>config/logging-library.php</code> の <code>forceEnableLogViewer</code> 設定によって上書きされています。',
    'This is being overridden by the <code>showCpSection</code> setting in <code>config/logging-library.php</code>.' => 'この設定は <code>config/logging-library.php</code> の <code>showCpSection</code> 設定によって上書きされています。',
];
