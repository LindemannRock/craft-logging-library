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
use lindemannrock\logginglibrary\migrations\Install;
use lindemannrock\logginglibrary\migrations\m260925_000000_add_runtime_log_settings;
use lindemannrock\logginglibrary\migrations\m260925_000001_rename_runtime_category_filters;
use lindemannrock\logginglibrary\models\Settings;
use lindemannrock\logginglibrary\services\RuntimeLogStoreService;
use lindemannrock\logginglibrary\tests\Support\RuntimePreferencesConfig;
use lindemannrock\logginglibrary\tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use yii\caching\ArrayCache;

/**
 * CP runtime preferences, nested overrides, and additive upgrade behavior.
 *
 * @since 5.19.0
 */
final class RuntimeSettingsTest extends TestCase
{
    public function testConfiguredStorageSummaryDoesNotReadOrWriteLogRecords(): void
    {
        $original = Craft::$app->getCache();
        $cache = $this->createMock(ArrayCache::class);
        foreach (['get', 'set', 'delete', 'flush'] as $method) {
            $cache->expects(self::never())->method($method);
        }
        try {
            Craft::$app->set('cache', $cache);
            $status = (new RuntimeLogStoreService())->getStorageStatus((new Settings())->getStoredRuntimeConfig());
            self::assertSame('generic-cache', $status['backend']);
            self::assertTrue($status['available']);
        } finally {
            Craft::$app->set('cache', $original);
        }
    }

    public function testPartialNestedConfigurationOverridesOnlySpecifiedPreferences(): void
    {
        $this->withConfig(['runtimeLogStore' => [
            'maxEntries' => 8000,
            'levels' => ['debug', 'error'],
            'redis' => ['database' => null],
            'privacy' => ['includeUserId' => false],
        ]], function(): void {
            $settings = new Settings([
                'runtimeEnabled' => true,
                'runtimeTtl' => 1234,
                'runtimeLevels' => ['info'],
                'runtimeIncludeUserId' => true,
            ]);
            $config = $settings->getRuntimeConfig();
            self::assertTrue($config['enabled']);
            self::assertSame(1234, $config['ttl']);
            self::assertSame(8000, $config['maxEntries']);
            self::assertSame(['trace', 'error'], $config['levels']);
            self::assertSame(['database' => null], $config['redis']);
            self::assertFalse($config['privacy']['includeUserId']);
            self::assertTrue($settings->isOverriddenByConfig('runtimeIncludeUserId'));
            self::assertFalse($settings->isOverriddenByConfig('runtimeEnabled'));
            self::assertTrue($settings->runtimeIncludeUserId, 'Effective reads must not import config into persisted preferences.');
        });
    }

    public function testDefaultCaptureAndSafeguardsAreUnchanged(): void
    {
        $this->withConfig([], function(): void {
            $config = (new Settings())->getRuntimeConfig();
            self::assertFalse($config['enabled']);
            self::assertTrue($config['skipConsoleRequests']);
            self::assertTrue($config['skipQueueRequests']);
            self::assertFalse($config['privacy']['includeUserId']);
            self::assertSame([], $config['redis'], 'Omission must keep inherited Redis database semantics.');
            self::assertSame(['error', 'warning', 'info'], $config['levels']);
        });
    }

    #[DataProvider('invalidPreferenceProvider')]
    public function testControlPanelRejectsOutOfRangePreferences(string $attribute, mixed $value): void
    {
        $settings = new Settings();
        $result = SettingsPostHelper::apply($settings, [$attribute => $value], [$attribute]);
        self::assertFalse(!$result->hasErrors && $settings->validate([$attribute]));
    }

