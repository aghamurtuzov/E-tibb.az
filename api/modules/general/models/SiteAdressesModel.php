<?php

namespace api\modules\general\models;

use Yii;
use yii\base\Model;

class SiteAdressesModel extends Model
{
    public $db;
    public $datetime;
    public $menus;
    public static $tableName = 'site_addresses';

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->datetime = Yii::$app->params['current.date'].' '.Yii::$app->params['current.time'];
        $this->db       = Yii::$app->db;

    }


    public static function getAddress($connect_id,$type=1)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT id,address FROM `".self::$tableName."` WHERE `connect_id`=:connect_id  and `type`=:type",[":connect_id" => $connect_id,":type" => $type])->queryOne();
        if($result){
            $address = $result["address"];
        }else{
            $address = Yii::$app->params['site.not_found'];
        }
        return $address;
    }

    public static function getAddresses($connect_id,$type=1)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT `id`,`address` as name FROM `".self::$tableName."` WHERE `connect_id`=:connect_id  and `type`=:type",[":connect_id" => $connect_id,":type" => $type])->queryAll();
        return $result;
    }

    public static function getMobilePhones($connect_id,$type=1)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT `id`,`number`,`number_type`  FROM `site_phone_numbers` WHERE `connect_id`=:connect_id  and `type`=:type",[":connect_id" => $connect_id,":type" => $type])->queryAll();
        return $result;
    }


}
