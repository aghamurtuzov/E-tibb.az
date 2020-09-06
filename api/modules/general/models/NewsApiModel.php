<?PHP

namespace api\modules\general\models;

use yii;
use yii\base\Model;

class NewsApiModel extends Model
{

    const STATUS_DEACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    /** Xeberler */
    public function NewsCount($type = 'all')
    {
        $add = $type != 'all' && ($type == 1 || $type == 0) ? ' n.status=' . $type : '';
        $add = !empty($add) ? ' AND ' . $add : '';

        return Yii::$app->db->createCommand("SELECT count(n.`id`) AS `count` FROM `site_news` as n INNER JOIN `site_menus` as m ON n.`category_id` = m.`id` LEFT JOIN `site_doctors` as doc ON n.connect_id = doc.id LEFT JOIN `site_enterprises` as enter ON n.`connect_id`=enter.`id` WHERE n.`status`<>:status AND n.`category_id`<>:category_id " . $add, [':status' => self::STATUS_DELETED, ':category_id' => 34])->queryOne();

    }

    public function NewsList($limits, $type = 'all')
    {
        $add = $type != 'all' && ($type == 1 || $type == 0) ? ' n.status=' . $type : '';
        $add = !empty($add) ? ' AND ' . $add : '';

        return Yii::$app->db->createCommand("SELECT n.`id`,n.`connect_id`,n.`category_id`,n.`photo`,n.`news_read`,n.`datetime`,n.`headline`,n.`status`,m.`name` as `category`,doc.`name` as doctor_name,enter.`name` as enter_name FROM `site_news` as n INNER JOIN `site_menus` as m ON n.`category_id` = m.`id` LEFT JOIN `site_doctors` as doc ON n.`connect_id` = doc.`id` LEFT JOIN `site_enterprises` as enter ON n.`connect_id`=enter.`id` WHERE n.`status`<>:status AND n.`category_id`<>:category_id " . $add . " ORDER BY n.id DESC LIMIT {$limits[0]},{$limits[1]}", [':status' => self::STATUS_DELETED, ':category_id' => 34])->queryAll();
    }

    /** Xeberler id ile */
    public function NewsByIdCount($id,$type = 'all')
    {
        $add = $type != 'all' && ($type == 1 || $type == 0) ? ' n.status=' . $type : '';
        $add = !empty($add) ? ' AND ' . $add : '';
        return Yii::$app->db->createCommand("SELECT count(n.`id`) AS `count` FROM `site_news` as n INNER JOIN `site_menus` AS m ON n.`category_id` = m.`id` LEFT JOIN `site_doctors` as doc ON n.`connect_id` = doc.`id` LEFT JOIN `site_enterprises` as enter ON n.`connect_id`=enter.`id` WHERE n.`category_id`=:category_id AND n.`status`<>:status " . $add, [':category_id' => $id, ':status' => self::STATUS_DELETED])->queryOne();
    }

    public function NewsByIdList($id, $limits,$type = 'all')
    {
        $add = $type != 'all' && ($type == 1 || $type == 0) ? ' n.status=' . $type : '';
        $add = !empty($add) ? ' AND ' . $add : '';

        return Yii::$app->db->createCommand("SELECT n.`id`,n.`connect_id`,n.`category_id`,n.`photo`,n.`news_read`,n.`datetime`,n.`headline`,n.`status`,m.`name` AS `category`,doc.`name` as doctor_name,enter.`name` as enter_name FROM `site_news` AS n INNER JOIN `site_menus` AS m ON n.`category_id` = m.`id` LEFT JOIN `site_doctors` as doc ON n.`connect_id` = doc.`id` LEFT JOIN `site_enterprises` as enter ON n.`connect_id`=enter.`id` WHERE n.`category_id`=:category_id AND n.`status`<>:status " . $add . " ORDER BY n.id DESC LIMIT {$limits[0]},{$limits[1]}", [':category_id' => $id, ':status' => self::STATUS_DELETED])->queryAll();
    }

    /** Xeber */
    public static function News($id)
    {
        return Yii::$app->db->createCommand("SELECT n.*,m.`name` as `category` FROM `site_news` as n INNER JOIN `site_menus` as m ON n.`category_id` = m.`id` WHERE n.`id`=:id AND n.`status`<>:status", [':id' => $id, ':status' => self::STATUS_DELETED])->queryOne();
    }

    /** Xeber sil */
    public static function NewsDelete($id)
    {
        return Yii::$app->db->createCommand("UPDATE `site_news` SET `status`=:status WHERE id=:id", [':id' => $id, ':status' => self::STATUS_DELETED])->execute();
    }

    public static function NewsFind($id)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_news` WHERE `id`=:id", [':id' => $id])->queryOne();
    }

    public static function NewsDeletePermanently($id)
    {
        Yii::$app->db->createCommand("DELETE FROM `site_news` WHERE `id`=:id", [':id' => $id])->execute();
    }

}

?>