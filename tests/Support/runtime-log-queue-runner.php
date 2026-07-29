<?php
/**
 * Isolated deterministic runner for the Runtime Logs queue regression.
 */

declare(strict_types=1);

use craft\queue\Queue;
use lindemannrock\logginglibrary\tests\Support\RuntimeLogQueueMetrics;
use Symfony\Component\Process\Exception\RuntimeException as ProcessRuntimeException;
use Symfony\Component\Process\Process;
use yii\queue\ExecEvent;
use yii\queue\Queue as BaseQueue;

require dirname(__DIR__) . '/bootstrap.php';

$encoded = $_SERVER['LOGGING_LIBRARY_RUNTIME_QUEUE_RUNNER']
    ?? $_ENV['LOGGING_LIBRARY_RUNTIME_QUEUE_RUNNER']
    ?? '';
$payload = json_decode(
    base64_decode((string)$encoded, true) ?: '',
    true,
    512,
    JSON_THROW_ON_ERROR,
);

$queue = new Queue([
    'db' => Craft::$app->getDb(),
    'mutex' => Craft::$app->getMutex(),
    'tableName' => '{{%queue}}',
    'channel' => (string)$payload['channel'],
]);
Craft::$app->set((string)$payload['componentId'], $queue);

$metricsPath = (string)$payload['metricsPath'];
$runId = (string)$payload['runId'];

$jobRole = static function(mixed $job): string {
    $class = is_object($job) ? $job::class : get_debug_type($job);

    return str_ends_with($class, 'RuntimeLogQueueFollowUpJob') ? 'follow-up' : 'main';
};

$queue->on(BaseQueue::EVENT_BEFORE_EXEC, static function(ExecEvent $event) use ($metricsPath, $runId, $jobRole): void {
    RuntimeLogQueueMetrics::write($metricsPath, $runId, 'queue-before-exec', [
        'queueId' => (string)$event->id,
        'role' => $jobRole($event->job),
        'attempt' => (int)$event->attempt,
        'failed' => $event->error !== null,
    ]);
});
$queue->on(BaseQueue::EVENT_AFTER_EXEC, static function(ExecEvent $event) use ($metricsPath, $runId, $jobRole): void {
    RuntimeLogQueueMetrics::write($metricsPath, $runId, 'queue-after-exec', [
        'queueId' => (string)$event->id,
        'role' => $jobRole($event->job),
        'attempt' => (int)$event->attempt,
        'failed' => $event->error !== null,
    ]);
});
$queue->on(BaseQueue::EVENT_AFTER_ERROR, static function(ExecEvent $event) use ($metricsPath, $runId, $jobRole): void {
    RuntimeLogQueueMetrics::write($metricsPath, $runId, 'queue-after-error', [
        'queueId' => (string)$event->id,
        'role' => $jobRole($event->job),
        'attempt' => (int)$event->attempt,
        'failed' => true,
        'retry' => (bool)$event->retry,
        'errorMessage' => $event->error?->getMessage(),
    ]);
});

if (($payload['action'] ?? '') === 'exec') {
    $message = file_get_contents('php://stdin');
    $success = $queue->execute(
        (string)$payload['queueId'],
        $message === false ? '' : $message,
        (int)$payload['ttr'],
        (int)$payload['attempt'],
        (int)$payload['workerPid'],
    );
    exit($success ? 0 : 3);
}

$queue->on(Queue::EVENT_AFTER_EXEC_AND_RELEASE, static function(ExecEvent $event) use ($metricsPath, $runId, $jobRole): void {
    RuntimeLogQueueMetrics::write($metricsPath, $runId, 'queue-released', [
        'queueId' => (string)$event->id,
        'role' => $jobRole($event->job),
        'attempt' => (int)$event->attempt,
        'failed' => $event->error !== null,
    ]);
});

$queue->messageHandler = static function(
    string $id,
    string $message,
    int $ttr,
    int $attempt,
) use ($queue, $payload, $metricsPath, $runId): bool {
    $childPayload = array_merge($payload, [
        'action' => 'exec',
        'queueId' => $id,
        'ttr' => $ttr,
        'attempt' => $attempt,
        'workerPid' => getmypid(),
    ]);
    $process = new Process(
        [PHP_BINARY, __FILE__],
        Craft::getAlias('@root'),
        [
            'LOGGING_LIBRARY_RUNTIME_QUEUE_RUNNER' => base64_encode(json_encode($childPayload, JSON_THROW_ON_ERROR)),
        ],
        $message,
        $ttr,
    );

    try {
        $exitCode = $process->run();
        if ($exitCode !== 0) {
            fwrite(STDERR, $process->getErrorOutput() . $process->getOutput());
        }

        return $exitCode === 0;
    } catch (ProcessRuntimeException $error) {
        [$job] = $queue->unserializeMessage($message);
        RuntimeLogQueueMetrics::write($metricsPath, $runId, 'queue-process-error', [
            'queueId' => $id,
            'attempt' => $attempt,
            'errorClass' => $error::class,
        ]);

        return $queue->handleError(new ExecEvent([
            'id' => $id,
            'job' => $job,
            'ttr' => $ttr,
            'attempt' => $attempt,
            'error' => $error,
        ]));
    }
};

$executed = $queue->executeJob((string)$payload['queueId']);
if (!$executed) {
    exit(2);
}

$remaining = Craft::$app->getDb()->createCommand(
    'SELECT [[attempt]], [[fail]], [[error]] FROM {{%queue}} WHERE [[id]] = :id AND [[channel]] = :channel',
    [
        ':id' => (string)$payload['queueId'],
        ':channel' => (string)$payload['channel'],
    ],
)->queryOne();

if ($remaining === false) {
    exit(0);
}

fwrite(STDERR, json_encode($remaining, JSON_THROW_ON_ERROR) . PHP_EOL);
if (is_file($metricsPath)) {
    fwrite(STDERR, (string)file_get_contents($metricsPath));
}
exit(3);
