<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%queue}}`.
 */
class m250804_101013_create_queue_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%queue}}', [
            'id' => $this->bigPrimaryKey(),
            'channel' => $this->string()->notNull(),
            'job' => $this->binary()->notNull(),
            'pushed_at' => $this->integer()->notNull(),
            'ttr' => $this->integer()->notNull(),
            'delay' => $this->integer()->notNull(),
            'priority' => $this->integer()->defaultValue(1024),
            'reserved_at' => $this->integer()->defaultValue(null),
            'attempt' => $this->integer()->defaultValue(null),
            'done_at' => $this->integer()->defaultValue(null),
        ]);

        $this->createIndex('idx_queue_channel', '{{%queue}}', 'channel');
        $this->createIndex('idx_queue_reserved', '{{%queue}}', 'reserved_at');
        $this->createIndex('idx_queue_priority', '{{%queue}}', 'priority');
    }

    public function down()
    {
        $this->dropTable('{{%queue}}');
    }
}
