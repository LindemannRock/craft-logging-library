<?php
/**
 * Logging Library for Craft CMS
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\helpers;

use Craft;

/**
 * Builds shared category/source filter option arrays.
 *
 * @since 5.14.0
 */
class CategoryOptionsHelper
{
    /**
     * Build category filter options from category counts.
     *
     * @param array<string, int> $categoryCounts
     * @return array
     */
    public static function options(array $categoryCounts): array
    {
        $formatter = Craft::$app->getFormatter();

        $options = [[
            'value' => 'all',
            'label' => Craft::t('logging-library', 'Source'),
            'extra' => '(' . $formatter->asInteger(array_sum($categoryCounts)) . ')',
        ]];

        foreach ($categoryCounts as $category => $count) {
            $options[] = [
                'value' => $category,
                'label' => $category,
                'extra' => '(' . $formatter->asInteger($count) . ')',
            ];
        }

        return $options;
    }
}
