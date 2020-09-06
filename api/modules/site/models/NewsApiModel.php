<?PHP

namespace api\modules\site\models;

use yii;
use yii\base\Model;

class NewsApiModel extends Model
{

    const STATUS_DEACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    const STATUS_DELETED  = 2;

    /** Xeberler */
    public static function NewsCount($search)
    {
        if(strlen($search)>=3)
        {
            return Yii::$app->db->createCommand("SELECT count(n.`id`) AS `count` FROM `site_news` as n INNER JOIN `site_menus` as m ON n.`category_id` = m.`id` WHERE n.`status`=:status AND (n.`headline` LIKE '%$search%' OR n.`content` LIKE '%$search%' OR n.`keywords` LIKE '%$search%')",[':status'=>self::STATUS_ACTIVE])->queryOne();
        }
        else
        {
            return Yii::$app->db->createCommand("SELECT count(n.`id`) AS `count` FROM `site_news` as n INNER JOIN `site_menus` as m ON n.`category_id` = m.`id` WHERE n.`status`=:status",[':status'=>self::STATUS_ACTIVE])->queryOne();
        }
    }

    public static function NewsList($limits,$search)
    {
        if(strlen($search)>=3)
        {
            return Yii::$app->db->createCommand("SELECT n.`id`,n.`category_id`,n.`photo`,n.`news_read`,n.`datetime`,n.`headline`,n.`slug`,m.`name` as `category` FROM `site_news` as n INNER JOIN `site_menus` as m ON n.`category_id` = m.`id` WHERE n.`status`=:status AND (n.`headline` LIKE '%$search%' OR n.`content` LIKE '%$search%' OR n.`keywords` LIKE '%$search%') ORDER BY n.id DESC LIMIT {$limits[0]},{$limits[1]}",[':status'=>self::STATUS_ACTIVE])->queryAll();
        }
        else
        {
            return Yii::$app->db->createCommand("SELECT n.`id`,n.`category_id`,n.`photo`,n.`news_read`,n.`datetime`,n.`headline`,n.`slug`,m.`name` as `category` FROM `site_news` as n INNER JOIN `site_menus` as m ON n.`category_id` = m.`id` WHERE n.`status`=:status ORDER BY n.id DESC LIMIT {$limits[0]},{$limits[1]}",[':status'=>self::STATUS_ACTIVE])->queryAll();
        }
    }

    /** Xeberler kateqoriya id ile */
    public static function NewsByIdCount($id,$search)
    {
        if(strlen($search)>=3)
        {
            return Yii::$app->db->createCommand("SELECT count(n.`id`) AS `count` FROM `site_news` as n INNER JOIN `site_menus` AS m ON n.`category_id` = m.`id` WHERE n.`category_id`=:category_id AND n.`status`=:status AND (n.`headline` LIKE '%$search%' OR n.`content` LIKE '%$search%' OR n.`keywords` LIKE '%$search%')",[':category_id'=>$id,':status'=>self::STATUS_ACTIVE])->queryOne();
        }
        else
        {
            return Yii::$app->db->createCommand("SELECT count(n.`id`) AS `count` FROM `site_news` as n INNER JOIN `site_menus` AS m ON n.`category_id` = m.`id` WHERE n.`category_id`=:category_id AND n.`status`=:status",[':category_id'=>$id,':status'=>self::STATUS_ACTIVE])->queryOne();
        }
    }

    public static function NewsByIdList($id,$limits,$search)
    {
        if(strlen($search)>=3)
        {
            return Yii::$app->db->createCommand("SELECT n.`id`,n.`category_id`,n.`photo`,n.`news_read`,n.`datetime`,n.`headline`,n.`slug`,m.`name` AS `category` FROM `site_news` AS n INNER JOIN `site_menus` AS m ON n.`category_id` = m.`id` WHERE n.`category_id`=:category_id AND n.`status`=:status AND (n.`headline` LIKE '%$search%' OR n.`content` LIKE '%$search%' OR n.`keywords` LIKE '%$search%') ORDER BY n.id DESC LIMIT {$limits[0]},{$limits[1]}",[':category_id'=>$id,':status'=>self::STATUS_ACTIVE])->queryAll();
        }
        else
        {
            return Yii::$app->db->createCommand("SELECT n.`id`,n.`category_id`,n.`photo`,n.`news_read`,n.`datetime`,n.`headline`,n.`slug`,m.`name` AS `category` FROM `site_news` AS n INNER JOIN `site_menus` AS m ON n.`category_id` = m.`id` WHERE n.`category_id`=:category_id AND n.`status`=:status ORDER BY n.id DESC LIMIT {$limits[0]},{$limits[1]}",[':category_id'=>$id,':status'=>self::STATUS_ACTIVE])->queryAll();
        }
    }

    /** Xeber */
    public static function News($id)
    {
        return Yii::$app->db->createCommand("SELECT n.`id`,n.`category_id`,n.`photo`,n.`news_read`,n.`datetime`,n.`headline`,n.`slug`,n.`content`,m.`name` AS `category` FROM `site_news` as n INNER JOIN `site_menus` as m ON n.`category_id` = m.`id` WHERE n.`id`=:id AND n.`status`=:status",[':id'=>$id,':status'=>self::STATUS_ACTIVE])->queryOne();
    }

    /** Xeber limit ile */
    public static function LimitedNews($limit = 12,$categoryID = null)
    {
        $limit = $limit<=0 ? 12: $limit;
        if(!empty($categoryID))
        {
            return Yii::$app->db->createCommand("SELECT n.`id`,n.`category_id`,n.`photo`,n.`news_read`,n.`datetime`,n.`headline`,n.`slug`,m.`name` AS `category` FROM `site_news` AS n INNER JOIN `site_menus` AS m ON n.`category_id` = m.`id` WHERE n.`category_id`=:category_id AND n.`status`=:status ORDER BY n.id DESC LIMIT {$limit}",[':category_id'=>$categoryID,':status'=>self::STATUS_ACTIVE])->queryAll();
        }
        else
        {
            return Yii::$app->db->createCommand("SELECT n.`id`,n.`category_id`,n.`photo`,n.`news_read`,n.`datetime`,n.`headline`,n.`slug`,m.`name` AS `category` FROM `site_news` AS n INNER JOIN `site_menus` AS m ON n.`category_id` = m.`id` WHERE n.`status`=:status ORDER BY n.id DESC LIMIT {$limit}",[':status'=>self::STATUS_ACTIVE])->queryAll();
        }
    }

}
?>