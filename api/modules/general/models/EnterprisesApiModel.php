<?PHP

namespace api\modules\general\models;

use yii;
use yii\base\Model;

class EnterprisesApiModel extends Model
{

    const STATUS_DEACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    /** Obyektler */

    public function EnterpriseCount($cat_id, $type = 'all')
    {
        $add = $type != 'all' && ($type == 1 || $type == 0) ? ' e.status=' . $type : '';
        $add = !empty($add) ? 'AND ' . $add : '';

        return Yii::$app->db->createCommand("SELECT count(e.`id`) AS `count` FROM `site_enterprises` AS e INNER JOIN `site_users` as u ON e.`user_id`=u.`id` WHERE e.`category_id`=:cat_id " . $add, [':cat_id' => $cat_id])->queryOne();
    }

    public function Enterprises($cat_id, $limits, $type = 'all')
    {
        $add = $type != 'all' && ($type == 1 || $type == 0) ? ' e.status=' . $type : '';
        $add = !empty($add) ? 'AND ' . $add : '';


        return Yii::$app->db->createCommand("SELECT e.`id`,e.`status`,e.`photo`,e.`name`,u.`name` as user_name,u.`email` as user_email,u.`phone_number` as user_phone_number FROM `site_enterprises` AS e INNER JOIN `site_users` as u ON e.`user_id`=u.`id` WHERE e.`category_id`=:cat_id " . $add . " ORDER BY e.id DESC LIMIT {$limits[0]},{$limits[1]}", [':cat_id' => $cat_id])->queryAll();

    }

    /** Obyektdəki Hekimler */
    public function DoctorsCount($cat_id)
    {
        return Yii::$app->db->createCommand("SELECT (d.`id`) `count` FROM `site_doctors` AS d INNER JOIN `site_enterprise_doctor` as sde ON d.`id`=sde.`doctor_id` WHERE sde.`enterprise_id`=:cat_id AND d.`status`=:status", [':cat_id' => $cat_id, ':status' => 1])->queryOne();
    }

    public function Doctors($cat_id, $limits)
    {
        return Yii::$app->db->createCommand("SELECT d.`id`,d.`name`,d.`slug`,d.`photo`,d.`email`,d.`status` FROM `site_doctors` AS d INNER JOIN `site_enterprise_doctor` as sde ON d.`id`=sde.`doctor_id` WHERE sde.`enterprise_id`=:cat_id AND d.`status`=:status ORDER BY d.id DESC LIMIT {$limits[0]},{$limits[1]}", [':cat_id' => $cat_id, ':status' => 1])->queryAll();
    }

    /** Obyekt status deyismek */
    public static function ChangeStatus($id, $status)
    {
        return Yii::$app->db->createCommand("UPDATE `site_enterprises` SET `status`=:status WHERE id=:id", [':id' => $id, 'status' => $status])->execute();
    }

    public static function EnterpriseFind($id)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_enterprises` WHERE id=:id", [':id' => $id])->queryOne();
    }

    public static function EnterpriseDelete($id, $user_id = "")
    {
        if(!empty($user_id)) {
            Yii::$app->db->createCommand("UPDATE `site_users` SET `status`=:status WHERE `id`=:id", [':id' => intval($user_id), ':status' => 2])->execute();
        }
        return Yii::$app->db->createCommand("UPDATE `site_enterprises` SET `status`=:status WHERE id=:id", [':id' => $id, ':status' => self::STATUS_DELETED])->execute();
    }

    public static function EnterprisesDeletePermanently($id, $user_id)
    {
        /*
         * site_users.id = enterprice.user_id
         * site_sosial_links.connect_id = enter.id
         * site_phone_numbers.connect_id = enter.id
         * site_addresses.connect_id = enter.id
         * site_user_contact.connect_id = enter.id
         * */
        if ($user_id) {
            Yii::$app->db->createCommand("DELETE FROM `site_users` WHERE `id`=:id", [':id' => $user_id])->execute();
            Yii::$app->db->createCommand("DELETE FROM `site_sosial_links` WHERE `connect_id`=:id", [':id' => $id])->execute();
            Yii::$app->db->createCommand("DELETE FROM `site_phone_numbers` WHERE `connect_id`=:id", [':id' => $id])->execute();
            Yii::$app->db->createCommand("DELETE FROM `site_addresses` WHERE `connect_id`=:id", [':id' => $id])->execute();
            Yii::$app->db->createCommand("DELETE FROM `site_user_contact` WHERE `connect_id`=:id", [':id' => $id])->execute();

            Yii::$app->db->createCommand("DELETE FROM `site_enterprises` WHERE `id`=:id", [':id' => $id])->execute();
        }
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

    public function search($search)
    {
        if (!empty($search['phone'])) {
            $phone = '994' . substr($search['phone'], 1);
        } else {
            $phone = '----';
        }
        if ($search['status'] == 'all') {

            $status = ' status <> 2';
        } else {
            $status = "status= " . $search['status'] . " AND status <> 2";
        }

        $name = !empty($search['name']) ? $search['name'] : '-----';
        $code = !empty($search['code']) ? $search['code'] : '-----';
        $category_id = !empty($search['category_id']) ? $search['category_id'] : '-----';


        return Yii::$app->db->createCommand("SELECT DISTINCT `id`,`photo`,`name`,`user_id`,`user_name`,`user_email`,`user_phone_number`,`status` FROM `view_enterprise_search` WHERE " . $status . " AND `category_id` = :category_id AND ((`user_id` LIKE '%$code%') OR (`name` LIKE '%$name%') OR (`user_phone_number` LIKE '%$phone%'))", [':category_id' => $category_id])->queryAll();

    }

}

?>