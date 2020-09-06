<?php
namespace api\models;

use Yii;
use yii\base\Model;

class SitePhoneNumbersModel extends Model
{
    public $db;
    public $datetime;
    public $menus;
    public static $TYPE_PHONE = 0;
    public static $TYPE_MOBILE = 1;
    public static $TYPE_WP = 2;
    public static $tableName = 'site_phone_numbers';
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->datetime = Yii::$app->params['current.date'].' '.Yii::$app->params['current.time'];
        $this->db       = Yii::$app->db;
    }
//    public static function getPhones($connect_id,$type=1)
//    {
//        $db = Yii::$app->db;
//        $substr = $db->createCommand("SELECT `number` FROM `".self::$tableName."` WHERE `connect_id`=:connect_id  and `type`=:type",[":connect_id" => $connect_id,":type" => $type])->queryAll();
//
//        if (!empty($substr)){
//            return  $result = $db->createCommand("SELECT id,`number`, SUBSTR(`number`,4) as `num`,`number_type`,`type` FROM `".self::$tableName."` WHERE `connect_id`=:connect_id  and `type`=:type",[":connect_id" => $connect_id,":type" => $type])->queryAll();
//        }
//
//        return $result = $db->createCommand("SELECT id,`number`,`number_type`,`type` FROM `".self::$tableName."` WHERE `connect_id`=:connect_id  and `type`=:type",[":connect_id" => $connect_id,":type" => $type])->queryAll();;
//    }

    public static function getPhones($connect_id,$type=1,$number_type = null)
    {
        $db = Yii::$app->db;
        if(empty($number_type))
        {
            $result = $db->createCommand("SELECT id,`number`,number_type,type FROM ".self::$tableName." WHERE connect_id=:connect_id  and type=:type",[":connect_id" => $connect_id,":type" => $type])->queryAll();
        }else{
            $result = $db->createCommand("SELECT id,`number`,number_type,type FROM ".self::$tableName." WHERE connect_id=:connect_id  and type=:type and number_type=:number_type",[":connect_id" => $connect_id,":type" => $type,':number_type'=>$number_type])->queryAll();
        }
        return $result;
    }


}