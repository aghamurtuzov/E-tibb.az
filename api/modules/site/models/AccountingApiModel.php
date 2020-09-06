<?PHP

namespace api\modules\general\models;

use yii;
use yii\base\Model;

class AccountingApiModel extends Model
{

    /** Odenisler | Hekim ve ya Obyekt */
    public function PaymentsCount($id,$type = 1)
    {
        return Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `site_transaction_details` WHERE `connect_id`=:connect_id AND `type`=:type_",[':connect_id'=>$id,':type_'=>$type])->queryOne();
    }

    public function Payments($id,$limits,$type = 1)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_transaction_details` WHERE `connect_id`=:connect_id AND `type`=:type_ ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}",[':connect_id'=>$id,':type_'=>$type])->queryAll();
    }

    /** Butun Odenisler */
    public function AllPaymentsCount()
    {
        return Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `site_transaction_details` AND status=:status_",['status_'=>1])->queryOne();
    }

    public function AllPayments($limits)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_transaction_details` AND status=:status_ ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}",['status_'=>1])->queryAll();
    }

    /** Odenisler | Kartla = 1 Naqd = 0 */
    public function AllCartPaymentsCount($method = 1)
    {
        return Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `site_transaction_details` WHERE payment_method=:method AND status=:status_",[':method'=>$method,'status_'=>1])->queryOne();
    }

    public function AllCartPayments($limits,$method = 1)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_transaction_details` WHERE payment_method=:method AND status=:status_ ORDER BY id DESC LIMIT {$limits[0]},{$limits[1]}",[':method'=>$method,'status_'=>1])->queryAll();
    }

}

?>