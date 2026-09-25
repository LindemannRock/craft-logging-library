<?php
/**
 * @link https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Support;

use craft\config\BaseConfig;
use craft\services\Config;

/**
 * Scoped configuration used by runtime settings persistence and rendering tests.
 *
 * @since 5.19.0
 */
final class RuntimePreferencesConfig extends Config
{
    public function __construct(private array $values)
    {
        parent::__construct();
    }

    public function getConfigFromFile(string $filename): array|callable|BaseConfig
    {
        return $filename === 'logging-library' ? $this->values : parent::getConfigFromFile($filename);
    }
}
