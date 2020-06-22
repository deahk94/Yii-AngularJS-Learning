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

    		<h4 style="text-align: left; margin-bottom: 20px; margin-top:-30px">Search Product</h4>
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

    		<h3 style="text-align: left; margin-top: 60px">Product List</h3>
    		<h5 style="text-align: left;">Click on the <span class="glyphicon glyphicon-shopping-cart"></span> button to purchase an item.</h5>

		    <?= GridView::widget([
		            'dataProvider' => $dataProvider,
		            'columns' => [
		                [
		                  'class' => 'yii\grid\SerialColumn',
		                  'header' => 'No.',
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
		                    'class' => 'yii\grid\ActionColumn',
		                    'template'=>'{purchase}',
		                    'buttons'=>[
		                        'purchase' => function ($url, $model) {
		                            $url = Url::toRoute(['purchase-product', 'id' => $model->id]);

		                            return Html::a('<span class="glyphicon glyphicon-shopping-cart" style="margin-right: 10px;"></span>', $url, [
		                                    'title' => Yii::t('app', 'Purchase'),
		                                ]);                                
		                        },
		                ]                            
		                ],
		            ],
		        ]); ?>
    	</div>

    </div>
</div>