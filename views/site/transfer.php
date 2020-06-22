<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Transfer';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-transaction">
    <h1><?= Html::encode($this->title) ?></h1>
    <h4>Wallet <?= $type ?> to Wallet <?= ($type === "A") ? 'B' : 'A' ?></h4>

    <p style="margin-top: 30px">Please fill out the following fields:</p>
    <h4>Current Balance : <?= $balance ?></h4>

    <?php $form = ActiveForm::begin([
        'id' => 'transaction',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($transaction, 'amount')->textInput(['autofocus' => true]) ?>
    <!--?= $form->field($transaction, 'username')->hiddenInput(['username'=> $user])->label(false); ?-->
    <?= Html::activeHiddenInput($transaction, 'username', ['value' => $user]) ?>
    <?= Html::activeHiddenInput($transaction, 'type', ['value' => $type]) ?>
        
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Confirm', ['class' => 'btn btn-primary', 'name' => 'confirm-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
