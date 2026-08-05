<?php
/**
 * LindemannRock Logging Library
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Integration;

use lindemannrock\logginglibrary\tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Process\Process;

/**
 * Protects the package quality-gate, CI, Act, and cleanup contracts.
 *
 * @since 5.18.0
 */
final class QualityGateOrchestrationTest extends TestCase
{
    private const CONSTITUENTS = [
        'package-validation',
        'platform-compatibility',
        'composer-audit',
        'php-quality',
        'phpunit',
        'package-boundary',
    ];

    public function testAggregateDeclaresEveryConstituentExactlyOnce(): void
    {
        $result = $this->runProcess(['bash', 'scripts/quality-gate', '--list']);
        self::assertSame(0, $result->getExitCode(), $result->getErrorOutput());

        $rows = array_values(array_filter(explode("\n", trim($result->getOutput()))));
        $ids = [];
        $families = [];
        foreach ($rows as $row) {
            [$id, $family] = explode("\t", $row, 2);
            $ids[] = $id;
            $families[] = $family;
        }

        self::assertSame(self::CONSTITUENTS, $ids);
        self::assertCount(count($families), array_unique($families));

        $composer = json_decode((string)file_get_contents($this->packageRoot() . '/composer.json'), true, flags: JSON_THROW_ON_ERROR);
        self::assertSame([
            'Composer\\Config::disableProcessTimeout',
            'bash scripts/quality-gate',
        ], $composer['scripts']['quality-gate']);
    }

    public function testSuccessfulProbeRunsEveryConstituentInOrder(): void
    {
        [$probe, $log] = $this->createGateProbe('');
        $result = $this->runProcess(
            ['bash', 'scripts/quality-gate', '--probe', $probe],
            ['LOGGING_LIBRARY_GATE_PROBE_LOG' => $log],
        );

        self::assertSame(0, $result->getExitCode(), $result->getErrorOutput());
        self::assertSame(self::CONSTITUENTS, $this->probeIds($log));
    }

    #[DataProvider('constituentProvider')]
    public function testEveryConstituentFailurePropagatesItsExactStatus(string $failureId): void
    {
        [$probe, $log] = $this->createGateProbe($failureId);
        $result = $this->runProcess(
            ['bash', 'scripts/quality-gate', '--probe', $probe],
            [
                'LOGGING_LIBRARY_GATE_PROBE_LOG' => $log,
                'LOGGING_LIBRARY_GATE_FAIL_ID' => $failureId,
            ],
        );

        self::assertSame(71, $result->getExitCode(), $result->getErrorOutput());
        $ids = $this->probeIds($log);
        self::assertSame($failureId, end($ids));
        self::assertStringContainsString("{$failureId} failed with exit 71.", $result->getErrorOutput());
    }

    public static function constituentProvider(): array
    {
        return array_combine(self::CONSTITUENTS, array_map(
            static fn(string $id): array => [$id],
            self::CONSTITUENTS,
        ));
    }

    #[DataProvider('invalidWorkflowProvider')]
    public function testWorkflowValidatorRejectsInvalidContracts(string $workflow, string $message): void
    {
        $root = $this->createTrackedTempDirectory('logging-library-workflow');
        $path = $root . '/ci.yml';
        file_put_contents($path, $workflow);

        $result = $this->runProcess(['bash', 'scripts/check-ci-workflow', $path]);

        self::assertNotSame(0, $result->getExitCode());
        self::assertStringContainsString($message, $result->getErrorOutput());
    }

    public static function invalidWorkflowProvider(): array
    {
        return [
            'missing canonical gate' => [
                self::workflow(['      - uses: actions/checkout@v6']),
                "must invoke 'composer quality-gate' exactly once",
            ],
            'wildcard trust' => [
                self::workflow([
                    '      - uses: actions/checkout@v6',
                    '      - run: git config --global --add safe.directory "*"',
                    '      - run: composer quality-gate',
                ]),
                'must never use wildcard safe.directory trust',
            ],
            'container trust before checkout' => [
                self::workflow([
                    '      - run: git config --global --add safe.directory "$GITHUB_WORKSPACE"',
                    '      - uses: actions/checkout@v6',
                    '      - run: composer quality-gate',
                ], true),
                'must run after checkout and before composer quality-gate',
            ],
        ];
    }

    public function testCurrentWorkflowAndBothTrustModesPassValidation(): void
    {
        $current = $this->runProcess(['bash', 'scripts/check-ci-workflow', '.github/workflows/ci.yml']);
        self::assertSame(0, $current->getExitCode(), $current->getErrorOutput());

        $root = $this->createTrackedTempDirectory('logging-library-valid-workflows');
        $nonContainer = $root . '/non-container.yml';
        $container = $root . '/container.yml';
        file_put_contents($nonContainer, self::workflow([
            '      - uses: actions/checkout@v6',
            '      - run: composer quality-gate',
        ]));
        file_put_contents($container, self::workflow([
            '      - uses: actions/checkout@v6',
            '      - run: git config --global --add safe.directory "$GITHUB_WORKSPACE"',
            '      - run: composer quality-gate',
        ], true));

        foreach ([$nonContainer, $container] as $path) {
            $result = $this->runProcess(['bash', 'scripts/check-ci-workflow', $path]);
            self::assertSame(0, $result->getExitCode(), $result->getErrorOutput());
        }
    }

