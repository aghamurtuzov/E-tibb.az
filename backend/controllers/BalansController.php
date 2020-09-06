<?php
/**
 * Created by PhpStorm.
 * User: Chingiz
 * Date: 5/7/2019
 * Time: 10:57 AM
 */

namespace backend\controllers;
use backend\components\Functions;
use backend\models\Email;
use backend\models\SiteDoctors;
use backend\models\SiteEnterprises;
use backend\models\SiteUsers;
use Yii;
use yii\web\Controller;
use backend\controllers\MainController;

class BalansController extends MainController
{
    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionSetDoctorBalans()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel(new SiteDoctors(),Yii::$app->request->post('id'));
            $old_balance = $model->balance;
            if ($model->load(Yii::$app->request->post())) {
                $model->balance = intval($model->balance * 100) + $old_balance;
                if ($model->save(false)) {
                    Yii::$app->session->setFlash("success", "Balansınız artirildi!");
                    return $this->asJson(['data' => 'success']);
                } else {
                    return $this->asJson(['data' => 'error']);
                }
            }
        }
    }
    public function actionSetEnterpriseBalans()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel(new SiteEnterprises(),Yii::$app->request->post('id'));
            $old_balance = $model->balance;
            if ($model->load(Yii::$app->request->post())) {
                $model->balance = intval($model->balance * 100) + $old_balance;
                if ($model->save(false)) {
                    Yii::$app->session->setFlash("success", "Balansınız artirildi!");
                    return $this->asJson(['data' => 'success']);
                } else {
                    return $this->asJson(['data' => 'error']);
                }
            }
        }
    }
    protected function findModel($modelName, $id)
    {
        if (($model = $modelName::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}