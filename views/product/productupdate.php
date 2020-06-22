<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Product Update';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-entry">
    <h1><?= Html::encode($this->title) ?></h1>
    <h4>Add New Product</h4>

    <p style="margin-top: 30px">Please fill out the following fields:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'update-product-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <!-- <div class="col-lg-2">Current Code : <?= $product->code ?></div> -->
    <?= $form->field($updateProduct, 'code')->textInput(['autofocus' => true, 'value' => $product->code])?>
    <!-- <div class="col-lg-2 control-label">Current Name : <?= $product->name ?></div> -->
    <?= $form->field($updateProduct, 'name')->textInput(['value' => $product->name]) ?>
    <!-- <div class="col-lg-2">Current Quantity : <?= $product->quantity ?></div> -->
    <?= $form->field($updateProduct, 'quantity')->textInput(['value' => $product->quantity]) ?>
    <!-- <div class="col-lg-2">Current Price : <?= $product->price ?></div> -->
    <?= $form->field($updateProduct, 'price')->textInput(['value' => Yii::$app->formatter->asDecimal($product->price, 2)]) ?>
        
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Update', ['class' => 'btn btn-primary', 'name' => 'update-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
