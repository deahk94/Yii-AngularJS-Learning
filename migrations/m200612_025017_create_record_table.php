<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%record}}`.
 */
class m200612_025017_create_record_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%record}}', [
            'id' => $this->primaryKey()->unique()->notNull(),
            'by_user' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->defaultValue(1),
            'price' => $this->float()->defaultValue(1),
            'total_price' => $this->float()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->null()->append('ON UPDATE CURRENT_TIMESTAMP'),
            'deleted_at' => $this->timestamp()->null(),
        ], 'engine = InnoDb charset = utf8');
        
        $this->addForeignKey('record_fk_userid', '{{%record}}', 'by_user', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('record_fk_productid', '{{%record}}', 'product_id', '{{%product}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('record_fk_userid', '{{%record}}');
        $this->dropForeignKey('record_fk_productid', '{{%record}}');
        $this->dropTable('{{%record}}');
    }
}
