<?PHP

namespace api\models;

use yii;
use yii\base\Model;

class SliderApiModel extends Model
{
    
    public function SliderCount()
    {
        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_slider` ORDER BY `id` DESC")->queryOne();
    }

    public function Slider($limits)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_slider` ORDER BY `id` DESC LIMIT {$limits[0]},{$limits[1]}")->queryAll();
    }
}