<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class SiteSliderModel extends Model
{
    public $db;
    public $datetime;
    public static $tableName = 'site_slider';

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->datetime = Yii::$app->params['current.date'].' '.Yii::$app->params['current.time'];
        $this->db       = Yii::$app->db;
    }

    public static function getSlider($status)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT * FROM `".self::$tableName."` WHERE `status`=:status ORDER BY id DESC LIMIT 5",[":status" => $status])->cache(120)->queryAll();

        return $result;
    }

}
