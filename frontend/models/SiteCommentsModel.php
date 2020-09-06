<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class SiteCommentsModel extends Model
{
    public $db;
    public $datetime;
    public $menus;
    public static $TYPE_PHONE = 0;
    public static $TYPE_MOBILE = 1;
    public static $TYPE_WP = 2;
    public static $tableName = 'site_comments';

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->datetime = Yii::$app->params['current.date'].' '.Yii::$app->params['current.time'];
        $this->db       = Yii::$app->db;

    }

    public static function getCommentsCount($connect_id,$type=1)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT count(id) FROM `".self::$tableName."` WHERE `connect_id`=:connect_id  and `type`=:type and status=1",[":connect_id" => $connect_id,":type" => $type])->cache(120)->queryScalar();

        return $result;
    }


    public static function getComments($connect_id,$type=1,$page=0)
    {
        $db = Yii::$app->db;
        $limit = Yii::$app->params["site.hospital_comment_count"];
        $offset = $page*$limit;
        $result = $db->createCommand("SELECT id,`name`,`email`,`comment`,`datetime`,`positive`,`rating` FROM `".self::$tableName."` WHERE `connect_id`=:connect_id  and `type`=:type and status=1 ORDER BY id DESC LIMIT $offset,$limit",[":connect_id" => $connect_id,":type" => $type])->cache(120)->queryAll();

        return $result;
    }

}
