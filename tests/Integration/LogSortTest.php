<?php
/**
 * LindemannRock Logging Library
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Integration;

use lindemannrock\logginglibrary\services\LogCacheService;
use lindemannrock\logginglibrary\helpers\UserLabelHelper;
use lindemannrock\logginglibrary\tests\TestCase;

/**
 * Pins LogCacheService::sortLogs() — same-second entries must reverse cluster
 * order when dir=desc so terminal failures appear at the top of a same-second
 * cluster rather than buried at the bottom. Regression test for audit 1.1.
 *
 * @since 5.9.0
 */
final class LogSortTest extends TestCase
{
    /**
     * @return list<array{timestamp: string, level: string, message: string}>
     */
    private function sameSecondCluster(): array
    {
        return [
            ['timestamp' => '2026-05-16 20:37:07', 'level' => 'info',  'message' => 'first'],
            ['timestamp' => '2026-05-16 20:37:07', 'level' => 'info',  'message' => 'second'],
            ['timestamp' => '2026-05-16 20:37:07', 'level' => 'info',  'message' => 'third'],
            ['timestamp' => '2026-05-16 20:37:07', 'level' => 'error', 'message' => 'terminal'],
        ];
    }

    public function testDescSortReversesSameSecondCluster(): void
    {
        $sorted = LogCacheService::sortLogs($this->sameSecondCluster(), 'timestamp', 'desc');

        self::assertSame(
            ['terminal', 'third', 'second', 'first'],
            array_column($sorted, 'message'),
        );
    }

    public function testAscSortPreservesSameSecondClusterOrder(): void
    {
        $sorted = LogCacheService::sortLogs($this->sameSecondCluster(), 'timestamp', 'asc');

        self::assertSame(
            ['first', 'second', 'third', 'terminal'],
            array_column($sorted, 'message'),
        );
    }

    public function testDescSortOrdersDifferentSecondsNewestFirst(): void
    {
        $logs = [
            ['timestamp' => '2026-05-16 20:37:05', 'level' => 'info', 'message' => 'older'],
            ['timestamp' => '2026-05-16 20:37:07', 'level' => 'info', 'message' => 'newer'],
        ];

        $sorted = LogCacheService::sortLogs($logs, 'timestamp', 'desc');

        self::assertSame(['newer', 'older'], array_column($sorted, 'message'));
    }

    public function testLevelSortAppliesTiebreakerOnEqualLevels(): void
    {
        $logs = [
            ['timestamp' => '2026-05-16 20:37:07', 'level' => 'info',  'message' => 'first-info'],
            ['timestamp' => '2026-05-16 20:37:08', 'level' => 'error', 'message' => 'the-error'],
            ['timestamp' => '2026-05-16 20:37:09', 'level' => 'info',  'message' => 'second-info'],
        ];

        $sortedAsc = LogCacheService::sortLogs($logs, 'level', 'asc');
        self::assertSame(
            ['the-error', 'first-info', 'second-info'],
            array_column($sortedAsc, 'message'),
        );

        $sortedDesc = LogCacheService::sortLogs($logs, 'level', 'desc');
        self::assertSame(
            ['second-info', 'first-info', 'the-error'],
            array_column($sortedDesc, 'message'),
        );
    }

    public function testLevelSortTreatsTraceAsDebug(): void
    {
        $logs = [
            ['timestamp' => '2026-05-16 20:37:07', 'level' => 'trace', 'message' => 'trace-message'],
            ['timestamp' => '2026-05-16 20:37:08', 'level' => 'unknown', 'message' => 'unknown-message'],
            ['timestamp' => '2026-05-16 20:37:09', 'level' => 'error', 'message' => 'error-message'],
        ];

        $sortedAsc = LogCacheService::sortLogs($logs, 'level', 'asc');

        self::assertSame(
            ['error-message', 'trace-message', 'unknown-message'],
            array_column($sortedAsc, 'message'),
        );
    }

    public function testSeqHelperIsStrippedFromResult(): void
    {
        $sorted = LogCacheService::sortLogs($this->sameSecondCluster(), 'timestamp', 'desc');

        foreach ($sorted as $entry) {
            self::assertArrayNotHasKey('_seq', $entry);
        }
    }

    public function testFileLogUserLabelsResolveAndFallBack(): void
    {
        $records = [
            ['user' => 'user:999999999', 'message' => 'a'],
            ['user' => '', 'message' => 'b'],
            ['user' => 'System', 'message' => 'c'],
            ['user' => 'cli', 'message' => 'd'],
        ];

        $out = UserLabelHelper::withUserLabels($records);

        self::assertSame('User #999999999', $out[0]['userLabel']);
        self::assertSame('System', $out[1]['userLabel']);
        self::assertSame('System', $out[2]['userLabel']);
        self::assertSame('cli', $out[3]['userLabel']);
    }
}
