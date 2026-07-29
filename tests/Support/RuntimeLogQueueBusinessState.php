<?php
/**
 * LindemannRock Logging Library
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Support;

use Craft;
use craft\queue\Queue;
use yii\db\Expression;
use yii\db\Query;

/**
 * Durable business-effect evidence independent from queue lifecycle metrics.
 *
 * @internal
 * @since 5.18.0
 */
final class RuntimeLogQueueBusinessState
{
    public static function recordMainStarted(string $table, string $runId, mixed $queue): void
    {
        $state = self::queueState($queue);

        Craft::$app->getDb()->createCommand()->update($table, [
            'main_started' => new Expression('[[main_started]] + 1'),
            'main_attempt' => $state['attempt'],
            'main_failed' => $state['failed'],
        ], ['run_id' => $runId])->execute();
    }

    public static function recordMainCompleted(string $table, string $runId): void
    {
        Craft::$app->getDb()->createCommand()->update($table, [
            'main_completed' => new Expression('[[main_completed]] + 1'),
        ], ['run_id' => $runId])->execute();
    }

    public static function recordFollowUp(string $table, string $runId, mixed $queue): void
    {
        $state = self::queueState($queue);

        Craft::$app->getDb()->createCommand()->update($table, [
            'follow_up_completed' => new Expression('[[follow_up_completed]] + 1'),
            'follow_up_attempt' => $state['attempt'],
            'follow_up_failed' => $state['failed'],
        ], ['run_id' => $runId])->execute();
    }

    /**
     * @return array{attempt: int, failed: int}
     */
    private static function queueState(mixed $queue): array
    {
        if (!$queue instanceof Queue) {
            throw new \RuntimeException('The queue regression requires Craft database queue execution.');
        }

        $queueId = $queue->getJobId();
        $row = (new Query())
            ->select(['attempt', 'fail'])
            ->from($queue->tableName)
            ->where(['id' => $queueId, 'channel' => $queue->channel])
            ->one(Craft::$app->getDb());

        if (!is_array($row)) {
            throw new \RuntimeException('The executing queue row was not available for attempt evidence.');
        }

        return [
            'attempt' => (int)$row['attempt'],
            'failed' => (int)(bool)$row['fail'],
        ];
    }
}
