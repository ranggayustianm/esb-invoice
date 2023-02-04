<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use kartik\date\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\Invoice $model */
/** @var yii\widgets\ActiveForm $form */
/** @var app\models\Item[] $itemsFromDb */
?>

<div class="invoice-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="container">
        <div class="row">
            <div class="col-6"><?= $form->field($model, 'invoice_id')->textInput(['maxlength' => true]) ?></div>
            <div class="col-6"><?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?></div>
        </div>
        <div class="row">
            <div class="col-6">
            <?= $form->field($model, 'issue_date')->widget(DatePicker::className(), [
                'name' => 'issue_date', 
                'type' => DatePicker::TYPE_INPUT,
                'value' => date('Y-m-d'),
                'options' => ['placeholder' => 'Select issue date ...'],
                'pluginOptions' => [
                    'startDate' => date('Y-m-d'),
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]) ?>    
            <?= $form->field($model, 'is_paid')->checkbox() ?>
            </div>
            <div class="col-6">
            <?= $form->field($model, 'due_date')->widget(DatePicker::className(), [
                'name' => 'due_date', 
                'type' => DatePicker::TYPE_INPUT,
                'value' => date('Y-m-d'),
                'options' => ['placeholder' => 'Select due date ...'],
                'pluginOptions' => [
                    'startDate' => date('Y-m-d'),
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6"><?= $form->field($model, 'party_from')->dropDownList($partiesForDropDown) ?></div>
            <div class="col-6"><?= $form->field($model, 'party_to')->dropDownList($partiesForDropDown) ?></div>
        </div>
        <hr>
        <div class="row">
            <div class="col-10"><h3>Items</h3></div>
            <div class="col-2"><button type="button" class="btn btn-primary add-item">Add Items</button></div>
        </div>
        <div class="row">
            <div class="col-12">                         
                <table class="table table-bordered" id="item-table">
                    <thead>
                        <tr>
                            <th>Item Type</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Amount</th>
                            <th style="width: 5%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(is_null($itemsFromDb)):?>
                            <tr>
                                <td>
                                    <select class="form-control item-type" name="Item[0][item_type]" required>
                                        <option value="Service">Service</option>
                                        <option value="Product">Product</option>
                                        <option value="Shipping">Shipping</option>
                                        <option value="Tax">Tax</option>
                                        <option value="Miscellaneous">Miscellaneous</option>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control description" name="Item[0][description]" required></td>
                                <td><input type="text" class="form-control quantity" name="Item[0][quantity]" required></td>
                                <td><input type="text" class="form-control unit-price" name="Item[0][unit_price]" required></td>
                                <td><input type="text" class="form-control amount" name="Item[0][amount]" required></td>
                                <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
                                <input type="hidden" name="Item[0][invoice_id]" value="">
                            </tr>
                        <?php else:?>
                            <?php foreach($itemsFromDb as $key => $item):?>
                                <tr>
                                    <td>
                                        <select class="form-control item-type" name="Item[<?= $key ?>][item_type]" required>
                                            <option value="Service" <?= $item->item_type === "Service" ? "selected" : "" ?>>Service</option>
                                            <option value="Product" <?= $item->item_type === "Product" ? "selected" : "" ?>>Product</option>
                                            <option value="Shipping" <?= $item->item_type === "Shipping" ? "selected" : "" ?>>Shipping</option>
                                            <option value="Tax" <?= $item->item_type === "Tax" ? "selected" : "" ?>>Tax</option>
                                            <option value="Miscellaneous" <?= $item->item_type === "Miscellaneous" ? "selected" : "" ?>>Miscellaneous</option>
                                        </select>
                                    </td>
                                    <td><input type="text" value="<?= $item->description ?>" class="form-control description" name="Item[<?= $key ?>][description]" required></td>
                                    <td><input type="text" value="<?= $item->quantity ?>" class="form-control quantity" name="Item[<?= $key ?>][quantity]" required></td>
                                    <td><input type="text" value="<?= $item->unit_price ?>" class="form-control unit-price" name="Item[<?= $key ?>][unit_price]" required></td>
                                    <td><input type="text" value="<?= $item->amount ?>" class="form-control amount" name="Item[<?= $key ?>][amount]" required></td>
                                    <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
                                    <input type="hidden" name="Item[<?= $key ?>][invoice_id]" value="<?= $item->invoice_id ?>">
                                </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

    $formJs = <<<JS
        var i = 0;

        $(document).on('click', '.add-item', function() {
            i++;
            var html = 
                '<tr> \
                    <td> \
                        <select class="form-control item-type" name="Item['+i+'][item_type]" required> \
                            <option value="Service">Service</option> \
                            <option value="Product">Product</option> \
                            <option value="Shipping">Shipping</option> \
                            <option value="Tax">Tax</option> \
                            <option value="Miscellaneous">Miscellaneous</option> \
                        </select> \
                    </td> \
                    <td><input type="text" class="form-control description" name="Item['+i+'][description]" required></td> \
                    <td><input type="text" class="form-control quantity" name="Item['+i+'][quantity]" required></td> \
                    <td><input type="text" class="form-control unit_price" name="Item['+i+'][unit_price]" required></td> \
                    <td><input type="text" class="form-control amount" name="Item['+i+'][amount]" required></td> \
                    <td><button type="button" class="btn btn-danger remove-item">Remove</button></td> \
                    <input type="hidden" name="Item['+i+'][invoice_id]" value=""> \
                </tr>'
                ;
            $('#item-table tbody').append(html)
        });
        $(document).on('click', '.remove-item', function() {
            $(this).closest('tr').remove();            
        });
    JS;
    $this->registerJs($formJs, View::POS_END, 'dynamic-table');

?>