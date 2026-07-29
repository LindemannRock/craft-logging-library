<?php
/**
 * Logging Library for Craft CMS
 *
 * Dedicated Runtime Logs Redis connection construction.
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\services\runtime;

use craft\helpers\App;
use yii\redis\Connection;

/**
 * Resolves Runtime Logs database configuration and clones safe connection settings.
 *
 * @internal
 * @since 5.18.0
 */
final class RuntimeLogRedisConnectionFactory
{
    private const CONNECTION_PROPERTIES = [
        'hostname',
        'scheme',
        'redirectConnectionString',
        'port',
        'unixSocket',
        'username',
        'password',
        'connectionTimeout',
        'dataTimeout',
        'useSSL',
        'contextOptions',
        'retryInterval',
        'redisCommands',
    ];

    /**
     * Resolve the configured database without ever coercing invalid input to database 0.
     *
     * @return array{valid: bool, database: ?int, mode: string, error: ?string}
     */
    public static function resolveDatabase(array $redisConfig, mixed $inheritedDatabase): array
    {
        if (!array_key_exists('database', $redisConfig)) {
            $database = self::_nonNegativeIntegerOrNull($inheritedDatabase);

            return $database['valid']
                ? ['valid' => true, 'database' => $database['value'], 'mode' => 'inherited', 'error' => null]
                : ['valid' => false, 'database' => null, 'mode' => 'inherited', 'error' => 'invalid-inherited-database'];
        }

        $configured = $redisConfig['database'];
        if ($configured === null) {
            return ['valid' => true, 'database' => null, 'mode' => 'none', 'error' => null];
        }

        if (is_int($configured)) {
            return $configured >= 0
                ? ['valid' => true, 'database' => $configured, 'mode' => 'literal', 'error' => null]
                : ['valid' => false, 'database' => null, 'mode' => 'literal', 'error' => 'negative-database'];
        }

        if (is_string($configured) && str_starts_with($configured, '$')) {
            if (!preg_match('/^\$([A-Z_][A-Z0-9_]*)$/i', $configured, $matches)) {
                return ['valid' => false, 'database' => null, 'mode' => 'environment', 'error' => 'invalid-environment-reference'];
            }

            $resolved = App::env($matches[1]);
            if (!is_int($resolved) || $resolved < 0) {
                return ['valid' => false, 'database' => null, 'mode' => 'environment', 'error' => 'invalid-environment-database'];
            }

            return ['valid' => true, 'database' => $resolved, 'mode' => 'environment', 'error' => null];
        }

        return ['valid' => false, 'database' => null, 'mode' => 'literal', 'error' => 'invalid-database'];
    }

    /**
     * Build a new Yii Redis connection without sharing the source connection's socket state.
     */
    public static function create(Connection $source, ?int $database): Connection
    {
        $config = [];
        foreach (self::CONNECTION_PROPERTIES as $property) {
            $config[$property] = $source->{$property};
        }

        // Runtime Logs owns this transport independently. A persistent socket
        // identity can otherwise be shared with Craft's cache connection.
        $config['socketClientFlags'] = ($source->socketClientFlags & ~STREAM_CLIENT_PERSISTENT) | STREAM_CLIENT_CONNECT;

        // Redis list appends are one transaction. Yii must not reconnect and
        // replay an individual queued command outside a lost MULTI context.
        $config['retries'] = 0;
        $config['database'] = $database;

        return new Connection($config);
    }

    /**
     * @return array{valid: bool, value: ?int}
     */
    private static function _nonNegativeIntegerOrNull(mixed $value): array
    {
        if ($value === null) {
            return ['valid' => true, 'value' => null];
        }

        if (is_int($value)) {
            return ['valid' => $value >= 0, 'value' => $value >= 0 ? $value : null];
        }

        if (is_string($value) && ctype_digit($value)) {
            return ['valid' => true, 'value' => (int)$value];
        }

        return ['valid' => false, 'value' => null];
    }
}
