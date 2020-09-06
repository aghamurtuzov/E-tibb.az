<?php
namespace frontend\controllers;

//use dp\models\SiteAppointment;
//use frontend\models\Doctor;
//use frontend\models\Enterprise;
//use frontend\models\User;
//use frontend\models\SignupForm;
//use yii\filters\AccessControl;
//use yii\filters\VerbFilter;
//use yii\web\NotFoundHttpException;
//use frontend\components\Menu;
//use yii\helpers\ArrayHelper;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use frontend\models\LoginTestForm;
use frontend\models\LoginUserModel;
use backend\models\SiteDoctors;
use backend\models\SiteEnterprises;

/**
 * Login controller
 */
class LoginController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionMogin()
    {

        if(!Yii::$app->request->isAjax){
            exit();
        }

        $model = new LoginTestForm();
        if($model->load(Yii::$app->request->post()) && $model->login())
        {
            $id   = Yii::$app->user->identity->id;
            $type = Yii::$app->user->identity->type;

            if($type == LoginUserModel::TYPE_DOCTOR)
            {
                $getDoctor = SiteDoctors::findOne(['user_id'=>Yii::$app->user->identity->id]);
                $id = $getDoctor['id'];
            }else if($type == LoginUserModel::TYPE_ENTERPRISE){
                $getEnterprise = SiteEnterprises::findOne(['user_id'=>Yii::$app->user->identity->id]);
                $id = $getEnterprise['id'];
            }

            if(empty($id))
            {
                Yii::$app->user->logout();
                return false;
            }

            Yii::$app->session->set('userID',$id);
            Yii::$app->session->set('userType',$type);
            //return $this->redirect(Url::to(["site/index"]));
            exit('Ok');
        }else{
            $model->password = '';
            return $this->renderAjax('/layouts/partials/modals/login_form', [
                'model' => $model
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

}