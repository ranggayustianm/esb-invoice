<?php

use yii\db\Migration;

/**
 * Handles the creation of table `invoice`.
 */
class m230203_122516_create_invoice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('invoice', [
            'id' => $this->primaryKey(),
            'invoice_id' => $this->string()->notNull(),
            'issue_date' => $this->date()->notNull(),
            'due_date' => $this->date()->notNull(),
            'subject' => $this->string()->notNull(),
            'is_paid' => $this->boolean()->notNull(),
            'party_from' => $this->integer()->notNull(),
            'party_to' => $this->integer()->notNull(),
        ]);

        $this->insert('invoice', [
            'invoice_id' => '0001',
            'issue_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d', strtotime('+7 days')),
            'subject' => 'Invoice 1',
            'is_paid' => false,
            'party_from' => 1,
            'party_to' => 2,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('invoice', ['id' => 1]);
        $this->dropTable('invoice');
    }
}
