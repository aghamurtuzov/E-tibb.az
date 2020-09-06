<?PHP

namespace api\modules\general\models;

use yii;
use yii\base\Model;

class AdsApiModel extends Model
{

    const STATUS_DEACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    const STATUS_PENDİNG  = 2;
    const STATUS_DELETED  = 3;

    const TYPE_USER = 0;
    const TYPE_DOCTOR = 1;
    const TYPE_ENTERPRISE = 2;

    /** Ads */
    public function Ads($id,$isBlood=1)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_ads` WHERE `id`=:id AND `status`<>:status AND `is_blood`=:is_blood LIMIT 1",[':id'=>$id,':status'=>self::STATUS_DELETED,':is_blood'=>$isBlood])->queryOne();
    }

    /** Ads baqli obyekt ve ya hekim */
    public function getOrganizer($id,$type = self::TYPE_DOCTOR)
    {
        if($type == self::TYPE_DOCTOR)
        {
            return Yii::$app->db->createCommand("SELECT `name` FROM `site_doctors` WHERE `id`=:id LIMIT 1",[':id'=>$id])->queryScalar();
        }else if($type == self::TYPE_ENTERPRISE){
            return Yii::$app->db->createCommand("SELECT `name` FROM `site_enterprises` WHERE `id`=:id LIMIT 1",[':id'=>$id])->queryScalar();
        }else if($type == self::TYPE_USER){
            return Yii::$app->db->createCommand("SELECT `name` FROM `site_users` WHERE `id`=:id LIMIT 1",[':id'=>$id])->queryScalar();
        }
    }

    /** Ads */
    public function AdsCount($search)
    {
        if(strlen($search)>=3)
        {
            return Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `site_ads` WHERE `status`<>:status AND `title` LIKE '%$search%'",[':status'=>self::STATUS_DELETED])->queryOne();
        }
        else
        {
            return Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `site_ads` WHERE `status`<>:status",[':status'=>self::STATUS_DELETED])->queryOne();
        }
    }

    public function AdsAll($limits,$search)
    {
        if(strlen($search)>=3)
        {
            return Yii::$app->db->createCommand("SELECT * FROM `site_ads`  WHERE `status`<>:status AND `title` LIKE '%$search%' ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}",[':status'=>self::STATUS_DELETED])->queryAll();
        }
        else
        {
            return Yii::$app->db->createCommand("SELECT * FROM `site_ads`  WHERE `status`<>:status ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}",[':status'=>self::STATUS_DELETED])->queryAll();
        }
    }

    /** Obyekte ve ya hekime aid ads */
    public function AdsListCount($id,$type = 1,$search)
    {
        if(strlen($search)>=3)
        {
            return Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `site_ads` WHERE `user_id`=:id AND `status`<>:status AND `type`=:type_ AND `title` LIKE '%$search%'",[':id'=>$id,':type_'=>$type,':status'=>self::STATUS_DELETED])->queryOne();
        }
        else
        {
            return Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `site_ads` WHERE `user_id`=:id AND `status`<>:status AND `type`=:type_",[':id'=>$id,':type_'=>$type,':status'=>self::STATUS_DELETED])->queryOne();
        }
    }

    public function AdsList($id,$limits,$type = 1,$search)
    {
        if(strlen($search)>=3)
        {
            return Yii::$app->db->createCommand("SELECT * FROM `site_ads` WHERE `user_id`=:id AND `type`=:type_ AND `status`<>:status AND `title` LIKE '%$search%' ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}",[':id'=>$id,':type_'=>$type,':status'=>self::STATUS_DELETED])->queryAll();
        }
        else
        {
            return Yii::$app->db->createCommand("SELECT * FROM `site_ads` WHERE `user_id`=:id AND `type`=:type_ AND `status`<>:status ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}",[':id'=>$id,':type_'=>$type,':status'=>self::STATUS_DELETED])->queryAll();
        }
    }

    /** Ads sil */
    public function AdsDelete($id)
    {
        return Yii::$app->db->createCommand("UPDATE `site_ads` SET `status`=:status WHERE id=:id",[':id'=>$id,':status'=>self::STATUS_DELETED])->execute();
    }
    
}
?>