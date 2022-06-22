<?php

use yii\db\Migration;

/**
 * Class m220620_233728_module_admin_check_table
 */
class m220620_233728_module_admin_check_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE db_project.`check` (
            id INTEGER auto_increment NOT NULL,
            url_id INTEGER NOT NULL,
            http_code varchar(100) NULL,
            date_request DATETIME NULL,
            try_number SMALLINT NULL,
            CONSTRAINT check_PK PRIMARY KEY (id),
            CONSTRAINT check_FK FOREIGN KEY (url_id) REFERENCES db_project.urls(id) ON DELETE CASCADE ON UPDATE CASCADE
        );");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220620_233728_module_admin_check_table cannot be reverted.\n";
        $this->dropTable('check');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220620_233728_module_admin_check_table cannot be reverted.\n";

        return false;
    }
    */
}
