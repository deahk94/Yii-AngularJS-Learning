<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Transaction extends Model
{
    public $amount;
    public $username;
    public $type;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // amount is required
            [['amount'], 'required'],
            [['amount'], 'double', 'min' => 1],

            // [['amount'], 'double', 'max' => function() {
            //     $userid = (Account::findOne(['username' => $username]))->id;
            //     $amount = (Wallet::find()->AndWhere(['type' => $type, 'userid' => $userid])->one())->amount;
                
            //     return (float)$amount;
            // }],

            // username, type is set as safe to make sure that the variable is populated
            [['username', 'type'], 'safe'],

            // validation on possible amount
            [['amount'], 'validateAmount'],
        ];
    }

    public function validateAmount($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $userid = Account::findOne(['username' => $this->username])->id;
            $walletid = Wallet::findOne(['type' => $this->type, 'userid' => $userid])->id;
            $walletBalance = TransactionAR::getWalletById($walletid)->amount;
            
            if ($this->amount > $walletBalance)
                $this->addError($attribute, 'Amount to transfer is move than Wallet Balance!');
        }
    }

    public function attributeLabels()
    {
        return [
            'amount' => 'Transaction Amount',
        ];
    }
}
