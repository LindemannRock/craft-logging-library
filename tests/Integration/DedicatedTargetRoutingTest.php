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
use craft\log\Dispatcher;
use craft\log\MonologTarget;
use lindemannrock\logginglibrary\LoggingLibrary;
use lindemannrock\logginglibrary\tests\TestCase;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\TestHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LogLevel;
use yii\log\Logger as YiiLogger;

/**
 * Dedicated and default target routing regression tests.
 *
 * @since 5.19.0
 */
final class DedicatedTargetRoutingTest extends TestCase
{
    private const HANDLE = 'lglib-test-dedicated-routing';

    public function testConfigurePreservesDefaultTargetsAndRoutesPluginMessagesOnlyToDedicatedTarget(): void
    {
        $configProperty = new \ReflectionProperty(LoggingLibrary::class, '_pluginConfigs');
        $originalPluginConfigs = $configProperty->getValue();
        $dispatcher = Craft::getLogger()->dispatcher;
        $originalTargets = $dispatcher->targets;
        $logComponent = Craft::$app->getLog();
        $originalMonologConfig = $logComponent->monologTargetConfig;
        $stream = fopen('php://memory', 'w+');

        if ($stream === false) {
            self::fail('Unable to create the isolated logging stream.');
        }

        $ownedLoggers = [];

        try {
            $globalCapture = new TestHandler(Level::Debug);
            $streamFormatter = new LineFormatter("GLOBAL [%channel%] %message%\n");
            $streamHandler = (new StreamHandler($stream, Level::Debug))->setFormatter($streamFormatter);
            $defaultTargets = [];

            foreach ([Dispatcher::TARGET_WEB, Dispatcher::TARGET_CONSOLE, Dispatcher::TARGET_QUEUE] as $name) {
                $handlers = $name === Dispatcher::TARGET_WEB
                    ? [$globalCapture, $streamHandler]
                    : [new TestHandler(Level::Debug)];
                $logger = new Logger($name, $handlers);
                $ownedLoggers[] = $logger;
                $defaultTargets[$name] = new MonologTarget([
                    'name' => $name,
                    'logger' => $logger,
                    'enabled' => $name === Dispatcher::TARGET_WEB,
                    'level' => LogLevel::WARNING,
                    'categories' => [],
                    'except' => ["existing-$name:*"],
                    'formatter' => $streamFormatter,
                    'logContext' => false,
                ]);
            }

            $customHandler = new TestHandler(Level::Debug);
            $customLogger = new Logger('custom-target', [$customHandler]);
            $ownedLoggers[] = $customLogger;
            $customTarget = new MonologTarget([
                'name' => 'custom-target',
                'logger' => $customLogger,
                'enabled' => false,
                'level' => LogLevel::ERROR,
                'categories' => ['unrelated:*'],
                'except' => ['custom-existing:*'],
                'formatter' => $streamFormatter,
                'logContext' => false,
            ]);

            $dispatcher->targets = $defaultTargets + ['custom' => $customTarget];
            $logComponent->monologTargetConfig = [
                'except' => ['future-existing:*'],
                'level' => LogLevel::ERROR,
                'maxFiles' => 9,
            ];

            $defaultSnapshots = [];
            foreach ($defaultTargets as $name => $target) {
                $defaultSnapshots[$name] = $this->targetConfiguration($target);
            }
            $customSnapshot = $this->targetConfiguration($customTarget);

            LoggingLibrary::configure([
                'pluginHandle' => self::HANDLE,
                'logLevel' => 'info',
                'enableLogViewer' => false,
            ]);
            LoggingLibrary::configure([
                'pluginHandle' => self::HANDLE,
                'logLevel' => 'warning',
                'enableLogViewer' => false,
            ]);

            self::assertSame(
                [
                    'except' => ['future-existing:*', self::HANDLE],
                    'level' => LogLevel::ERROR,
                    'maxFiles' => 9,
                ],
                $logComponent->monologTargetConfig,
            );
            self::assertSame(1, array_count_values($logComponent->monologTargetConfig['except'])[self::HANDLE] ?? 0);

            foreach ($defaultTargets as $name => $target) {
                self::assertSame(["existing-$name:*", self::HANDLE], $target->except);
                self::assertSame(1, array_count_values($target->except)[self::HANDLE] ?? 0);
                self::assertSame($defaultSnapshots[$name], $this->targetConfiguration($target));
            }

            self::assertSame(['custom-existing:*'], $customTarget->except);
            self::assertSame($customSnapshot, $this->targetConfiguration($customTarget));

            $dedicatedTargets = array_values(array_filter(
                $dispatcher->targets,
                static fn(mixed $target): bool => $target instanceof MonologTarget && $target->getName() === self::HANDLE,
            ));
            self::assertCount(1, $dedicatedTargets);

            $dedicatedTarget = $dedicatedTargets[0];
            self::assertSame([self::HANDLE], $dedicatedTarget->categories);
            self::assertSame(LogLevel::WARNING, $dedicatedTarget->getLevel());

            $dedicatedLogger = $dedicatedTarget->getLogger();
            $ownedLoggers[] = $dedicatedLogger;
            foreach ($dedicatedLogger->getHandlers() as $handler) {
                $handler->close();
            }
            $dedicatedCapture = new TestHandler(Level::Debug);
            $dedicatedLogger->setHandlers([$dedicatedCapture]);

            $dispatcher->dispatch([
                ['plugin message', YiiLogger::LEVEL_WARNING, self::HANDLE, microtime(true), [], memory_get_usage()],
                ['unrelated message', YiiLogger::LEVEL_WARNING, 'unrelated-category', microtime(true), [], memory_get_usage()],
            ], true);

            self::assertTrue($dedicatedCapture->hasWarning('plugin message'));
            self::assertFalse($dedicatedCapture->hasWarning('unrelated message'));
            self::assertFalse($globalCapture->hasWarning('plugin message'));
            self::assertTrue($globalCapture->hasWarning('unrelated message'));
            self::assertCount(1, $dedicatedCapture->getRecords());
            self::assertCount(1, $globalCapture->getRecords());

            rewind($stream);
            $streamOutput = stream_get_contents($stream);
            self::assertIsString($streamOutput);
            self::assertStringContainsString('GLOBAL [web] unrelated message', $streamOutput);
            self::assertStringNotContainsString('plugin message', $streamOutput);
        } finally {
            foreach ($ownedLoggers as $logger) {
                $logger->close();
            }
            fclose($stream);
            $configProperty->setValue(null, $originalPluginConfigs);
            $dispatcher->targets = $originalTargets;
            $logComponent->monologTargetConfig = $originalMonologConfig;
        }
    }

    /**
     * Return target state that configuring another target must not change.
     */
    private function targetConfiguration(MonologTarget $target): array
    {
        return [
            'object' => spl_object_id($target),
            'enabled' => $target->enabled,
            'categories' => $target->categories,
            'level' => $target->getLevel(),
            'logContext' => $target->logContext,
            'formatter' => spl_object_id($target->getFormatter()),
            'logger' => spl_object_id($target->getLogger()),
            'handlers' => array_map('spl_object_id', $target->getLogger()->getHandlers()),
        ];
    }
}
