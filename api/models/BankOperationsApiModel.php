<?PHP

namespace api\models;

use yii;
use yii\base\Model;

class BankOperationsApiModel extends Model
{

    const STATUS_DEACTIVE = 0;
    const STATUS_ACTIVE   = 1;

    public function OperationsCount()
    {
        return Yii::$app->db->createCommand("SELECT count(`o`.id) AS `count` FROM `bank_account_operations` AS `o` INNER JOIN `bank_accounts` AS `a` ON o.`account_id`=a.`id` ORDER BY o.`id` DESC")->queryOne();
    }

    public function Operations($limits)
    {
        return Yii::$app->db->createCommand("SELECT a.`bank`,o.* FROM `bank_account_operations` AS `o` INNER JOIN `bank_accounts` AS `a` ON o.`account_id`=a.`id` ORDER BY o.id DESC LIMIT {$limits[0]},{$limits[1]}")->queryAll();
    }

}

?>