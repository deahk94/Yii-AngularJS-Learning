<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\TransactionAR;
use app\models\ProductForm;
use app\models\ProductSearchForm;
use app\models\ProductPurchaseForm;
use app\models\Product;
use app\models\Record;
use app\models\RecordSearchForm;
use app\models\Wallet;

class ProductController extends \yii\web\Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'except' => ['template', 'test'],
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}

	public function actions()
	{
		return [
			'template'  => [
				'class' => 'yii\web\ViewAction',
				'viewPrefix' => 'templates',
				'layout'     => 'empty',
			],
		];
	}

	public function actionTest()
	{
		$this->layout = 'test';

		return $this->render('test');
	}

	public function actionIndex()
	{
		//$this->layout = 'test.php';

		$search = new ProductSearchForm();
		$search->load(Yii::$app->request->get());
		$dataProvider = $search->search();

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'search' => $search,
		]);
	}

	public function actionWallet()
	{
		$account = Yii::$app->user->identity;

		$search = new RecordSearchForm();
		$search->load(Yii::$app->request->get());
		$search->by_user = $account->id;
		$dataProvider = $search->search();
		$dataProvider->setPagination(['pageSize' => 5]);

		return $this->render('wallet', [
			'account' => $account,
			'dataProvider' => $dataProvider,
			'search' => $search,
		]);
	}

	public function actionPanel()
	{
		$account = Yii::$app->user->identity;

		$search = new ProductSearchForm();
		$search->load(Yii::$app->request->get());
		$dataProvider = $search->search();

		return $this->render('panel', [
			'account' => $account,
			'dataProvider' => $dataProvider,
			'search' => $search,
		]);
	}

	public function actionRecord()
	{
		$account = Yii::$app->user->identity;

		$search = new RecordSearchForm();
		$query = Record::find();
		if ($search->load(Yii::$app->request->get()) && $search->validate()) {

			$query = $this->buildRecordSearchQuery($search);
		}

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 10,
			],
			'sort' => [
				'defaultOrder' => [
					'created_at' => SORT_DESC
				],
			],
		]);

		return $this->render('record', [
			'account' => $account,
			'dataProvider' => $dataProvider,
			'search' => $search,
		]);
	}

	public function actionAddProduct()
	{
		$addProduct = new ProductForm();
		$addProduct->scenario = 'create';
		if ($addProduct->load(Yii::$app->request->post()) && $addProduct->validate()) {
			Product::addProduct($addProduct);

			return $this->redirect('panel');
		}

		return $this->render('productentry', [
			'addProduct' => $addProduct,
		]);
	}

	public function actionUpdateProduct()
	{
		$productid = $_GET['id'];
		$product = Product::findOne(['id' => $productid]);

		//Uses the same attributes, reuse Form
		$updateProduct = new ProductForm();
		$updateProduct->scenario = 'create';
		if ($updateProduct->load(Yii::$app->request->post()) && $updateProduct->validate()) {
			$product->updateProduct($updateProduct);

			return $this->redirect('panel');
		}

		return $this->render('productupdate', [
			'updateProduct' => $updateProduct,
			'product' => $product,
		]);
	}

	public function actionDeleteProduct()
	{
		$productid = $_GET['id'];
		$product = Product::findOne(['id' => $productid]);
		$product->deleteProduct();

		return $this->redirect('panel');
	}

	public function actionPurchaseProduct()
	{
		$productid = $_GET['id'];
		$product = Product::findOne(['id' => $productid]);

		$account = Yii::$app->user->identity;
		$balance = Wallet::find()->AndWhere(['userid' => $account->id, 'type' => 'A'])->one()->amount;

		//Uses the same attributes, reuse Form
		$purchaseProduct = new ProductForm();
		$purchaseProduct->scenario = 'purchase';

		if ($purchaseProduct->load(Yii::$app->request->post()) && $purchaseProduct->validate()) {

			$validQuantity = ($product->quantity >= $purchaseProduct['quantity']) ? true : false;
			if ($validQuantity)
			{
				$dbTran = Yii::$app->db->beginTransaction();
				try {
					Record::addRecord($account, $product, $purchaseProduct->quantity);
					$product->deductQuantity($purchaseProduct->quantity);
					$dbTran->commit();
				} catch (\Exception $e) {
					$dbTran->rollBack();
				}
				
				return $this->redirect('wallet');
			}
			else
				$purchaseProduct->addError('quantity', 'Purchase quantity is over the quantity available!');
		}

		return $this->render('productpurchase', [
			'purchaseProduct' => $purchaseProduct,
			'product' => $product,
			'balance' => $balance,
		]);

	}

	public function buildProductSearchQuery($form)
	{
		$query = Product::find();

		if(!empty($form->code))
		{
			$query->andWhere(['like', 'code', $form->code]);
		}

		if(!empty($form->name))
		{
			$query->andWhere(['like', 'name', $form->name]);
		}

		// when min or max Amount is not empty
		if (!empty($form->min_quantity) || !empty($form->max_quantity)){

			if (empty($form->min_quantity))
				$form->min_quantity = $form->max_quantity;
			if (empty($form->max_quantity))
				$form->max_quantity = $form->min_quantity;

			$query->andFilterWhere(['between',
				'quantity', $form->min_quantity, $form->max_quantity
			]);
		}

		// when min or max Amount is not empty
		if (!empty($form->min_price) || !empty($form->max_price)){

			if (empty($form->min_price))
				$form->min_price = $form->max_price;
			if (empty($form->max_price))
				$form->max_price = $form->min_price;

			$query->andFilterWhere(['between',
				'price', $form->min_price, $form->max_price
			]);
		}

		return $query;
	}

	public function buildRecordSearchQuery($form)
	{
		$query = Record::find();

		/*
		if(!empty($form->code))
		{
			$query->andWhere(['like', 'product.code', $form->code]);
		}

		if(!empty($form->name))
		{
			$query->andWhere(['like', 'product.name', $form->name]);
		}*/

		if (!empty($form->min_quantity) || !empty($form->max_quantity)){

			if (empty($form->min_quantity))
				$form->min_quantity = $form->max_quantity;
			if (empty($form->max_quantity))
				$form->max_quantity = $form->min_quantity;

			$query->andFilterWhere(['between',
				'quantity', $form->min_quantity, $form->max_quantity
			]);
		}

		if (!empty($form->min_price) || !empty($form->max_price)){

			if (empty($form->min_price))
				$form->min_price = $form->max_price;
			if (empty($form->max_price))
				$form->max_price = $form->min_price;

			$query->andFilterWhere(['between',
				'price', $form->min_price, $form->max_price
			]);
		}

		if (!empty($form->min_total) || !empty($form->max_total)){

			if (empty($form->min_total))
				$form->min_total = $form->max_total;
			if (empty($form->max_total))
				$form->max_total = $form->min_total;

			$query->andFilterWhere(['between',
				'price', $form->min_total, $form->max_total
			]);
		}

		return $query;
	}
}
