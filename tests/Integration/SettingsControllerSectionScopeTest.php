<?php
/**
 * LindemannRock Logging Library
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Integration;

use lindemannrock\logginglibrary\controllers\SettingsController;
use lindemannrock\logginglibrary\LoggingLibrary;
use lindemannrock\logginglibrary\models\Settings;
use lindemannrock\logginglibrary\tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @since 5.10.0
 */
#[CoversClass(SettingsController::class)]
final class SettingsControllerSectionScopeTest extends TestCase
{
    public function testSettingsSectionsMatchRenderedFormScopes(): void
    {
        $controller = new SettingsController('settings', LoggingLibrary::getInstance());
        $method = new \ReflectionMethod($controller, '_validationAttributesForSection');

        $expected = [
            'general' => [
                'pluginName',
                'showCpSection',
            ],
            'files' => ['forceEnableLogViewer'],
            'runtime' => array_keys(Settings::RUNTIME_FIELDS),
            'interface' => [
                'itemsPerPage',
                'timeFormat',
                'showSeconds',
            ],
        ];

        foreach ($expected as $section => $attributes) {
            self::assertSame($attributes, $method->invoke($controller, $section), "Unexpected {$section} settings scope.");
        }
    }
}
