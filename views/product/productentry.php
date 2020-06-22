<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Product Entry';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-entry">
    <h1><?= Html::encode($this->title) ?></h1>
    <h4>Add New Product</h4>

    <p style="margin-top: 30px">Please fill out the following fields:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'add-product-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($addProduct, 'code')->textInput(['autofocus' => true]) ?>
    <?= $form->field($addProduct, 'name')->textInput() ?>
    <?= $form->field($addProduct, 'quantity')->textInput() ?>
    <?= $form->field($addProduct, 'price')->textInput() ?>
        
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Add', ['class' => 'btn btn-primary', 'name' => 'add-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
