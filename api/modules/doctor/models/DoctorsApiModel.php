<?PHP

namespace api\modules\doctor\models;

use yii;
use yii\base\Model;
use backend\models\SiteDoctors;
use api\components\Pagination;

class DoctorsApiModel extends Model
{

    /** Hekimler */
    public function DoctorsCount()
    {
        return Yii::$app->db->createCommand("select count(`id`) as `count` from `site_doctors`")->queryOne();
    }

    public function Doctors($limits)
    {
        return Yii::$app->db->createCommand("SELECT `id`,`name`,`email`,`photo` FROM `site_doctors` ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}")->queryAll();
    }
    
    /** Hekim block */
    public function Block($id)
    {
        return Yii::$app->db->createCommand("UPDATE `site_doctors` SET `status`=:status WHERE `id`=:id",[':id'=>$id,':status'=>0])->execute();
    }

    /** Hekim active */
    public function Active($id)
    {
        return Yii::$app->db->createCommand("UPDATE `site_doctors` SET `status`=:status WHERE `id`=:id",[':id'=>$id,':status'=>1])->execute();
    }

}

?>