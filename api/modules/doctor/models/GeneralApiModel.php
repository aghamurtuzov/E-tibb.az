<?PHP

namespace api\modules\doctor\models;

use yii;
use yii\base\Model;

class GeneralApiModel extends Model
{
    /** Odenisler */
    public function PaymentsCount($id,$type = 1)
    {
        return Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `site_transaction_details` WHERE `connect_id`=:connect_id AND `type`=:type_",[':connect_id'=>$id,':type_'=>$type])->queryOne();
    }

    public function Payments($id,$limits,$type = 1)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_transaction_details` WHERE `connect_id`=:connect_id AND `type`=:type_ ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}",[':connect_id'=>$id,':type_'=>$type])->queryAll();
    }
    
    /** Obyekte hekim elave etmek */
    public function EnterpriseAddDoctor($enterprise_id,$doctor_id)
    {
        return Yii::$app->db->createCommand('INSERT INTO `site_enterprise_doctor`(`enterprise_id`,`doctor_id`) VALUES("'.$enterprise_id.'","'.$doctor_id.'")')->execute();
    }

}

?>