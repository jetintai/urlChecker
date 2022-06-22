<?php

use yii\db\Migration;

/**
 * Class m220620_195641_url_table
 */
class m220620_195641_url_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('urls', [
            'id' => $this->primaryKey(),
            'url' => $this->string(255),
            'frequency' => $this->integer(),
            'repeat_count' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220620_195641_url_table cannot be reverted.\n";
        $this->dropTable('urls');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220620_195641_url_table cannot be reverted.\n";

        return false;
    }
    */
}
