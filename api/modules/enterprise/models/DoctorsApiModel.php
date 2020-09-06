<?PHP

namespace api\modules\enterprise\models;

use yii;
use yii\base\Model;
use backend\models\SiteDoctors;
use api\components\Pagination;

class DoctorsApiModel extends Model
{

    const STATUS_DEACTIVE = 0;
    const STATUS_ACTIVE   = 1;

    /** Hekimler */
    public function DoctorsCount()
    {
        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_doctors`")->queryOne();
    }

    public function Doctors($limits)
    {
        return Yii::$app->db->createCommand("SELECT `id`,`name`,`email`,`photo`,`status` FROM `site_doctors` ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}")->queryAll();
    }

    public function DoctorsListCount($enterpriseID)
    {
        return Yii::$app->db->createCommand("SELECT count(d.`id`) AS `count` FROM `site_doctors` AS d LEFT JOIN `site_enterprise_doctor` AS ed ON d.`id`=ed.`doctor_id` WHERE ed.`enterprise_id`=:enterprise_id",[':enterprise_id'=>$enterpriseID])->queryOne();
    }

    public function DoctorsList($enterpriseID,$limits)
    {
        return Yii::$app->db->createCommand("SELECT d.`id`,d.`name`,d.`email`,d.`photo`,d.`status` FROM `site_doctors` AS d LEFT JOIN `site_enterprise_doctor` AS ed ON d.`id`=ed.`doctor_id` WHERE ed.`enterprise_id`=:enterprise_id ORDER BY d.`id` DESC LIMIT {$limits[0]},{$limits[1]}",[':enterprise_id'=>$enterpriseID])->queryAll();
    }

    /** Hekim block */
//    public function Block($id)
//    {
//        return Yii::$app->db->createCommand("UPDATE `site_doctors` SET `status`=:status WHERE `id`=:id",[':id'=>$id,':status'=>0])->execute();
//    }
//
//    /** Hekim active */
//    public function Active($id)
//    {
//        return Yii::$app->db->createCommand("UPDATE `site_doctors` SET `status`=:status WHERE `id`=:id",[':id'=>$id,':status'=>1])->execute();
//    }

    /** Hekim status deyismek */
    public static function ChangeStatus($id,$status)
    {
        return Yii::$app->db->createCommand("UPDATE `site_doctors` SET `status`=:status WHERE id=:id",[':id'=>$id,'status'=>$status])->execute();
    }

    public static function ChangeStatus2($enterpriseID,$doctorID,$status)
    {
        return Yii::$app->db->createCommand("UPDATE `site_doctors` AS d INNER JOIN `site_enterprise_doctor` as ed ON d.`id`=ed.`doctor_id` SET d.`status`=:status WHERE d.`id`=:doctor_id AND ed.`enterprise_id`=:enterprise_id AND d.`status`<>:status",[':enterprise_id'=>$enterpriseID,':doctor_id'=>$doctorID,'status'=>$status])->execute();
    }

}

?>