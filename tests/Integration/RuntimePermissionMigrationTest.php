<?php
/**
 * @link https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Integration;

use Craft;
use craft\db\Connection;
use craft\db\Table;
use craft\services\ProjectConfig;
use lindemannrock\logginglibrary\migrations\m260925_000002_split_runtime_log_permissions;
use lindemannrock\logginglibrary\tests\TestCase;

/**
 * Permission upgrades preserve direct and group grant ownership.
 *
 * @since 5.19.0
 */
final class RuntimePermissionMigrationTest extends TestCase
{
    public function testUpgradePreservesGrantSourcesAndIsIdempotent(): void
    {
        $originalDb = Craft::$app->getDb();
        $originalConfig = Craft::$app->getProjectConfig();
        $db = new Connection([
            'dsn' => $originalDb->dsn,
            'username' => $originalDb->username,
            'password' => $originalDb->password,
            'tablePrefix' => 'll_perm_' . bin2hex(random_bytes(5)) . '_',
        ]);
        $view = 'logginglibrary:viewalllogs';
        $clear = 'logginglibrary:clearcache';
        $runtimeView = 'logginglibrary:viewruntimelogs';
        $runtimeClear = 'logginglibrary:clearruntimelogs';
        $groups = [
            'viewers' => ['permissions' => [$view, 'accesscp']],
            'clearers' => ['permissions' => [$clear]],
            'both' => ['permissions' => [$view, $clear]],
            'already-upgraded' => ['permissions' => [$view, $runtimeView]],
            'unrelated' => ['permissions' => ['accesscp']],
        ];
        $config = $this->createMock(ProjectConfig::class);
        $config->method('get')->with('users.groups')->willReturnCallback(static function() use (&$groups): array {
            return $groups;
        });
        $config->expects(self::exactly(3))->method('set')->willReturnCallback(static function($path, $value) use (&$groups): bool {
            $uid = explode('.', $path)[2];
            $groups[$uid]['permissions'] = $value;
            return true;
        });
        try {
            $migration = new m260925_000002_split_runtime_log_permissions(['db' => $db, 'compact' => true]);
            $db->createCommand()->createTable(Table::USERPERMISSIONS, [
                'id' => 'pk', 'name' => 'string NOT NULL UNIQUE',
                'dateCreated' => 'datetime', 'dateUpdated' => 'datetime', 'uid' => 'string',
            ])->execute();
            $db->createCommand()->createTable(Table::USERPERMISSIONS_USERS, [
                'permissionId' => 'integer NOT NULL', 'userId' => 'integer NOT NULL',
                'dateCreated' => 'datetime', 'dateUpdated' => 'datetime', 'uid' => 'string',
                'PRIMARY KEY ([[permissionId]], [[userId]])',
            ])->execute();
            Craft::$app->set('projectConfig', $config);
            $seededGroups = $groups;
            $groups = [];
            self::assertTrue($migration->safeUp(), 'Fresh installations with no grants need no permission assignments.');
            self::assertSame([], $this->grants($db));
            self::assertSame('0', (string)$db->createCommand('SELECT COUNT(*) FROM ' . Table::USERPERMISSIONS)->queryScalar());
            $groups = $seededGroups;
            // Supported pre-upgrade sources: view only, clear only, both, unrelated,
            // plus a new permission already explicitly granted by an administrator.
            foreach ([$view, $clear, 'accesscp', $runtimeView] as $index => $name) {
                $db->createCommand()->insert(Table::USERPERMISSIONS, ['id' => $index + 1, 'name' => $name])->execute();
            }
            foreach ([[1, 1], [2, 2], [1, 3], [2, 3], [3, 4], [4, 5]] as [$permissionId, $userId]) {
                $db->createCommand()->insert(Table::USERPERMISSIONS_USERS, compact('permissionId', 'userId'))->execute();
            }
            Craft::$app->set('projectConfig', $config);
            ob_start();
            try {
                self::assertTrue($migration->safeUp());
                $first = $this->grants($db);
                self::assertTrue($migration->safeUp());
                self::assertSame($first, $this->grants($db));
                self::assertFalse($migration->safeDown());
                self::assertSame($first, $this->grants($db));
            } finally {
                ob_end_clean();
            }
            self::assertSame([
                1 => [$view, $runtimeView],
                2 => [$clear, $runtimeClear],
                3 => [$clear, $runtimeClear, $view, $runtimeView],
                4 => ['accesscp'],
                5 => [$runtimeView],
            ], $first);
            self::assertSame([$view, 'accesscp', $runtimeView], $groups['viewers']['permissions']);
            self::assertSame([$clear, $runtimeClear], $groups['clearers']['permissions']);
            self::assertSame([$view, $clear, $runtimeView, $runtimeClear], $groups['both']['permissions']);
            self::assertSame([$view, $runtimeView], $groups['already-upgraded']['permissions']);
            self::assertSame(['accesscp'], $groups['unrelated']['permissions']);
            // Combining groups or direct/group grants enables the same intersection;
            // removing viewer membership does not leave a direct viewing grant behind.
            $combined = array_merge($groups['viewers']['permissions'], $first[2]);
            self::assertContains($runtimeView, $combined);
            self::assertContains($runtimeClear, $combined);
            self::assertNotContains($runtimeView, $first[2]);
            self::assertNotContains($runtimeView, $groups['clearers']['permissions']);
        } finally {
            Craft::$app->set('projectConfig', $originalConfig);
            try {
                foreach ([Table::USERPERMISSIONS_USERS, Table::USERPERMISSIONS] as $table) {
                    if ($db->tableExists($table, false)) {
                        $db->createCommand()->dropTable($table)->execute();
                    }
                    self::assertFalse($db->tableExists($table, false));
                }
            } finally {
                $db->close();
            }
        }
    }

    private function grants(Connection $db): array
    {
        $rows = $db->createCommand('SELECT pu.[[userId]], p.[[name]] FROM ' . Table::USERPERMISSIONS_USERS . ' pu INNER JOIN ' . Table::USERPERMISSIONS . ' p ON p.[[id]] = pu.[[permissionId]] ORDER BY pu.[[userId]], p.[[name]]')->queryAll();
        $grants = [];
        foreach ($rows as $row) {
            $grants[(int)$row['userId']][] = $row['name'];
        }
        return $grants;
    }
}
