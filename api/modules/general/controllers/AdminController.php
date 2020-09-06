<?php

namespace api\modules\general\controllers;


use api\models\AdminLog;
use api\modules\general\models\AdminUsers;
use api\modules\general\models\AdsApiModel;
use api\modules\general\models\AuthAssignment;
use api\modules\general\models\AuthItem;
use Yii;

class AdminController extends MainController
{

    public $modelClass = '';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Adminler
     * https://e-tibb.az/api/general/admin
     */
    public function actionIndex()
    {
        $data = AdminUsers::getAdmins();

        return $this->response(200, "Məlumat mövcuddur", $data);
    }

    /**
     * Adminler
     * https://e-tibb.az/api/general/admin/select
     */
    public function actionSelect()
    {
        $data['authItems'] = AuthItem::getPermissions();

        return $this->response(200, "Məlumat mövcuddur", $data);
    }

    /**
     * Admin create
     * https://e-tibb.az/api/general/admin/create
     * name=agha
     * username=agha
     * email = agha@gmail.com
     * permissions = admin
     * password = 123456
     * password_repeat = 123456
     * phone = 0555555555
     * status = 1
     */
    public function actionCreate()
    {

        Yii::$app->db->schema->refresh();

        $model = new AdminUsers();

        $model->validate();

        if ($model->load(Yii::$app->request->post(), '')) {

            if ($model->validate()) {

                $model->setPassword($model->password);

                $model->generateAuthKey();

                if ($model->save(false)) {

                    $permissions = $model->permissions;

                    if ($permissions) {
                        $auth = Yii::$app->authManager;
                        $authRole = $auth->getRole($permissions);
                        $auth->assign($authRole, $model->id);
                    }
                    return $this->response(200, "Melumat əlavə olundu");
                }

            } else {
                return $this->response(400, 'Məlumatın əlavə olunması zamanı xəta baş verdi', $model->errors);
            }

        };


        return $this->response(200, "Bir xeta yarandi");

    }

    /**
     * Admin edit
     * https://e-tibb.az/api/general/admin/edit
     * id = 105
     * name=agha
     * username=agha
     * email = agha@gmail.com
     * permissions = admin
     * password = 123456
     * password_repeat = 123456
     * phone = 0555555555
     * status = 1
     */
    public function actionEdit()
    {
        Yii::$app->db->schema->refresh();

        $id = intval(Yii::$app->request->post('id'));

        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        $model = $this->findModel($id);
        $OldModel = $this->findModel($id);

        if ($model->load(Yii::$app->request->post(), '')) {

            //var_dump($model->password);

            if ($model->validate()) {

                if (!empty($model->password)) {
                    $model->setPassword($model->password);
                } else {
                    $model->password = $OldModel->password;
                }

//                $model->setPassword($model->password);
                $model->generateAuthKey();


                if ($model->save(false)) {

                    $permissions = $model->permissions;

                    if ($permissions) {

                        AdminUsers::permissionAdmin($model->permissions, $id);

                    }
                    return $this->response(200, "Melumat redaktə olundu");
                }
            } else {
                return $this->response(400, 'Məlumatın redaktə olunması zamanı xəta baş verdi', $model->errors);
            }


        }

        return $this->response(200, "Bir xeta yarandi");

    }


    /**
     * Admin info
     * https://e-tibb.az/api/general/admin/info
     * id = 105
     */
    public function actionInfo()
    {
        Yii::$app->db->schema->refresh();

        $id = intval(Yii::$app->request->post('id'));

        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        $model = AdminUsers::getAdminsOne($id);

        if ($model) {

            return $this->response(200, "Məlumat mövcuddur", $model);
        }

        return $this->response(400, 'Heçbir məlumat tapılmadı');

    }

    protected function findModel($id)
    {
        if (($model = AdminUsers::findOne($id)) !== null) {
            return $model;
        }
    }

    /**
     * Admin delete
     * https://e-tibb.az/api/general/admin/delete-one
     * id = 105
     */
    public function actionDeleteOne()
    {
        $id = Yii::$app->request->post('id');
        $userId = Yii::$app->session->get('userID');

        try {
            if (Yii::$app->userCheck->can("superadmin")) {

                if ($id != $userId) {
                    $data = $this->findModel($id);

                    AdminUsers::adminDeleteOne($id);

                    AdminLog::write(['id' => $data['id'], 'name' => $data['name']]);

                    return $this->response(200, "Məlumat silindi");
                } else {
                    return $this->response(403, "Bu əməliyyatı icra etmək üçün icazəniz yoxdur");
                }

            } else {
                return $this->response(403, "Bu əməliyyatı icra etmək üçün icazəniz yoxdur");
            }

        } catch (\Exception $e) {
            return $this->response(400, "Bu nömrəli həkim tapilmadı");
        }
    }


}
