<?php
/**
 * LindemannRock Logging Library
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Integration;

use Craft;
use lindemannrock\logginglibrary\LoggingLibrary;
use lindemannrock\logginglibrary\tests\TestCase;

/**
 * Pins the log-file discovery contracts:
 *  - `LoggingLibrary::getLogFiles($handle)` — filter to handle-owned dated
 *    log files, sort newest-first, return the metadata shape the CP log
 *    viewer template reads
 *  - `LoggingLibrary::getAllLogFiles()` — drop files smaller than 10 bytes
 *    and classify the remaining files into the source/type buckets the
 *    standalone viewer uses for its source filter dropdown
 *
 * A regression in either path empties or mis-buckets the log viewer's file
 * picker, so the contract is worth pinning.
 *
 * @since 5.9.0
 */
final class LogFilesDiscoveryTest extends TestCase
{
    public function testGetLogFilesFiltersByHandleAndSortsDesc(): void
    {
        $handleA = self::TEST_HANDLE_PREFIX . 'alpha';
        $handleB = self::TEST_HANDLE_PREFIX . 'beta';

        // Seed three dated logs for handle A out of date order and one for
        // an unrelated handle B that must NOT appear in the result.
        $this->seedLogFile("{$handleA}-2026-05-15.log");
        $this->seedLogFile("{$handleA}-2026-05-14.log");
        $this->seedLogFile("{$handleA}-2026-05-16.log");
        $this->seedLogFile("{$handleB}-2026-05-16.log");

        $files = LoggingLibrary::getLogFiles($handleA);

        self::assertCount(3, $files, 'Only handle A files should be returned');
        self::assertSame(
            ['2026-05-16', '2026-05-15', '2026-05-14'],
            array_column($files, 'date'),
            'Files must be sorted newest-first by date',
        );

        $first = $files[0];
        self::assertArrayHasKey('size', $first);
        self::assertArrayHasKey('formattedSize', $first);
        self::assertArrayHasKey('lastModified', $first);
        self::assertArrayHasKey('path', $first);
        self::assertGreaterThan(0, $first['size']);
        self::assertStringEndsWith("{$handleA}-2026-05-16.log", $first['path']);
    }

    public function testGetAllLogFilesSkipsTinyFilesAndClassifiesPluginSource(): void
    {
        $handle = self::TEST_HANDLE_PREFIX . 'gamma';

        $normalPath = $this->seedLogFile(
            "{$handle}-2026-05-16.log",
            "2026-05-16 10:00:00 [user:1][INFO][{$handle}] message body that is comfortably over ten bytes\n",
        );
        // < 10 bytes — must be dropped by the early-skip guard.
        $tinyPath = $this->seedLogFile("{$handle}-2026-04-01.log", "x\n");

        $all = LoggingLibrary::getAllLogFiles();

        $byPath = [];
        foreach ($all as $entry) {
            $byPath[$entry['path']] = $entry;
        }

        self::assertArrayNotHasKey($tinyPath, $byPath, 'Files smaller than 10 bytes must be skipped');
        self::assertArrayHasKey($normalPath, $byPath, 'Plugin-format log over 10 bytes must be included');

        $entry = $byPath[$normalPath];
        self::assertSame('plugin', $entry['type']);
        self::assertSame($handle, $entry['source']);
        self::assertSame($handle, $entry['category']);
        self::assertSame('2026-05-16', $entry['date']);
        self::assertSame("{$handle}-2026-05-16.log", $entry['filename']);
    }

    public function testGetAllLogFilesSortsDatedLogsByFilenameDateBeforeMtime(): void
    {
        $handle = self::TEST_HANDLE_PREFIX . 'dated';

        $newerPath = $this->seedLogFile(
            "{$handle}-2026-07-03.log",
            "2026-07-03 10:00:00 [user:1][INFO][{$handle}] newer file\n",
        );
        $olderPath = $this->seedLogFile(
            "{$handle}-2026-07-02.log",
            "2026-07-02 10:00:00 [user:1][INFO][{$handle}] older file\n",
        );

        touch($newerPath, strtotime('2026-07-03 00:00:00'));
        touch($olderPath, strtotime('2026-07-04 00:00:00'));

        $files = array_values(array_filter(
            LoggingLibrary::getAllLogFiles(),
            fn(array $file) => ($file['source'] ?? '') === $handle
        ));

        self::assertSame(
            ["{$handle}-2026-07-03.log", "{$handle}-2026-07-02.log"],
            array_column($files, 'filename'),
            'Standalone file picker options must follow filename date before mtime.',
        );
    }

    public function testGetAllLogFilesClassifiesDatedCraftSystemLogsBeforePluginLogs(): void
    {
        $webPath = $this->seedLogFile(
            'web-2099-01-01.log',
            "2099-01-01 10:00:00 [web.INFO] [application] Future web log\n",
        );

        $all = LoggingLibrary::getAllLogFiles();

        $byPath = [];
        foreach ($all as $entry) {
            $byPath[$entry['path']] = $entry;
        }

        self::assertArrayHasKey($webPath, $byPath, 'Dated web logs must be included');
        self::assertSame('craft', $byPath[$webPath]['type']);
        self::assertSame('web', $byPath[$webPath]['source']);
        self::assertSame('web', $byPath[$webPath]['category']);
        self::assertSame('2099-01-01', $byPath[$webPath]['date']);
    }

    public function testGetAllLogFilesCachesDirectoryListingForCurrentDirectoryState(): void
    {
        $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . 'cached-2026-05-16.log',
            "2026-05-16 10:00:00 [user:1][INFO][cached] cacheable listing\n",
        );

        $logPath = Craft::$app->getPath()->getLogPath();
        $method = new \ReflectionMethod(LoggingLibrary::class, '_allLogFilesCacheKey');
        $logFiles = glob($logPath . '/*.log*') ?: [];
        $cacheKey = $method->invoke(null, $logPath, $logFiles);

        Craft::$app->getCache()->delete($cacheKey);
        LoggingLibrary::getAllLogFiles();

        self::assertIsArray(Craft::$app->getCache()->get($cacheKey));

        $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . 'cached-extra-2026-05-17.log',
            "2026-05-17 10:00:00 [user:1][INFO][cached] changed listing\n",
        );

        $changedLogFiles = glob($logPath . '/*.log*') ?: [];
        $changedCacheKey = $method->invoke(null, $logPath, $changedLogFiles);

        self::assertNotSame($cacheKey, $changedCacheKey);
    }

    public function testGetAllLogFilesClassifiesUndatedSourceLog(): void
    {
        $source = self::TEST_HANDLE_PREFIX . 'freeform-email';
        $path = $this->seedLogFile(
            "{$source}.log",
            "[2026-06-23T10:02:56.653489+01:00] notification.INFO: ExportNotifications handleNotifications - Started processing [] {\"requestId\":\"VmzbtM\"}\n",
        );

        $all = LoggingLibrary::getAllLogFiles();

        $byPath = [];
        foreach ($all as $entry) {
            $byPath[$entry['path']] = $entry;
        }

        self::assertArrayHasKey($path, $byPath, 'Undated source logs must be available in the standalone viewer');

        $entry = $byPath[$path];
        self::assertSame('plugin', $entry['type']);
        self::assertSame($source, $entry['source']);
        self::assertSame($source, $entry['category']);
        self::assertSame('current', $entry['date']);
        self::assertSame("{$source}.log", $entry['filename']);
    }
}
