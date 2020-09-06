<?php

namespace api\modules\general\controllers;

use api\models\AdminLog;
use api\models\AdminUsers;
use api\models\AuthItem;
use backend\models\AdminUsersSearch;
use yii;



class RoleController extends MainController
{
    public $modelClass = '';
    public $customPath = 'roles';

    const STATUS_ACTIVE = 10;
    const STATUS_DEACTIVE = 0;


    public function actionIndex()
    {
        $searchModel = new AdminUsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }




    /**
     *
     * https://e-tibb.az/api/general/role/create
     */
    public function actionCreate()
    {

        $model = new AdminUsers();
        $data['authItems'] = AuthItem::getPermissions();

        if($model->load(Yii::$app->request->post()))
        {
            if(Yii::$app->request->post($model->getClassName()))
            {


                $model->setPassword($model->password);

                $model->generateAuthKey();

                if($model->save())
                {

                    $permissions = $model->permissions;

                    if($permissions)
                    {
                        $auth     = Yii::$app->authManager;
                        $authRole = $auth->getRole($permissions);
                        $auth->assign($authRole,$model->id);
                    }
                    AdminLog::write(['id' => $model->id, 'name' => $model->name]);

                    Yii::$app->session->setFlash('success',lang('data_added'));
                }else{
                    Yii::$app->session->setFlash('error',lang('module_error'));
                }


                return $this->redirect(['index']);

            };
        };

        return $this->render('create', [
            'model' => $model,
            'data' => $data
        ]);

    }

    /**
     * Updates an existing AdminUsers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model    = $this->findModel($id);
        $oldModel = $this->findModel($id);

        $data['authItems']        = AuthItem::getPermissions();
        $checkedAuthItems = AuthAssignment::getPermission($id);
        $data['checkedAuthItems'] = !empty($checkedAuthItems) ? $checkedAuthItems['item_name'] : null;

        if($model->load(Yii::$app->request->post()))
        {
            if(Yii::$app->request->post($model->getClassName()))
            {
                if(!empty($model->password))
                {
                    $model->setPassword($model->password);
                }else{
                    $model->password = $oldModel->password;
                }

                if($model->save())
                {

                    /** Permissions */
                    if($data['checkedAuthItems'] != $model->permissions)
                    {

                        if(!empty($data['checkedAuthItems']))
                        {
                            $auth     = Yii::$app->authManager;
                            $authRole = $auth->getRole($data['checkedAuthItems']);
                            $auth->revoke($authRole,$model->id);
                        }

                        $auth     = Yii::$app->authManager;
                        $authRole = $auth->getRole($model->permissions);
                        $auth->assign($authRole,$model->id);

                    }
                    AdminLog::write(['id' => $model->id, 'name' => $model->name]);

                    Yii::$app->session->setFlash('success',lang('data_updated'));
                }else{
                    Yii::$app->session->setFlash('error',lang('module_error'));
                }

                return $this->redirect(['index']);

            }
        }

        return $this->render('update', [
            'model' => $model,
            'data'  => $data
        ]);
    }

    public function actionStatus()
    {

        $id = intval(Yii::$app->request->post('id'));

        $model = $this->findModel($id);

        $model->status = $model->status == AdminUsers::STATUS_ACTIVE ? AdminUsers::STATUS_DEACTIVE : AdminUsers::STATUS_ACTIVE;

        if($model->save(false))
        {
            Yii::$app->session->setFlash('success',lang('change_status'));
        }else{
            Yii::$app->session->setFlash('error',lang('module_error'));
        }

        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);

    }

    /**
     * Deletes an existing AdminUsers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if($this->findModel($id)->delete())
        {
            $deleteAuthData = AuthAssignment::deleteAll(['user_id'=>$id]);
            if($deleteAuthData)
            {
                Yii::$app->session->setFlash('success',lang('deleted_data'));
            }
        }else{
            Yii::$app->session->setFlash('error',lang('no_deleted_data'));
        }

        return $this->redirect(['index']);
    }

    public function actionDeletemore()
    {

        $ids = Yii::$app->request->post('del_check');

        if(!empty($ids))
        {
            foreach ($ids as $id)
            {
                $this->actionDelete($id);
            }
            Yii::$app->session->setFlash('success',lang('deleted_all_data'));
        }else{
            Yii::$app->session->setFlash('error',lang('no_deleted_all_data'));
        }

        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);

    }
}