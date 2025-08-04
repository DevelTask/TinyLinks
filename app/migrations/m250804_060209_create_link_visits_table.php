<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%link_visits}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%links}}`
 */
class m250804_060209_create_link_visits_table extends Migration
{
    public function safeUp()
    {
        // используем raw SQL, так как Yii2 не поддерживает partition напрямую
        $this->execute(<<<SQL
CREATE TABLE link_visits (
    link_id INT NOT NULL,
    visited_at DATETIME NOT NULL,
    year INT NOT NULL,
    month INT NOT NULL,
    user_agent VARCHAR(255),
    ip_address VARCHAR(45),
    PRIMARY KEY (year, month, link_id, visited_at),
    INDEX idx_link_id (link_id)
)
PARTITION BY RANGE COLUMNS(year, month) (
    PARTITION p2024_05 VALUES LESS THAN (2024, 6),
    PARTITION p2024_06 VALUES LESS THAN (2024, 7),
    PARTITION p2024_08 VALUES LESS THAN (2024, 9),
    PARTITION p2024_09 VALUES LESS THAN (2024, 10),
    PARTITION pMax VALUES LESS THAN (MAXVALUE, MAXVALUE)
);
SQL
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%link_visits}}');
    }
}