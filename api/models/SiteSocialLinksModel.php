<?php

namespace api\models;

use Yii;
use yii\base\Model;

class SiteSocialLinksModel extends Model
{
    public $db;
    public $datetime;
    public static $tableName = 'site_sosial_links';
    public static $LINK_TYPE_FACEBOOK = 0;
    public static $LINK_TYPE_INSTAGRAM = 1;
    public static $LINK_TYPE_YOUTUBE = 2;
    public static $LINK_TYPE_TWITTER = 3;
    public static $LINK_TYPE_LINKEDIN = 4;

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->datetime = Yii::$app->params['current.date'].' '.Yii::$app->params['current.time'];
        $this->db       = Yii::$app->db;

    }


    public static function getSocialLinks($connect_id,$type=1)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT id,link,`link_type`,`type` FROM `".self::$tableName."` WHERE `connect_id`=:connect_id and `type`=:type",[":connect_id" => $connect_id,":type" => $type])->queryAll();
        return $result;
    }

    public static function generateSocialsArray($social_links)
    {
        $socials = [];
        foreach ($social_links as $link){
            if($link["link_type"]==SiteSocialLinksModel::$LINK_TYPE_FACEBOOK){
                $socials["facebook"] = $link["link"];
            }elseif($link["link_type"]==SiteSocialLinksModel::$LINK_TYPE_INSTAGRAM){
                $socials["instagram"] = $link["link"];
            }elseif($link["link_type"]==SiteSocialLinksModel::$LINK_TYPE_YOUTUBE){
                $socials["youtube"] = $link["link"];
            }elseif($link["link_type"]==SiteSocialLinksModel::$LINK_TYPE_TWITTER){
                $socials["twitter"] = $link["link"];
            }elseif($link["link_type"]==SiteSocialLinksModel::$LINK_TYPE_LINKEDIN){
                $socials["linkedin"] = $link["link"];
            }
        }

        return $socials;
    }


}
