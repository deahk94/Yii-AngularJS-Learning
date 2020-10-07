<?php

namespace app\controllers\api;

use Yii;
use yii\filters\AccessControl;
use Lcobucci\JWT;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use app\models\Account;
use app\models\Product;
use app\models\ProductForm;
use app\models\ProductSearchForm;
use app\models\Record;
use app\models\RecordSearchForm;

class ProductController extends \yii\rest\Controller
{
	public function behaviors()
	{
		$behaviors = parent::behaviors();

		$behaviors['authenticator']['authMethods'] = [
			[
				'class'=> 'yii\filters\auth\QueryParamAuth',
				'tokenParam' => 'token',
			]
		];

		$behaviors['authenticator']['except'] = [
			'login',
		];

		$behaviors['rules'] = [
			'class' => AccessControl::className(),
			'except' => ['login'],
			'rules' => [
				[
					'allow' => true,
					'roles' => ['@'],
				],
			],
		];

		return $behaviors;
	}

	public function actionP()
	{
		return [
			'payload' => Yii::$app->request->post(),
		];
	}

	public function actionValidate()
	{
		$token = Yii::$app->request->post('token');

		return [
			'status' => $this->tokenValidation($token),
		];
	}

	public function actionVerify()
	{
		$token = Yii::$app->request->post('token');

		return [
			'status' => $this->tokenVerification($this->parseStringToToken($token)),
		];
	}

	/**
	 * @return data $reponse Contain either 'token' or 'error'
	 */
	public function actionLogin()
	{
		$data = Yii::$app->request->post();

		if ($data)
		{
			$account = Account::findByUsername($data['username'], $data['password']);

			if ($account) //Not using session, Yii::$app->user->login($account)
			{
				//Yii::$app->user->identity = $account;
				
				$token = (string)$this->createToken($account->id);
				$account->setAccessToken($token);

				return [
					'token' => $token,
				];
			}
			else
			{
				return [
					'error' => 'Login failed',
				];
			}
		}
		else
		{
			return [
				'error' => 'Missing post data',
			];
		}
	}

	/**
	 * @return data $response Contain both 'wallet' and 'product_list'
	 */
	public function actionWallet()
	{		
		$account = Yii::$app->user->identity;
		$dataProvider = $this->actionListProduct();

		return [
			'wallet' => [
				'type' => 'A',
				'amount' => $account->getWallet('A')->amount,
			],
			'product_list' => $dataProvider,
		];
	}

	/**
	 * Previously access via product/index
	 * @return yii\data\ActiveDataProvider
	 */
	public function actionListProduct()
	{
		$get = Yii::$app->request->get();

		$search = new ProductSearchForm();
		$search->setSearchCriteria($get);
		$dataProvider = $search->search();

		return $dataProvider;
	}

	/**
	 * Previously access via product/panel
	 * @return yii\data\ActiveDataProvider|error
	 */
	public function actionListRecord()
	{
		$get = Yii::$app->request->get();

		$search = new RecordSearchForm();
		$search->setSearchCriteria($get);

		if (isset($get['isAdmin']) && json_decode($get['isAdmin']) === false)
			$search->by_user = $this->getIdFromToken($get['token']);
		// throw new \Exception(var_export($search, 1), 1);

		$dataProvider = $search->search();
		$models = $dataProvider->getModels();
		$recordList = new \ArrayObject();

		foreach ($models as $model) {
			$product = $model->product;

			$obj = (object)$model->attributes;
			$obj->product_name = $product->name;
			$obj->product_code = $product->code;
			$recordList->append($obj);
		}

		return $recordList;
	}

	/**
	 * @return app\modes\Product|response Contain 'error'
	 */
	public function actionAddProduct()
	{
		$post = Yii::$app->request->post();
		$addProduct = new ProductForm();
		$addProduct->scenario = 'create';
		$addProduct->setProductData($post);

		if (!$addProduct->validate())
			return [
				'error' => $addProduct->getErrors()
			];

		return Product::addProduct($addProduct);
	}

	/**
	 * 'product_id' changed from _GET to _POST
	 * @return app\modes\Product|response Contain 'error'
	 */
	public function actionUpdateProduct()
	{
		$post = Yii::$app->request->post();
		$updateProduct = new ProductForm();
		$updateProduct->scenario = 'update';
		$updateProduct->setProductData($post);

		if (!$updateProduct->validate())
			return [
				'error' => $updateProduct->getErrors()
			];

		$product = Product::findOne(['id' => $post['id']]);

		return $product->updateProduct($updateProduct);
	}

