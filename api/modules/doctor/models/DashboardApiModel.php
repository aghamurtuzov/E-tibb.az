<?PHP

namespace api\modules\doctor\models;

use yii;
use yii\base\Model;

class DashboardApiModel extends Model
{

    public static function PromotionCount($connectID,$connectTYPE)
    {
        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_promotions` WHERE `connect_id`=:connect_id AND `type`=:type_ AND `status`=:status",[':connect_id'=>$connectID,':type_'=>$connectTYPE,':status'=>1])->queryScalar();
    }

    public static function QuestionCount($id = null)
    {
        if($id == null)
        {
            return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_consultation`")->queryScalar();
        }else{
            return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_consultation` WHERE `doctor_id`=:doctor_id",[':doctor_id'=>$id])->queryScalar();
        }
    }

    public static function CommentCount($connect_id = null,$type = null)
    {
        if($type == null)
        {
            return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_comments`")->queryScalar();
        }else{
            return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_comments` WHERE `connect_id`=:connect_id AND `type`=:type_",[':connect_id'=>$connect_id,':type_'=>$type])->queryScalar();
        }
    }

    public static function NewsCount($connectID,$connectTYPE,$categoryID)
    {
        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_news` WHERE `connect_id`=:connect_id AND `type`=:type_ AND `category_id`=:category_id AND `status`=:status",[':connect_id'=>$connectID,':type_'=>$connectTYPE,':category_id'=>$categoryID,':status'=>1])->queryScalar();
    }

    public static function RezervasiyaCount($id = null)
    {
        if($id == null)
        {
            return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_appoint`")->queryScalar();
        }else{
            return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_appoint` WHERE `doctor_id`=:doctor_id",[':doctor_id'=>$id])->queryScalar();
        }
    }

}