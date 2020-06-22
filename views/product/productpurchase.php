<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Product Purchase';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-entry">
    <h1><?= Html::encode($this->title) ?></h1>
    <h4>Add New Product</h4>

    <p style="margin-top: 30px">Please fill out the following fields:</p>
    <h4>Wallet Balance : <?= $balance ?></h4>

    <?php $form = ActiveForm::begin([
        'id' => 'purchase-product-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <div class="form-group">

        <?= $form->field($purchaseProduct, 'name')->textInput(['value' => $product->name, 'readonly' => true]) ?>
        <?= $form->field($purchaseProduct, 'price')->textInput(['value' => $product->price, 'readonly' => true]) ?>
        <div class="form-group field-productform-price">
            <label class="col-lg-1 control-label" for="productform-price">Quantity Available</label>
            <div class="col-lg-3"><input type="text" id="productform-price" class="form-control" value="<?= $product->quantity ?>" readonly=""></div>
        </div>
        <?= $form->field($purchaseProduct, 'quantity')->textInput(['autofocus' => true]) ?>
        
        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Purchase', ['class' => 'btn btn-primary', 'name' => 'purchase-button']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
