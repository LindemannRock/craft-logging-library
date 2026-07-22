<?php
/**
 * Logging Library for Craft CMS
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\helpers;

use Craft;
use lindemannrock\base\helpers\PluginHelper;
use yii\helpers\Inflector;

/**
 * Builds readable grouped filter options for runtime log categories.
 *
 * @since 5.15.0
 */
class RuntimeCategoryOptionsHelper
{
    /**
     * Build grouped runtime category options from raw category counts.
     *
     * @param array<string, int> $categoryCounts
     * @return array{options: array, rawCategoriesByValue: array<string, array<int, string>>, labelsByValue: array<string, string>, valuesByRawCategory: array<string, string>, labelsByRawCategory: array<string, string>}
     */
    public static function groupedOptions(array $categoryCounts): array
    {
        $groups = [];
        $valuesByRawCategory = [];
        $labelsByRawCategory = [];
        $pluginMetadata = self::pluginMetadata();

        foreach ($categoryCounts as $category => $count) {
            $group = self::groupForCategory((string)$category, $pluginMetadata);
            $value = $group['value'];

            if (!isset($groups[$value])) {
                $groups[$value] = [
                    'value' => $value,
                    'label' => $group['label'],
                    'count' => 0,
                    'rawCategories' => [],
                ];
            }

            $groups[$value]['count'] += $count;
            $groups[$value]['rawCategories'][] = (string)$category;
            $valuesByRawCategory[(string)$category] = $value;
            $labelsByRawCategory[(string)$category] = $group['recordLabel'];
        }

        uasort($groups, static function(array $a, array $b): int {
            return strcasecmp((string)$a['label'], (string)$b['label'])
                ?: strcasecmp((string)$a['value'], (string)$b['value']);
        });

        $formatter = Craft::$app->getFormatter();
        $options = [[
            'value' => 'all',
            'label' => Craft::t('logging-library', 'Source'),
            'extra' => '(' . $formatter->asInteger(array_sum($categoryCounts)) . ')',
        ]];
        $rawCategoriesByValue = [];
        $labelsByValue = [
            'all' => Craft::t('logging-library', 'Source'),
        ];

        foreach ($groups as $group) {
            sort($group['rawCategories'], SORT_STRING | SORT_FLAG_CASE);

            $options[] = [
                'value' => $group['value'],
                'label' => $group['label'],
                'extra' => '(' . $formatter->asInteger((int)$group['count']) . ')',
            ];

            $rawCategoriesByValue[$group['value']] = $group['rawCategories'];
            $labelsByValue[$group['value']] = $group['label'];
        }

        return [
            'options' => $options,
            'rawCategoriesByValue' => $rawCategoriesByValue,
            'labelsByValue' => $labelsByValue,
            'valuesByRawCategory' => $valuesByRawCategory,
            'labelsByRawCategory' => $labelsByRawCategory,
        ];
    }

    /**
     * Resolve a selected category value or legacy raw category to a group value.
     */
    public static function resolveSelectedValue(string $category, array $groupedOptions): string
    {
        if ($category === 'all') {
            return 'all';
        }

        if (isset($groupedOptions['rawCategoriesByValue'][$category])) {
            return $category;
        }

        return (string)($groupedOptions['valuesByRawCategory'][$category] ?? 'all');
    }

    /**
     * Add readable category labels to records without changing the raw category.
     */
    public static function withRecordLabels(array $records, array $groupedOptions): array
    {
        foreach ($records as &$record) {
            $category = (string)($record['category'] ?? '');
            $record['categoryLabel'] = $groupedOptions['labelsByRawCategory'][$category]
                ?? self::shortCategoryLabel($category);
        }
        unset($record);

        return $records;
    }

    /**
     * @param array<int, array{handle: string, namespace: string, label: string}> $pluginMetadata
     * @return array{value: string, label: string, recordLabel: string}
     */
    private static function groupForCategory(string $category, array $pluginMetadata): array
    {
        $pluginGroup = self::pluginGroupForCategory($category, $pluginMetadata);
        if ($pluginGroup !== null) {
            return $pluginGroup;
        }

        $systemGroup = self::systemGroupForCategory($category);
        if ($systemGroup !== null) {
            return $systemGroup;
        }

        $label = self::shortCategoryLabel($category);

        return [
            'value' => $category,
            'label' => $label,
            'recordLabel' => $label,
        ];
    }

    /**
     * @return array<int, array{handle: string, namespace: string, label: string}>
     */
    private static function pluginMetadata(): array
    {
        try {
            $plugins = Craft::$app->getPlugins()->getAllPlugins();
        } catch (\Throwable) {
            return [];
        }

        $metadata = [];
        foreach ($plugins as $handle => $plugin) {
            $handle = (string)$handle;
            $metadata[] = [
                'handle' => $handle,
                'namespace' => self::namespaceOf(get_class($plugin)),
                'label' => self::pluginLabel($plugin, $handle),
            ];
        }

        return $metadata;
    }

    /**
     * @param array<int, array{handle: string, namespace: string, label: string}> $pluginMetadata
     * @return array{value: string, label: string, recordLabel: string}|null
     */
    private static function pluginGroupForCategory(string $category, array $pluginMetadata): ?array
    {
        foreach ($pluginMetadata as $plugin) {
            if ($category === $plugin['handle'] || str_starts_with($category, $plugin['handle'] . ':')) {
                return self::pluginGroup($plugin);
            }
        }

        $bestMatch = null;
        foreach ($pluginMetadata as $plugin) {
            $namespace = $plugin['namespace'];
            if ($namespace === '' || !str_starts_with($category, $namespace . '\\')) {
                continue;
            }

            if ($bestMatch === null || strlen($namespace) > strlen($bestMatch['namespace'])) {
                $bestMatch = $plugin;
            }
        }

        return $bestMatch === null ? null : self::pluginGroup($bestMatch);
    }

