<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%record}}".
 *
 * @property int $id
 * @property int $by_user
 * @property int $product_id
 * @property int|null $quantity
 * @property float|null $price
 * @property float|null $total_price
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Product $product
 * @property Users $byUser
 */
class Record extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%record}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['by_user', 'product_id'], 'required'],
            [['by_user', 'product_id', 'quantity'], 'integer'],
            [['price', 'total_price'], 'double'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
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
            'product_id' => Yii::t('app', 'Product ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'price' => Yii::t('app', 'Price'),
            'total_price' => Yii::t('app', 'Total Price'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
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

    public function addRecord(Account $account, Product $product, $quantity)
    {
        $record = new Record();
        $record->by_user = $account->id;
        $record->product_id = $product->id;
        $record->quantity = $quantity;
        $record->price = $product->price;
        $record->total_price = $product->price * $quantity;

        $record->save();
        $record->refresh();

        $wallet = $account->getWallet('A');
        $wallet->deduct($record->total_price, [
            'remark' => 'asdfasdf',
        ]);

        return $record;
    }
}
