<?php
/**
 * LindemannRock Logging Library
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Integration;

use lindemannrock\logginglibrary\services\LoggingService;
use lindemannrock\logginglibrary\tests\TestCase;

/**
 * Pins LoggingService::sanitizeLogMessage() — neutralizes CR/LF in log messages
 * so attacker-controlled values can't forge new log lines (CWE-117). Regression
 * test for audit 3.2.
 *
 * @since 5.9.0
 */
final class LogMessageSanitizationTest extends TestCase
{
    public function testUnixNewlineBecomesLiteralBackslashN(): void
    {
        $sanitized = LoggingService::sanitizeLogMessage("first line\nsecond line");

        self::assertSame('first line\nsecond line', $sanitized);
        self::assertStringNotContainsString("\n", $sanitized);
    }

    public function testWindowsNewlineBecomesLiteralBackslashN(): void
    {
        $sanitized = LoggingService::sanitizeLogMessage("first line\r\nsecond line");

        self::assertSame('first line\nsecond line', $sanitized);
        self::assertStringNotContainsString("\r", $sanitized);
        self::assertStringNotContainsString("\n", $sanitized);
    }

    public function testBareCarriageReturnBecomesLiteralBackslashN(): void
    {
        $sanitized = LoggingService::sanitizeLogMessage("first line\rsecond line");

        self::assertSame('first line\nsecond line', $sanitized);
        self::assertStringNotContainsString("\r", $sanitized);
    }

    public function testForgedLogEntryAttemptIsNeutralized(): void
    {
        // Attacker-controlled value: a newline + a fully crafted timestamp/level
        // line that would otherwise be indistinguishable from a real log entry.
        $hostile = "search foo\n2026-05-17 12:00:00 [user:1][ERROR][admin-tool] Database wipe authorized";

        $sanitized = LoggingService::sanitizeLogMessage($hostile);

        // The result must be a single line (one log entry per emit) with the
        // forged content visibly inlined behind a literal \n marker.
        self::assertStringNotContainsString("\n", $sanitized);
        self::assertStringContainsString('\\n2026-05-17 12:00:00', $sanitized);
        self::assertSame(
            'search foo\n2026-05-17 12:00:00 [user:1][ERROR][admin-tool] Database wipe authorized',
            $sanitized,
        );
    }

    public function testRepeatedNewlinesAreAllConverted(): void
    {
        $sanitized = LoggingService::sanitizeLogMessage("a\n\n\nb");

        self::assertSame('a\n\n\nb', $sanitized);
        self::assertStringNotContainsString("\n", $sanitized);
    }

    public function testCleanMessageIsUnchanged(): void
    {
        $clean = 'No newlines here — just a normal log message';

        self::assertSame($clean, LoggingService::sanitizeLogMessage($clean));
    }

    public function testEmptyStringIsUnchanged(): void
    {
        self::assertSame('', LoggingService::sanitizeLogMessage(''));
    }
}
