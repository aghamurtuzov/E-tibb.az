<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "site_transaction".
 *
 * @property int $id
 * @property int $order_id
 * @property int $connect_id
 * @property int $type 0 - User | 1 - Doctor | 2 - Enterprise
 * @property string $log_data
 * @property string $return_data
 * @property double $total_price
 * @property string $created_date
 * @property string $payment_date
 * @property int $payment_method 0 - cash | 1 - card
 * @property int $status
 */
class SiteTransaction extends \yii\db\ActiveRecord
{

    const TYPE_USER = 0;
    const TYPE_DOCTOR = 1;
    const TYPE_ENTERPRISE = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'connect_id', 'type', 'log_data', 'total_price', 'created_date', 'payment_method'], 'required'],
            [['order_id', 'connect_id', 'type', 'payment_method', 'status'], 'integer'],
            [['log_data', 'return_data'], 'string'],
            [['total_price'], 'number'],
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
            'log_data' => 'Log Data',
            'return_data' => 'Return Data',
            'total_price' => 'Total Price',
            'created_date' => 'Created Date',
            'payment_date' => 'Payment Date',
            'payment_method' => 'Payment Method',
            'status' => 'Status',
        ];
    }
}
