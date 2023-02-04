<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "party".
 *
 * @property int $id
 * @property string $party_name
 * @property string $party_address
 *
 * @property Invoice[] $invoices
 * @property Invoice[] $invoices0
 */
class Party extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'party';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['party_name', 'party_address'], 'required'],
            [['party_name', 'party_address'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'party_name' => 'Name',
            'party_address' => 'Address',
        ];
    }

    /**
     * Gets query for [[Invoices]] on Party From.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvoicesOnPartyFrom()
    {
        return $this->hasMany(Invoice::class, ['party_from' => 'id']);
    }

    /**
     * Gets query for [[Invoices]] on Party To.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvoicesOnPartyTo()
    {
        return $this->hasMany(Invoice::class, ['party_to' => 'id']);
    }
}
