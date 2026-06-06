<?php
/**
 * LindemannRock Logging Library
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests;

use Craft;
use lindemannrock\base\testing\IntegrationTestCase;

/**
 * Base test case for logging-library integration tests.
 *
 * The plugin's testable surface is mostly pure functions (log-format
 * detection) and filesystem reads under `Craft::$app->getPath()->getLogPath()`.
 * No DB writes, so no marker-prefix DB cleanup is needed — the only cleanup is
 * unlinking seeded log files.
 *
 * Marker prefix `lglib-test-` is hyphens only (no underscores) so seeded
 * filenames match the plugin-log classification regex
 * `/^([a-z0-9\-]+)-(\d{4}-\d{2}-\d{2})\.log$/` in
 * `LoggingLibrary::getAllLogFiles()`.
 *
 * @since 5.9.0
 */
abstract class TestCase extends IntegrationTestCase
{
    protected const TEST_HANDLE_PREFIX = 'lglib-test-';

    protected function setUp(): void
    {
        parent::setUp();
        // Belt-and-braces: drop stale lglib-test-* files left over from a
        // crashed prior run before each test, so tests can rely on a clean
        // slate even when cleanupExternalState() didn't get a chance to fire.
        $this->purgeStaleSeededFiles();
    }

    protected function cleanupExternalState(): void
    {
        // Catch anything created via filename patterns we forgot to record.
        $this->purgeStaleSeededFiles();
    }

    /**
     * Write a log file under `Craft::$app->getPath()->getLogPath()` and record
     * it for cleanup. Returns the full path.
     */
    protected function seedLogFile(string $filename, string $content = "stub content for test\n"): string
    {
        $path = Craft::$app->getPath()->getLogPath() . '/' . $filename;
        file_put_contents($path, $content);
        $this->trackTempPath($path);
        return $path;
    }

    private function purgeStaleSeededFiles(): void
    {
        $logPath = Craft::$app->getPath()->getLogPath();
        foreach (glob($logPath . '/' . self::TEST_HANDLE_PREFIX . '*') ?: [] as $stale) {
            @unlink($stale);
        }
    }
}
