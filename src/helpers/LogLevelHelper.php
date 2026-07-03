<?php
/**
 * Logging Library for Craft CMS
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\helpers;

/**
 * Canonicalizes string log levels parsed from file-based logs.
 *
 * @since 5.14.0
 */
class LogLevelHelper
{
    /**
     * Map parsed string levels to the viewer's canonical buckets.
     */
    public static function canonicalLevel(string $level): string
    {
        $level = strtolower($level);

        if ($level === '') {
            return '';
        }

        if (
            str_contains($level, 'fatal')
            || str_contains($level, 'parse')
            || str_contains($level, 'recoverable')
            || str_contains($level, 'error')
            || in_array($level, ['critical', 'alert', 'emergency'], true)
        ) {
            return 'error';
        }

        if (str_contains($level, 'warning')) {
            return 'warning';
        }

        if (
            str_contains($level, 'notice')
            || str_contains($level, 'deprecated')
            || str_contains($level, 'strict')
        ) {
            return 'info';
        }

        if ($level === 'trace') {
            return 'debug';
        }

        if (in_array($level, ['debug', 'info'], true)) {
            return $level;
        }

        return 'unknown';
    }
}
