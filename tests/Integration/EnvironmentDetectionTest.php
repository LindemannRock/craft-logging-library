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
use craft\base\Plugin;
use craft\config\BaseConfig;
use craft\console\Request as ConsoleRequest;
use craft\log\MonologTarget;
use craft\services\Config;
use craft\web\Request as WebRequest;
use lindemannrock\logginglibrary\LoggingLibrary;
use lindemannrock\logginglibrary\models\Settings;
use lindemannrock\logginglibrary\tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Process\Process;
use yii\base\Event;

/**
 * Edge environment detection and viewer-boundary regression tests.
 *
 * @since 5.14.0
 */
final class EnvironmentDetectionTest extends TestCase
{
    private const CRAFT_EPHEMERAL = 'CRAFT_EPHEMERAL';
    private const SERVD_PROJECT_SLUG = 'SERVD_PROJECT_SLUG';

    #[DataProvider('craftEphemeralValueProvider')]
    public function testCraftEphemeralValuesUseCraftNormalization(bool $present, mixed $value, bool $expected): void
    {
        $result = $this->detectInIsolatedProcess($present, $value, false, null);

        self::assertSame($expected, $result['craft']);
        self::assertSame($expected, $result['detected']);
    }

    public static function craftEphemeralValueProvider(): iterable
    {
        yield 'boolean true' => [true, true, true];
        yield 'string true' => [true, 'true', true];
        yield 'boolean false' => [true, false, false];
        yield 'string false' => [true, 'false', false];
        yield 'blank' => [true, '', false];
        yield 'whitespace' => [true, '   ', false];
        yield 'invalid' => [true, 'not-a-boolean', false];
        yield 'absent' => [false, null, false];
    }

    #[DataProvider('servdValueProvider')]
    public function testServdValuesPreserveExistingSemantics(bool $present, mixed $value, bool $expected): void
    {
        $result = $this->detectInIsolatedProcess(false, null, $present, $value);

        self::assertSame($expected, $result['detected']);
    }

    public static function servdValueProvider(): iterable
    {
        yield 'nonblank slug' => [true, 'lr-craftplugins', true];
        yield 'blank' => [true, '', false];
        yield 'whitespace' => [true, '   ', false];
        yield 'string false' => [true, 'false', false];
        yield 'absent' => [false, null, false];
    }

    #[DataProvider('composedSignalProvider')]
    public function testCraftAndServdSignalsComposeWithOr(
        mixed $craftValue,
        bool $craftPresent,
        mixed $servdValue,
        bool $servdPresent,
        bool $expected,
    ): void {
        $result = $this->detectInIsolatedProcess($craftPresent, $craftValue, $servdPresent, $servdValue);

        self::assertSame($expected, $result['detected']);
    }

    public static function composedSignalProvider(): iterable
    {
        yield 'Craft only' => ['true', true, null, false, true];
        yield 'Servd remains true when Craft is false' => ['false', true, 'lr-craftplugins', true, true];
        yield 'both true' => ['true', true, 'lr-craftplugins', true, true];
        yield 'both false or absent' => ['false', true, null, false, false];
    }

    #[DataProvider('viewerTruthTableProvider')]
    public function testViewerAndRuntimeNavigationTruthTable(
        string $environment,
        bool $forceEnableLogViewer,
        bool $runtimeEnabled,
        bool $expectedFileViewer,
        bool $expectedRuntimeNavigation,
    ): void {
        [$craftPresent, $craftValue, $servdPresent, $servdValue] = $this->environmentFixture($environment);

        $this->withEnvironmentValues(
            craftPresent: $craftPresent,
            craftValue: $craftValue,
            servdPresent: $servdPresent,
            servdValue: $servdValue,
            callback: function() use ($forceEnableLogViewer, $runtimeEnabled, $expectedFileViewer, $expectedRuntimeNavigation): void {
                $this->withRuntimeConfig($runtimeEnabled, function() use ($forceEnableLogViewer, $expectedFileViewer, $expectedRuntimeNavigation): void {
                    $settings = new Settings([
                        'showCpSection' => true,
                        'forceEnableLogViewer' => $forceEnableLogViewer,
                    ]);

                    self::assertSame($expectedFileViewer, LoggingLibrary::areLogViewersAvailable($settings));

                    $sections = $this->sectionsByKey(LoggingLibrary::getInstance()->getCpSections($settings));
                    self::assertSame($expectedFileViewer, $sections['all-logs']['when']);
                    self::assertSame($expectedRuntimeNavigation, $sections['runtime-logs']['when']);

                    if (!$expectedFileViewer && !$expectedRuntimeNavigation) {
                        $this->withPluginSettings(
                            $settings,
                            fn() => self::assertNull(LoggingLibrary::getInstance()->getCpNavItem()),
                        );
                    }
                });
            },
        );
    }

