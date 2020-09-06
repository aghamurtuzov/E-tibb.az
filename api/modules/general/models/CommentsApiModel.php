<?PHP

namespace api\modules\general\models;

use yii;
use yii\base\Model;

class CommentsApiModel extends Model
{

    const STATUS_DEACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    const STATUS_DELETED  = 2;

    const TYPE_DOCTOR     = 1;
    const TYPE_ENTERPRISE = 2;
    const TYPE_NEWS       = 3;

    /** Serhler */
    public static function CommentsCount($type='all')
    {
        $add = $type != 'all' && ($type == 1 || $type == 0) ? ' site_comments.status='.$type :  '';
        $add = !empty($add) ? 'AND '.$add : '';

        return Yii::$app->db->createCommand("SELECT count(site_comments.id) AS `count` FROM `site_comments` INNER JOIN `site_doctors` ON site_comments.connect_id=site_doctors.id WHERE site_comments.status<>:status ".$add,[':status'=>self::STATUS_DELETED])->queryOne();
//        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_comments` WHERE `status`<>:status",[':status'=>self::STATUS_DELETED])->queryOne();
//        return Yii::$app->db->createCommand("SELECT count(site_comments.id) AS `count` FROM site_comments LEFT JOIN site_doctors ON site_comments.connect_id=site_doctors.id WHERE site_comments.status <> :status",[':status'=>self::STATUS_DELETED])->queryOne();
    }

    public static function Comments($limits, $type='all')
    {
        $add = $type != 'all' && ($type == 1 || $type == 0) ? ' status='.$type :  '';
        $add = !empty($add) ? 'AND '.$add : '';

        return Yii::$app->db->createCommand("SELECT * FROM `site_comments` WHERE `status`<>:status ".$add." ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}",[':status'=>self::STATUS_DELETED])->queryAll();
    }

    public static function Commentes($limits)
    {
        return Yii::$app->db->createCommand("SELECT * FROM site_comments INNER JOIN site_doctors ON site_comments.connect_id=site_doctors.id ORDER BY site_comments.id DESC LIMIT {$limits[0]},{$limits[1]}",[':status'=>self::STATUS_DELETED])->queryAll();
    }

    /** Serh */
    public static function Comment($id)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_comments` WHERE `id`=:id and `status`<>:status",[':id'=>$id,':status'=>self::STATUS_DELETED])->queryOne();
    }

    /** Hekim Obyekt Xeber serhi */
    public function CommentBy($id)
    {
        if (Yii::$app->db->createCommand("SELECT `name`, `rating` FROM `site_doctors` WHERE `id`=:id LIMIT 1",[':id'=>$id])->queryOne()) {

            return Yii::$app->db->createCommand("SELECT `name`, `rating` FROM `site_doctors` WHERE `id`=:id LIMIT 1", [':id' => $id])->queryOne();

        }
//        if (Yii::$app->db->createCommand("SELECT `id` FROM `site_doctors` WHERE `id`=:id LIMIT 1",[':id'=>$id])->queryScalar()) {
//            if ($type == self::TYPE_DOCTOR) {
//                return Yii::$app->db->createCommand("SELECT `name` FROM `site_doctors` WHERE `id`=:id LIMIT 1", [':id' => $id])->queryScalar();
//            } else if ($type == self::TYPE_ENTERPRISE) {
//                return Yii::$app->db->createCommand("SELECT `name` FROM `site_enterprises` WHERE `id`=:id LIMIT 1", [':id' => $id])->queryScalar();
//            } else {
//                return Yii::$app->db->createCommand("SELECT `headline` FROM `site_news` WHERE `id`=:id LIMIT 1", [':id' => $id])->queryScalar();
//            }
//        }
        return false;
    }

    public static function getRatingDoctor($doctor_id)
    {
        return Yii::$app->db->createCommand("SELECT sum(rating) as sum_rating,count(id) as count_rating FROM site_comments where connect_id=:doctor_id and status=1 group by connect_id;",[":doctor_id" => $doctor_id])->queryOne();
    }

    /** Serh status deyismek */
    public static function ChangeStatus($id,$status)
    {
        return Yii::$app->db->createCommand("UPDATE `site_comments` SET `status`=:status WHERE id=:id",[':id'=>$id,'status'=>$status])->execute();
    }

    public static  function CommentDelete($id)
    {
        return Yii::$app->db->createCommand("UPDATE `site_comments` SET `status`=:status WHERE id=:id",[':id'=>$id,':status'=>self::STATUS_DELETED])->execute();
    }

    public static function CommentFind($id) {
        return Yii::$app->db->createCommand("SELECT * FROM `site_comments` WHERE `id`=:id",[':id' => $id])->queryOne();
    }

    public static function CommentDeletePermanently($id) {
        Yii::$app->db->createCommand("DELETE FROM `site_comments` WHERE `id`=:id", [':id' => $id])->execute();
    }

    /** Serh */
    public static function CommentAll()
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_comments` WHERE `status`<>:status",[':status'=>self::STATUS_DELETED])->queryAll();
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
