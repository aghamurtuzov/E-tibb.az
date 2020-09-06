<?PHP

namespace api\models;

use yii;
use yii\base\Model;
use api\models\SitePhotoGallery;

class PhotoGalleryApiModel extends Model
{
    public static function GetGalleryImagesByConnect($connect_id, $type) {
        return Yii::$app->db->createCommand("SELECT id, name FROM `site_photo_gallery` WHERE connect_id=:connect_id AND type=:type ORDER BY id DESC",[':connect_id'=>$connect_id, ':type'=>$type])->queryAll();
    }

    public static function DeleteImagesByConnect($connect_id) {
        if(!empty($connect_id)) {
            Yii::$app->db->createCommand("DELETE FROM `site_photo_gallery` WHERE `connect_id`=:connect_id", [':connect_id' => $connect_id])->execute();
        }
    }

    public static function GetGalleryImagesByConnectAndIds($connect_id, $ids) {
        return Yii::$app->db->createCommand("SELECT id, name FROM `site_photo_gallery` WHERE connect_id=:connect_id AND id IN(".$ids.") ", [':connect_id' => $connect_id])->queryAll();
    }

    public static function DeleteImagesByIds($ids) {
        if(!empty($ids)) {
            Yii::$app->db->createCommand("DELETE FROM `site_photo_gallery` WHERE id IN(".$ids.") ")->execute();
        }
    }

}
