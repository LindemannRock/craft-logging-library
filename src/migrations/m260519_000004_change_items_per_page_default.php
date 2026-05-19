<?php
/**
 * Change itemsPerPage default from 50 to 100 for Logging Library installs.
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\migrations;

use craft\db\Migration;

/**
 * Standardize the `itemsPerPage` default on 100 to match the
 * `ItemsPerPageSettingsTrait` shipped in `lindemannrock-base`.
 *
 * Forward path:
 *   1. Change the column default 50 → 100 (affects fresh installs / new rows).
 *   2. Bump existing rows still holding the old default value (50) up to 100,
 *      so previously-installed clients pick up the new standard. Rows that
 *      hold any other explicit value are left untouched.
 *
 * Down path reverses the column default only — it deliberately does NOT
 * touch row values, because by rollback time we can no longer tell which
 * rows were bumped by this migration vs. which were always 100 by the user.
 *
 * @since 5.9.0
 */
class m260519_000004_change_items_per_page_default extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $table = '{{%logginglibrary_settings}}';
        $schema = $this->db->getTableSchema($table, true);

        if ($schema === null || !isset($schema->columns['itemsPerPage'])) {
            return true;
        }

        $this->alterColumn($table, 'itemsPerPage', $this->integer()->notNull()->defaultValue(100));
        $this->update($table, ['itemsPerPage' => 100], ['itemsPerPage' => 50]);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $table = '{{%logginglibrary_settings}}';
        $schema = $this->db->getTableSchema($table, true);

        if ($schema === null || !isset($schema->columns['itemsPerPage'])) {
            return true;
        }

        $this->alterColumn($table, 'itemsPerPage', $this->integer()->notNull()->defaultValue(50));

        return true;
    }
}