    public static function invalidPreferenceProvider(): iterable
    {
        yield 'zero retention' => ['runtimeTtl', 0];
        yield 'retention over thirty days' => ['runtimeTtl', 2592001];
        yield 'too many records' => ['runtimeMaxEntries', 10001];
        yield 'negative refresh' => ['runtimeRefreshInterval', -1];
        yield 'refresh over one hour' => ['runtimeRefreshInterval', 3601];
        yield 'oversize message' => ['runtimeMaxMessageBytes', 65537];
        yield 'empty levels' => ['runtimeLevels', []];
        yield 'unsupported level' => ['runtimeLevels', ['notice']];
        yield 'malformed boolean' => ['runtimeEnabled', 'maybe'];
        yield 'fractional retention' => ['runtimeTtl', '1.5'];
        yield 'non-string category' => ['runtimeIncludeCategories', [[]]];
    }

    public function testRetentionLimitAppliesToControlPanelWithoutClampingConfiguration(): void
    {
        $settings = new Settings();
        self::assertSame(86400, $settings->runtimeTtl);
        $settings->runtimeTtl = 2592000;
        self::assertTrue($settings->validate(['runtimeTtl']));

        $this->withConfig(['runtimeLogStore' => ['ttl' => 31536000]], function() use ($settings): void {
            self::assertSame(31536000, $settings->getRuntimeConfig()['ttl']);
            self::assertTrue($settings->isOverriddenByConfig('runtimeTtl'));
            self::assertSame(2592000, $settings->runtimeTtl);
        });
    }

    public function testRefreshLimitAcceptsDisabledAndOneHourWithoutClampingConfiguration(): void
    {
        $settings = new Settings();
        self::assertSame(5, $settings->runtimeRefreshInterval);
        foreach ([0, 5, 3600] as $seconds) {
            $settings->runtimeRefreshInterval = $seconds;
            self::assertTrue($settings->validate(['runtimeRefreshInterval']));
        }
        $this->withConfig(['runtimeLogStore' => ['refreshInterval' => 7200]], function() use ($settings): void {
            self::assertSame(7200, $settings->getRuntimeConfig()['refreshInterval']);
            self::assertTrue($settings->isOverriddenByConfig('runtimeRefreshInterval'));
            self::assertSame(3600, $settings->runtimeRefreshInterval);
        });
    }

