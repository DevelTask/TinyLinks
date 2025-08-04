<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%links}}`.
 */
class m250804_055326_create_links_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%links}}', [
            'id' => $this->primaryKey(),
            'original_url' => $this->text()->notNull(),
            'short_code' => $this->string(32)->notNull()->unique(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex('idx_links_created_at', '{{%links}}', 'created_at');
    }

    public function safeDown()
    {
        $this->dropTable('{{%links}}');
    }
}