<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class SiteSpecialistsModel extends Model
{
    public $db;
    public $datetime;
    public static $tableName = 'site_specialists';

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->datetime = Yii::$app->params['current.date'].' '.Yii::$app->params['current.time'];
        $this->db       = Yii::$app->db;

    }

    public static function getSpecialists()
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT `id`,`name`,`icon`,`description`,`slug` FROM `".self::$tableName."` order by `name`")->cache(120)->queryAll();
        return $result;
    }

    public static function getSpecialistSearch()
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT `id`,`name`,`description`,`slug` FROM `".self::$tableName."` order by `name`")->cache(120)->queryAll();
        return $result;
    }


}
