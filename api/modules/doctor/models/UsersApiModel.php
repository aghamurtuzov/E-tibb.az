<?PHP

namespace api\modules\doctor\models;

use yii;
use yii\base\Model;

class UsersApiModel extends Model
{

    const STATUS_DEACTIVE = 0;
    const STATUS_ACTIVE   = 1;

    /** Istifadeciler */
    public static function UsersCount()
    {
        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_users`")->queryOne();
    }

    public static function Users($limits)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_users` ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}")->queryAll();
    }

    /** Serh status deyismek */
    public static function ChangeStatus($id,$status)
    {
        return Yii::$app->db->createCommand("UPDATE `site_users` SET `status`=:status WHERE id=:id",[':id'=>$id,'status'=>$status])->execute();
    }

    public static function getStatus()
    {
        return [
            'DeAktiv',
            'Aktiv'
        ];
    }

    public static function getTypes()
    {
        return [
            1 => 'Həkim',
            2 => 'Obyekt',
            3 => 'Xəbər'
        ];
    }

}