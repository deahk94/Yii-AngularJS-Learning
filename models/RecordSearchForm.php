<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class RecordSearchForm extends Model
{
	public $by_user;
	public $code;
	public $name;
	public $min_quantity;
	public $max_quantity;
	public $min_price;
	public $max_price;
	public $min_total;
	public $max_total;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			[['code', 'name', 'by_user'], 'safe'],
			[['min_quantity', 'max_quantity'], 'integer'],
			[['min_price', 'max_price', 'min_total', 'max_total'], 'double'],
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
			'min_total' => 'Min Total',
			'max_total' => 'Max Total',
		];
	}

	/**
	 * @param _GET request $get
	 */
	public function setSearchCriteria($get)
	{
		(!empty($get['code']))         ? $this->code = $get['code']                 : '';
		(!empty($get['name']))         ? $this->name = $get['name']                 : '';
		(!empty($get['min_quantity'])) ? $this->min_quantity = $get['min_quantity'] : '';
		(!empty($get['max_quantity'])) ? $this->max_quantity = $get['max_quantity'] : '';
		(!empty($get['min_price']))    ? $this->min_price = $get['min_price']       : '';
		(!empty($get['max_price']))    ? $this->max_price = $get['max_price']       : '';
		(!empty($get['min_total']))    ? $this->min_total = $get['min_total']       : '';
		(!empty($get['max_total']))    ? $this->max_total = $get['max_total']       : '';
	}
	/**
	 * @return \yii\data\ActiveDataProvider
	 */
	public function search()
	{
		return new ActiveDataProvider([
			'query' =>  $this->query(),
			'pagination' => [
		        'pageSize' => "0"
		    ],
			'sort' => [
				'defaultOrder' => [
					'id' => SORT_DESC
				],
			],
		]);
	}

	public function query()
	{
		$query = Record::find();
		$query->alias('t');

		if (!$this->validate()) {
			return $query->andWhere('1=0');
		}

		// Search by product name and code
		$query->leftJoin('{{%product}} p', 'p.id = t.product_id');

		// $query->joinWith([
		//     'product' => function ($query) {
		//         $query->andFilterWhere(['code' => $this->code]);
		//     }
		// ]);

		$query->andFilterWhere(['like', 'p.code', $this->code]);
		$query->andFilterWhere(['like', 'p.name', $this->name]);
		$query->andFilterWhere(['t.by_user' => $this->by_user]);
		$query->andFilterWhere(['>=', 't.quantity', $this->min_quantity]);
		$query->andFilterWhere(['<=', 't.quantity', $this->max_quantity]);
		$query->andFilterWhere(['>=', 't.price', $this->min_price]);
		$query->andFilterWhere(['<=', 't.price', $this->max_price]);
		$query->andFilterWhere(['>=', 't.total_price', $this->min_total]);
		$query->andFilterWhere(['<=', 't.total_price', $this->max_total]);
		
		return $query;
	}
}
