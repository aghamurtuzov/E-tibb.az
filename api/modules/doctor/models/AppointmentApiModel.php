<?PHP

namespace api\modules\doctor\models;

use yii;
use yii\base\Model;

class AppointmentApiModel extends Model
{

    const STATUS_DEACTIVE = 0;
    const STATUS_ACTIVE   = 1;

    /** Randevular */
    public static function AppointmentsCount($doctor_id = null)
    {
        if(empty($doctor_id))
        {
            return Yii::$app->db->createCommand("SELECT count(a.`id`) AS `count` FROM `site_appoint` AS a INNER JOIN site_doctors AS d ON a.`doctor_id`=d.`id` INNER JOIN site_users AS u ON a.`user_id`=u.id WHERE d.`name`!=u.`name`")->queryOne();
        }else{
            return Yii::$app->db->createCommand("SELECT count(a.`id`) AS `count` FROM `site_appoint` AS a INNER JOIN site_doctors AS d ON a.`doctor_id`=d.`id` INNER JOIN site_users AS u ON a.`user_id`=u.id WHERE a.`doctor_id`=:doctor_id AND d.`name`!=u.`name`",[':doctor_id'=>$doctor_id])->queryOne();
        }
    }

    public static function Appointments($limits,$doctor_id = null)
    {
        if(empty($doctor_id))
        {
            return Yii::$app->db->createCommand("SELECT a.*,d.`name` AS doctor_name,u.`name` as `user_name`,u.`id` AS `user_id`,u.`email` AS `user_email`,u.`phone_number` AS `user_phone_number` FROM `site_appoint` AS a INNER JOIN site_doctors AS d ON a.`doctor_id`=d.`id` INNER JOIN site_users AS u ON a.`user_id`=u.id WHERE d.`name`!=u.`name` ORDER BY a.id DESC LIMIT {$limits[0]},{$limits[1]}")->queryAll();
        }else{
            return Yii::$app->db->createCommand("SELECT a.*,d.`name` AS doctor_name,u.`name` as `user_name`,u.`id` AS `user_id`,u.`email` AS `user_email`,u.`phone_number` AS `user_phone_number` FROM `site_appoint` AS a INNER JOIN site_doctors AS d ON a.`doctor_id`=d.`id` INNER JOIN site_users AS u ON a.`user_id`=u.id WHERE `doctor_id`=:doctor_id AND d.`name`!=u.`name` ORDER BY a.id DESC LIMIT {$limits[0]},{$limits[1]}",[':doctor_id'=>$doctor_id])->queryAll();
        }
    }

    public static function Appoint($doctorID,$day)
    {
        return Yii::$app->db->createCommand("SELECT ap.`id`,ap.`time`,u.`name` AS user_name,u.`id` AS user_id FROM `site_appoint` AS ap INNER JOIN `site_users` AS u ON ap.`user_id`=u.`id` WHERE ap.`doctor_id`=:doctor_id AND ap.`date`=:day_ AND ap.`status`=1",['doctor_id'=>$doctorID,'day_'=>$day])->queryAll();
    }

    public static function ChangeStatus($id,$status,$doctorID)
    {
        return Yii::$app->db->createCommand("UPDATE `site_appoint` SET `status`=:status WHERE id=:id AND doctor_id=:doctor_id",[':id'=>$id,':doctor_id'=>$doctorID,':status'=>$status])->execute();
    }

    public static function getStatus()
    {
        return [
            'DeAktiv',
            'Aktiv'
        ];
    }
    
}