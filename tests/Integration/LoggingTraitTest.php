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
use lindemannrock\logginglibrary\traits\LoggingTrait;

/**
 * Regression coverage for logging handle auto-detection.
 *
 * @since 5.14.0
 */
final class LoggingTraitTest extends TestCase
{
    public function testLoggingHandleUsesGetHandleWhenAvailable(): void
    {
        $logger = new LoggingTraitMethodHandleStub();

        self::assertSame('method-handle', $logger->loggingHandleForTest());
    }

    public function testLoggingHandleUsesPublicHandleProperty(): void
    {
        $logger = new LoggingTraitPropertyHandleStub();

        self::assertSame('property-handle', $logger->loggingHandleForTest());
    }

    public function testLoggingHandleFallsBackToClassName(): void
    {
        $logger = new LoggingTraitFallbackStub();

        self::assertSame('logging-trait-fallback-stub', $logger->loggingHandleForTest());
    }

    public function testFormatMessageHandlesInvalidUtf8Context(): void
    {
        $logger = new LoggingTraitFallbackStub();

        self::assertSame(
            'Invalid context event | [context encoding failed]',
            $logger->formatMessageForTest('Invalid context event', ['payload' => "\xB1\x31"]),
        );
    }
}

final class LoggingTraitMethodHandleStub
{
    use LoggingTrait;

    public function getHandle(): string
    {
        return 'method-handle';
    }

    public function loggingHandleForTest(): string
    {
        return $this->getLoggingHandle();
    }
}

final class LoggingTraitPropertyHandleStub
{
    use LoggingTrait;

    public string $handle = 'property-handle';

    public function loggingHandleForTest(): string
    {
        return $this->getLoggingHandle();
    }
}

final class LoggingTraitFallbackStub
{
    use LoggingTrait;

    public function loggingHandleForTest(): string
    {
        return $this->getLoggingHandle();
    }

    public function formatMessageForTest(string $message, array $params): string
    {
        return $this->formatMessage($message, $params);
    }
}
