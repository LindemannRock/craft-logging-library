<?php
/**
 * LindemannRock Logging Library
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

use lindemannrock\logginglibrary\services\runtime\RedisRuntimeLogStorage;
use yii\redis\Connection;

$projectRoot = dirname(__DIR__, 4);
require $projectRoot . '/vendor/autoload.php';
require $projectRoot . '/vendor/yiisoft/yii2/Yii.php';

$encoded = $_SERVER['LOGGING_LIBRARY_RUNTIME_WRITER'] ?? $_ENV['LOGGING_LIBRARY_RUNTIME_WRITER'] ?? null;
if (!is_string($encoded) || $encoded === '') {
    exit(2);
}

try {
    $payload = json_decode(base64_decode($encoded, true), true, 512, JSON_THROW_ON_ERROR);
    if (!is_array($payload) || !is_array($payload['connection'] ?? null)) {
        exit(2);
    }

    $connection = new Connection($payload['connection']);
    $storage = new RedisRuntimeLogStorage(
        $connection,
        (string)($payload['key'] ?? ''),
        'inherited',
    );
    $writer = (int)($payload['writer'] ?? -1);
    $batches = (int)($payload['batches'] ?? 0);

    for ($batch = 0; $batch < $batches; $batch++) {
        if (!$storage->append([
            ['id' => sprintf('writer-%d-batch-%d-new', $writer, $batch)],
            ['id' => sprintf('writer-%d-batch-%d-old', $writer, $batch)],
        ], 100, 120)) {
            fwrite(STDERR, "Runtime Redis append failed.\n");
            exit(1);
        }
    }

    $connection->close();
} catch (Throwable $e) {
    fwrite(STDERR, $e::class . ': ' . $e->getMessage() . "\n");
    exit(1);
}

exit(0);