    public function testActFailurePropagatesAndCleansRunnerOwnedResources(): void
    {
        $root = $this->createTrackedTempDirectory('logging-library-act');
        $bin = $root . '/bin';
        $resources = $root . '/resources';
        mkdir($bin);
        mkdir($resources);
        mkdir($root . '/scripts');
        mkdir($root . '/.github/workflows', recursive: true);
        copy($this->packageRoot() . '/scripts/act-quality-gates', $root . '/scripts/act-quality-gates');
        copy($this->packageRoot() . '/scripts/check-ci-workflow', $root . '/scripts/check-ci-workflow');
        file_put_contents($root . '/.github/workflows/ci.yml', self::workflow([
            '      - uses: actions/checkout@v6',
            '      - run: git config --global --add safe.directory "$GITHUB_WORKSPACE"',
            '      - run: composer quality-gate',
        ], true));
        $argumentLog = $root . '/act-arguments.log';
        $fakeAct = $bin . '/act';
        file_put_contents($fakeAct, <<<'SH'
#!/bin/sh
printf '%s\n' "$*" > "$LOGGING_LIBRARY_ACT_ARGUMENT_LOG"
touch "$LOGGING_LIBRARY_ACT_RESOURCE_ROOT/job-container"
touch "$LOGGING_LIBRARY_ACT_RESOURCE_ROOT/service-container"
touch "$LOGGING_LIBRARY_ACT_RESOURCE_ROOT/network"
touch "$LOGGING_LIBRARY_ACT_RESOURCE_ROOT/volume"
case " $* " in
    *" --rm "*) rm -f "$LOGGING_LIBRARY_ACT_RESOURCE_ROOT"/* ;;
esac
exit 73
SH);
        chmod($fakeAct, 0700);

        $process = new Process(
            ['/bin/bash', 'scripts/act-quality-gates'],
            $root,
            [
                'PATH' => $bin . ':/usr/bin:/bin',
                'LOGGING_LIBRARY_ACT_ARGUMENT_LOG' => $argumentLog,
                'LOGGING_LIBRARY_ACT_RESOURCE_ROOT' => $resources,
            ],
        );
        $process->run();

        self::assertSame(73, $process->getExitCode(), $process->getErrorOutput());
        self::assertStringContainsString('--rm', (string)file_get_contents($argumentLog));
        self::assertSame([], array_values(array_diff(scandir($resources) ?: [], ['.', '..'])));
    }

    public function testTemporaryAuditAndArchiveResourcesCleanAfterInjectedFailure(): void
    {
        $root = $this->createTrackedTempDirectory('logging-library-runner-cleanup');
        $bin = $root . '/bin';
        mkdir($bin);
        $fakeComposer = $bin . '/composer';
        file_put_contents($fakeComposer, "#!/bin/sh\nexit 0\n");
        chmod($fakeComposer, 0700);

        $audit = $this->runProcess(
            ['bash', 'scripts/composer-audit'],
            [
                'PATH' => $bin . ':/usr/bin:/bin',
                'TMPDIR' => $root,
                'LOGGING_LIBRARY_COMPOSER_AUDIT_FORCE_TEMP' => '1',
                'LOGGING_LIBRARY_COMPOSER_AUDIT_FAIL_STAGE' => 'after-update',
            ],
        );
        self::assertSame(79, $audit->getExitCode());
        self::assertSame([], glob($root . '/logging-library-composer-audit.*') ?: []);

        $archive = $this->runProcess(
            ['bash', 'scripts/check-package-boundary'],
            [
                'TMPDIR' => $root,
                'LOGGING_LIBRARY_PACKAGE_BOUNDARY_FAIL_STAGE' => 'after-archive',
            ],
        );
        self::assertSame(78, $archive->getExitCode());
        self::assertSame([], glob($root . '/logging-library-package-boundary.*') ?: []);
    }

    private static function workflow(array $steps, bool $container = false): string
    {
        $containerLine = $container ? "    container: node:24-bookworm\n" : '';

        return "jobs:\n  quality-gates:\n    runs-on: ubuntu-latest\n"
            . $containerLine
            . "    steps:\n"
            . implode("\n", $steps)
            . "\n      - uses: ramsey/composer-install@v4\n";
    }

    /** @return array{string, string} */
    private function createGateProbe(string $failureId): array
    {
        $root = $this->createTrackedTempDirectory('logging-library-gate-probe');
        $probe = $root . '/probe.sh';
        $log = $root . '/constituents.log';
        file_put_contents($probe, <<<'SH'
#!/bin/sh
printf '%s:%s\n' "$1" "$2" >> "$LOGGING_LIBRARY_GATE_PROBE_LOG"
if [ "$1" = "${LOGGING_LIBRARY_GATE_FAIL_ID:-}" ]; then exit 71; fi
exit 0
SH);
        chmod($probe, 0700);
        if ($failureId === '') {
            file_put_contents($log, '');
        }

        return [$probe, $log];
    }

    /** @return list<string> */
    private function probeIds(string $path): array
    {
        $lines = array_values(array_filter(explode("\n", trim((string)file_get_contents($path)))));

        return array_map(static fn(string $line): string => explode(':', $line, 2)[0], $lines);
    }

    private function runProcess(array $command, array $environment = []): Process
    {
        $process = new Process($command, $this->packageRoot(), $environment);
        $process->setTimeout(60);
        $process->run();

        return $process;
    }

    private function packageRoot(): string
    {
        return dirname(__DIR__, 2);
    }
}