    public static function viewerTruthTableProvider(): iterable
    {
        yield 'durable, runtime disabled' => ['durable', false, false, true, false];
        yield 'durable, runtime enabled' => ['durable', false, true, true, true];
        yield 'Servd, runtime disabled' => ['servd', false, false, false, false];
        yield 'Servd, runtime enabled' => ['servd', false, true, false, true];
        yield 'Craft ephemeral, runtime disabled' => ['craft', false, false, false, false];
        yield 'Craft ephemeral, runtime enabled' => ['craft', false, true, false, true];
        yield 'detected host forced, runtime disabled' => ['both', true, false, true, false];
        yield 'detected host forced, runtime enabled' => ['both', true, true, true, true];
    }

    public function testHiddenMainMenuDoesNotCreateNavigationEvenWhenRuntimeLogsAreEnabled(): void
    {
        $this->withEnvironmentValues(true, false, true, false, function(): void {
            $this->withRuntimeConfig(true, function(): void {
                $settings = new Settings([
                    'showCpSection' => false,
                    'forceEnableLogViewer' => false,
                ]);

                $this->withPluginSettings(
                    $settings,
                    fn() => self::assertNull(LoggingLibrary::getInstance()->getCpNavItem()),
                );
            });
        });
    }

    #[DataProvider('requestModeProvider')]
    public function testAutomaticSuppressionPreservesDedicatedTargetsInConsoleAndControlPanel(string $requestMode): void
    {
        $this->withEnvironmentValues(true, 'true', true, false, function() use ($requestMode): void {
            $this->withLoggingState(function() use ($requestMode): void {
                $originalRequest = Craft::$app->getRequest();
                $handle = 'lglib-test-ephemeral-' . $requestMode;

                try {
                    Craft::$app->set('request', $requestMode === 'console' ? new ConsoleRequest() : new WebRequest());
                    $beforeMonologConfig = Craft::$app->getLog()->monologTargetConfig ?? [];
                    $beforeTargetCount = count(Craft::getLogger()->dispatcher->targets);

                    LoggingLibrary::configure(['pluginHandle' => $handle]);

                    self::assertFalse(LoggingLibrary::getConfig($handle)['enableLogViewer']);
                    self::assertCount($beforeTargetCount + 1, Craft::getLogger()->dispatcher->targets);

                    $target = $this->dedicatedTarget($handle);
                    self::assertInstanceOf(MonologTarget::class, $target);
                    self::assertSame($handle, $target->getName());
                    self::assertSame([$handle], $target->categories);

                    $expectedMonologConfig = $beforeMonologConfig;
                    $expectedMonologConfig['except'] = $expectedMonologConfig['except'] ?? [];
                    if (!in_array($handle, $expectedMonologConfig['except'], true)) {
                        $expectedMonologConfig['except'][] = $handle;
                    }
                    self::assertSame($expectedMonologConfig, Craft::$app->getLog()->monologTargetConfig);
                } finally {
                    Craft::$app->set('request', $originalRequest);
                }
            });
        });
    }

    public static function requestModeProvider(): iterable
    {
        yield 'console' => ['console'];
        yield 'control panel' => ['control-panel'];
    }

