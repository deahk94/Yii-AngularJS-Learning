<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product}}`.
 */
class m200611_093216_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey()->unique()->notNull(),
            'by_user' => $this->integer()->notNull(),
            'code' => $this->string(64),
            'name' => $this->string(64),
            'quantity' => $this->integer()->defaultValue(0),
            'price' => $this->float()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->null()->append('ON UPDATE CURRENT_TIMESTAMP'),
            'deleted_at' => $this->timestamp()->null(),
        ], 'engine = InnoDb charset = utf8');

        $this->addForeignKey('product_fk_userid', '{{%product}}', 'by_user', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('product_idx_1', '{{%product}}', ['code', 'deleted_at']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('product_fk_userid', '{{%product}}');
        $this->dropIndex('product_idx_1', '{{%product}}');
        $this->dropTable('{{%product}}');
    }
}
