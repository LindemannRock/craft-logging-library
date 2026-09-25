<?php
/**
 * Logging Library for Craft CMS
 *
 * @link https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\migrations;

use craft\db\Migration;

/**
 * Give persisted capture filters explicit include/exclude names without changing data.
 *
 * @since 5.19.0
 */
class m260925_000001_rename_runtime_category_filters extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        return $this->renameFilters([
            'runtimeCategories' => 'runtimeIncludeCategories',
            'runtimeExcept' => 'runtimeExcludeCategories',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        return $this->renameFilters([
            'runtimeIncludeCategories' => 'runtimeCategories',
            'runtimeExcludeCategories' => 'runtimeExcept',
        ]);
    }

    private function renameFilters(array $columns): bool
    {
        $table = '{{%logginglibrary_settings}}';
        if (!$this->db->tableExists($table)) {
            return true;
        }
        foreach ($columns as $from => $to) {
            if ($this->db->columnExists($table, $from)) {
                $this->renameColumn($table, $from, $to);
            }
        }
        return true;
    }
}
