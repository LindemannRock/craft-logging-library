<?php
/**
 * Add date format settings columns for Logging Library installs.
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\migrations;

use craft\db\Migration;

/**
 * Add the two date format settings columns to existing settings tables.
 *
 * Logging Library only consumes `timeFormat` and `showSeconds` (the log
 * viewer's timestamp column uses `|lrTime`, which only honors those two).
 * The other three properties on `DateFormatSettingsTrait` (`monthFormat`,
 * `dateOrder`, `dateSeparator`) stay null on this Settings model and are
 * not persisted to DB.
 *
 * Columns added (both nullable — null means "inherit from base config /
 * hardcoded default", matching DateFormatSettingsTrait's cascade design):
 *   - timeFormat   VARCHAR(2)  NULL   ('12' or '24')
 *   - showSeconds  BOOL        NULL
 *
 * @since 5.9.0
 */
class m260518_000003_add_date_format_settings extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $table = '{{%logginglibrary_settings}}';
        $schema = $this->db->getTableSchema($table, true);

        if ($schema === null) {
            return true;
        }

        if (!isset($schema->columns['timeFormat'])) {
            $this->addColumn($table, 'timeFormat', $this->string(2)->null()->after('forceEnableLogViewer'));
        }
        if (!isset($schema->columns['showSeconds'])) {
            $this->addColumn($table, 'showSeconds', $this->boolean()->null()->after('timeFormat'));
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

        if ($schema === null) {
            return true;
        }

        foreach (['showSeconds', 'timeFormat'] as $column) {
            if (isset($schema->columns[$column])) {
                $this->dropColumn($table, $column);
            }
        }

        return true;
    }
}
