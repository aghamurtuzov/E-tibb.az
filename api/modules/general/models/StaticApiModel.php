<?PHP

namespace api\modules\general\models;

use yii;
use yii\base\Model;

class StaticApiModel extends Model
{
    /** Info */
    public static function info($name)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_menus` WHERE `name`=:name_",[':name_'=>$name])->queryOne();
    }
}