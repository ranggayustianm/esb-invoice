<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\Invoice $model */

$this->title = $model->invoice_id;
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="invoice-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'invoice_id',
            [
                'label' => 'Issue Date',
                'value' => date_format(new DateTime($model->issue_date), 'd F Y'),
            ],
            [
                'label' => 'Due Date',
                'value' => date_format(new DateTime($model->due_date), 'd F Y'),
            ],
            'subject',
            [
                'label' => 'Is Paid?',
                'value' => (boolean)$model->is_paid ? "Yes" : "No",
            ],
            [
                'label' => 'From',
                'value' => $model->partyFrom->party_name,
            ],
            [
                'label' => 'To',
                'value' => $model->partyTo->party_name,
            ],
        ],
    ]) ?>

    <h2>Items</h2>

    <?= GridView::widget([
        'dataProvider' => $itemsDataProvider,
        'columns' => [
            'item_type',
            'description',
            'quantity',
            'unit_price',
            'amount',
        ],
    ]) ?>

    <p>Subtotal: <?= Html::encode($itemsSubtotal) ?></p>
    <p>Tax (10%): <?= Html::encode($itemsTax) ?></p>
    <p>Payments: <?= Html::encode($itemsPayments) ?></p>

</div>
