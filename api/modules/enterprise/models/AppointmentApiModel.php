<?PHP

namespace api\modules\enterprise\models;

use yii;
use yii\base\Model;

class AppointmentApiModel extends Model
{
    /** Randevular */
    public static function AppointmentsCount()
    {
        return Yii::$app->db->createCommand("SELECT count(a.`id`) AS `count` FROM `site_appoint` as a INNER JOIN site_doctors AS d ON a.`doctor_id`=d.`id`")->queryOne();
    }

    public static function Appointments($limits)
    {
        return Yii::$app->db->createCommand("SELECT a.*,d.`name` as doctor_name FROM `site_appoint` as a INNER JOIN site_doctors AS d ON a.`doctor_id`=d.`id` ORDER BY a.id DESC LIMIT {$limits[0]},{$limits[1]}")->queryAll();
    }

    public static function getStatus()
    {
        return [
            'DeAktiv',
            'Aktiv'
        ];
    }
    
}