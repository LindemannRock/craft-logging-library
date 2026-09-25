<?php
/**
 * @link https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Integration;

use Craft;
use craft\db\Connection;
use lindemannrock\base\helpers\SettingsPostHelper;
use lindemannrock\logginglibrary\helpers\RuntimeCategoryOptionsHelper;
use lindemannrock\logginglibrary\log\targets\RuntimeLogTarget;
use lindemannrock\logginglibrary\LoggingLibrary;
use lindemannrock\logginglibrary\migrations\Install;
use lindemannrock\logginglibrary\models\Settings;
use lindemannrock\logginglibrary\tests\Support\RuntimePreferencesConfig;
use lindemannrock\logginglibrary\tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use yii\log\Logger;
use yii\log\Target;

/**
 * Named source choices retain the raw category capture contract.
 *
 * @since 5.19.0
 */
final class RuntimeCaptureFilterTest extends TestCase
{
    #[DataProvider('unicodeFilterProvider')]
    public function testUnicodeSelectionsPersistFilterAndRespectRemovalAndLocks(string $attribute, string $pattern): void
    {
        $originalDb = Craft::$app->getDb();
        $originalConfig = Craft::$app->getConfig();
        $dispatcher = Craft::getLogger()->dispatcher;
        $originalTargets = $dispatcher->targets;
        $db = new Connection([
            'dsn' => $originalDb->dsn,
            'username' => $originalDb->username,
            'password' => $originalDb->password,
            'tablePrefix' => 'll_filter_' . bin2hex(random_bytes(6)) . '_',
        ]);
        $table = '{{%logginglibrary_settings}}';
        $path = Settings::RUNTIME_FIELDS[$attribute];
        $otherAttribute = $attribute === 'runtimeIncludeCategories' ? 'runtimeExcludeCategories' : 'runtimeIncludeCategories';
        $otherPatterns = $attribute === 'runtimeIncludeCategories' ? ['other:*'] : ['*'];
        try {
            $dispatcher->targets = [];
            Craft::$app->set('db', $db);
            Craft::$app->set('config', new RuntimePreferencesConfig([]));
            ob_start();
            try {
                self::assertTrue((new Install(['db' => $db, 'compact' => true]))->safeUp());
            } finally {
                ob_end_clean();
            }
            $initial = Settings::loadFromDatabase();
            $initial->$otherAttribute = $otherPatterns;
            self::assertTrue($initial->saveToDatabase([$otherAttribute]));

            $options = array_column(RuntimeCategoryOptionsHelper::capturePicker([])['options'], 'value', 'label');
            $named = ['craft\\web\\UrlManager::*', 'yii\\web\\UrlRule::*'];
            $custom = 'Custom\\Namespace::"quote"[*]';
            $full = [...$named, $pattern, $custom];
            $messages = array_map(static fn(string $category): array => ['test', Logger::LEVEL_INFO, $category, microtime(true)], [
                'craft\\web\\UrlManager::parseRequest', 'yii\\web\\UrlRule::parseRequest',
                str_replace('*', 'event', $pattern), $custom, 'unrelated',
            ]);
            $stages = [
                [[$options['URL Routing'], 'p_' . bin2hex($pattern), 'p_' . bin2hex($custom)], $full, [0, 1, 2, 3]],
                [['p_' . bin2hex($pattern), 'p_' . bin2hex($custom)], [$pattern, $custom], [2, 3]],
                [['p_' . bin2hex($custom)], [$custom], [3]],
                ['', [], []],
            ];
            foreach ($stages as [$posted, $expected, $matching]) {
                $settings = Settings::loadFromDatabase();
                $result = SettingsPostHelper::apply($settings, [$attribute => $posted], [$attribute],
                    shouldSkipAttribute: $settings->isOverriddenByConfig(...),
                    adapters: [$attribute => RuntimeCategoryOptionsHelper::capturePatterns(...)],
                );
                self::assertFalse($result->hasErrors);
                self::assertTrue($settings->saveToDatabase($result->attributesToValidate));
                $saved = Settings::loadFromDatabase();
                self::assertSame($expected, $saved->$attribute);
                self::assertSame($otherPatterns, $saved->$otherAttribute, 'A scoped save must preserve the other filter.');
                self::assertSame($expected, json_decode($db->createCommand('SELECT [[' . $attribute . ']] FROM ' . $table)->queryScalar(), true, 512, JSON_THROW_ON_ERROR));
                $picker = RuntimeCategoryOptionsHelper::capturePicker($saved->$attribute);
                self::assertSame($expected, RuntimeCategoryOptionsHelper::capturePatterns($picker['values']));
                $effective = $saved->getRuntimeConfig();
                self::assertSame($expected, $effective[$path]);
                $accepted = $attribute === 'runtimeIncludeCategories'
                    ? ($expected === [] ? array_keys($messages) : $matching)
                    : array_values(array_diff(array_keys($messages), $matching));
                self::assertSame(array_intersect_key($messages, array_flip($accepted)), Target::filterMessages($messages, 0, $effective['includeCategories'], $effective['excludeCategories']));
            }

            $settings = Settings::loadFromDatabase();
            $settings->$attribute = $full;
            self::assertTrue($settings->saveToDatabase([$attribute]));
            foreach ([[['nested']], 42, ['raw:*'], ['p_1'], ['p_zz'], ['p_']] as $invalid) {
                $settings = Settings::loadFromDatabase();
                $result = SettingsPostHelper::apply($settings, [$attribute => $invalid], [$attribute], adapters: [$attribute => RuntimeCategoryOptionsHelper::capturePatterns(...)]);
                $valid = $settings->validate($result->attributesToValidate, false);
                self::assertFalse($valid && !$result->hasErrors);
                self::assertSame($full, Settings::loadFromDatabase()->$attribute, 'Rejected input must not replace saved filters.');
            }

            Craft::$app->set('config', new RuntimePreferencesConfig(['runtimeLogStore' => [$path => [$pattern]]]));
            foreach (['', ['p_' . bin2hex('replacement:*')], ['p_zz']] as $posted) {
                $settings = Settings::loadFromDatabase();
                $result = SettingsPostHelper::apply($settings, [$attribute => $posted], [$attribute],
                    shouldSkipAttribute: $settings->isOverriddenByConfig(...),
                    adapters: [$attribute => RuntimeCategoryOptionsHelper::capturePatterns(...)],
                );
                self::assertFalse($result->hasErrors);
                self::assertSame([], $result->attributesToValidate);
                self::assertTrue($settings->saveToDatabase($result->attributesToValidate));
                $saved = Settings::loadFromDatabase();
                self::assertSame($full, $saved->$attribute);
                self::assertSame([$pattern], $saved->getRuntimeConfig()[$path]);
            }
        } finally {
            Craft::$app->set('db', $originalDb);
            Craft::$app->set('config', $originalConfig);
            $dispatcher->targets = $originalTargets;
            try {
                if ($db->tableExists($table, false)) {
                    $db->createCommand()->dropTable($table)->execute();
                }
                self::assertFalse($db->tableExists($table, false));
            } finally {
                $db->close();
            }
        }
    }

