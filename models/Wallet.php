<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wallets".
 *
 * @property int $id
 * @property string|null $type
 * @property float $amount
 * @property int $userid
 *
 * @property Users $user
 */
class Wallet extends \yii\db\ActiveRecord
{

    public static $wallets;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wallets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'string'],
            [['amount', 'userid'], 'required'],
            [['amount'], 'number'],
            [['userid'], 'integer'],
            [['userid'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['userid' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'amount' => 'Amount',
            'userid' => 'Userid',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'userid']);
    }

    public function fetchData($username)
    {
        $user = Account::find()
            ->where(['username' => $username])
            ->all();

        self::$wallets = Wallet::find()
            ->where(['userid' => $user['0']->id])
            ->all();

        return self::$wallets;

        /*foreach (self::$wallets as $wallet) {
            if ($wallet['type'] === "A")
            {
                self::$walletA = $wallet->amount;
            }
            else
            {
                self::$walletB = $wallet->amount;
            }
        }

        return array('walletA' => self::$walletA, 'walletB' => self::$walletB, 'wallets' => self::$wallets);*/
    }

    public function getWalletID($username, $type)
    {
        $user = Account::find()
            ->where(['username' => $username])
            ->all();
    }

    /**
     * @param float $amount
     * @return app\models\TransactionAR
     */
    public function add($amount)
    {
        $amount = round(abs($amount), 2); //Round : Prevent Floating issue
        //Prevent dirty read and write
        $this->updateCounters([
            'amount' => $amount,
        ]);

        $transaction = new TransactionAR();
        $transaction->userid = $this->userid;
        $transaction->outwalletid = $this->id;
        $transaction->inwalletid = $this->id;
        $transaction->amount = $this->amount;
        $transaction->date = date("Y-m-d H:i:sa");

        return $transaction;
    }

    /**
     * @param float $amount
     * @return app\models\TransactionAR
     */
    public function deduct($amount, $data = [])
    {
        $amount = round(abs($amount), 2);
        $this->updateCounters([
            'amount' => $amount * -1,
        ]);

        // $ip = ArrayHelper::getValue($data, 'ip', null);

        // if (Yii::$app instanceof \yii\web\Application) {
        //     $ip = Yii::$app->request->getUserIp();
        // }

        $transaction = new TransactionAR();
        $transaction->userid = $this->userid;
        $transaction->outwalletid = $this->id;
        $transaction->inwalletid = $this->id;
        $transaction->amount = $this->amount;
        // $transaction->remark = ArrayHelper::getValue($data, 'remark', '');
        // $transaction->ip     = $ip;
        $transaction->date = date("Y-m-d H:i:sa");

        return $transaction;
    }

    /*public function add($amount, $params = [])
    {
        if (empty($wallet)) {
            $wallet = new Wallet();
            $wallet->userid = $this->id;
            $wallet->type   = $type;
            $wallet->amount = 0;

            $wallet->save();
            $wallet->refresh();
        }
    }*/

    public function transfer($amount, $targetid, $params = [])
    {
        $current = Wallet::findOne(['id' => $this->id]);
        $current->amount -= $amount;
        $current->save();
        $current->refresh();

        $target = Wallet::findOne(['id' => $targetid]);
        $target->amount += $amount;
        $target->save();
        $target->refresh();

        Wallet::addNewTransaction($amount, $targetid);
    }

    public function addNewTransaction($amount, $targetid)
    {
        $transaction = new TransactionAR();
        $transaction->userid = $this->userid;
        $transaction->outwalletid = $this->id;
        $transaction->inwalletid = $targetid;
        $transaction->amount = $amount;
        date_default_timezone_set("Asia/Singapore");
        $transaction->date = date("Y-m-d H:i:sa");

        $transaction->save();
        $transaction->refresh();
    }
}
