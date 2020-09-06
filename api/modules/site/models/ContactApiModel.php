<?PHP

namespace api\modules\general\models;

use yii;
use yii\base\Model;

class ContactApiModel extends Model
{
    /** Ads */
    public function Contact($id)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_contact` WHERE `id`=:id LIMIT 1",[':id'=>$id])->queryOne();
    }

    /** Ads */
    public function ContactCount()
    {
        return Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `site_contact`")->queryOne();
    }

    public function ContactAll($limits)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_contact` ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}")->queryAll();
    }

    public function AdsList($id,$limits,$type = 1)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_ads` WHERE `user_id`=:id AND `type`=:type_ AND `status`<>:status ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}",[':id'=>$id,':type_'=>$type,':status'=>self::STATUS_DELETED])->queryAll();
    }

    /** Contact sil */
    public function ContactDelete($id)
    {
        return Yii::$app->db->createCommand("DELETE FROM `site_contact` WHERE `id`=:id",[':id'=>$id])->execute();
    }
    
}
?>