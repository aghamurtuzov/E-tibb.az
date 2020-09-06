<?PHP

namespace api\modules\general\models;

use yii;
use yii\base\Model;
use backend\models\SiteDoctors;
use api\components\Pagination;

class DoctorsApiModel extends Model
{

    /** Hekimler */
    public function DoctorsCount($type='all')
    {
        $add = $type != 'all' && ($type == 1 || $type == 0) ? ' status=' . $type : 'status <> 2';

        $add = !empty($add) ? 'WHERE ' . $add : '';
        return Yii::$app->db->createCommand("select count(`id`) as `count` from `site_doctors` " . $add)->queryOne();

    }

    public function Doctors($limits, $type = 'all')
    {
        $add = $type != 'all' && ($type == 1 || $type == 0) ? ' status=' . $type : 'status <> 2';

        $add = !empty($add) ? 'WHERE ' . $add : '';
        return Yii::$app->db->createCommand("SELECT `id`,`name`,`email`,`photo`,`status` FROM `site_doctors` " . $add . " ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}")->queryAll();

    }

    /** Hekim block */
    public static function Block($id)
    {
        return Yii::$app->db->createCommand("UPDATE `site_doctors` SET `status`=:status WHERE `id`=:id", [':id' => $id, ':status' => 0])->execute();
    }

    /** Hekim active */
    public static function Active($id)
    {
        return Yii::$app->db->createCommand("UPDATE `site_doctors` SET `status`=:status WHERE `id`=:id", [':id' => $id, ':status' => 1])->execute();
    }

    /** Obyekte hekim elave etmek */
    public function EnterpriseAddDoctor($enterprise_id, $doctor_id)
    {
        return Yii::$app->db->createCommand('INSERT INTO `site_enterprise_doctor`(`enterprise_id`,`doctor_id`) VALUES("' . $enterprise_id . '","' . $doctor_id . '")')->execute();
    }

    public static function deleteDoctor($id, $user_id = "") {
        if(!empty($user_id)) {
            Yii::$app->db->createCommand("UPDATE `site_users` SET `status`=:status WHERE `id`=:id", [':id' => intval($user_id), ':status' => 2])->execute();
        }
        return Yii::$app->db->createCommand("UPDATE `site_doctors` SET `status`=:status WHERE `id`=:id", [':id' => $id, ':status' => 2])->execute();
    }

    public static function deleteBaseDoctor($id)
    {

        if (Yii::$app->db->createCommand("SELECT * FROM `site_doctors` WHERE `id`=:id", [':id' => $id])->queryOne()) {

            $doctor = Yii::$app->db->createCommand("SELECT * FROM `site_doctors` WHERE `id`=:id", [':id' => $id])->queryOne();
            if ($doctor["user_id"]) {
                Yii::$app->db->createCommand("DELETE FROM `site_users` WHERE `id`=:id", [':id' => $doctor["user_id"]])->execute();
            }
            Yii::$app->db->createCommand("DELETE FROM `site_doctors` WHERE `id`=:id", [':id' => $id])->execute();
            Yii::$app->db->createCommand("DELETE FROM `site_doctor_specialist` WHERE `doctor_id`=:id", [':id' => $id])->execute();
            Yii::$app->db->createCommand("DELETE FROM `site_doctor_workplaces` WHERE `doctor_id`=:id", [':id' => $id])->execute();
            Yii::$app->db->createCommand("DELETE FROM `site_doctor_workplaces_list` WHERE `doctor_id`=:id", [':id' => $id])->execute();
        }
    }


    public static function doctorFind($id)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_doctors` WHERE `id`=:id", [':id' => $id])->queryOne();

    }

}

?>
