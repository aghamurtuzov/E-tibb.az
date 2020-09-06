<?php

namespace api\components;

use Yii;
use backend\models\AdminUsers;

class UserHelper {

    public static function can($permission = '') {
        $session = Yii::$app->session;
        $key = $session->get('gakey');
        if(!empty($key)) {
            $user = AdminUsers::find()->where(new \yii\db\Expression('FIND_IN_SET(:key, login_key)'))->addParams([':key' => $key])->one();
            if(!empty($user)) {
                $auth_assignment = Yii::$app->db->createCommand("SELECT item_name FROM `auth_assignment` WHERE user_id=:user_id", [":user_id" => $user['id']])->queryOne();
                if(!empty($auth_assignment)) {
                    $item_name = $auth_assignment['item_name'];
                    if(!empty($item_name)) {
                        if($item_name == $permission) {
                            return true;
                        } else {
                            $auth_assignment = Yii::$app->db->createCommand("SELECT * FROM `auth_item_child` WHERE child=:child AND parent=:parent", [":child" => $permission, ':parent' => $item_name])->queryOne();
                            if($auth_assignment) {
                                return true;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

}
