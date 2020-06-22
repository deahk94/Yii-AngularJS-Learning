<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;

$this->title = 'Member Page';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome, <?php echo $account->username; ?>!</h1>

    <div class="body-content">
        <div class="row">
            <div class="col-lg-6">
                <h2>Wallet A</h2>

                <p>RM<?php echo $account->getWallet('A')->amount; ?></p>

                <!--button type="button" class="btn btn-default" data-toggle="modal" data-target="#transferModal">Transfer</button-->
                <a class="btn btn-default" href='<?= Url::to(['site/transfer', 'walletType' => 'A', 'username' => $account->username, 'balance' => $account->getWallet('A')->amount]) ?>'>Transfer</a>
                <!-- <a class="btn btn-default" href='<?php echo Url::base(false); ?>/index.php?r=site/transfer&walletType=A&username=<?= $account->username?>'>Transfer</a> -->
            </div>
            <div class="col-lg-6">
                <h2>Wallet B</h2>

                <p>RM<?php echo $account->getWallet('B')->amount; ?></p>

                <a class="btn btn-default" href='<?= Url::to(['site/transfer', 'walletType' => 'B', 'username' => $account->username, 'balance' => $account->getWallet('B')->amount]) ?>'>Transfer</a>
                <!-- <a class="btn btn-default" href='<?php echo Url::base(false); ?>/index.php?r=site/transfer&walletType=B&username=<?= $account->username?>'>Transfer</a> -->
                <!--button type="button" class="btn btn-default" data-toggle="modal" data-target="#transferModal">Transfer</button-->
            </div>
        </div>
    </div>

    <div style="margin-top: 100px">
        <h2 style="text-align: left; margin-bottom: 20px">Search</h2>
        <div style="margin-left: 10px">
            <?php $form = ActiveForm::begin([
                'id' => 'search-form',
                'layout' => 'horizontal',
                'method' => 'get',
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-1 control-label'],
                ],
            ]); ?>

            <div>
                <p style="text-align: left; font-size: 15px;"><?= Html::activeLabel($search, 'walletType') ?>
                <?= Html::activeDropDownList($search, 'walletType', $walletList, ['value' => $search->walletType]) ?></p>
              
                <?= $form->field($search, 'minAmount')->textInput(['autofocus' => true, 'value' => $search->minAmount]) ?>
                <?= $form->field($search, 'maxAmount')->textInput(['value' => $search->maxAmount]) ?>
            </div>

            <div class="form-group">
                <div class="col-lg-offset-1 col-lg-11" style="text-align: left; margin-left: -10px !important;">
                    <?= Html::submitButton('Search', ['class' => 'btn btn-primary', 'name' => 'search-button']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>

    <h2 style="text-align: left; margin-top: 30px">Transaction History</h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
              'class' => 'yii\grid\SerialColumn',
              'header' => 'No.',
            ],
            [
                'attribute' => 'date',
                'label' => 'Transaction Date',
            ],
            [
                'attribute' => 'outwalletid',
                'label' => 'Wallet (OUT)',
                'value' => function ($model) {
                    return $model->getWalletById($model->outwalletid)->type;
                }
            ],
            [
                'attribute' => 'inwalletid',
                'label' => 'Wallet (IN)',
                'value' => function ($model) {
                    return $model->getWalletById($model->inwalletid)->type;
                }
            ],
            [
                'attribute' => 'amount',
                'label' => 'Transfer Amount',
                'format'=> ['decimal', 2],
            ],
        ],
    ]); ?>
    </div>
</div>