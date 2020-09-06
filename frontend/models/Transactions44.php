<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "site_transactions".
 *
 * @property int $id
 * @property int $user_id
 * @property int $connect_id
 * @property int $type 	1 - Doctor | 2 - Enterprise
 * @property string $products
 * @property string $create_date
 * @property string $pay_date
 * @property double $price
 * @property int $pay_method 0=>cash,1=>card
 * @property int $status
 */
class Transactions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_transactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'connect_id', 'type', 'pay_method', 'status'], 'integer'],
            [['products', 'create_date', 'price'], 'required'],
            [['create_date', 'pay_date'], 'safe'],
            [['price'], 'number'],
            [['products'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'connect_id' => 'Connect ID',
            'type' => 'Type',
            'products' => 'Products',
            'create_date' => 'Create Date',
            'pay_date' => 'Pay Date',
            'price' => 'Price',
            'pay_method' => 'Pay Method',
            'status' => 'Status',
        ];
    }
}
