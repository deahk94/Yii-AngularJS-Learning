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

        <div class="body-content">

            <div class="row">
                <div class="col-lg-6">
                    <h3>Product Entry</h3>
                    <h5>Add a new product into the product list</h5>

                    <a class="btn btn-default" href='<?= Url::to(['product/add-product']) ?>'>Add</a>
                </div>
                <div class="col-lg-6">
                    <h3>Purchase Record</h3>
                    <h5>Check all user's purchase record</h5>

                    <a class="btn btn-default" href='<?= Url::to(['product/record']) ?>'>View</a>
                </div>
            </div>

            <div>
                <h4 style="text-align: left; margin-bottom: 20px; margin-top: 40px">Search Product</h4>
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

                <h3 style="text-align: left; margin-top: 50px">Product List</h3>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                          'class' => 'yii\grid\SerialColumn',
                          'header' => 'No.',
                        ],
                        [
                            'attribute' => 'by_user',
                            'label' => 'User ID'
                        ],
                        [
                            'attribute' => 'code',
                        ],
                        [
                            'attribute' => 'name',
                        ],
                        [
                            'attribute' => 'quantity',
                        ],
                        [
                            'attribute' => 'price',
                            'format'=> ['decimal', 2],
                        ],
                        [
                            'attribute' => 'updated_at',
                            'label' => 'Last Update',
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template'=>'{update}{delete}',
                            'buttons'=>[
                                'update' => function ($url, $model) {
                                    $url = Url::toRoute(['update-product', 'id' => $model->id]);

                                    return Html::a('<span class="glyphicon glyphicon-pencil" style="margin-right: 10px;"></span>', $url, [
                                            'title' => Yii::t('app', 'Update'),
                                        ]);                                
                                },
                                'delete' => function ($url, $model) {
                                    $url = Url::toRoute(['delete-product', 'id' => $model->id]);

                                    return Html::a('<span class="glyphicon glyphicon-trash" style="margin-right: 10px;"></span>', $url, [
                                            'title' => Yii::t('app', 'Delete'), 'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                        ]);                                
                                }
                            ]                            
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
