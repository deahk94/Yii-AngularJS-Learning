<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property int $userid
 * @property int $outwalletid
 * @property int $inwalletid
 * @property float|null $amount
 *
 * @property Users $user
 * @property Wallets $outwallet
 * @property Wallets $inwallet
 */
class TransactionAR extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid', 'outwalletid', 'inwalletid'], 'required'],
            [['userid', 'outwalletid', 'inwalletid'], 'integer'],
            [['amount'], 'double'],
            [['userid'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['userid' => 'id']],
            // [['outwalletid'], 'exist', 'skipOnError' => true, 'targetClass' => Wallet::className(), 'targetAttribute' => ['outwalletid' => 'id']],
            // [['inwalletid'], 'exist', 'skipOnError' => true, 'targetClass' => Wallet::className(), 'targetAttribute' => ['inwalletid' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => 'Userid',
            // 'outwalletid' => 'Outwalletid',
            // 'inwalletid' => 'Inwalletid',
            'amount' => 'Amount',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Account::className(), ['id' => 'userid']);
    }

    public function getWalletById($walletid)
    {
        return Wallet::findOne(['id' => $walletid]);
    }

    // /**
    //  * Gets query for [[Outwallet]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getOutwallet()
    // {
    //     return $this->hasOne(Wallets::className(), ['id' => 'outwalletid']);
    // }

    // /**
    //  * Gets query for [[Inwallet]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getInwallet()
    // {
    //     return $this->hasOne(Wallets::className(), ['id' => 'inwalletid']);
    // }
}