    public function testExplicitPerPluginViewerSettingKeepsPrecedence(): void
    {
        $this->withLoggingState(function(): void {
            $this->withEnvironmentValues(true, 'true', true, false, function(): void {
                LoggingLibrary::configure([
                    'pluginHandle' => 'lglib-test-explicit-on',
                    'enableLogViewer' => true,
                ]);

                self::assertTrue(LoggingLibrary::getConfig('lglib-test-explicit-on')['enableLogViewer']);
                self::assertInstanceOf(MonologTarget::class, $this->dedicatedTarget('lglib-test-explicit-on'));
            });

            $this->withEnvironmentValues(true, false, true, false, function(): void {
                LoggingLibrary::configure([
                    'pluginHandle' => 'lglib-test-explicit-off',
                    'enableLogViewer' => false,
                ]);

                self::assertFalse(LoggingLibrary::getConfig('lglib-test-explicit-off')['enableLogViewer']);
                self::assertInstanceOf(MonologTarget::class, $this->dedicatedTarget('lglib-test-explicit-off'));
            });
        });
    }

    private function withEnvironmentValues(
        bool $craftPresent,
        mixed $craftValue,
        bool $servdPresent,
        mixed $servdValue,
        callable $callback,
    ): void {
        $snapshots = [];
        foreach ([self::CRAFT_EPHEMERAL, self::SERVD_PROJECT_SLUG] as $name) {
            $snapshots[$name] = [
                'serverPresent' => array_key_exists($name, $_SERVER),
                'serverValue' => $_SERVER[$name] ?? null,
                'envPresent' => array_key_exists($name, $_ENV),
                'envValue' => $_ENV[$name] ?? null,
            ];
        }

        try {
            $this->setEnvironmentValue(self::CRAFT_EPHEMERAL, $craftPresent, $craftValue);
            $this->setEnvironmentValue(self::SERVD_PROJECT_SLUG, $servdPresent, $servdValue);
            $callback();
        } finally {
            foreach ($snapshots as $name => $snapshot) {
                if ($snapshot['serverPresent']) {
                    $_SERVER[$name] = $snapshot['serverValue'];
                } else {
                    unset($_SERVER[$name]);
                }

                if ($snapshot['envPresent']) {
                    $_ENV[$name] = $snapshot['envValue'];
                } else {
                    unset($_ENV[$name]);
                }
            }
        }
    }

    private function setEnvironmentValue(string $name, bool $present, mixed $value): void
    {
        if (!$present) {
            unset($_SERVER[$name], $_ENV[$name]);
            return;
        }

        $_SERVER[$name] = $value;
        $_ENV[$name] = $value;
    }

    private function environmentFixture(string $environment): array
    {
        return match ($environment) {
            'durable' => [true, false, true, false],
            'servd' => [true, 'false', true, 'lr-craftplugins'],
            'craft' => [true, 'true', true, false],
            'both' => [true, 'true', true, 'lr-craftplugins'],
            default => throw new \InvalidArgumentException('Unknown environment fixture: ' . $environment),
        };
    }

