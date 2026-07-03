<?php
/**
 * LindemannRock Logging Library
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Integration;

use lindemannrock\logginglibrary\LoggingLibrary;
use lindemannrock\logginglibrary\services\LogCacheService;
use lindemannrock\logginglibrary\services\LoggingService;
use lindemannrock\logginglibrary\tests\TestCase;

/**
 * Pins parser normalization for log formats that are not emitted by
 * LoggingLibrary's own Monolog formatter.
 *
 * @since 5.9.0
 */
final class LogParsingTest extends TestCase
{
    public function testCraftLogCategoryWithDashIsParsed(): void
    {
        $path = $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . "formie-rest-api-2026-05-17.log",
            "2026-05-17 10:00:00 [formie-rest-api.ERROR] [craft\\web\\Application::run] Request failed\n",
        );

        $logs = LoggingLibrary::getInstance()->logCache->getLogs($path)->all();

        self::assertCount(1, $logs);
        self::assertSame('error', $logs[0]['level']);
        self::assertSame('craft\\web\\Application::run', $logs[0]['category']);
        self::assertSame('Request failed', $logs[0]['message']);
    }

    public function testCraftWebRequestContextKeepsDumpOutOfTableMessage(): void
    {
        $path = $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . "web-2026-05-17.log",
            "2026-05-17 14:02:03 [web.INFO] [application] Request context:\n\$_GET = [\n    'p' => 'admin/actions/queue/run'\n]\n",
        );

        $logs = LoggingLibrary::getInstance()->logCache->getLogs($path)->all();

        self::assertCount(1, $logs);
        self::assertSame('info', $logs[0]['level']);
        self::assertSame('web', $logs[0]['channel']);
        self::assertSame('application', $logs[0]['category']);
        self::assertSame('Request context:', $logs[0]['message']);
        self::assertStringContainsString('$_GET', $logs[0]['context']);
    }

    public function testCraftQueueLogParsesCanonicalLevel(): void
    {
        $path = $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . "queue-2025-09-22.log",
            "2025-09-22 14:47:06 [queue.INFO] [translation-manager] Cleaned up old backups | {\"deleted\":5} {\"memory\":2475256}\n",
        );

        $logs = LoggingLibrary::getInstance()->logCache->getLogs($path)->all();

        self::assertCount(1, $logs);
        self::assertSame('info', $logs[0]['level']);
        self::assertSame('queue', $logs[0]['channel']);
        self::assertSame('translation-manager', $logs[0]['category']);
        self::assertStringStartsWith('Cleaned up old backups', $logs[0]['message']);
    }

    public function testCraftTraceLogMapsToDebugInViewerPage(): void
    {
        $path = $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . 'trace-2026-05-17.log',
            "2026-05-17 10:00:00 [web.TRACE] [application] Trace message\n",
        );

        $page = LoggingLibrary::getInstance()->logCache->getLogPage($path, 'debug', 'all', '', 'timestamp', 'desc', 1, 10);

        self::assertSame(1, $page['total']);
        self::assertSame('debug', $page['entries'][0]['canonicalLevel']);
        self::assertSame('lr-level-debug', $page['entries'][0]['levelClass']);
    }

    public function testPhpErrorLogGetsSourceCategory(): void
    {
        $path = $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . 'phperrors.log',
            "[17-May-2026 19:46:49 UTC] PHP Fatal error: Access level problem in /var/www/html/plugin.php on line 42\n",
        );

        $logs = LoggingLibrary::getInstance()->logCache->getLogs($path)->all();

        self::assertCount(1, $logs);
        self::assertSame('2026-05-17 19:46:49', $logs[0]['timestamp']);
        self::assertSame('error', $logs[0]['level']);
        self::assertSame('php-errors', $logs[0]['category']);
        self::assertSame('Access level problem', $logs[0]['message']);
        self::assertSame('in /var/www/html/plugin.php on line 42', $logs[0]['context']);
    }

    public function testPhpErrorTimestampsSortChronologicallyAcrossMonths(): void
    {
        $path = $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . 'phperrors.log',
            "[30-Apr-2026 19:46:49 UTC] PHP Fatal error: April error\n" .
            "[23-May-2026 09:52:47 UTC] PHP Fatal error: May error\n",
        );

        $logs = LoggingLibrary::getInstance()->logCache->getLogs($path)->all();

        self::assertSame(
            ['2026-04-30 19:46:49', '2026-05-23 09:52:47'],
            array_column($logs, 'timestamp'),
        );

        $sorted = LogCacheService::sortLogs($logs, 'timestamp', 'desc');

        self::assertSame(
            ['May error', 'April error'],
            array_column($sorted, 'message'),
        );
    }

    public function testPhpStackTraceIsMovedToContext(): void
    {
        $path = $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . 'phperrors.log',
            "[17-May-2026 13:42:21 UTC] PHP Fatal error: Uncaught yii\\base\\InvalidConfigException: Unable to determine the entry script file path. in /var/www/html/vendor/yiisoft/yii2/base/Request.php:85 Stack trace:\n#0 /var/www/html/vendor/yiisoft/yii2/base/Request.php(62): yii\\base\\Request->setScriptFile()\n#1 {main}\n  thrown in /var/www/html/vendor/yiisoft/yii2/base/Request.php on line 85\n",
        );

        $logs = LoggingLibrary::getInstance()->logCache->getLogs($path)->all();

        self::assertCount(1, $logs);
        self::assertSame('error', $logs[0]['level']);
        self::assertSame('Uncaught yii\\base\\InvalidConfigException: Unable to determine the entry script file path.', $logs[0]['message']);
        self::assertStringStartsWith('in /var/www/html/vendor/yiisoft/yii2/base/Request.php:85', $logs[0]['context']);
        self::assertStringContainsString('Stack trace:', $logs[0]['context']);
        self::assertStringContainsString('#0 /var/www/html/vendor/yiisoft/yii2/base/Request.php(62)', $logs[0]['context']);
        self::assertStringContainsString('thrown in /var/www/html/vendor/yiisoft/yii2/base/Request.php on line 85', $logs[0]['context']);
    }

    public function testPhpWarningLogUsesCanonicalWarningLevel(): void
    {
        $path = $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . 'phpwarnings.log',
            "[17-May-2026 12:14:29 UTC] PHP Warning: session_start(): Session cannot be started after headers have already been sent\n",
        );

        $logs = LoggingLibrary::getInstance()->logCache->getLogs($path)->all();

        self::assertCount(1, $logs);
        self::assertSame('warning', $logs[0]['level']);
        self::assertSame('php-errors', $logs[0]['category']);
    }

    public function testPhpNoticeLogUsesCanonicalInfoLevel(): void
    {
        $path = $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . 'phpnotices.log',
            "[17-May-2026 12:14:29 UTC] PHP Deprecated: Creation of dynamic property Example::\$name is deprecated\n",
        );

        $logs = LoggingLibrary::getInstance()->logCache->getLogs($path)->all();

        self::assertCount(1, $logs);
        self::assertSame('info', $logs[0]['level']);
        self::assertSame('php-errors', $logs[0]['category']);
    }

    public function testFallbackTimestampLogNeverLeavesNullLevelOrCategory(): void
    {
        $path = $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . "fallback-2026-05-17.log",
            "2026-05-17 10:00:00 plain message without bracketed metadata\n",
        );

        $logs = LoggingLibrary::getInstance()->logCache->getLogs($path)->all();

        self::assertCount(1, $logs);
        self::assertSame('unknown', $logs[0]['level']);
        self::assertSame(self::TEST_HANDLE_PREFIX . 'fallback', $logs[0]['category']);
        self::assertSame('plain message without bracketed metadata', $logs[0]['message']);
    }

    public function testLogStatsCountsUnknownLevels(): void
    {
        $handle = self::TEST_HANDLE_PREFIX . 'stats';
        $this->seedLogFile(
            "{$handle}-2026-05-17.log",
            "2026-05-17 10:00:00 plain message without bracketed metadata\n",
        );

        $stats = LoggingService::getLogStats($handle);

        self::assertSame(1, $stats['levels']['unknown']);
    }

    public function testLogStatsCountsTraceAsDebug(): void
    {
        $handle = self::TEST_HANDLE_PREFIX . 'stats-trace';
        $this->seedLogFile(
            "{$handle}-2026-05-17.log",
            "2026-05-17 10:00:00 [web.TRACE] [application] Trace message\n",
        );

        $stats = LoggingService::getLogStats($handle);

        self::assertSame(1, $stats['levels']['debug']);
    }

    public function testRecentEntriesDebugFilterIncludesTrace(): void
    {
        $handle = self::TEST_HANDLE_PREFIX . 'recent-trace';
        $this->seedLogFile(
            "{$handle}-2026-05-17.log",
            "2026-05-17 10:00:00 [web.TRACE] [application] Trace message\n" .
            "2026-05-17 10:00:01 [web.INFO] [application] Info message\n",
        );

        $entries = LoggingService::getRecentEntries($handle, 10, 'debug');

        self::assertCount(1, $entries);
        self::assertSame('debug', $entries[0]['level']);
        self::assertSame('Trace message', $entries[0]['message']);
    }

    public function testBracketLevelPluginLogParsesLevelAndContext(): void
    {
        $path = $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . "formie-2026-05-17.log",
            "2026-05-17 14:02:03 [INFO] Request context:\n\$_GET = [\n    'site' => 'en'\n]\n",
        );

        $logs = LoggingLibrary::getInstance()->logCache->getLogs($path)->all();

        self::assertCount(1, $logs);
        self::assertSame('info', $logs[0]['level']);
        self::assertSame(self::TEST_HANDLE_PREFIX . 'formie', $logs[0]['category']);
        self::assertSame('Request context:', $logs[0]['message']);
        self::assertStringContainsString('$_GET', $logs[0]['context']);
    }

    public function testBracketLevelPluginLogParsesError(): void
    {
        $path = $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . "formie-error-2026-05-17.log",
            "2026-05-17 14:02:03 [ERROR] dev: Failed to send SMS to +96597255330\n",
        );

        $logs = LoggingLibrary::getInstance()->logCache->getLogs($path)->all();

        self::assertCount(1, $logs);
        self::assertSame('error', $logs[0]['level']);
        self::assertSame('dev: Failed to send SMS to +96597255330', $logs[0]['message']);
        self::assertSame('', $logs[0]['context']);
    }

    public function testUndatedMonologSourceLogParsesLevelMessageAndContext(): void
    {
        $path = $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . "freeform-email.log",
            "[2026-06-23T10:02:56.653489+01:00] notification.INFO: ExportNotifications handleNotifications - Started processing [] {\"requestId\":\"VmzbtM\"}\n",
        );

        $logs = LoggingLibrary::getInstance()->logCache->getLogs($path)->all();

        self::assertCount(1, $logs);
        self::assertSame('2026-06-23 10:02:56', $logs[0]['timestamp']);
        self::assertSame('info', $logs[0]['level']);
        self::assertSame('notification', $logs[0]['channel']);
        self::assertSame(self::TEST_HANDLE_PREFIX . 'freeform-email', $logs[0]['category']);
        self::assertSame('ExportNotifications handleNotifications - Started processing', $logs[0]['message']);
        self::assertSame('[] {"requestId":"VmzbtM"}', $logs[0]['context']);
    }

    public function testUndatedMonologSourceLogKeepsBracketedMessageTokensInHeadline(): void
    {
        $path = $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . "freeform-email.log",
            "[2026-06-23T10:02:56.653489+01:00] notification.INFO: User [admin] logged in [] []\n",
        );

        $logs = LoggingLibrary::getInstance()->logCache->getLogs($path)->all();

        self::assertCount(1, $logs);
        self::assertSame('User [admin] logged in', $logs[0]['message']);
        self::assertSame('[] []', $logs[0]['context']);
    }
}
