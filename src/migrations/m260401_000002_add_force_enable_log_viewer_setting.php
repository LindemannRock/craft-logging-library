<?php
/**
 * Add forceEnableLogViewer setting for Logging Library installs.
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\migrations;

use craft\db\Migration;

/**
 * Add forceEnableLogViewer column to existing settings tables.
 *
 * @since 5.8.0
 */
class m260401_000002_add_force_enable_log_viewer_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $table = '{{%logginglibrary_settings}}';
        $schema = $this->db->getTableSchema($table, true);

        if ($schema !== null && !isset($schema->columns['forceEnableLogViewer'])) {
            $this->addColumn($table, 'forceEnableLogViewer', $this->boolean()->notNull()->defaultValue(false)->after('showCpSection'));
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $table = '{{%logginglibrary_settings}}';
        $schema = $this->db->getTableSchema($table, true);

        if ($schema !== null && isset($schema->columns['forceEnableLogViewer'])) {
            $this->dropColumn($table, 'forceEnableLogViewer');
        }

        return true;
    }
}
