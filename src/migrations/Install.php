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
                'itemsPerPage' => $this->integer()->notNull()->defaultValue(50),
                'showCpSection' => $this->boolean()->notNull()->defaultValue(true),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
            ]);

            $this->insert('{{%logginglibrary_settings}}', [
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
