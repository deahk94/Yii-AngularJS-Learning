<?php

namespace app\models;

use Yii;
use Lcobucci\JWT;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $authKey
 * @property string $accessToken
 */

class Account extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    private static $users;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'authKey', 'accessToken'], 'required'],
            [['username', 'password', 'authKey'], 'string', 'max' => 50],
            [['accessToken'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'authKey' => 'Auth Key',
            'accessToken' => 'Access Token',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return Account::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        //Yii::warning($token);
        $token = self::parseStringToToken($token);
        $signer = new Sha256();
        
        if($token && $token->verify($signer, 'test'))
        {
            $data = new ValidationData();
            $data->setIssuer('webstore');
            $data->setAudience('product');

            if(!$token->validate($data) || !$token->hasClaim('jti')) //invalid or no claim
                return false;

            $id = $token->getClaim('jti');

            return Account::findOne($id);
        }
        else
            return false;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username, $password)
    {
        $result = Account::find()
            ->andWhere(['username' => $username])
            ->andWhere(['password' => $password])
            ->one();

        if (strcasecmp($result->username, $username) === 0)
        {
            return $result;
            //return new static($result); //Will create new instance, $result->save() will insert new row
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return true;
        //return $this->password === $password;
    }

    public function getUsername()
    {
        return $this->username;
    }

    /**
     * return wallet instance
     * @param string $type walletType
     * @return \app\models\Wallet
     */
    public function getWallet($type)
    {
        $query = Wallet::find();
        $query->andWhere([
            'userid' => $this->id,
            'type'   => $type,
        ]);

        $wallet = $query->one();

        if (empty($wallet)) {
            $wallet = new Wallet();
            $wallet->userid = $this->id;
            $wallet->type   = $type;
            $wallet->amount = 0;

            $wallet->save();
            $wallet->refresh();
        }

        return $wallet;
    }

    public function setAccessToken($token)
    {
        $this->accessToken = $token;
        //throw new \Exception(var_export($this, true), 1);
        $this->update();
        $this->refresh();
    }

    /**
     * @param string $string
     * @return Lcobucci/JWT/Token|false
     */
    private static function parseStringToToken($string)
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
}
