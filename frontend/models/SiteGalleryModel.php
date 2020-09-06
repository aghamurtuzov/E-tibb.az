<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class SiteGalleryModel extends Model
{
    public $db;
    public $datetime;
    public $menus;
    public static $tableName = 'site_gallery';

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->datetime = Yii::$app->params['current.date'].' '.Yii::$app->params['current.time'];
        $this->db       = Yii::$app->db;

    }

    public static function getGallery($connect_id,$type=1)
    {
        $gallery = Yii::$app->db->createCommand("SELECT `id`,`photo` FROM `".self::$tableName."` WHERE `type`=$type and `connect_id`=$connect_id ORDER BY `position` ASC")->cache(120)->queryAll();

        return $gallery;
    }

}
