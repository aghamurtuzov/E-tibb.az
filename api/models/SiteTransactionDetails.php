<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "site_transaction_details".
 *
 * @property int $id
 * @property int $order_id
 * @property int $connect_id
 * @property int $type 0 - User | 1 - Doctor | 2 - Enterprise
 * @property int $payment_type
 * @property int $payment_info
 * @property int $quantity
 * @property double $price
 * @property string $created_date
 * @property string $payment_date
 * @property int $payment_method 0 - cash | 1 - card
 * @property int $status
 */
class SiteTransactionDetails extends \yii\db\ActiveRecord
{

    const TYPE_USER = 0;
    const TYPE_DOCTOR = 1;
    const TYPE_ENTERPRISE = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_transaction_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'connect_id', 'type', 'payment_type', 'payment_info', 'quantity', 'price', 'created_date', 'payment_method'], 'required'],
            [['order_id', 'connect_id', 'type', 'payment_type', 'payment_info', 'quantity', 'payment_method', 'status'], 'integer'],
            [['price'], 'number'],
            [['created_date', 'payment_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'connect_id' => 'Connect ID',
            'type' => 'Type',
            'payment_type' => 'Payment Type',
            'payment_info' => 'Payment Info',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'created_date' => 'Created Date',
            'payment_date' => 'Payment Date',
            'payment_method' => 'Payment Method',
            'status' => 'Status',
        ];
    }
}
