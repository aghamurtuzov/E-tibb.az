<?PHP

namespace api\modules\enterprise\models;

use yii;
use yii\base\Model;

class DashboardApiModel extends Model
{

    public static function NewsCount($connectID,$connectTYPE,$categoryID)
    {
        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_news` WHERE `connect_id`=:connect_id AND `type`=:type_ AND `category_id`=:category_id AND `status`=:status",[':connect_id'=>$connectID,':type_'=>$connectTYPE,':category_id'=>$categoryID,':status'=>1])->queryScalar();
    }

    public static function PromotionCount($connectID,$connectTYPE)
    {
        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_promotions` WHERE `connect_id`=:connect_id AND `type`=:type_ AND `status`=:status",[':connect_id'=>$connectID,':type_'=>$connectTYPE,':status'=>1])->queryScalar();
    }
    
    public static function DoctorsCount($enterpriseID)
    {
        return Yii::$app->db->createCommand("SELECT count(d.`id`) AS `count` FROM `site_doctors` AS d INNER JOIN `site_enterprise_doctor` AS ed ON d.`id`=ed.`doctor_id` WHERE ed.`enterprise_id`=:enterprise_id AND d.`status`=:status",[':enterprise_id'=>$enterpriseID,':status'=>1])->queryScalar();
    }

}