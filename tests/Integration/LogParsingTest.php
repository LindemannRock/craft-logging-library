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
}
