<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product;


class ProductSearchForm extends Model
{
	public $code;
	public $name;
	public $min_quantity;
	public $max_quantity;
	public $min_price;
	public $max_price;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			[['code', 'name',], 'safe'],
			[['min_quantity', 'max_quantity'], 'integer'],
			[['min_price', 'max_price'], 'double'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'code' => 'Product Code',
			'name' => 'Product Name',
			'min_quantity' => 'Min Quantity',
			'max_quantity' => 'Max Quantity',
			'min_price' => 'Min Price',
			'min_price' => 'Max Price',
		];
	}

	/**
	 * @param _GET request $get
	 */
	public function setSearchCriteria($get)
	{
		(!empty($get['code']))		   ? $this->code = $get['code']					: '';
		(!empty($get['name']))		   ? $this->name = $get['name']					: '';
		(!empty($get['min_quantity'])) ? $this->min_quantity = $get['min_quantity'] : '';
		(!empty($get['max_quantity'])) ? $this->max_quantity = $get['max_quantity'] : '';
		(!empty($get['min_price']))	   ? $this->min_price = $get['min_price']		: '';
		(!empty($get['max_price']))	   ? $this->max_price = $get['max_price']		: '';
	}

	/**
	 * @return \yii\data\ActiveDataProvider
	 */
	public function search()
	{
		return new ActiveDataProvider([
			'query' =>	$this->query(),
			'pagination' => [
				// 'defaultPageSize' => 20,
				// 'pageSize' => 10,
			],
			'sort' => [
				'defaultOrder' => [
					'id' => SORT_DESC
				],
			],
		]);
	}

	protected function query()
	{
		$query = Product::find();

		if (!$this->validate()) {
			return $query->andWhere('1=0');
		}

		$query->andFilterWhere(['like', 'code', $this->code]);
		$query->andFilterWhere(['like', 'name', $this->name]);
		$query->andFilterWhere(['>=', 'quantity', $this->min_quantity]);
		$query->andFilterWhere(['<=', 'quantity', $this->max_quantity]);
		$query->andFilterWhere(['>=', 'price', $this->min_price]);
		$query->andFilterWhere(['<=', 'price', $this->max_price]);
		$query->andWhere(['deleted_at'=> null]);

		return $query;
	}
}
