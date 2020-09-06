<?php

namespace api\modules\general\controllers;

use yii;
use api\models\SiteUsers;
use api\components\Pagination;
use api\modules\general\models\UsersApiModel;
use api\modules\general\controllers\MainController;

/**2
 *
 * User API
 */

class UsersController extends MainController
{

    public $modelClass = '';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'],$actions['create']);
        return $actions;
    }

    /**
     * Istideciler
     * https://e-tibb.az/api/general/users
     * https://e-tibb.az/api/general/users?page=1&count=5
     */
    public function actionIndex()
    {
        $model  = new UsersApiModel();
        $status = SiteUsers::get_Status();
        $type   = SiteUsers::get_Type();

        $totalCount = $model->UsersCount();

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $model->Users($limits);

        if(!empty($list))
        {
            foreach($list as $key => $val)
            {
                unset($list[$key]['password']);
                unset($list[$key]['auth_key']);
                unset($list[$key]['password_reset_token']);
                $list[$key]['status_name'] = $status[$list[$key]['status']];
                $list[$key]['type_name']   = $type[$list[$key]['type']];
            }
        }

        $data['list'] = $list;

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages,$data);

        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Istifadeci block
     * https://e-tibb.az/api/general/comments/block
     */
    public function actionBlock()
    {
        $id = intval(Yii::$app->request->post('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $update = UsersApiModel::ChangeStatus($id,UsersApiModel::STATUS_DEACTIVE);
        if($update)
        {
            return $this->response(200,"Melumat bloklandı");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Istifadeci aktiv
     * https://e-tibb.az/api/general/comments/active
     */
    public function actionActive()
    {
        $id = intval(Yii::$app->request->post('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $update = UsersApiModel::ChangeStatus($id,UsersApiModel::STATUS_ACTIVE);
        if($update)
        {
            return $this->response(200,"Melumat aktiv edildi");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * https://e-tibb.az/api/general/users/user-phone
     * Users phone number mask
     */
    public function actionUserPhone(){
        $DefaultNumnber = '0775643888';
        $mass = [];
        $users = SiteUsers::find()->where(['type' => 2])->all();


        if (!empty($users)) {
            foreach ($users as $key => $user) {
                $phone_number = $user->phone_number;

                if (strlen($phone_number) < 9) {
                    $phone_number = '';
                }

                $first3simvol = substr($phone_number, 0, 3);
                if ($first3simvol == '994') {
                    $phone_number = '0' . substr($phone_number,3);
                }
                $first3simvol = substr($phone_number, 0, 3);
                if ($first3simvol == '094') {
                    $phone_number = '0' . substr($phone_number,3);
                }
                $first3simvol = substr($phone_number, 0, 4);
                if ($first3simvol == '+994') {
                    $phone_number = '0' . substr($phone_number,3);
                }
                if (empty($phone_number) || $phone_number == 'NaN' || strlen($phone_number) < 9) {
                    $phone_number = $DefaultNumnber;
                }


                $user->phone_number = $phone_number;
                if ($user->save(false)) {
                    $mass[] = $user->phone_number;
                } else {
                    $mass[$user->id] = 'no';
                }

            }
        }

        return $mass;
    }

}