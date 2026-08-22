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
use craft\console\Request as CraftConsoleRequest;
use craft\helpers\Json;
use lindemannrock\base\testing\IntegrationTestCase;
use lindemannrock\logginglibrary\log\targets\RuntimeLogTarget;
use lindemannrock\logginglibrary\LoggingLibrary;
use lindemannrock\logginglibrary\services\LoggingService;
use lindemannrock\logginglibrary\services\RuntimeLogStoreService;
use PHPUnit\Framework\Attributes\DataProvider;
use samdark\log\PsrMessage;
use yii\caching\ArrayCache;
use yii\helpers\VarDumper;
use yii\log\Logger;
use yii\mutex\Mutex;

/**
 * Covers structured PSR message normalization for the Runtime Logs viewer.
 *
 * @since 5.19.0
 */
final class RuntimeLogMessageNormalizationTest extends IntegrationTestCase
{
    public function testRuntimeTargetCarriesRawPsrMessageIntoStructuredRecord(): void
    {
        $this->withIsolatedStore(function(RuntimeLogStoreService $store): void {
            $this->swapPluginComponent('logging-library', 'runtimeLogStore', $store);
            $originalRequest = Craft::$app->getRequest();
            $originalRoute = Craft::$app->requestedRoute;

            try {
                Craft::$app->set('request', new RuntimeMessageNormalizationWebRequest());
                Craft::$app->requestedRoute = 'site/index';

                $target = new RuntimeLogTarget([
                    'runtimeSettings' => $this->settings(),
                ]);
                $target->messages = [[
                    new PsrMessage('Captured structured message', ['requestId' => 'req-target']),
                    Logger::LEVEL_WARNING,
                    'runtime-structured',
                    microtime(true),
                    [],
                    256,
                ]];
                $target->export();

                $page = $store->getLogPage('all', 'all', 'req-target', 'timestamp', 'desc', 1, 10);

                self::assertSame(1, $page['total']);
                self::assertSame('Captured structured message', $page['entries'][0]['message']);
                self::assertSame('req-target', Json::decode($page['entries'][0]['context'])['requestId']);
            } finally {
                Craft::$app->requestedRoute = $originalRoute;
                Craft::$app->set('request', $originalRequest);
            }
        });
    }

    public function testPsrMessageUsesItsMessageAndContextWithoutReconstructionText(): void
    {
        $record = (new RuntimeLogStoreService())->normalizeMessage([
            new PsrMessage('Structured runtime message', ['requestId' => 'req-123']),
            Logger::LEVEL_WARNING,
            'runtime-structured',
            1787385600.25,
            [],
            2048,
        ], $this->settings());

        self::assertSame('Structured runtime message', $record['message']);
        self::assertStringNotContainsString('unserialize(', $record['message']);
        self::assertStringNotContainsString(PsrMessage::class, $record['message']);
        self::assertSame([
            'requestId' => 'req-123',
            'trace' => [],
            'memory' => 2048,
            'category' => 'runtime-structured',
            'timestamp' => 1787385600.25,
        ], Json::decode($record['context']));
    }

    public function testYiiTupleContextWinsPsrContextCollisions(): void
    {
        $trace = [
            ['file' => '/tmp/one.php', 'line' => 1],
            ['file' => '/tmp/two.php', 'line' => 2],
            ['file' => '/tmp/three.php', 'line' => 3],
            ['file' => '/tmp/four.php', 'line' => 4],
            ['file' => '/tmp/five.php', 'line' => 5],
            ['file' => '/tmp/six.php', 'line' => 6],
        ];
        $timestamp = 1787385600.5;

        $record = (new RuntimeLogStoreService())->normalizeMessage([
            new PsrMessage('Collision check', [
                'custom' => 'preserved',
                'trace' => ['psr-trace'],
                'memory' => 1,
                'category' => 'psr-category',
                'timestamp' => 1.0,
            ]),
            Logger::LEVEL_ERROR,
            'yii-category',
            $timestamp,
            $trace,
            8192,
        ], $this->settings());

        $context = Json::decode($record['context']);

        self::assertSame('yii-category', $record['category']);
        self::assertStringContainsString('.500000', $record['timestamp']);
        self::assertSame('preserved', $context['custom']);
        self::assertSame(array_slice($trace, 0, 5), $context['trace']);
        self::assertSame(8192, $context['memory']);
        self::assertSame('yii-category', $context['category']);
        self::assertSame($timestamp, $context['timestamp']);
    }