    public static function unicodeFilterProvider(): iterable
    {
        foreach (['runtimeIncludeCategories', 'runtimeExcludeCategories'] as $attribute) {
            foreach (['Åudit:*', '作者:*'] as $pattern) {
                yield $attribute . ' ' . $pattern => [$attribute, $pattern];
            }
        }
    }

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
        $validInput = ['p_' . bin2hex(" Åudit:* \n\n作者:*\r\n"), 'p_' . bin2hex('Åudit:*')];
        self::assertSame(['Åudit:*', '作者:*'], RuntimeCategoryOptionsHelper::capturePatterns($validInput));
        foreach (['', $validInput, [['nested']], 42, ['raw:*'], ['p_1'], ['p_zz']] as $input) {
            $settings = new Settings(['runtimeExcludeCategories' => ['previous']]);
            $result = SettingsPostHelper::apply($settings, ['runtimeExcludeCategories' => $input], ['runtimeExcludeCategories'], adapters: [
                'runtimeExcludeCategories' => RuntimeCategoryOptionsHelper::capturePatterns(...),
            ]);
            $valid = !$result->hasErrors && $settings->validate(['runtimeExcludeCategories']);
            if ($input === '' || $input === $validInput) {
                self::assertTrue($valid);
                self::assertSame($input === '' ? [] : ['Åudit:*', '作者:*'], $settings->runtimeExcludeCategories);
            } else {
                self::assertFalse($valid);
            }
        }
    }

    public function testEveryPickerValueIsSelectorSafeIncludingCustomUnicodeAndQuotes(): void
    {
        $patterns = ['Custom\\Namespace::*', 'café:日本語:*', 'Åudit:*', '作者:*', 'quote:"single\'[*]', 'p_636174'];
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
