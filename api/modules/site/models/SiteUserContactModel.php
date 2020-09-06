<?php

namespace api\modules\general\models;

use Yii;
use yii\base\Model;

class SiteUserContactModel extends Model
{
    public $db;
    public $datetime;
    public $menus;
    public static $tableName = 'site_user_contact';

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    public static function getEmails($connect_id,$user_type=1)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT * FROM `".self::$tableName."` WHERE `connect_id`=:connect_id  AND `user_type`=:user_type AND `type`=:type",[":connect_id" => $connect_id,":user_type" => $user_type,'type'=>3])->queryAll();
        return $result;
    }


}
