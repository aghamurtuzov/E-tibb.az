<?PHP

namespace api\modules\site\models;

use yii;
use yii\base\Model;

class PromotionsApiModel extends Model
{

    const STATUS_DEACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    const STATUS_PENDİNG  = 2;
    const STATUS_DELETED  = 3;

    const TYPE_DOCTOR = 1;
    const TYPE_ENTERPRISE = 2;

    /** Aksiya */
    public function Promotion($id)
    {
        return Yii::$app->db->createCommand("SELECT `id`,`headline`,`slug`,`price`,`discount`,`date_start`,`date_end`,`date`,`photo`,`organizer`,`content`,`phones`,`address` FROM `site_promotions` WHERE `id`=:id AND `status`=:status LIMIT 1",[':id'=>$id,':status'=>self::STATUS_ACTIVE])->queryOne();
    }

    /** Aksiyaya baqli obyekt ve ya hekim */
    public function getOrganizer($id,$type = self::TYPE_DOCTOR)
    {
        if($type == self::TYPE_DOCTOR)
        {
            return Yii::$app->db->createCommand("SELECT `name` FROM `site_doctors` WHERE `id`=:id LIMIT 1",[':id'=>$id])->queryScalar();
        }else if($type == self::TYPE_ENTERPRISE){
            return Yii::$app->db->createCommand("SELECT `name` FROM `site_enterprises` WHERE `id`=:id LIMIT 1",[':id'=>$id])->queryScalar();
        }
    }

    /** Aksiyalar */
    public function PromotionsCount($search)
    {
        if(strlen($search)>=3)
        {
            return Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `site_promotions` WHERE (`headline` LIKE '%$search%' OR `organizer` LIKE '%$search%') AND `status`=:status",[':status'=>self::STATUS_ACTIVE])->queryOne();
        }
        else
        {
            return Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `site_promotions` WHERE `status`=:status",[':status'=>self::STATUS_ACTIVE])->queryOne();
        }
    }

    public function Promotions($limits,$search)
    {
        if(strlen($search)>=3)
        {
            return Yii::$app->db->createCommand("SELECT * FROM `site_promotions`  WHERE `status`=:status AND (`headline` LIKE '%$search%' OR `organizer` LIKE '%$search%') ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}",[':status'=>self::STATUS_ACTIVE])->queryAll();
        }
        else
        {
            return Yii::$app->db->createCommand("SELECT `id`,`headline`,`slug`,`price`,`discount`,`date_start`,`date_end`,`date`,`photo`,`organizer` FROM `site_promotions`  WHERE `status`=:status ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}",[':status'=>self::STATUS_ACTIVE])->queryAll();
        }
    }

    /** Obyekte ve ya hekime aid aksiyalar */
    public function PromotionsListCount($id,$type = 1,$search)
    {
        if(strlen($search)>=3)
        {
            return Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `site_promotions` WHERE `connect_id`=:id AND `status`=:status AND `type`=:type_ AND (`headline` LIKE '%$search%' OR `organizer` LIKE '%$search%')",[':id'=>$id,':type_'=>$type,':status'=>self::STATUS_ACTIVE])->queryOne();
        }
        else
        {
            return Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `site_promotions` WHERE `connect_id`=:id AND `status`=:status AND `type`=:type_",[':id'=>$id,':type_'=>$type,':status'=>self::STATUS_ACTIVE])->queryOne();
        }
    }

    public function PromotionsList($id,$limits,$type = 1,$search)
    {
        if(strlen($search)>=3)
        {
            return Yii::$app->db->createCommand("SELECT * FROM `site_promotions` WHERE `connect_id`=:id AND `type`=:type_ AND `status`=:status AND (`headline` LIKE '%$search%' OR `organizer` LIKE '%$search%') ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}",[':id'=>$id,':type_'=>$type,':status'=>self::STATUS_ACTIVE])->queryAll();
        }
        else
        {
            return Yii::$app->db->createCommand("SELECT * FROM `site_promotions` WHERE `connect_id`=:id AND `type`=:type_ AND `status`=:status ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}",[':id'=>$id,':type_'=>$type,':status'=>self::STATUS_ACTIVE])->queryAll();
        }
    }
    
}
?>