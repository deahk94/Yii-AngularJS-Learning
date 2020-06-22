<?php

namespace app\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property int $id
 * @property int $by_user
 * @property string|null $code
 * @property string|null $name
 * @property int|null $quantity
 * @property int|null $price
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Users $byUser
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['by_user'], 'required'],
            [['by_user', 'quantity'], 'integer'],
            [['price'], 'double'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['code', 'name'], 'string', 'max' => 64],
            [['by_user'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['by_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'by_user' => Yii::t('app', 'By User'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'quantity' => Yii::t('app', 'Quantity'),
            'price' => Yii::t('app', 'Price'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    /**
     * Gets query for [[ByUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getByUser()
    {
        return $this->hasOne(Account::className(), ['id' => 'by_user']);
    }

    // Add product into table
    public function addProduct($data)
    {
        $userid = Yii::$app->user->identity->id;

        $product = new Product();
        $product->by_user = $userid;
        $product->code = $data->code;
        $product->name = $data->name;
        $product->quantity = $data->quantity;
        $product->price = $data->price;

        $product->save();
        $product->refresh();

        return $product;
    }

    // Update product based on form input
    public function updateProduct($data)
    {
        $this->code = $data->code;
        $this->name = $data->name;
        $this->quantity = $data->quantity;
        $this->price = $data->price;

        $this->save();
        $this->refresh();

        return $this;
    }

    public function deleteProduct()
    {
        $this->deleted_at = new Expression('NOW()');

        $this->save();
        $this->refresh();

        return $this;
    }

    public function deductQuantity($quantity)
    {
        $quantity = round(abs($quantity), 2); //Round : Prevent Floating issue
        //Prevent dirty read and write
        $this->updateCounters([
            'quantity' => $quantity * -1,
        ]);

        return $this;
    }

}
