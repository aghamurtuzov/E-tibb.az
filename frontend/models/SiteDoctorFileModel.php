<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class SiteDoctorFileModel extends Model
{
    public $db;
    public $datetime;
    public $menus;
    public static $tableName = 'site_doctor_files';

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->datetime = Yii::$app->params['current.date'].' '.Yii::$app->params['current.time'];
        $this->db       = Yii::$app->db;

    }

    public static function getFiles($connect_id,$type=1)
    {
        $files = Yii::$app->db->createCommand("SELECT `id`,`file_photo` FROM `".self::$tableName."` WHERE `type`=$type and `connect_id`=$connect_id ORDER BY `id` DESC")->cache(30)->queryAll();

        return $files;
    }

}
