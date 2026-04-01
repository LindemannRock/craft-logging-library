<?php
/**
 * Add showCpSection setting for Logging Library installs.
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\migrations;

use craft\db\Migration;

/**
 * Add showCpSection column to existing settings tables.
 *
 * @since 5.8.0
 */
class m260401_000001_add_show_cp_section_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $table = '{{%logginglibrary_settings}}';
        if (!$this->db->tableExists($table)) {
            return true;
        }

        $schema = $this->db->getTableSchema($table);
        if ($schema !== null && !isset($schema->columns['showCpSection'])) {
            $this->addColumn($table, 'showCpSection', $this->boolean()->notNull()->defaultValue(true)->after('itemsPerPage'));
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $table = '{{%logginglibrary_settings}}';
        if ($this->db->tableExists($table)) {
            $schema = $this->db->getTableSchema($table);
            if ($schema !== null && isset($schema->columns['showCpSection'])) {
                $this->dropColumn($table, 'showCpSection');
            }
        }

        return true;
    }
}
