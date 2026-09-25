<?php
/**
 * @link https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Integration;

use Craft;
use lindemannrock\base\helpers\SettingsPostHelper;
use lindemannrock\logginglibrary\helpers\RuntimeCategoryOptionsHelper;
use lindemannrock\logginglibrary\log\targets\RuntimeLogTarget;
use lindemannrock\logginglibrary\LoggingLibrary;
use lindemannrock\logginglibrary\models\Settings;
use lindemannrock\logginglibrary\tests\Support\RuntimePreferencesConfig;
use lindemannrock\logginglibrary\tests\TestCase;
use yii\log\Logger;
use yii\log\Target;

/**
 * Named source choices retain the raw category capture contract.
 *
 * @since 5.19.0
 */
final class RuntimeCaptureFilterTest extends TestCase
{
    public function testNamedSourcesCompileToPatternsAndRoundTripWithoutChangingTheirMeaning(): void
    {
        $picker = RuntimeCategoryOptionsHelper::capturePicker([]);
        $byLabel = array_column($picker['options'], 'value', 'label');
        self::assertSame(['yii\\db\\Connection::open'], RuntimeCategoryOptionsHelper::capturePatterns([$byLabel['DB Connection']]));
        self::assertContains('nystudio107\\pluginvite\\*', RuntimeCategoryOptionsHelper::capturePatterns([$byLabel['Vite']]));
        self::assertSame(['nystudio107\\codeeditor\\*'], RuntimeCategoryOptionsHelper::capturePatterns([$byLabel['Code Editor']]));
        $selected = [$byLabel['Vite'], $byLabel['DB Connection'], $byLabel['Code Editor'], 'p_' . bin2hex('custom:*')];
        $patterns = RuntimeCategoryOptionsHelper::capturePatterns($selected);
        self::assertContains('nystudio107\\pluginvite\\*', $patterns);
        self::assertContains('custom:*', $patterns);
        self::assertNotContains('Vite', $patterns);
        $roundTrip = RuntimeCategoryOptionsHelper::capturePicker($patterns);
        self::assertEqualsCanonicalizing($selected, $roundTrip['values']);
        self::assertEqualsCanonicalizing($patterns, RuntimeCategoryOptionsHelper::capturePatterns($roundTrip['values']));
    }

    public function testViewerAndPickerUseTheSameSourceNames(): void
    {
        $patterns = ['yii\\db\\Connection::open', 'yii\\db\\Command::query', 'nystudio107\\codeeditor\\*', 'craft\\queue\\*'];
        $viewer = RuntimeCategoryOptionsHelper::groupedOptions(array_fill_keys(array_map(static fn(string $pattern): string => str_replace('*', 'Job::execute', $pattern), $patterns), 1));
        $picker = RuntimeCategoryOptionsHelper::capturePicker($patterns);
        $selectedLabels = array_column(array_filter($picker['options'], static fn(array $option): bool => in_array($option['value'], $picker['values'], true)), 'label');
        self::assertEqualsCanonicalizing(array_slice(array_column($viewer['options'], 'label'), 1), $selectedLabels);
    }

    public function testCustomPatternsAndPreviouslyTypedDisplayNamesAreNotReinterpreted(): void
    {
        $patterns = ['Vite', 'DB Connection', 'My\\Custom\\*', 'craft\\web\\UrlManager::*'];
        $picker = RuntimeCategoryOptionsHelper::capturePicker($patterns);
        self::assertSame($patterns, RuntimeCategoryOptionsHelper::capturePatterns($picker['values']), 'A partial source selection must not expand to its other categories.');
        $labels = array_column($picker['options'], 'label', 'value');
        self::assertSame('Category: Vite', $labels['p_' . bin2hex('Vite')]);
        self::assertSame('Category: craft\\web\\UrlManager::*', $labels['p_' . bin2hex('craft\\web\\UrlManager::*')]);
        self::assertSame($patterns, RuntimeCategoryOptionsHelper::capturePatterns($picker['values']));
    }

    public function testMixedSourceAndCustomFiltersApplyExclusionsUsingTheExistingYiiContract(): void
    {
        $options = array_column(RuntimeCategoryOptionsHelper::capturePicker([])['options'], 'value', 'label');
        $categories = RuntimeCategoryOptionsHelper::capturePatterns([$options['Code Editor'], 'p_' . bin2hex('custom:*')]);
        $except = RuntimeCategoryOptionsHelper::capturePatterns(['p_' . bin2hex('custom:private*')]);
        $messages = array_map(static fn(string $category): array => ['test', Logger::LEVEL_INFO, $category, microtime(true)], [
            'nystudio107\\codeeditor\\CodeEditor::init', 'custom:public', 'custom:private:secret', 'Custom:public', 'unrelated',
        ]);
        self::assertSame(array_slice($messages, 0, 2), array_values(Target::filterMessages($messages, 0, $categories, $except)));
        self::assertSame([], Target::filterMessages($messages, 0, $categories, $categories));
        self::assertCount(5, $messages, 'Capture filtering must not mutate existing records.');
    }