    private function detectInIsolatedProcess(
        bool $craftPresent,
        mixed $craftValue,
        bool $servdPresent,
        mixed $servdValue,
    ): array {
        $vendorRoot = $_SERVER['LOGGING_LIBRARY_FIXTURE_SOURCE_VENDOR_ROOT']
            ?? $_ENV['LOGGING_LIBRARY_FIXTURE_SOURCE_VENDOR_ROOT']
            ?? dirname(__DIR__, 4) . '/vendor';
        if (!is_string($vendorRoot) || $vendorRoot === '') {
            throw new \RuntimeException('Environment detection child-process vendor root must be a non-empty path.');
        }

        $autoloadPath = rtrim($vendorRoot, '/\\') . '/autoload.php';
        if (!is_file($autoloadPath)) {
            throw new \RuntimeException(sprintf(
                'Environment detection child-process autoloader was not found at "%s".',
                $autoloadPath,
            ));
        }

        $payload = base64_encode(json_encode([
            'craftPresent' => $craftPresent,
            'craftValue' => $craftValue,
            'servdPresent' => $servdPresent,
            'servdValue' => $servdValue,
        ], JSON_THROW_ON_ERROR));

        $script = <<<'PHP'
require $argv[1];
$payload = json_decode(base64_decode($argv[2], true), true, 512, JSON_THROW_ON_ERROR);
foreach ([
    'CRAFT_EPHEMERAL' => ['present' => $payload['craftPresent'], 'value' => $payload['craftValue']],
    'SERVD_PROJECT_SLUG' => ['present' => $payload['servdPresent'], 'value' => $payload['servdValue']],
] as $name => $fixture) {
    unset($_SERVER[$name], $_ENV[$name]);
    if ($fixture['present']) {
        $_SERVER[$name] = $fixture['value'];
        $_ENV[$name] = $fixture['value'];
    }
}
$method = new ReflectionMethod(\lindemannrock\logginglibrary\LoggingLibrary::class, '_detectEdgeEnvironment');
echo json_encode([
    'craft' => \craft\helpers\App::isEphemeral(),
    'detected' => (bool)$method->invoke(null),
], JSON_THROW_ON_ERROR);
PHP;

        $process = new Process(
            [PHP_BINARY, '-r', $script, $autoloadPath, $payload],
            null,
            [
                self::CRAFT_EPHEMERAL => false,
                self::SERVD_PROJECT_SLUG => false,
            ],
        );
        $process->setTimeout(10);
        $process->mustRun();

        $result = json_decode($process->getOutput(), true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($result)) {
            throw new \RuntimeException('The isolated environment detector returned an invalid result.');
        }

        return $result;
    }

    private function withRuntimeConfig(bool $enabled, callable $callback): void
    {
        $originalConfig = Craft::$app->getConfig();

        try {
            Craft::$app->set('config', new EdgeEnvironmentConfig($enabled));
            $callback();
        } finally {
            Craft::$app->set('config', $originalConfig);
        }
    }

    private function withPluginSettings(Settings $settings, callable $callback): void
    {
        $property = new \ReflectionProperty(Plugin::class, '_settings');
        $plugin = LoggingLibrary::getInstance();
        $originalSettings = $property->getValue($plugin);

        try {
            $property->setValue($plugin, $settings);
            $callback();
        } finally {
            $property->setValue($plugin, $originalSettings);
        }
    }

    private function sectionsByKey(array $sections): array
    {
        $keyed = [];
        foreach ($sections as $section) {
            $keyed[$section['key']] = $section;
        }

        return $keyed;
    }

    private function withLoggingState(callable $callback): void
    {
        $configProperty = new \ReflectionProperty(LoggingLibrary::class, '_pluginConfigs');
        $eventsProperty = new \ReflectionProperty(Event::class, '_events');
        $wildcardsProperty = new \ReflectionProperty(Event::class, '_eventWildcards');
        $originalPluginConfigs = $configProperty->getValue();
        $originalEvents = $eventsProperty->getValue();
        $originalWildcards = $wildcardsProperty->getValue();
        $originalTargets = Craft::getLogger()->dispatcher->targets;
        $originalMonologConfig = Craft::$app->getLog()->monologTargetConfig;

        try {
            $callback();
        } finally {
            $configProperty->setValue(null, $originalPluginConfigs);
            $eventsProperty->setValue(null, $originalEvents);
            $wildcardsProperty->setValue(null, $originalWildcards);
            Craft::getLogger()->dispatcher->targets = $originalTargets;
            Craft::$app->getLog()->monologTargetConfig = $originalMonologConfig;
        }
    }

    private function dedicatedTarget(string $handle): ?MonologTarget
    {
        foreach (Craft::getLogger()->dispatcher->targets as $target) {
            if ($target instanceof MonologTarget && $target->getName() === $handle) {
                return $target;
            }
        }

        return null;
    }
}

final class EdgeEnvironmentConfig extends Config
{
    public function __construct(private bool $runtimeEnabled)
    {
        parent::__construct();
    }

    public function getConfigFromFile(string $filename): array|callable|BaseConfig
    {
        if ($filename === 'logging-library') {
            return [
                'runtimeLogStore' => [
                    'enabled' => $this->runtimeEnabled,
                ],
            ];
        }

        return parent::getConfigFromFile($filename);
    }
}
