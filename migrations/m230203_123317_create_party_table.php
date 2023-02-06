<?php

use yii\db\Migration;

/**
 * Handles the creation of table `party`.
 */
class m230203_123317_create_party_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('party', [
            'id' => $this->primaryKey(),
            'party_name' => $this->string()->notNull(),
            'party_address' => $this->string()->notNull(),
        ]);

        $this->insert('party', [
            'party_name' => 'Company A',
            'party_address' => 'Address A',
        ]);

        $this->insert('party', [
            'party_name' => 'Company B',
            'party_address' => 'Address B',
        ]);

        $this->insert('party', [
            'party_name' => 'Company C',
            'party_address' => 'Address C',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('party', ['id' => 3]);
        $this->delete('party', ['id' => 2]);
        $this->delete('party', ['id' => 1]);
        $this->dropTable('party');
    }
}