    public function testPostDecodingSupportsClearingAndRejectsMalformedInput(): void
    {
        self::assertSame([], RuntimeCategoryOptionsHelper::capturePatterns(''));
        $validInput = ['p_' . bin2hex("first\nsecond:*"), 'p_' . bin2hex('first')];
        self::assertSame(['first', 'second:*'], RuntimeCategoryOptionsHelper::capturePatterns($validInput));
        foreach (['', $validInput, [['nested']], 42, ['raw:*'], ['p_1'], ['p_zz']] as $input) {
            $settings = new Settings(['runtimeExcludeCategories' => ['previous']]);
            $result = SettingsPostHelper::apply($settings, ['runtimeExcludeCategories' => $input], ['runtimeExcludeCategories'], adapters: [
                'runtimeExcludeCategories' => RuntimeCategoryOptionsHelper::capturePatterns(...),
            ]);
            $valid = !$result->hasErrors && $settings->validate(['runtimeExcludeCategories']);
            if ($input === '' || $input === $validInput) {
                self::assertTrue($valid);
                self::assertSame($input === '' ? [] : ['first', 'second:*'], $settings->runtimeExcludeCategories);
            } else {
                self::assertFalse($valid);
            }
        }
    }

    public function testEveryPickerValueIsSelectorSafeIncludingCustomUnicodeAndQuotes(): void
    {
        $patterns = ['Custom\\Namespace::*', 'café:日本語:*', 'quote:"single\'[*]', 'p_636174'];
        $picker = RuntimeCategoryOptionsHelper::capturePicker($patterns);
        foreach ($picker['options'] as $option) {
            self::assertMatchesRegularExpression('/\Ap_[0-9a-f]+\z/', $option['value']);
        }
        self::assertSame($patterns, RuntimeCategoryOptionsHelper::capturePatterns($picker['values']));
    }

    public function testOnlyExplicitIncludeExcludeConfigKeysControlCapture(): void
    {
        $config = Craft::$app->getConfig();
        try {
            $settings = new Settings(['runtimeIncludeCategories' => ['stored:*'], 'runtimeExcludeCategories' => ['stored:private*']]);
            Craft::$app->set('config', new RuntimePreferencesConfig(['runtimeLogStore' => [
                'categories' => ['old:*'], 'except' => ['old:private*'],
            ]]));
            self::assertFalse($settings->isOverriddenByConfig('runtimeIncludeCategories'));
            self::assertFalse($settings->isOverriddenByConfig('runtimeExcludeCategories'));
            self::assertSame(['stored:*'], $settings->getRuntimeConfig()['includeCategories']);
            self::assertSame(['stored:private*'], $settings->getRuntimeConfig()['excludeCategories']);
            Craft::$app->set('config', new RuntimePreferencesConfig(['runtimeLogStore' => [
                'includeCategories' => [], 'excludeCategories' => [],
            ]]));
            self::assertTrue($settings->isOverriddenByConfig('runtimeIncludeCategories'));
            self::assertTrue($settings->isOverriddenByConfig('runtimeExcludeCategories'));
            self::assertSame([], $settings->getRuntimeConfig()['includeCategories']);
            self::assertSame([], $settings->getRuntimeConfig()['excludeCategories']);
        } finally {
            Craft::$app->set('config', $config);
        }
    }

    public function testNewConfigNamesReachTheNativeYiiTargetWithoutChangingNoiseExclusions(): void
    {
        $config = Craft::$app->getConfig();
        $dispatcher = Craft::getLogger()->dispatcher;
        $targets = $dispatcher->targets;
        try {
            $dispatcher->targets = [];
            Craft::$app->set('config', new RuntimePreferencesConfig(['runtimeLogStore' => [
                'enabled' => true,
                'includeCategories' => ['named:*'],
                'excludeCategories' => ['named:private*'],
            ]]));
            (new \ReflectionMethod(LoggingLibrary::class, '_registerRuntimeLogTarget'))->invoke(null);
            $registered = Craft::getLogger()->dispatcher->targets;
            self::assertCount(1, $registered);
            $target = $registered[0];
            self::assertInstanceOf(RuntimeLogTarget::class, $target);
            self::assertSame(['named:*'], $target->categories);
            self::assertSame(['yii\\i18n\\PhpMessageSource:*', 'named:private*'], $target->except);
        } finally {
            $dispatcher->targets = $targets;
            Craft::$app->set('config', $config);
        }
    }

    public function testConfigLocksAndTranslatedLabelsDoNotChangeStoredPatterns(): void
    {
        $config = Craft::$app->getConfig();
        $language = Craft::$app->language;
        try {
            Craft::$app->set('config', new RuntimePreferencesConfig(['runtimeLogStore' => ['excludeCategories' => ['configured:*']]]));
            $settings = new Settings(['runtimeExcludeCategories' => ['stored:*']]);
            SettingsPostHelper::apply($settings, ['runtimeExcludeCategories' => ''], ['runtimeExcludeCategories'],
                shouldSkipAttribute: $settings->isOverriddenByConfig(...),
                adapters: ['runtimeExcludeCategories' => RuntimeCategoryOptionsHelper::capturePatterns(...)],
            );
            self::assertSame(['stored:*'], $settings->runtimeExcludeCategories);
            self::assertSame(['configured:*'], $settings->getRuntimeConfig()['excludeCategories']);
            Craft::$app->language = 'de';
            $picker = RuntimeCategoryOptionsHelper::capturePicker(['yii\\db\\Connection::open', 'custom:*']);
            self::assertSame(['yii\\db\\Connection::open', 'custom:*'], RuntimeCategoryOptionsHelper::capturePatterns($picker['values']));
        } finally {
            Craft::$app->language = $language;
            Craft::$app->set('config', $config);
        }
    }
}
