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
use Symfony\Component\Process\Process;

/**
 * Protects read-only workspace and standalone pre-commit routing.
 *
 * @since 5.18.0
 */
final class PreCommitHookTest extends TestCase
{
    public function testWorkspaceRunsComposerCiOnlyThroughDdevWithoutMutation(): void
    {
        $fixture = $this->createHookFixture(workspace: true);
        $result = $this->runHook($fixture);

        self::assertSame(0, $result->getExitCode(), $result->getErrorOutput());
        $log = (string)file_get_contents($fixture['log']);
        self::assertStringContainsString('ddev:exec cd plugins/logging-library', $log);
        self::assertStringContainsString('composer ci', $log);
        self::assertStringNotContainsString("\nphp:", "\n" . $log);
        self::assertStringNotContainsString("\ncomposer:", "\n" . $log);
        self::assertSame($fixture['snapshot'], $this->snapshot($fixture['packageRoot']));
    }

    public function testWorkspaceDdevFailurePropagatesWithoutHostFallbackOrMutation(): void
    {
        $fixture = $this->createHookFixture(workspace: true, ddevExit: 37);
        $result = $this->runHook($fixture);

        self::assertSame(37, $result->getExitCode());
        self::assertStringContainsString('failed in DDEV (exit 37)', $result->getErrorOutput());
        $log = (string)file_get_contents($fixture['log']);
        self::assertStringNotContainsString("\nphp:", "\n" . $log);
        self::assertStringNotContainsString("\ncomposer:", "\n" . $log);
        self::assertSame($fixture['snapshot'], $this->snapshot($fixture['packageRoot']));
    }

    public function testStandaloneValidatesPackagePlatformAndToolsBeforeComposerCi(): void
    {
        $fixture = $this->createHookFixture();
        $result = $this->runHook($fixture);

        self::assertSame(0, $result->getExitCode(), $result->getErrorOutput());
        $log = (string)file_get_contents($fixture['log']);
        self::assertMatchesRegularExpression('/composer:validate --no-plugins --no-check-publish --no-interaction.*composer:check-platform-reqs --no-interaction.*php:scripts\/check-quality-platform[.]php.*composer:ci/s', $log);
        self::assertStringNotContainsString("\nddev:", "\n" . $log);
        self::assertSame($fixture['snapshot'], $this->snapshot($fixture['packageRoot']));
    }

    public function testStandaloneFailuresPropagateBeforeLaterChecks(): void
    {
        foreach ([
            ['validateExit' => 41, 'expected' => 41, 'absent' => 'check-platform-reqs'],
            ['platformExit' => 42, 'expected' => 42, 'absent' => 'check-quality-platform.php'],
            ['qualityExit' => 43, 'expected' => 43, 'absent' => 'composer:ci'],
            ['ciExit' => 44, 'expected' => 44, 'absent' => 'never-matches'],
        ] as $case) {
            $fixture = $this->createHookFixture(
                validateExit: $case['validateExit'] ?? 0,
                platformExit: $case['platformExit'] ?? 0,
                qualityExit: $case['qualityExit'] ?? 0,
                ciExit: $case['ciExit'] ?? 0,
            );
            $result = $this->runHook($fixture);

            self::assertSame($case['expected'], $result->getExitCode());
            self::assertStringNotContainsString($case['absent'], (string)file_get_contents($fixture['log']));
            self::assertSame($fixture['snapshot'], $this->snapshot($fixture['packageRoot']));
        }
    }

    /** @return array{root: string, packageRoot: string, bin: string, log: string, snapshot: array<string, string>} */
    private function createHookFixture(
        bool $workspace = false,
        int $ddevExit = 0,
        int $validateExit = 0,
        int $platformExit = 0,
        int $qualityExit = 0,
        int $ciExit = 0,
    ): array {
        $root = $this->createTrackedTempDirectory('logging-library-hook');
        $packageRoot = $workspace ? $root . '/plugins/logging-library' : $root . '/logging-library';
        $bin = $root . '/bin';
        $log = $root . '/commands.log';
        mkdir($packageRoot . '/.githooks', recursive: true);
        mkdir($bin);
        copy($this->packageRoot() . '/.githooks/pre-commit', $packageRoot . '/.githooks/pre-commit');
        file_put_contents($packageRoot . '/sentinel.txt', "must remain byte-identical\n");
        file_put_contents($log, '');
        if ($workspace) {
            mkdir($root . '/.ddev');
            file_put_contents($root . '/.ddev/config.yaml', "php_version: \"8.3\"\n");
        }

        $this->writeExecutable($bin . '/ddev', "#!/bin/sh\nprintf 'ddev:%s\\n' \"\$*\" >> \"\$LOGGING_LIBRARY_HOOK_TEST_LOG\"\nexit {$ddevExit}\n");
        $this->writeExecutable($bin . '/php', "#!/bin/sh\nprintf 'php:%s\\n' \"\$*\" >> \"\$LOGGING_LIBRARY_HOOK_TEST_LOG\"\nif [ \"\$1\" = \"-r\" ]; then printf '8.3.30'; exit 0; fi\nif [ \"\$1\" = \"scripts/check-quality-platform.php\" ]; then exit {$qualityExit}; fi\nexit 92\n");
        $this->writeExecutable($bin . '/composer', "#!/bin/sh\nprintf 'composer:%s\\n' \"\$*\" >> \"\$LOGGING_LIBRARY_HOOK_TEST_LOG\"\nif [ \"\$1\" = \"validate\" ]; then exit {$validateExit}; fi\nif [ \"\$1\" = \"check-platform-reqs\" ]; then exit {$platformExit}; fi\nif [ \"\$1\" = \"ci\" ]; then exit {$ciExit}; fi\nexit 91\n");

        return [
            'root' => $root,
            'packageRoot' => $packageRoot,
            'bin' => $bin,
            'log' => $log,
            'snapshot' => $this->snapshot($packageRoot),
        ];
    }

    private function runHook(array $fixture): Process
    {
        $process = new Process(
            ['/bin/bash', $fixture['packageRoot'] . '/.githooks/pre-commit'],
            $fixture['packageRoot'],
            [
                'PATH' => $fixture['bin'] . ':/usr/bin:/bin',
                'LOGGING_LIBRARY_HOOK_TEST_LOG' => $fixture['log'],
            ],
        );
        $process->run();

        return $process;
    }

    /** @return array<string, string> */
    private function snapshot(string $root): array
    {
        $snapshot = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS),
        );
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $snapshot[substr($file->getPathname(), strlen($root) + 1)] = hash_file('sha256', $file->getPathname());
            }
        }
        ksort($snapshot);

        return $snapshot;
    }

    private function writeExecutable(string $path, string $source): void
    {
        file_put_contents($path, $source);
        chmod($path, 0700);
    }

    private function packageRoot(): string
    {
        return dirname(__DIR__, 2);
    }
}
