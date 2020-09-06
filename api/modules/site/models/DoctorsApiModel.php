<?PHP

namespace api\modules\site\models;

use yii;
use yii\base\Model;
use backend\models\SiteDoctors;
use api\components\Pagination;

class DoctorsApiModel extends Model
{

    const STATUS_DEACTIVE = 0;
    const STATUS_ACTIVE   = 1;

    /** Hekimler limit ile */
    public static function LimitedDoctor($limit = 12,$categoryID = null,$vip)
    {
        $limit = $limit<=0 ? 12: $limit;
        if($vip > 0){
            $result = Yii::$app->db->createCommand("SELECT * FROM `view_doctors_list` WHERE `is_premium`=1 and `status`=:status_ ORDER BY `id` DESC LIMIT :limit",[":limit"=>$limit,':status_'=>self::STATUS_ACTIVE])->cache(20)->queryAll();
        }else if(!empty($categoryID)){
            $result = Yii::$app->db->createCommand("SELECT * FROM `view_doctors_list` WHERE `specialist_id`=:category and `status`=:status_ ORDER BY `id` DESC LIMIT :limit",[":limit"=>$limit,":category"=>$categoryID,':status_'=>self::STATUS_ACTIVE])->cache(20)->queryAll();
        }else{
            $result = Yii::$app->db->createCommand("SELECT * FROM `view_doctors_list` WHERE `status`=:status_ ORDER BY `id` DESC LIMIT :limit",[":limit"=>$limit,':status_'=>self::STATUS_ACTIVE])->cache(20)->queryAll();
        }
        return $result;
    }

    /** Hekimler */
    public static function DoctorsCount($keyowrd = null,$category = null)
    {
        if($keyowrd!=null && $category!=null)
        {
            $result = Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `view_doctors_list` WHERE `specialist_id`=:category and `status`=:status_ and (`name` LIKE '%$keyowrd%')",[":category"=>$category,':status_'=>self::STATUS_ACTIVE])->cache(20)->queryOne();
        }
        elseif($keyowrd!=null)
        {
            $result = Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `view_doctors_list` WHERE `status`=:status_ and (`name` LIKE '%$keyowrd%')",[':status_'=>self::STATUS_ACTIVE])->cache(20)->queryOne();
        }
        elseif($category!=null)
        {
            $result = Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `view_doctors_list` WHERE `specialist_id`=:category and `status`=:status_",[":category"=>$category,':status_'=>self::STATUS_ACTIVE])->cache(20)->queryOne();
        }
        else
        {
            $result = Yii::$app->db->createCommand("SELECT count(`id`) as `count` FROM `view_doctors_list` WHERE `status`=:status_",[':status_'=>self::STATUS_ACTIVE])->cache(20)->queryOne();
        }
        return $result;
    }

    public static function Doctors($limits,$keyowrd=null,$category=null)
    {
        if($keyowrd!=null && $category!=null)
        {
            $result = Yii::$app->db->createCommand("SELECT * FROM `view_doctors_list` WHERE `status`=:status_ and (`name` LIKE '%$keyowrd%') and `specialist_id`=:category ORDER BY `id` DESC LIMIT :offset,:limit",[":offset"=>$limits[0],":limit"=>$limits[1],":category"=>$category,':status_'=>self::STATUS_ACTIVE])->cache(20)->queryAll();
        }
        elseif($keyowrd!=null)
        {
            $result = Yii::$app->db->createCommand("SELECT * FROM `view_doctors_list` WHERE `status`=:status_ and (`name` LIKE '%$keyowrd%') ORDER BY `id` DESC LIMIT :offset,:limit",[":offset"=>$limits[0],":limit"=>$limits[1],':status_'=>self::STATUS_ACTIVE])->cache(20)->queryAll();
        }
        elseif($category!=null)
        {
            $result = Yii::$app->db->createCommand("SELECT * FROM `view_doctors_list` WHERE `status`=:status_ and `specialist_id`=:category ORDER BY `id` DESC LIMIT :offset,:limit",[":offset"=>$limits[0],":limit"=>$limits[1],":category"=>$category,':status_'=>self::STATUS_ACTIVE])->cache(20)->queryAll();
        }
        else
        {
            $result = Yii::$app->db->createCommand("SELECT * FROM `view_doctors_list` WHERE `status`=:status_ ORDER BY `id` DESC LIMIT :offset,:limit",[":offset"=>$limits[0],":limit"=>$limits[1],':status_'=>self::STATUS_ACTIVE])->cache(20)->queryAll();
        }

        return $result;
    }


}

?>