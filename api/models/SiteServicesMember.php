<?php

namespace api\models;

use Yii;
use api\models\SiteDoctors;
use api\models\SiteEnterprises;

/**
 * This is the model class for table "site_services_member".
 *
 * @property int $id
 * @property int $order_id
 * @property int $connect_id
 * @property int $type 0 - User | 1 - Doctor | 2 - Enterprise
 * @property int $service_id
 * @property string $payment_date
 * @property string $expires_date
 */
class SiteServicesMember extends \yii\db\ActiveRecord
{
    public $db;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_services_member';
    }

    public static function getType()
    {
        return [
            0 => 'İstifadəçi',
            1 => 'Həkim',
            2 => 'Obyekt'
        ];
    }

    public static function getServices($services_id)
    {   $db = Yii::$app->db;
        $connected = $db->createCommand("SELECT * FROM site_services WHERE `id`=$services_id")->queryOne();
        //return print_r($connected);
        if(!empty($connected))
        {
            return $connected['name'];
        }
        return 'Yoxdur';
    }

    public static function getAllServices()
    {   $db = Yii::$app->db;
        $connected = $db->createCommand("SELECT * FROM site_services")->queryAll();
        //return print_r($connected);
        if(!empty($connected))
        {
            return $connected;
        }
        return 'Yoxdur';
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
            [['order_id', 'connect_id', 'type', 'service_id', 'payment_date', 'expires_date'], 'required'],
            [['order_id', 'connect_id', 'type', 'service_id'], 'integer'],
            [['payment_date', 'expires_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order',
            'connect_id' => 'Əlaqəçi',
            'type' => 'Tip',
            'service_id' => 'Xidmət',
            'payment_date' => 'Ödəniş tarixi',
            'expires_date' => 'Bitiş tarixi',
        ];
    }
}
