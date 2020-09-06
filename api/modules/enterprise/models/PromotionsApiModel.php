<?PHP

namespace api\modules\enterprise\models;

use yii;
use yii\base\Model;

class PromotionsApiModel extends Model
{

    const STATUS_DEACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    const STATUS_PENDİNG  = 2;
    const STATUS_DELETED  = 3;

    const TYPE_DOCTOR = 1;
    const TYPE_ENTERPRISE = 2;

    /** Aksiya */
    public function Promotion($id)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_promotions` WHERE `id`=:id AND `status`<>:status LIMIT 1",[':id'=>$id,':status'=>self::STATUS_DELETED])->queryOne();
    }

    public static function PromotionByConnect($id,$connect_id,$type)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_promotions` WHERE `id`=:id AND `status`<>:status AND `connect_id`=:connect_id AND `type`=:type_ LIMIT 1",[':id'=>$id,':status'=>self::STATUS_DELETED,':connect_id'=>$connect_id,':type_'=>$type])->queryOne();
    }

    /** Aksiyaya baqli obyekt ve ya hekim */
    public function getOrganizer($id,$type = self::TYPE_DOCTOR)
    {
        if($type == self::TYPE_DOCTOR)
        {
            return Yii::$app->db->createCommand("SELECT `name` FROM `site_doctors` WHERE `id`=:id LIMIT 1",[':id'=>$id])->queryScalar();
        }else if($type == self::TYPE_ENTERPRISE){
            return Yii::$app->db->createCommand("SELECT `name` FROM `site_enterprises` WHERE `id`=:id LIMIT 1",[':id'=>$id])->queryScalar();
        }
    }

    /** Aksiyalar */
    public function PromotionsCount()
    {
        return Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `site_promotions` WHERE `status`<>:status",[':status'=>self::STATUS_DELETED])->queryOne();
    }

    public function Promotions($limits)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_promotions`  WHERE `status`<>:status ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}",[':status'=>self::STATUS_DELETED])->queryAll();
    }

    /** Obyekte ve ya hekime aid aksiyalar */
    public function PromotionsListCount($id,$type = self::TYPE_DOCTOR)
    {
        return Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `site_promotions` WHERE `connect_id`=:id AND `type`=:type_ AND `status`<>:status",[':id'=>$id,':type_'=>$type,':status'=>self::STATUS_DELETED])->queryOne();
    }

    public function PromotionsList($id,$limits,$type = self::TYPE_DOCTOR)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_promotions` WHERE `connect_id`=:id AND `type`=:type_ AND `status`<>:status ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}",[':id'=>$id,':type_'=>$type,':status'=>self::STATUS_DELETED])->queryAll();
    }

    public function PromotionsListCount2($enterpriseID,$id,$type = 1)
    {
        return Yii::$app->db->createCommand("SELECT count(p.`id`) as `count` FROM `site_promotions` AS p INNER JOIN `site_enterprise_doctor` AS ed ON p.`connect_id`=ed.`doctor_id` WHERE p.`connect_id`=:id AND ed.`enterprise_id`=:enterprise_id AND p.`status`<>:status AND p.`type`=:type_",[':enterprise_id'=>$enterpriseID,':id'=>$id,':type_'=>$type,':status'=>self::STATUS_DELETED])->queryOne();
    }

    public function PromotionsList2($enterpriseID,$id,$limits,$type = 1)
    {
        return Yii::$app->db->createCommand("SELECT p.* FROM `site_promotions` AS p INNER JOIN `site_enterprise_doctor` AS ed ON p.`connect_id`=ed.`doctor_id` WHERE p.`connect_id`=:id AND ed.`enterprise_id`=:enterprise_id AND p.`status`<>:status AND p.`type`=:type_ ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}",[':enterprise_id'=>$enterpriseID,':id'=>$id,':type_'=>$type,':status'=>self::STATUS_DELETED])->queryAll();
    }

    /** Istifade olunmus promokodlar */
    public function UsedPromocodesCount()
    {
        return Yii::$app->db->createCommand("SELECT count(used.`id`) as `count` FROM `site_used_promotions` AS used INNER JOIN `site_promotions` AS p ON used.`promotion_id`=p.`id`")->queryOne();
    }

    public function UsedPromocodes($limits)
    {
        return Yii::$app->db->createCommand("SELECT used.*,p.`headline` AS promotion_headline,p.`connect_id`,p.`type` FROM `site_used_promotions` AS used INNER JOIN `site_promotions` AS p ON used.`promotion_id`=p.`id` ORDER BY used.`id` DESC LIMIT {$limits[0]},{$limits[1]}")->queryAll();
    }

    public function UsedPromocodesListCount($id,$type = 1)
    {
        return Yii::$app->db->createCommand("SELECT count(used.`id`) as `count` FROM `site_used_promotions` AS used INNER JOIN `site_promotions` AS p ON used.`promotion_id`=p.`id` WHERE p.`connect_id`=:connect_id AND p.`type`=:type_",[':connect_id'=>$id,':type_'=>$type])->queryOne();
    }

    public function UsedPromocodesList($id,$limits,$type = 1)
    {
        return Yii::$app->db->createCommand("SELECT used.*,p.`headline` AS promotion_headline,p.`connect_id`,p.`type` FROM `site_used_promotions` AS used INNER JOIN `site_promotions` AS p ON used.`promotion_id`=p.`id` WHERE p.`connect_id`=:connect_id AND p.`type`=:type_ ORDER BY used.`id` DESC LIMIT {$limits[0]},{$limits[1]}",[':connect_id'=>$id,':type_'=>$type])->queryAll();
    }

    /** Obyekti sil */
    public static function PromotionsDelete($id)
    {
        return Yii::$app->db->createCommand("UPDATE `site_promotions` SET `status`=:status WHERE id=:id",[':id'=>$id,':status'=>self::STATUS_DELETED])->execute();
    }

    /** Check promotion */
//    public function UsedPromotion($promotionID,$userID)
//    {
//        return Yii::$app->db->createCommand("SELECT * FROM `site_used_promotions` WHERE `promotion_id`=:promotion_id AND `user_id`=:user_id",[':promotion_id'=>$promotionID,':user_id'=>$userID])->queryOne();
//    }

}
?>