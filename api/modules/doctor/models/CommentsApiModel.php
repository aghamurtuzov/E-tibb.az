<?PHP

namespace api\modules\doctor\models;

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
    public static function CommentsCount()
    {
        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_comments` WHERE `status`<>:status",[':status'=>self::STATUS_DELETED])->queryOne();
    }

    public static function Comments($limits)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_comments` WHERE `status`<>:status ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}",[':status'=>self::STATUS_DELETED])->queryAll();
    }

    public static function CommentsByConnectCount($userID, $status = '')
    {
        $add = "";
        if(!empty($status)) {
            $add = " AND status=".$status;
        }
        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_comments` WHERE `connect_id`=:connect_id AND `type`=:type_ AND `status`<>:status ".$add,['type_'=>self::TYPE_DOCTOR,':connect_id'=>$userID,':status'=>self::STATUS_DELETED])->queryOne();
    }

    public static function CommentsByConnect($limits,$userID, $status = '')
    {
        $add = "";
        if(!empty($status)) {
            $add = " AND status=".$status;
        }

        return Yii::$app->db->createCommand("SELECT * FROM `site_comments` WHERE `connect_id`=:connect_id AND `type`=:type_ AND `status`<>:status ".$add." ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}",['type_'=>self::TYPE_DOCTOR,':connect_id'=>$userID,':status'=>self::STATUS_DELETED])->queryAll();
    }

    /** Hekim Obyekt Xeber serhi */
    public function CommentBy($id,$type = self::TYPE_DOCTOR)
    {
        if($type == self::TYPE_DOCTOR)
        {
            return Yii::$app->db->createCommand("SELECT `name` FROM `site_doctors` WHERE `id`=:id LIMIT 1",[':id'=>$id])->queryScalar();
        }else if($type == self::TYPE_ENTERPRISE){
            return Yii::$app->db->createCommand("SELECT `name` FROM `site_enterprises` WHERE `id`=:id LIMIT 1",[':id'=>$id])->queryScalar();
        }else{
            return Yii::$app->db->createCommand("SELECT `headline` FROM `site_news` WHERE `id`=:id LIMIT 1",[':id'=>$id])->queryScalar();
        }
    }

    /** Serh status deyismek */
    public static function ChangeStatus($id,$status)
    {
        return Yii::$app->db->createCommand("UPDATE `site_comments` SET `status`=:status WHERE id=:id",[':id'=>$id,'status'=>$status])->execute();
    }

    public static function ChangeConnectStatus($id,$status,$userID)
    {
        return Yii::$app->db->createCommand("UPDATE `site_comments` SET `status`=:status WHERE id=:id AND `connect_id`=:connect_id",[':connect_id'=>$userID,':id'=>$id,'status'=>$status])->execute();
    }

    public static function CommentFind($id)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_comments` WHERE id=:id",[':id'=>$id])->queryOne();
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