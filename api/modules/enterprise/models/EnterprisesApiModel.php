<?PHP

namespace api\modules\enterprise\models;

use yii;
use yii\base\Model;

class EnterprisesApiModel extends Model
{

    const STATUS_DEACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    const STATUS_DELETED  = 2;

    /** Obyektler */
    public function EnterpriseCount($cat_id)
    {
        return Yii::$app->db->createCommand("SELECT count(e.`id`) AS `count` FROM `site_enterprises` AS e INNER JOIN `site_users` as u ON e.`user_id`=u.`id` WHERE e.`category_id`=:cat_id",[':cat_id'=>$cat_id])->queryOne();
    }

    public function Enterprises($cat_id,$limits)
    {
        return Yii::$app->db->createCommand("SELECT e.`id`,e.`photo`,e.`name`,u.`name` as user_name,u.`email` as user_email,u.`phone_number` as user_phone_number FROM `site_enterprises` AS e INNER JOIN `site_users` as u ON e.`user_id`=u.`id` WHERE e.`category_id`=:cat_id ORDER BY e.id DESC LIMIT {$limits[0]},{$limits[1]}",[':cat_id'=>$cat_id])->queryAll();
    }

    /** Obyektdəki Hekimler */
    public function DoctorsCount($cat_id)
    {
        return Yii::$app->db->createCommand("SELECT (d.`id`) `count` FROM `site_doctors` AS d INNER JOIN `site_enterprise_doctor` as sde ON d.`id`=sde.`doctor_id` WHERE sde.`enterprise_id`=:cat_id AND d.`status`=:status",[':cat_id'=>$cat_id,':status'=>1])->queryOne();
    }

    public function Doctors($cat_id,$limits)
    {
        return Yii::$app->db->createCommand("SELECT d.`id`,d.`name`,d.`slug`,d.`photo`,d.`email` FROM `site_doctors` AS d INNER JOIN `site_enterprise_doctor` as sde ON d.`id`=sde.`doctor_id` WHERE sde.`enterprise_id`=:cat_id AND d.`status`=:status ORDER BY d.id DESC LIMIT {$limits[0]},{$limits[1]}",[':cat_id'=>$cat_id,':status'=>1])->queryAll();
    }

    /** Obyekt status deyismek */
    public static function ChangeStatus($id,$status)
    {
        return Yii::$app->db->createCommand("UPDATE `site_enterprises` SET `status`=:status WHERE id=:id",[':id'=>$id,'status'=>$status])->execute();
    }

//    /** Obyekt block */
//    public function Block($id)
//    {
//        return Yii::$app->db->createCommand("UPDATE `site_enterprises` SET `status`=:status WHERE `id`=:id",[':id'=>$id,':status'=>0])->execute();
//    }
//
//    /** Obyekt active */
//    public function Active($id)
//    {
//        return Yii::$app->db->createCommand("UPDATE `site_enterprises` SET `status`=:status WHERE `id`=:id",[':id'=>$id,':status'=>1])->execute();
//    }

}

?>