<?php
/**
 * Real Redis MONITOR observer for the Runtime Logs queue regression.
 */

declare(strict_types=1);

use yii\redis\Connection;

$projectRoot = $_SERVER['LOGGING_LIBRARY_TEST_PROJECT_ROOT']
    ?? $_ENV['LOGGING_LIBRARY_TEST_PROJECT_ROOT']
    ?? dirname(__DIR__, 4);
require $projectRoot . '/vendor/autoload.php';
require $projectRoot . '/vendor/yiisoft/yii2/Yii.php';

$encoded = $_SERVER['LOGGING_LIBRARY_RUNTIME_REDIS_MONITOR']
    ?? $_ENV['LOGGING_LIBRARY_RUNTIME_REDIS_MONITOR']
    ?? '';
$config = json_decode(
    base64_decode((string)$encoded, true) ?: '',
    true,
    512,
    JSON_THROW_ON_ERROR,
);
$stopKey = (string)($config['_stopKey'] ?? '');
unset($config['_stopKey']);
$connection = new Connection($config);
$connection->executeCommand('MONITOR');
$socket = $connection->getSocket();

if (!is_resource($socket)) {
    fwrite(STDERR, "Redis MONITOR did not expose an active socket.\n");
    exit(2);
}

fwrite(STDOUT, "READY\n");
fflush(STDOUT);
stream_set_blocking($socket, false);

while (true) {
    $line = fgets($socket);
    if ($line === false) {
        // Idle backoff only; readiness and completion use explicit output/key signals.
        usleep(1000);
        continue;
    }

    fwrite(STDOUT, $line);
    fflush(STDOUT);

    if ($stopKey !== '' && str_contains($line, $stopKey)) {
        break;
    }
}