    public function testFreshInstallAndUpgradePreservePreferencesAndConfigLocks(): void
    {
        $originalDb = Craft::$app->getDb();
        $db = new Connection([
            'dsn' => $originalDb->dsn,
            'username' => $originalDb->username,
            'password' => $originalDb->password,
            'tablePrefix' => 'll_set_' . bin2hex(random_bytes(5)) . '_',
        ]);
        $table = '{{%logginglibrary_settings}}';
        try {
            Craft::$app->set('db', $db);
            $install = new Install(['db' => $db, 'compact' => true]);
            ob_start();
            try {
                self::assertTrue($install->safeUp());
            } finally {
                ob_end_clean();
            }
            $fresh = Settings::loadFromDatabase();
            self::assertSame((new Settings())->getStoredRuntimeConfig(), $fresh->getStoredRuntimeConfig());
            self::assertFalse($db->columnExists($table, 'runtimeCategories'));
            self::assertFalse($db->columnExists($table, 'runtimeExcept'));
            $rename = new m260925_000001_rename_runtime_category_filters(['db' => $db, 'compact' => true]);
            self::assertTrue($rename->safeUp(), 'Fresh installs already have the final names.');

            // Reconstruct the supported previous schema on our exact owned table.
            foreach (array_keys(Settings::RUNTIME_FIELDS) as $column) {
                $db->createCommand()->dropColumn($table, $column)->execute();
            }
            $db->createCommand()->update($table, ['pluginName' => 'Existing name', 'showCpSection' => false], ['id' => 1])->execute();
            $before = $db->createCommand('SELECT * FROM ' . $table)->queryOne();
            $migration = new m260925_000000_add_runtime_log_settings(['db' => $db, 'compact' => true]);
            ob_start();
            try {
                self::assertTrue($migration->safeUp());
                self::assertTrue($migration->safeUp(), 'Reentry must preserve existing preference values.');
            } finally {
                ob_end_clean();
            }
            $after = $db->createCommand('SELECT * FROM ' . $table)->queryOne();
            self::assertSame($before, array_intersect_key($after, $before));
            $db->createCommand()->update($table, [
                'runtimeCategories' => json_encode(['yii\\db\\*', 'custom:*']),
                'runtimeExcept' => json_encode(['yii\\db\\Connection::open']),
            ], ['id' => 1])->execute();
            $beforeRename = $db->createCommand('SELECT * FROM ' . $table)->queryOne();
            ob_start();
            try {
                self::assertTrue($rename->safeUp());
                self::assertTrue($rename->safeUp(), 'Reentry does not alter filter data.');
                $renamed = $db->createCommand('SELECT * FROM ' . $table)->queryOne();
                $expected = $beforeRename;
                $expected['runtimeIncludeCategories'] = $expected['runtimeCategories'];
                $expected['runtimeExcludeCategories'] = $expected['runtimeExcept'];
                unset($expected['runtimeCategories'], $expected['runtimeExcept']);
                self::assertEquals($expected, $renamed, 'Every value must survive the column rename.');
                self::assertTrue($rename->safeDown());
                self::assertSame($beforeRename, $db->createCommand('SELECT * FROM ' . $table)->queryOne());
                self::assertTrue($rename->safeUp());
            } finally {
                ob_end_clean();
            }
            $db->getSchema()->refresh();
            $this->withConfig(['runtimeLogStore' => ['maxEntries' => 8000]], function() use ($db, $table): void {
                $settings = Settings::loadFromDatabase();
                $settings->runtimeEnabled = true;
                $settings->runtimeMaxEntries = 77;
                $settings->runtimeIncludeCategories = ['my-plugin', 'yii\\db\\*'];
                self::assertTrue($settings->saveToDatabase(array_keys(Settings::RUNTIME_FIELDS)));
                $saved = Settings::loadFromDatabase();
                self::assertTrue($saved->runtimeEnabled);
                self::assertSame(1000, $saved->runtimeMaxEntries);
                self::assertSame(8000, $saved->getRuntimeConfig()['maxEntries']);
                self::assertSame(['my-plugin', 'yii\\db\\*'], $saved->runtimeIncludeCategories);
                self::assertSame('Existing name', $saved->pluginName);
                self::assertFalse($saved->showCpSection);
                self::assertSame(1, (int)$db->createCommand('SELECT COUNT(*) FROM ' . $table)->queryScalar());

                // The CP posts encoded choices; partial removal and clearing must
                // replace both lists, persist raw patterns, and survive a reload.
                $attributes = ['runtimeIncludeCategories', 'runtimeExcludeCategories'];
                foreach ([['yii\\db\\Connection::open', 'custom:*'], ['custom:*'], []] as $patterns) {
                    $values = RuntimeCategoryOptionsHelper::capturePicker($patterns)['values'];
                    $posted = array_fill_keys($attributes, $values === [] ? '' : $values);
                    $model = Settings::loadFromDatabase();
                    $result = SettingsPostHelper::apply($model, $posted, $attributes, adapters: array_fill_keys($attributes, RuntimeCategoryOptionsHelper::capturePatterns(...)));
                    self::assertFalse($result->hasErrors);
                    self::assertTrue($model->saveToDatabase($result->attributesToValidate));
                    $reloaded = Settings::loadFromDatabase();
                    self::assertSame($patterns, $reloaded->runtimeIncludeCategories);
                    self::assertSame($patterns, $reloaded->runtimeExcludeCategories);
                    self::assertSame('Existing name', $reloaded->pluginName);
                }
            });
        } finally {
            Craft::$app->set('db', $originalDb);
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

    private function withConfig(array $values, callable $callback): void
    {
        $original = Craft::$app->getConfig();
        try {
            Craft::$app->set('config', new RuntimePreferencesConfig($values));
            $callback();
        } finally {
            Craft::$app->set('config', $original);
        }
    }
}
