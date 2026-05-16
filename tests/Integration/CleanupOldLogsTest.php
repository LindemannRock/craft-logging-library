<?php
/**
 * LindemannRock Logging Library
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Integration;

use DateTime;
use lindemannrock\logginglibrary\services\LoggingService;
use lindemannrock\logginglibrary\tests\TestCase;

/**
 * Pins the retention math of `LoggingService::cleanupOldLogs()`. The cutoff
 * is parsed from the filename's date suffix (not the file's `mtime`), so this
 * test seeds two files whose physical mtimes are identical (now) but whose
 * filename-encoded dates straddle the retention window. Anything that
 * regressed the date-from-filename logic — e.g. reverting to mtime-based
 * comparison — would either delete recent logs or leave old logs forever.
 *
 * @since 5.9.0
 */
final class CleanupOldLogsTest extends TestCase
{
    public function testDeletesLogsOlderThanRetentionAndRetainsRecent(): void
    {
        $handle = self::TEST_HANDLE_PREFIX . 'retention';
        $retentionDays = 30;

        $oldDate = (new DateTime())->modify('-60 days')->format('Y-m-d');
        $recentDate = (new DateTime())->modify('-5 days')->format('Y-m-d');

        $oldPath = $this->seedLogFile("{$handle}-{$oldDate}.log");
        $recentPath = $this->seedLogFile("{$handle}-{$recentDate}.log");

        $deleted = LoggingService::cleanupOldLogs($handle, $retentionDays);

        self::assertSame(
            ["{$handle}-{$oldDate}.log"],
            $deleted,
            'Only the file dated past the retention window should be deleted',
        );
        self::assertFileDoesNotExist($oldPath, 'Old log must be unlinked');
        self::assertFileExists($recentPath, 'Recent log must be retained');
    }
}
