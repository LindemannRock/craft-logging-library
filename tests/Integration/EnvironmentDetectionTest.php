<?php
/**
 * LindemannRock Logging Library
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Integration;

use lindemannrock\logginglibrary\LoggingLibrary;
use lindemannrock\logginglibrary\tests\TestCase;

/**
 * Edge environment detection regression tests.
 *
 * @since 5.14.0
 */
final class EnvironmentDetectionTest extends TestCase
{
    public function testBlankServdProjectSlugDoesNotCountAsEdgeEnvironment(): void
    {
        $previous = $_SERVER['SERVD_PROJECT_SLUG'] ?? null;

        try {
            $_SERVER['SERVD_PROJECT_SLUG'] = '   ';

            self::assertFalse($this->detectEdgeEnvironment());
        } finally {
            if ($previous === null) {
                unset($_SERVER['SERVD_PROJECT_SLUG']);
            } else {
                $_SERVER['SERVD_PROJECT_SLUG'] = $previous;
            }
        }
    }

    public function testNonBlankServdProjectSlugCountsAsEdgeEnvironment(): void
    {
        $previous = $_SERVER['SERVD_PROJECT_SLUG'] ?? null;

        try {
            $_SERVER['SERVD_PROJECT_SLUG'] = 'lr-craftplugins';

            self::assertTrue($this->detectEdgeEnvironment());
        } finally {
            if ($previous === null) {
                unset($_SERVER['SERVD_PROJECT_SLUG']);
            } else {
                $_SERVER['SERVD_PROJECT_SLUG'] = $previous;
            }
        }
    }

    private function detectEdgeEnvironment(): bool
    {
        $method = new \ReflectionMethod(LoggingLibrary::class, '_detectEdgeEnvironment');

        return (bool)$method->invoke(null);
    }
}
