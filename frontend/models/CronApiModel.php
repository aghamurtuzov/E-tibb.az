<?php


namespace frontend\models;

use yii;
use yii\base\Model;

class CronApiModel extends Model
{
    public function getTasks() {
        return Yii::$app->db->createCommand("SELECT * FROM `site_notification_tasks`")->queryAll();
    }

    // Fill users into
    public function fillUsers($case_id, $send_type, $assets = null) {
        return Yii::$app->db->createCommand("INSERT INTO `site_notification_users`(user_id, case_id, send_type, assets) SELECT id, '$case_id', '$send_type', '$assets' FROM `site_users` WHERE site_users.type=0 AND site_users.status=1")->execute();
    }

    public function deleteTask($id) {
        if(!empty($id)) {
            return Yii::$app->db->createCommand("DELETE FROM `site_notification_tasks` WHERE id = '$id'")->execute();
        }
    }


}
