<?php

use yii\db\Migration;

/**
 * Handles the creation of table `item`.
 */
class m230203_125957_create_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('item', [
            'id' => $this->primaryKey(),
            'item_type' => $this->string()->notNull(),
            'description' => $this->string()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'unit_price' => $this->double()->notNull(),
            'amount' => $this->double()->notNull(),
            'invoice_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-item-invoice_id',
            'item',
            'invoice_id'
        );

        $this->addForeignKey(
            'fk-item-invoice_id',
            'item',
            'invoice_id',
            'invoice',
            'id',
            'CASCADE'
        );

        $this->insert('item', [
            'item_type' => 'Service',
            'description' => 'Item 1',
            'quantity' => '5',
            'unit_price' => '100',
            'amount' => '500',
            'invoice_id' => '1',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('item', ['id' => 1]);

        $this->dropForeignKey(
            'fk-item-invoice_id',
            'item'
        );
    
        $this->dropIndex(
            'idx-item-invoice_id',
            'item'
        );
    
        $this->dropTable('item');
    }
}
