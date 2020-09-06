<?php

namespace api\models;

use Yii;
use yii\base\Model;

class PackagesServicesModel extends Model
{
    public $db;
    public $datetime;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->db       = Yii::$app->db;
    }

    public static function getPackages($type = 1)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT * FROM `site_packages` WHERE `type`=:type and status=1",[":type" => $type])->queryAll();
        return $result;
    }

    public static function getServices($type = 1)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT * FROM `site_services` WHERE `type`=:type and status=1",[":type" => $type])->queryAll();
        return $result;
    }



}
