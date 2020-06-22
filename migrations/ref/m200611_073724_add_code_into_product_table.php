<?php

use yii\db\Migration;

/**
 * Class m200611_073724_add_code_into_product_table
 */
class m200611_073724_add_code_into_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'code', $this->string(64)->after('id'));
        $this->createIndex('product_idx_1', '{{%product}}', ['code', 'deleted_at']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('product_idx_1', '{{%product}}');
        $this->dropColumn('{{%product}}', 'code');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200611_073724_add_code_into_product_table cannot be reverted.\n";

        return false;
    }
    */
}
