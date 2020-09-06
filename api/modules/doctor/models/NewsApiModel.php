<?PHP

namespace api\modules\doctor\models;

use yii;
use yii\base\Model;

class NewsApiModel extends Model
{

    const STATUS_DEACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    const STATUS_DELETED  = 2;

    const TYPE_DOCTOR = 1;
    const TYPE_ENTERPRISE = 2;
    
    /** Xeberler */
    public function NewsCount()
    {
        return Yii::$app->db->createCommand("SELECT count(n.`id`) AS `count` FROM `site_news` as n INNER JOIN `site_menus` as m ON n.`category_id` = m.`id` WHERE n.`status`<>:status LIMIT 1",[':status'=>self::STATUS_DELETED])->queryOne();
    }

    public function NewsList($limits)
    {
        return Yii::$app->db->createCommand("SELECT n.`id`,n.`category_id`,n.`photo`,n.`news_read`,n.`datetime`,n.`headline`,n.`status`,m.`name` as `category` FROM `site_news` as n INNER JOIN `site_menus` as m ON n.`category_id` = m.`id` WHERE n.`status`<>:status ORDER BY n.id DESC LIMIT {$limits[0]},{$limits[1]}",[':status'=>self::STATUS_DELETED])->queryAll();
    }

    /** Xeberler id ile */
    public function NewsByIdCount($id)
    {
        return Yii::$app->db->createCommand("SELECT count(n.`id`) AS `count` FROM `site_news` as n INNER JOIN `site_menus` as m ON n.`category_id` = m.`id` WHERE n.`category_id`=:category_id AND n.`status`<>:status LIMIT 1",[':category_id'=>$id,':status'=>self::STATUS_DELETED])->queryOne();
    }

    public function NewsByIdList($id,$limits)
    {
        return Yii::$app->db->createCommand("SELECT n.`id`,n.`category_id`,n.`photo`,n.`news_read`,n.`datetime`,n.`headline`,n.`status`,m.`name` as `category` FROM `site_news` as n INNER JOIN `site_menus` as m ON n.`category_id` = m.`id` WHERE n.`category_id`=:category_id AND n.`status`<>:status ORDER BY n.id DESC LIMIT {$limits[0]},{$limits[1]}",[':category_id'=>$id,':status'=>self::STATUS_DELETED])->queryAll();
    }

    /** Xeber */
    public function News($id)
    {
        return Yii::$app->db->createCommand("SELECT n.*,m.`name` as `category` FROM `site_news` as n INNER JOIN `site_menus` as m ON n.`category_id` = m.`id` WHERE n.`id`=:id AND n.`status`<>:status",[':id'=>$id,':status'=>self::STATUS_DELETED])->queryOne();
    }

    /** Xeber sil */
    public function NewsDelete($id)
    {
        return Yii::$app->db->createCommand("UPDATE `site_news` SET `status`=:status WHERE id=:id",[':id'=>$id,':status'=>self::STATUS_DELETED])->execute();
    }




    /** Hekimin xeberleri */
    public function NewsByDoctorCount($id,$connectID,$type)
    {
        return Yii::$app->db->createCommand("SELECT count(n.`id`) AS `count` FROM `site_news` as n INNER JOIN `site_menus` as m ON n.`category_id` = m.`id` WHERE n.`connect_id`=:connect_id AND n.`type`=:type_ AND n.`category_id`=:category_id AND n.`status`<>:status LIMIT 1",[':type_'=>$type,':connect_id'=>$connectID,':category_id'=>$id,':status'=>self::STATUS_DELETED])->queryOne();
    }

    public function NewsByDoctorList($id,$limits,$connectID,$type)
    {
        return Yii::$app->db->createCommand("SELECT n.`id`,n.`category_id`,n.`photo`,n.`news_read`,n.`datetime`,n.`headline`,n.`status`,m.`name` as `category` FROM `site_news` as n INNER JOIN `site_menus` as m ON n.`category_id` = m.`id` WHERE n.`connect_id`=:connect_id AND n.`type`=:type_ AND n.`category_id`=:category_id AND n.`status`<>:status ORDER BY n.id DESC LIMIT {$limits[0]},{$limits[1]}",[':type_'=>$type,':connect_id'=>$connectID,':category_id'=>$id,':status'=>self::STATUS_DELETED])->queryAll();
    }

    /** Xeber */
    public static function NewsByDoctor($id,$connectID,$type)
    {
        return Yii::$app->db->createCommand("SELECT n.*,m.`name` as `category` FROM `site_news` as n INNER JOIN `site_menus` as m ON n.`category_id` = m.`id` WHERE n.`id`=:id AND n.`connect_id`=:connect_id AND n.`type`=:type_ AND n.`status`<>:status",[':type_'=>$type,':connect_id'=>$connectID,':id'=>$id,':status'=>self::STATUS_DELETED])->queryOne();
    }

    /** Hekim xeber sil */
    public static function NewsByDoctorDelete($id,$connectID,$type)
    {
        return Yii::$app->db->createCommand("UPDATE `site_news` SET `status`=:status WHERE id=:id AND `connect_id`=:connect_id AND `type`=:type_",['type_'=>$type,':connect_id'=>$connectID,':id'=>$id,':status'=>self::STATUS_DELETED])->execute();
    }



}
?>