<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;

$this->title = 'Record Page';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">

    <div class="jumbotron">

        <div class="body-content">

            <h4 style="text-align: left; margin-bottom: 20px; margin-top: 40px">Search Record</h4>
            <div style="margin-left: 10px">
                <?php $form = ActiveForm::begin([
                    'id' => 'search-form',
                    'layout' => 'horizontal',
                    'method' => 'get',
                    'fieldConfig' => [
                        'template' => "<div>{label}</div><div class=\"col-md-6\">{input}{error}</div>",
                        'labelOptions' => ['class' => 'col-md-3 control-label'],
                        'errorOptions' => ['style' => 'font-size: 12px; margin: 0px'],
                    ],
                ]); ?>

                <div>
                    <div class="col-md-6">
                        <?= $form->field($search, 'code')->textInput(['autofocus' => true, 'value' => $search->code])?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($search, 'name')->textInput(['value' => $search->name]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($search, 'min_quantity')->textInput(['value' => $search->min_quantity]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($search, 'max_quantity')->textInput(['value' => $search->max_quantity]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($search, 'min_price')->textInput(['value' => $search->min_price]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($search, 'max_price')->textInput(['value' => $search->max_price]) ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-offset-1 col-lg-11" style="text-align: left; margin-left: -10px !important;">
                        <?= Html::submitButton('Search', ['class' => 'btn btn-warning', 'name' => 'search-button']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>

            <h2>Users Purchase Record</h2>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                      'class' => 'yii\grid\SerialColumn',
                      'header' => 'No.',
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => 'Purchase Date',
                    ],
                    [
                        'attribute' => 'by_user',
                        'label' => 'Username',
                        'value' => function ($model) {
                            $user = $model->byUser;

                            return $user->username;
                        }
                    ],
                    [
                        'attribute' => 'product_id',
                        'label' => 'Product Name (CODE)',
                        'value' => function ($model) {
                            $product = $model->product;
                            
                            return $product->name." (".$product->code.")";
                        }
                    ],
                    [
                        'attribute' => 'quantity',
                    ],
                    [
                        'attribute' => 'price',
                        'format'=> ['decimal', 2],
                    ],
                    [
                        'attribute' => 'total_price',
                        'format'=> ['decimal', 2],
                    ],
                ],
            ]); ?>
        </div>

    </div>
</div>