<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ProductForm extends Model
{
	public $code;
	public $name;
	public $quantity;
	public $price;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			[['code', 'name', 'quantity', 'price'], 'required', 'on' => ['create', 'update']],

			[['quantity'], 'required', 'on' => 'purchase'],

			[['quantity'], 'integer', 'min' => 1],

			[['price'], 'double', 'min' => 1],

			[['quantity'], 'validateBalance', 'on' => 'purchase'],
		];
	}

	public function scenarios()
	{
		return [
			'create'   => ['code', 'name', 'quantity', 'price'],
			'update'   => ['code', 'name', 'quantity', 'price'],
			'purchase' => ['quantity'],
		];
	}

	public function validateBalance($attribute, $params)
	{
		if (!$this->hasErrors()) {
			$wallet = Wallet::findOne(['type' => 'A', 'userid' => Yii::$app->user->identity->id]);

			if (($this->quantity * $this->price) > $wallet->amount)
				$this->addError($attribute, 'Your balance is not enough for the purchase!');
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'code' => 'Product Code',
			'name' => 'Product Name',
			'quantity' => 'Quantity',
			'price' => 'Price',
		];
	}

	public function setProductData($data)
	{
		(!empty($data['code']))		? $this->code = $data['code']		  : '';
		(!empty($data['name']))		? $this->name = $data['name']		  : '';
		(!empty($data['quantity'])) ? $this->quantity = $data['quantity'] : '';
		(!empty($data['price']))	? $this->price = $data['price']		: '';
	}
}
