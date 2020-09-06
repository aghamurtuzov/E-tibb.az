<?php
/**
 * Created by PhpStorm.
 * User: Chingiz
 * Date: 5/2/2019
 * Time: 11:41 AM
 */

namespace backend\models;

use Yii;
use backend\models\SiteDoctors;
use backend\models\SiteEnterprises;

class SiteTransactions extends \yii\db\ActiveRecord
{
    public $db;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_transaction';
    }

    public static function getType()
    {
        return [
            0 => 'İstifadəçi',
            1 => 'Həkim',
            2 => 'Obyekt'
        ];
    }

    public static function getOrderedServices($connect_id)
    {   $db = Yii::$app->db;
        $data = $db->createCommand("SELECT * FROM `site_transaction` WHERE `connect_id`=$connect_id")->queryOne();
        //return print_r($connected);
        if(!empty($data))
        {
            return $data;
        }
        return 'Yoxdur';
    }

    public static function getAllOrderedServices($connect_id)
    {   $db = Yii::$app->db;
        $data = $db->createCommand("SELECT * FROM `site_transaction_details` WHERE `connect_id`=$connect_id")->queryAll();
        //return print_r($connected);
        if(!empty($data))
        {
            return $data;
        }
        return false;
    }

    public static function getConnect($type,$connect_id)
    {
        if( $type == 1)
        {
            $connected = SiteDoctors::find()->where(['id' => $connect_id])->one();
        }
        else if( $type== 2 )
        {
            $connected = SiteEnterprises::find()->where(['id' => $connect_id])->one();
        }
        else if( $type== 2 )
        {
            $connected = SiteUsers::find()->where(['id' => $connect_id])->one();
        }
        if(!empty($connected))
        {
            return $connected->name;
        }
        return 'Yoxdur';
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