	/**
	 * 'product_id' changed from _GET to _POST
	 * @return app\modes\Product
	 */
	public function actionDeleteProduct()
	{
		$post = Yii::$app->request->post();

		$product = Product::findOne(['id' => $post['id']]);
		$product->deleteProduct();

		return $product;
	}

	/**
	 * 'product_id' changed from _GET to _POST
	 * @return object app\modes\Record|error
	 */
	public function actionPurchaseProduct()
	{
		//Set form data
		$post = Yii::$app->request->post();
		$purchaseProduct = new ProductForm();
		$purchaseProduct->scenario = 'purchase';
		$purchaseProduct->setProductData($post);
		$purchaseProduct->validate();

		$account = Account::findIdentity($this->getIdFromToken($post['token']));
		$product = Product::findOne(['id' => $post['id']]);
		//Out of form validation
		$validQuantity = ($product->quantity >= $purchaseProduct['quantity']) ? true : false;
				
		// When quantity is valid, create record and deduct product quantity
		if ($validQuantity && !$purchaseProduct->hasErrors())
		{
			$dbTran = Yii::$app->db->beginTransaction();
			try {
				$record = Record::addRecord($account, $product, $purchaseProduct->quantity);
				$product->deductQuantity($purchaseProduct->quantity);
				$dbTran->commit();

				return $record;
			} catch (\Exception $e) {
				$dbTran->rollBack();
				return ['error' => 'Database error!'];
			}
		}
		else
		{
			if (!$validQuantity)
				$purchaseProduct->addError('quantity', 'Purchase quantity is over the quantity available!');

			return ['error' => $purchaseProduct->getErrors()]; //Note : Can implement to show form's validation error, remember to form->validate()
		}

	}

	/**
	 * @param string $key
	 * @return Lcobucci\JWT\Token
	 */
	private function createToken($userid, $key = 'test')
	{
		$account = Yii::$app->user->identity;
		$signer = new Sha256();
		$time = time();

		$token = (new Builder())->issuedBy('webstore') // Configures the issuer (iss claim)
		                        ->setAudience('product') // Configures the audience (aud claim)
		                        ->identifiedBy($userid, true) // Configures the id (jti claim), replicating as a header item
		                        ->issuedAt($time) // Configures the time that the token was issue (iat claim)
		                        ->canOnlyBeUsedAfter($time) // Configures the time that the token can be used (nbf claim)
		                        ->expiresAt($time + 6000) // Configures the expiration time of the token (exp claim)
		                        //->setRegisteredClaim('uid', 1, true) // Configures a new claim, called "uid"
		                        ->sign($signer, new Key($key))
		                        ->getToken(); // Retrieves the generated token

		return $token;
	}

	/**
	 * @param string $string
	 * @return boolean
	 */
	private function getTokenAndVerify()
	{
		$stringToken = Yii::$app->request->post('token');

		$token = $this->parseStringToToken($stringToken);

		if(!$token)
			return ['error' => 'Bad/Missing token'];
		else
			return $this->tokenVerification($token);
	}

	/**
	 * @param string $string
	 * @return Lcobucci/JWT/Token|false
	 */
	private function parseStringToToken($string)
	{
		$token = false;

		// Possible to receive broken string or wrong format
		try
		{
			$token = (new Parser())->parse((string) $string); // Parses from a string
		}
		catch (\Exception $exception)
		{
			return $token;
		}

		return $token;
	}

	/**
	 * @param string $token
	 * @return boolean
	 */
	private function tokenValidation($token)
	{
		$data = new ValidationData();
		$data->setIssuer('webstore');
		$data->setAudience('product');

		$token = $this->parseStringToToken($token);

		$valid = false;
		if ($token)
			$valid = $token->validate($data);

		return $valid;
	}

	/**
	 * @param string $token
	 * @param string $key
	 * @return boolean
	 */
	private function tokenVerification($token, $key = 'test')
	{
		$signer = new Sha256();

		return $token->verify($signer, new Key($key));
	}

	private function getIdFromToken ($token)
	{
		$token = $this->parseStringToToken($token);
		return $token->getClaim('jti');
	}
}
