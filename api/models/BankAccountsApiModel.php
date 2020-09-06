<?PHP

namespace api\models;

use yii;
use yii\base\Model;

class BankAccountsApiModel extends Model
{

    const STATUS_DEACTIVE = 0;
    const STATUS_ACTIVE   = 1;

    /** Bank hesablari */

    public function Accounts()
    {
        return Yii::$app->db->createCommand("SELECT * FROM `bank_accounts` ORDER BY id DESC")->queryAll();
    }

}

?>