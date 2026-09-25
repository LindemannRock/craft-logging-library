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
 * Add CP-managed preferences without importing environment configuration.
 *
 * @since 5.19.0
 */
class m260925_000000_add_runtime_log_settings extends Migration
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

        $columns = [
            'runtimeEnabled' => $this->boolean()->notNull()->defaultValue(false),
            'runtimeSkipConsoleRequests' => $this->boolean()->notNull()->defaultValue(true),
            'runtimeSkipQueueRequests' => $this->boolean()->notNull()->defaultValue(true),
            'runtimeTtl' => $this->integer()->notNull()->defaultValue(86400),
            'runtimeMaxEntries' => $this->integer()->notNull()->defaultValue(1000),
            'runtimeRefreshInterval' => $this->integer()->notNull()->defaultValue(5),
            'runtimeMaxMessageBytes' => $this->integer()->notNull()->defaultValue(8000),
            'runtimeMaxContextBytes' => $this->integer()->notNull()->defaultValue(8000),
            'runtimeLevels' => $this->text(),
            'runtimeCategories' => $this->text(),
            'runtimeExcept' => $this->text(),
            'runtimeIncludeUserId' => $this->boolean()->notNull()->defaultValue(false),
        ];
        foreach ($columns as $name => $definition) {
            if (!$this->db->columnExists($table, $name)) {
                $this->addColumn($table, $name, $definition);
            }
        }

        // Nullable text columns are portable across MySQL and PostgreSQL.
        foreach (['runtimeLevels' => '["error","warning","info"]', 'runtimeCategories' => '[]', 'runtimeExcept' => '[]'] as $name => $value) {
            $this->update($table, [$name => $value], [$name => null]);
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        // Preferences may have been configured since upgrade; do not discard them.
        return false;
    }
}
