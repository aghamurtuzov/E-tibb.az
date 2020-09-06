<?PHP

namespace api\modules\general\models;

use yii;
use yii\base\Model;

class DashboardApiModel extends Model
{

    public static function DoctorsCount()
    {
        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_doctors` WHERE `status`=:status",[':status'=>1])->queryScalar();
    }

    public static function EnterpriseCount($category_id)
    {
        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_enterprises` WHERE `category_id`=:category_id AND `status`=:status",[':category_id'=>$category_id,':status'=>1])->queryScalar();
    }

    public static function PromotionCount()
    {
        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_promotions` WHERE `status`=:status",[':status'=>1])->queryScalar();
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

    public static function UsersCount()
    {
        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_users`")->queryScalar();
    }

    public static function NewsCount($category_id)
    {
        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_news` WHERE `category_id`=:category_id AND `status`=:status",[':category_id'=>$category_id,':status'=>1])->queryScalar();
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