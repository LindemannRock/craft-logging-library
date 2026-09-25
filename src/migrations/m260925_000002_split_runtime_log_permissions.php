<?php
/**
 * @link https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\migrations;

use Craft;
use craft\db\Migration;
use craft\db\Query;
use craft\db\Table;

/**
 * Preserve runtime access while separating it from file-log permissions.
 *
 * @since 5.19.0
 */
class m260925_000002_split_runtime_log_permissions extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        // Preserve grant provenance, including permissions combined across groups
        // and direct assignments. Runtime clearing still requires BOTH new grants.
        $mapping = [
            'logginglibrary:viewalllogs' => 'logginglibrary:viewruntimelogs',
            'logginglibrary:clearcache' => 'logginglibrary:clearruntimelogs',
        ];

        foreach ($mapping as $old => $new) {
            $userIds = (new Query())
                ->select('pu.userId')
                ->from(['pu' => Table::USERPERMISSIONS_USERS])
                ->innerJoin(['p' => Table::USERPERMISSIONS], '[[p.id]] = [[pu.permissionId]]')
                ->where(['p.name' => $old])
                ->column($this->db);

            if ($userIds === []) {
                continue;
            }

            $permissionId = (new Query())
                ->select('id')->from(Table::USERPERMISSIONS)->where(['name' => $new])->scalar($this->db);
            if ($permissionId === false) {
                $this->insert(Table::USERPERMISSIONS, ['name' => $new]);
                $permissionId = $this->db->getLastInsertID(Table::USERPERMISSIONS);
            }
            foreach ($userIds as $userId) {
                $this->upsert(Table::USERPERMISSIONS_USERS, [
                    'permissionId' => $permissionId,
                    'userId' => $userId,
                ], false);
            }
        }

        // Craft owns group permissions through project config. Updating that
        // authority also updates database grants and survives subsequent deploys.
        $projectConfig = Craft::$app->getProjectConfig();
        foreach ($projectConfig->get('users.groups') ?? [] as $uid => $group) {
            $permissions = $group['permissions'] ?? [];
            $updated = $permissions;
            foreach ($mapping as $old => $new) {
                if (in_array($old, $permissions, true) && !in_array($new, $updated, true)) {
                    $updated[] = $new;
                }
            }
            if ($updated !== $permissions) {
                $projectConfig->set("users.groups.$uid.permissions", $updated);
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        // New permissions may have been independently assigned since upgrading.
        // Do not revoke administrator-managed grants during a downgrade.
        return false;
    }
}
