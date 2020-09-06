<?php
namespace frontend\models;
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
    public static function getPhones($connect_id,$type=1)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT id,`number`,`number_type` FROM `".self::$tableName."` WHERE `connect_id`=:connect_id  and `type`=:type",[":connect_id" => $connect_id,":type" => $type])->cache(120)->queryAll();
        return $result;
    }
}