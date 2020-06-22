<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SearchForm extends Model
{
	public $walletType;
	public $minAmount;
	public $maxAmount;

	/**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            //['wallettype', 'each', 'rule' => ['in', 'range' => ['A', 'B']]],
            [['minAmount', 'maxAmount'], 'double'],
            [['walletType'], 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'walletType' => 'Wallet Type (OUT)',
            'minAmount' => 'Minimum Amount',
            'maxAmount' => 'Maximum Amount',
        ];
    }
}