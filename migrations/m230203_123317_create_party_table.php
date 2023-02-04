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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('party');
    }
}