    public function testEmptyYiiTupleTraceOverridesNonEmptyPsrTrace(): void
    {
        $timestamp = 1787385600.75;

        $record = (new RuntimeLogStoreService())->normalizeMessage([
            new PsrMessage('Empty tuple trace collision', [
                'neighbor' => 'preserved',
                'trace' => ['psr-trace'],
                'memory' => 1,
                'category' => 'psr-category',
                'timestamp' => 1.0,
            ]),
            Logger::LEVEL_WARNING,
            'yii-category',
            $timestamp,
            [],
            4096,
        ], $this->settings());

        $context = Json::decode($record['context']);

        self::assertSame('preserved', $context['neighbor']);
        self::assertSame([], $context['trace']);
        self::assertSame(4096, $context['memory']);
        self::assertSame('yii-category', $context['category']);
        self::assertSame($timestamp, $context['timestamp']);
    }

    public function testPsrMessageAndContextRemainSearchable(): void
    {
        $this->withIsolatedStore(function(RuntimeLogStoreService $store): void {
            $store->appendMessages([
                [
                    new PsrMessage('Searchable structured message', ['correlationId' => 'context-needle']),
                    Logger::LEVEL_INFO,
                    'runtime-structured',
                    microtime(true),
                    [],
                    100,
                ],
            ], $this->settings());

            $messagePage = $store->getLogPage('all', 'all', 'structured message', 'message', 'asc', 1, 10);
            $contextPage = $store->getLogPage('all', 'all', 'context-needle', 'message', 'asc', 1, 10);

            self::assertSame(1, $messagePage['total']);
            self::assertSame('Searchable structured message', $messagePage['entries'][0]['message']);
            self::assertSame(1, $contextPage['total']);
            self::assertStringContainsString('context-needle', $contextPage['entries'][0]['context']);
        });
    }

    public function testPsrMessageAndContextRetainBoundsAndNewlineSanitization(): void
    {
        $record = (new RuntimeLogStoreService())->normalizeMessage([
            new PsrMessage("First line\r\nSecond line with extra text", [
                'details' => "Context line one\r\nContext line two with extra text",
            ]),
            Logger::LEVEL_INFO,
            'runtime-structured',
            microtime(true),
            [],
            100,
        ], $this->settings([
            'maxMessageBytes' => 24,
            'maxContextBytes' => 48,
        ]));

        self::assertStringNotContainsString("\r", $record['message']);
        self::assertStringNotContainsString("\n", $record['message']);
        self::assertStringEndsWith('...', $record['message']);
        self::assertLessThanOrEqual(27, strlen($record['message']));
        self::assertStringNotContainsString("\r", $record['context']);
        self::assertStringNotContainsString("\n", $record['context']);
        self::assertStringEndsWith('...', $record['context']);
        self::assertLessThanOrEqual(51, strlen($record['context']));
    }

    public function testUnnormalizablePsrContextDoesNotDropItsRecordOrNeighbors(): void
    {
        $this->withIsolatedStore(function(RuntimeLogStoreService $store): void {
            $store->appendMessages([
                ['Before structured record', Logger::LEVEL_INFO, 'runtime-structured', 1787385600.1, [], 100],
                [
                    new PsrMessage('Structured record survives', [
                        'safe' => 'retained',
                        'broken' => new ThrowingRuntimeContextValue(),
                    ]),
                    Logger::LEVEL_WARNING,
                    'runtime-structured',
                    1787385600.2,
                    [],
                    200,
                ],
                ['After structured record', Logger::LEVEL_ERROR, 'runtime-structured', 1787385600.3, [], 300],
            ], $this->settings());

            $page = $store->getLogPage('all', 'all', '', 'timestamp', 'asc', 1, 10);
            $entries = array_column($page['entries'], null, 'message');
            $messages = array_column($page['entries'], 'message');
            sort($messages);

            self::assertSame(3, $page['total']);
            self::assertSame([
                'After structured record',
                'Before structured record',
                'Structured record survives',
            ], $messages);
            self::assertSame('retained', Json::decode($entries['Structured record survives']['context'])['safe']);
            self::assertSame(
                '[Unable to normalize context value]',
                Json::decode($entries['Structured record survives']['context'])['broken'],
            );
        });
    }

