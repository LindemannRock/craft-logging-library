<?php
/**
 * LindemannRock Logging Library
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Integration;

use lindemannrock\logginglibrary\models\Settings;
use lindemannrock\logginglibrary\tests\TestCase;

/**
 * Coverage test for `Settings::attributeLabels()` — ensures every attribute
 * referenced in `rules()` has a matching label entry, so non-English CP users
 * never see Yii's auto-generated English fallback names in validation errors.
 *
 * Regression test for audit 3.3.
 *
 * @since 5.9.0
 */
final class SettingsAttributeLabelsTest extends TestCase
{
    public function testEveryValidatedAttributeHasALabel(): void
    {
        $settings = new Settings();
        $labels = $settings->attributeLabels();

        $validated = [];
        foreach ($settings->rules() as $rule) {
            $validated = array_merge($validated, (array) $rule[0]);
        }

        foreach (array_unique($validated) as $attr) {
            self::assertArrayHasKey(
                $attr,
                $labels,
                "Attribute '$attr' is in rules() but missing from attributeLabels() — non-English CP users will see Yii's auto-generated English fallback in validation errors",
            );
        }
    }
}
