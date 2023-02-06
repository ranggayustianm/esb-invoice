<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice".
 *
 * @property int $id
 * @property string $invoice_id
 * @property string $issue_date
 * @property string $due_date
 * @property string $subject
 * @property int $is_paid
 * @property int $party_from
 * @property int $party_to
 *
 * @property Item[] $items
 * @property Party $partyFrom
 * @property Party $partyTo
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_id', 'issue_date', 'due_date', 'subject', 'is_paid', 'party_from', 'party_to'], 'required'],
            [['issue_date', 'due_date'], 'safe'],
            [['is_paid', 'party_from', 'party_to'], 'integer'],
            [['invoice_id', 'subject'], 'string', 'max' => 255],
            [['party_from'], 'exist', 'skipOnError' => true, 'targetClass' => Party::class, 'targetAttribute' => ['party_from' => 'id']],
            [['party_to'], 'exist', 'skipOnError' => true, 'targetClass' => Party::class, 'targetAttribute' => ['party_to' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice_id' => 'Invoice ID',
            'issue_date' => 'Issue Date',
            'due_date' => 'Due Date',
            'subject' => 'Subject',
            'is_paid' => 'Is Paid',
            'party_from' => 'From',
            'party_to' => 'To',
        ];
    }

    /**
     * Gets query for [[Items]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Item::class, ['invoice_id' => 'id']);
    }

    /**
     * Gets query for [[PartyFrom]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPartyFrom()
    {
        return $this->hasOne(Party::class, ['id' => 'party_from']);
    }

    public function getPartyFromLabel()
    {
        return $this->hasOne(Party::class, ['id' => 'party_from'])->one()->party_name;
    }

    /**
     * Gets query for [[PartyTo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPartyTo()
    {
        return $this->hasOne(Party::class, ['id' => 'party_to']);
    }

    public function getPartyToLabel()
    {
        return $this->hasOne(Party::class, ['id' => 'party_to'])->one()->party_name;
    }
}
