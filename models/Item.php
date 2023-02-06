<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item".
 *
 * @property int $id
 * @property string $item_type
 * @property string $description
 * @property int $quantity
 * @property float $unit_price
 * @property float $amount
 * @property int $invoice_id
 *
 * @property Invoice $invoice
 */
class Item extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_type', 'description', 'quantity', 'unit_price', 'amount', 'invoice_id'], 'required'],
            [['quantity', 'invoice_id'], 'integer'],
            [['unit_price', 'amount'], 'number'],
            [['item_type', 'description'], 'string', 'max' => 255],
            [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::class, 'targetAttribute' => ['invoice_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_type' => 'Item Type',
            'description' => 'Description',
            'quantity' => 'Quantity',
            'unit_price' => 'Unit Price',
            'amount' => 'Amount',
            'invoice_id' => 'Invoice ID',
        ];
    }

    /**
     * Gets query for [[Invoice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::class, ['id' => 'invoice_id']);
    }

    public function getAmountSubtotal()
    {
        return $this->amount;
    }
}
