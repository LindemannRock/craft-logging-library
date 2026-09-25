<?php
/**
 * Logging Library install migration for Craft CMS 5.x
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\migrations;

use craft\db\Migration;
use craft\helpers\Db;
use craft\helpers\StringHelper;

/**
 * Install Migration
 *
 * @since 5.8.0
 */
class Install extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        if (!$this->db->tableExists('{{%logginglibrary_settings}}')) {
            $this->createTable('{{%logginglibrary_settings}}', [
                'id' => $this->primaryKey(),
                'pluginName' => $this->string(255)->notNull()->defaultValue('Logging Library'),
                'itemsPerPage' => $this->integer()->notNull()->defaultValue(100),
                'showCpSection' => $this->boolean()->notNull()->defaultValue(true),
                'forceEnableLogViewer' => $this->boolean()->notNull()->defaultValue(false),
                'timeFormat' => $this->string(2)->null(),
                'showSeconds' => $this->boolean()->null(),
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
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
            ]);

            $this->insert('{{%logginglibrary_settings}}', [
                'pluginName' => 'Logging Library',
                'itemsPerPage' => 100,
                'showCpSection' => true,
                'forceEnableLogViewer' => false,
                'timeFormat' => null,
                'showSeconds' => null,
                'runtimeLevels' => '["error","warning","info"]',
                'runtimeCategories' => '[]',
                'runtimeExcept' => '[]',
                'dateCreated' => Db::prepareDateForDb(new \DateTime()),
                'dateUpdated' => Db::prepareDateForDb(new \DateTime()),
                'uid' => StringHelper::UUID(),
            ]);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        if ($this->db->tableExists('{{%logginglibrary_settings}}')) {
            $this->dropTableIfExists('{{%logginglibrary_settings}}');
        }

        return true;
    }
}
