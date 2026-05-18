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
use lindemannrock\logginglibrary\tests\TestCase;

/**
 * Pins the `LoggingLibrary::detectLogFormat()` contract. The log viewer's
 * parsing pattern is selected from this return value, so a regression here
 * either drops valid log entries from the viewer or starts mis-tagging them
 * as the wrong format.
 *
 * @since 5.9.0
 */
final class LogFormatDetectionTest extends TestCase
{
    public function testPluginFormatIsRecognised(): void
    {
        $line = '2026-05-16 10:00:00 [user:42][INFO][shortlink-manager] Click recorded';

        self::assertSame('plugin', LoggingLibrary::detectLogFormat($line));
    }

    public function testCraftFormatIsRecognised(): void
    {
        $line = '2026-05-16 10:00:00 [web.INFO] [craft\\web\\Application::run] Request handled';

        self::assertSame('craft', LoggingLibrary::detectLogFormat($line));
    }

    public function testBracketLevelFormatIsRecognised(): void
    {
        $line = '2026-05-16 10:00:00 [ERROR] dev: Failed to send SMS';

        self::assertSame('bracket-level', LoggingLibrary::detectLogFormat($line));
    }

    public function testPhpErrorFormatIsRecognised(): void
    {
        $line = '[16-May-2026 10:00:00 UTC] PHP Fatal error: Uncaught Error in foo.php';

        self::assertSame('php', LoggingLibrary::detectLogFormat($line));
    }

    public function testEmptyAndUnmatchedReturnUnknown(): void
    {
        self::assertSame('unknown', LoggingLibrary::detectLogFormat(''));
        self::assertSame('unknown', LoggingLibrary::detectLogFormat('not a recognised line at all'));
        // A timestamp-only line without any of the bracketed markers must not
        // be misclassified as plugin/craft/php.
        self::assertSame(
            'unknown',
            LoggingLibrary::detectLogFormat('2026-05-16 10:00:00 plain message no markers'),
        );
    }
}
