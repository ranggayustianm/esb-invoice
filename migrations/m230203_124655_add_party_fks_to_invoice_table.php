<?php

use yii\db\Migration;

/**
 * Class m230203_124655_add_party_fks_to_invoice_table
 */
class m230203_124655_add_party_fks_to_invoice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('invoice', 'party_from', $this->integer()->notNull());
        $this->addColumn('invoice', 'party_to', $this->integer()->notNull());
    
        $this->createIndex(
            'idx-invoice-party_from',
            'invoice',
            'party_from'
        );
    
        $this->createIndex(
            'idx-invoice-party_to',
            'invoice',
            'party_to'
        );

        $this->addForeignKey(
            'fk-invoice-party_from',
            'invoice',
            'party_from',
            'party',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-invoice-party_to',
            'invoice',
            'party_to',
            'party',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-invoice-party_to',
            'invoice'
        );

        $this->dropForeignKey(
            'fk-invoice-party_from',
            'invoice'
        );
        
        $this->dropIndex(
            'idx-invoice-party_to',
            'invoice'
        );
        
        $this->dropIndex(
            'idx-invoice-party_from',
            'invoice'
        );
        
        $this->dropColumn('invoice', 'party_to');
        $this->dropColumn('invoice', 'party_from');        
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230203_123655_add_party_fks_to_invoice_table cannot be reverted.\n";

        return false;
    }
    */
}
