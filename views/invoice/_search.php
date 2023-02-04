<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\InvoiceSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="invoice-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'invoice_id') ?>

    <?= $form->field($model, 'issue_date') ?>

    <?= $form->field($model, 'due_date') ?>

    <?= $form->field($model, 'subject') ?>

    <?php // echo $form->field($model, 'is_paid') ?>

    <?php // echo $form->field($model, 'party_from') ?>

    <?php // echo $form->field($model, 'party_to') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
