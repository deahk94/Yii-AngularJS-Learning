<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ProductPurchaseForm extends Model
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
            [['code', 'name', 'quantity', 'price'], 'required'],
            [['quantity'], 'integer', 'min' => 1],
            [['price'], 'double', 'min' => 1],
            [['quantity'], 'validateBalance'],
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
            'quantity' => 'Quantity',
            'price' => 'Price',
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
}
