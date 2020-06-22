<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.9/angular.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.9/angular-messages.js"></script>
	<script src="//unpkg.com/@uirouter/angularjs/release/angular-ui-router.min.js"></script>
	<?php $this->registerCsrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body ng-app="app">
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" ui-sref="login">Home</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<!-- <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li> -->
					<li><a ui-sref="product">Product</a></li>
					<li><a ui-sref="wallet">Wallet</a></li>
					<!-- <li><a ui-sref="login">Admin</a></li> -->
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a ui-sref="login">Login</a></li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>
	<?php $this->beginBody() ?>
	<?= $content ?>
	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