    #[DataProvider('existingMessageTypeProvider')]
    public function testExistingMessageTypesKeepTheirRuntimeRendering(mixed $message, string $expected): void
    {
        $record = (new RuntimeLogStoreService())->normalizeMessage([
            $message,
            Logger::LEVEL_INFO,
            'runtime-existing',
            microtime(true),
            [],
        ], $this->settings());

        self::assertSame($expected, $record['message']);
        self::assertSame('', $record['context']);
    }

    public static function existingMessageTypeProvider(): array
    {
        $throwable = new \RuntimeException('Existing throwable behavior');
        $object = new ExistingRuntimeMessageObject();
        $stringable = new ExistingRuntimeStringableMessage();

        return [
            'string' => ['Existing string behavior', 'Existing string behavior'],
            'throwable' => [$throwable, LoggingService::sanitizeLogMessage((string)$throwable)],
            'generic object' => [$object, VarDumper::export($object)],
            'generic Stringable object' => [$stringable, VarDumper::export($stringable)],
        ];
    }

    public function testRuntimeNormalizationDoesNotMutateCraftOrDedicatedTargets(): void
    {
        $dispatcher = Craft::getLogger()->dispatcher;
        $targetIds = array_map('spl_object_id', $dispatcher->targets);
        $targetClasses = array_map(static fn(object $target): string => $target::class, $dispatcher->targets);
        $monologTargetConfig = Craft::$app->getLog()->monologTargetConfig;

        (new RuntimeLogStoreService())->normalizeMessage([
            new PsrMessage('Target invariant', ['custom' => 'context']),
            Logger::LEVEL_INFO,
            'runtime-target-invariant',
            microtime(true),
            [],
            100,
        ], $this->settings());

        self::assertSame($targetIds, array_map('spl_object_id', $dispatcher->targets));
        self::assertSame($targetClasses, array_map(static fn(object $target): string => $target::class, $dispatcher->targets));
        self::assertSame($monologTargetConfig, Craft::$app->getLog()->monologTargetConfig);
    }

    /**
     * @param callable(RuntimeLogStoreService): void $assertions
     */
    private function withIsolatedStore(callable $assertions): void
    {
        $originalCache = Craft::$app->getCache();
        $originalMutex = Craft::$app->getMutex();

        try {
            Craft::$app->set('cache', new ArrayCache());
            Craft::$app->set('mutex', new RuntimeMessageNormalizationMutex());
            $assertions(new RuntimeLogStoreService());
        } finally {
            Craft::$app->set('mutex', $originalMutex);
            Craft::$app->set('cache', $originalCache);
        }
    }

    private function settings(array $overrides = []): array
    {
        return array_merge(LoggingLibrary::getRuntimeLogStoreConfig(), [
            'enabled' => true,
            'ttl' => 60,
            'maxEntries' => 100,
            'maxMessageBytes' => 8000,
            'maxContextBytes' => 8000,
            'privacy' => [
                'includeUserId' => false,
            ],
        ], $overrides);
    }
}

final class ThrowingRuntimeContextValue implements \JsonSerializable
{
    public function jsonSerialize(): mixed
    {
        throw new \RuntimeException('Context cannot be serialized.');
    }
}

final class ExistingRuntimeMessageObject
{
    public string $value = 'generic object';
}

final class ExistingRuntimeStringableMessage implements \Stringable
{
    public function __toString(): string
    {
        return 'generic Stringable rendering must remain unchanged';
    }
}

final class RuntimeMessageNormalizationMutex extends Mutex
{
    protected function acquireLock($name, $timeout = 0): bool
    {
        return true;
    }

    protected function releaseLock($name): bool
    {
        return true;
    }
}

final class RuntimeMessageNormalizationWebRequest extends CraftConsoleRequest
{
    public function __construct()
    {
        parent::__construct();
        $this->setIsConsoleRequest(false);
    }

    public function getPathInfo(): string
    {
        return '';
    }

    public function getParam(string $name, mixed $defaultValue = null): mixed
    {
        return $defaultValue;
    }
}
