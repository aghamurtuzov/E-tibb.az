<?PHP

namespace api\modules\general\models;

use yii;
use yii\base\Model;

class PromotionsApiModel extends Model
{

    const STATUS_DEACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_PENDİNG = 2;
    const STATUS_DELETED = 3;

    const TYPE_DOCTOR = 1;
    const TYPE_ENTERPRISE = 2;

    /** Aksiya */
    public static function Promotion($id)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_promotions` WHERE `id`=:id AND `status`<>:status LIMIT 1", [':id' => $id, ':status' => self::STATUS_DELETED])->queryOne();
    }

    /** Aksiyaya baqli obyekt ve ya hekim */
    public function getOrganizer($id, $type = self::TYPE_DOCTOR)
    {
        if ($type == self::TYPE_DOCTOR) {
            return Yii::$app->db->createCommand("SELECT `name` FROM `site_doctors` WHERE `id`=:id LIMIT 1", [':id' => $id])->queryScalar();
        } else if ($type == self::TYPE_ENTERPRISE) {
            return Yii::$app->db->createCommand("SELECT `name` FROM `site_enterprises` WHERE `id`=:id LIMIT 1", [':id' => $id])->queryScalar();
        }
    }

    /** Aksiyalar */
    public function PromotionsCount($type = 'all')
    {
        $add = $type != 'all' && ($type == 1 || $type == 0) ? ' status=' . $type : '';
        $add = !empty($add) ? ' AND ' . $add : '';


        return Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `site_promotions` WHERE `status`<>:status " . $add, [':status' => self::STATUS_DELETED])->queryOne();
    }

    public function Promotions($limits, $type = 'all')
    {
        $add = $type != 'all' && ($type == 1 || $type == 0) ? ' status=' . $type : '';
        $add = !empty($add) ? ' AND ' . $add : '';

        return Yii::$app->db->createCommand("SELECT * FROM `site_promotions`  WHERE `status`<>:status " . $add . " ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}", [':status' => self::STATUS_DELETED])->queryAll();
    }

    /** Obyekte ve ya hekime aid aksiyalar */

    public function PromotionsListCount($id, $type = 1, $status = 'all')
    {
        $add = $status != 'all' && ($status == 1 || $status == 0) ? ' status=' . $status : '';
        $add = !empty($add) ? ' AND ' . $add : '';


        return Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `site_promotions` WHERE `connect_id`=:id AND `status`<>:status AND `type`=:type_ " . $add, [':id' => $id, ':type_' => $type, ':status' => self::STATUS_DELETED])->queryOne();
    }

    public function PromotionsList($id, $limits, $type = 1, $status = 'all')
    {
        $add = $status != 'all' && ($status == 1 || $status == 0) ? ' status=' . $status : '';
        $add = !empty($add) ? ' AND ' . $add : '';

        return Yii::$app->db->createCommand("SELECT * FROM `site_promotions` WHERE `connect_id`=:id AND `type`=:type_ AND `status`<>:status " . $add . " ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}", [':id' => $id, ':type_' => $type, ':status' => self::STATUS_DELETED])->queryAll();
    }

    /** Istifade olunmus promokodlar */
    public function UsedPromocodesCount()
    {
        return Yii::$app->db->createCommand("SELECT count(used.`id`) as `count` FROM `site_used_promotions` AS used INNER JOIN `site_promotions` AS p ON used.`promotion_id`=p.`id` WHERE p.`status` <> 3")->queryOne();
    }

    public function UsedPromocodes($limits)
    {
        return Yii::$app->db->createCommand("SELECT used.*,p.`headline` AS promotion_headline,p.`connect_id`,p.`type`, p.`organizer` FROM `site_used_promotions` AS used INNER JOIN `site_promotions` AS p ON used.`promotion_id`=p.`id` WHERE p.`status` <> 3 ORDER BY used.`id` DESC LIMIT {$limits[0]},{$limits[1]}")->queryAll();
    }

    public function UsedPromocodesListCount($id, $type = 1)
    {
        return Yii::$app->db->createCommand("SELECT count(used.`id`) as `count` FROM `site_used_promotions` AS used INNER JOIN `site_promotions` AS p ON used.`promotion_id`=p.`id` WHERE p.`connect_id`=:connect_id AND p.`type`=:type_ AND p.`status` <> 3", [':connect_id' => $id, ':type_' => $type])->queryOne();
    }

    public function UsedPromocodesList($id, $limits, $type = 1)
    {
        return Yii::$app->db->createCommand("SELECT used.*,p.`headline` AS promotion_headline,p.`connect_id`,p.`type`, p.`organizer` FROM `site_used_promotions` AS used INNER JOIN `site_promotions` AS p ON used.`promotion_id`=p.`id` WHERE p.`connect_id`=:connect_id AND p.`type`=:type_ AND p.`status` <> 3 ORDER BY used.`id` DESC LIMIT {$limits[0]},{$limits[1]}", [':connect_id' => $id, ':type_' => $type])->queryAll();
    }

    /** Obyekti sil */
    public static function PromotionsDelete($id)
    {
        return Yii::$app->db->createCommand("UPDATE `site_promotions` SET `status`=:status WHERE id=:id", [':id' => $id, ':status' => self::STATUS_DELETED])->execute();
    }

    public static function PromotionFind($id)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_promotions` WHERE id=:id", [':id' => $id])->queryOne();
    }

    public static function PromotionDeletePermanently($id)
    {
        Yii::$app->db->createCommand("DELETE FROM `site_promotions` WHERE `id`=:id", [':id' => $id])->execute();
    }

}

?>