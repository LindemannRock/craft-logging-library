<?php
/**
 * LindemannRock Logging Library
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

use lindemannrock\logginglibrary\tests\Support\DisposableCraftProject;

$packageRoot = dirname(__DIR__, 3);
$vendorRoot = $_SERVER['LOGGING_LIBRARY_FIXTURE_SOURCE_VENDOR_ROOT']
    ?? $_ENV['LOGGING_LIBRARY_FIXTURE_SOURCE_VENDOR_ROOT']
    ?? null;
if (!is_string($vendorRoot) || $vendorRoot === '') {
    fwrite(STDERR, "LOGGING_LIBRARY_FIXTURE_SOURCE_VENDOR_ROOT must be set.\n");
    exit(2);
}

require rtrim($vendorRoot, DIRECTORY_SEPARATOR) . '/autoload.php';

try {
    $result = (new DisposableCraftProject($packageRoot, $vendorRoot))->run(array_slice($argv, 1));
    fwrite(STDOUT, $result['phpunit']['stdout']);
    if ($result['phpunit']['stderr'] !== '') {
        fwrite(STDERR, $result['phpunit']['stderr']);
    }
    fwrite(STDOUT, json_encode([
        'fixture' => $result['fixture'],
        'cleanup' => $result['cleanup'],
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . PHP_EOL);
    exit(0);
} catch (Throwable $exception) {
    fwrite(STDERR, $exception::class . ': ' . $exception->getMessage() . PHP_EOL);
    exit(1);
}
