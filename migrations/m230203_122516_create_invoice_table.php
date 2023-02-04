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
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('invoice');
    }
}
