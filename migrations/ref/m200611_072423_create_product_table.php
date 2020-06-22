<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product}}`.
 */
class m200611_072423_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(64),
            'quantity' => $this->integer()->defaultValue(0),
            'price'    => $this->double()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->null()->append('ON UPDATE CURRENT_TIMESTAMP'),
            'deleted_at' => $this->timestamp()->null(),
        ], 'engine = InnoDb charset = utf8');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%product}}');
    }
}