    /**
     * @param array{handle: string, namespace: string, label: string} $plugin
     * @return array{value: string, label: string, recordLabel: string}
     */
    private static function pluginGroup(array $plugin): array
    {
        return [
            'value' => 'plugin:' . $plugin['handle'],
            'label' => $plugin['label'],
            'recordLabel' => $plugin['label'],
        ];
    }

    /**
     * @return array{value: string, label: string, recordLabel: string}|null
     */
    private static function systemGroupForCategory(string $category): ?array
    {
        if (str_starts_with($category, 'yii\db\Command::')) {
            $method = self::methodOf($category);
            $label = match ($method) {
                'query' => 'DB Queries',
                'execute' => 'DB Commands',
                default => 'DB Command::' . $method,
            };

            return [
                'value' => 'system:db-command:' . $method,
                'label' => $label,
                'recordLabel' => $label,
            ];
        }

        if (str_starts_with($category, 'yii\db\Connection::')) {
            $method = self::methodOf($category);
            $label = match ($method) {
                'open' => 'DB Connection',
                default => 'DB Connection::' . $method,
            };

            return [
                'value' => 'system:db-connection:' . $method,
                'label' => $label,
                'recordLabel' => $label,
            ];
        }

        if (str_starts_with($category, 'yii\redis\Connection::')) {
            $method = self::methodOf($category);
            $label = match ($method) {
                'executeCommand' => 'Redis Commands',
                'open' => 'Redis Connection',
                default => 'Redis Connection::' . $method,
            };

            return [
                'value' => 'system:redis-connection:' . $method,
                'label' => $label,
                'recordLabel' => $label,
            ];
        }

        if (str_starts_with($category, 'craft\web\UrlManager::')
            || str_starts_with($category, 'yii\web\UrlRule::')
        ) {
            return [
                'value' => 'system:url-routing',
                'label' => 'URL Routing',
                'recordLabel' => 'URL Routing',
            ];
        }

        if (str_starts_with($category, 'craft\web\Application::')
            || str_starts_with($category, 'yii\web\Application::')
            || str_starts_with($category, 'yii\base\Application::')
            || str_starts_with($category, 'yii\base\Controller::')
            || str_starts_with($category, 'yii\base\InlineAction::')
        ) {
            return [
                'value' => 'system:web-request',
                'label' => 'Web Request',
                'recordLabel' => 'Web Request',
            ];
        }

        if (str_starts_with($category, 'craft\queue\\')) {
            return [
                'value' => 'system:queue',
                'label' => Craft::t('logging-library', 'Queue'),
                'recordLabel' => Craft::t('logging-library', 'Queue'),
            ];
        }

        if (str_starts_with($category, 'yii\web\Session::')) {
            return [
                'value' => 'system:session',
                'label' => 'Session',
                'recordLabel' => 'Session',
            ];
        }

        if (str_starts_with($category, 'craft\web\View::')) {
            return [
                'value' => 'system:view-rendering',
                'label' => 'Template Rendering',
                'recordLabel' => 'Template Rendering',
            ];
        }

        if (str_starts_with($category, 'yii\base\Module::')) {
            return [
                'value' => 'system:modules',
                'label' => 'Modules',
                'recordLabel' => 'Modules',
            ];
        }

        if (str_starts_with($category, 'nystudio107\pluginvite\\')) {
            return [
                'value' => 'plugin:vite',
                'label' => 'Vite',
                'recordLabel' => 'Vite',
            ];
        }

        if (str_starts_with($category, 'nystudio107\codeeditor\\')) {
            return [
                'value' => 'package:codeeditor',
                'label' => 'Code Editor',
                'recordLabel' => 'Code Editor',
            ];
        }

        if ($category === 'integration-service') {
            return [
                'value' => 'integration-service',
                'label' => 'Integration Service',
                'recordLabel' => 'Integration Service',
            ];
        }

        return null;
    }

    private static function pluginLabel(object $plugin, string $handle): string
    {
        try {
            $settings = method_exists($plugin, 'getSettings') ? $plugin->getSettings() : null;
            if (is_object($settings) && method_exists($settings, 'getFullName')) {
                $fullName = trim((string)$settings->getFullName());
                if ($fullName !== '') {
                    return $fullName;
                }
            }
        } catch (\Throwable) {
        }

        try {
            $pluginName = trim(PluginHelper::getPluginName($handle, ''));
            if ($pluginName !== '') {
                return $pluginName;
            }
        } catch (\Throwable) {
        }

        $name = trim((string)($plugin->name ?? ''));
        if ($name !== '') {
            return $name;
        }

        return Inflector::titleize(str_replace('-', ' ', $handle));
    }

    private static function shortCategoryLabel(string $category): string
    {
        if ($category === '') {
            return Craft::t('logging-library', 'Unknown');
        }

        if (!str_contains($category, '\\')) {
            return $category;
        }

        $segments = explode('\\', $category);
        $last = array_pop($segments);

        if ($last === '{closure}' && $segments !== []) {
            $last = array_pop($segments) . '\\' . $last;
        }

        return $last !== '' ? $last : $category;
    }

    private static function methodOf(string $category): string
    {
        $position = strrpos($category, '::');

        return $position === false ? $category : substr($category, $position + 2);
    }

    private static function namespaceOf(string $class): string
    {
        $position = strrpos($class, '\\');

        return $position === false ? '' : substr($class, 0, $position);
    }
}
