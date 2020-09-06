<?php
namespace frontend\controllers;

use backend\models\SiteDoctors;
use backend\models\SiteEnterprises;
use dp\models\SiteAppointment;
use frontend\models\Doctor;
use frontend\models\Enterprise;
use frontend\models\LoginForm;
use frontend\models\User;
use frontend\models\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use frontend\components\Menu;
use yii\helpers\ArrayHelper;
use frontend\components\SeoLib;

/**
* Auth controller
*/
class AuthController extends Controller
{

    public $menus;
    public $layout = 'static';
    public $rememberMe = 1;
    const  TYPE = 0;
    public $seo;

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($action)
    {

        if(!Yii::$app->user->isGuest){
            $this->redirect(['/profile']);
            return false;
        }


        $menus       = ArrayHelper::toArray(new Menu());
        $this->menus = $menus['list'];
        $this->seo   = new SeoLib();
        return parent::beforeAction($action);
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        if($model->load(Yii::$app->request->post())){
            /* $model->rememberMe = true;*/
            if($model->login()){
                return $this->redirect(['profile/index']);
            }
        }
        return $this->render('login',[
            'model' => $model
        ]);
    }

    public function actionLogin2()
    {

        if(!Yii::$app->request->isAjax){
            exit();
        }

        $model = new LoginForm();

        if  (Yii::$app->request->isAjax) {
            if($model->load(Yii::$app->request->post())){
                if($model->login()){
                    $id   = Yii::$app->user->identity->id;
                    $type = Yii::$app->user->identity->type;

                    Yii::$app->session->remove('gakey');

                    if($type == User::TYPE_DOCTOR)
                    {
                        $getDoctor = SiteDoctors::findOne(['user_id'=>Yii::$app->user->identity->id]);
                        $id = $getDoctor['id'];
                        $user_id = $getDoctor['user_id'];
                    }else if($type == User::TYPE_ENTERPRISE){
                        $getEnterprise = SiteEnterprises::findOne(['user_id'=>Yii::$app->user->identity->id]);
                        $id = $getEnterprise['id'];
                        $user_id = $getEnterprise['user_id'];
                    }
                    if(empty($id))
                    {
                        Yii::$app->user->logout();
                        return false;
                    }

                    Yii::$app->session->set('userID',$id);
                    Yii::$app->session->set('userType',$type);
                    Yii::$app->session->set('user_id',$user_id);
                    return $this->redirect(['/profile/index']);
                }
                $model->password = '';

                return $this->renderAjax('/layouts/partials/modals/login_form', [
                    'model' => $model
                ]);
            }
        }
    }

    public function actionRegister()
    {
        if(!Yii::$app->user->isGuest){
            if(Yii::$app->user->identity->type==0){
                return $this->redirect(["/profile"]);
            }
        }

        $enterprise_categories = $this->menus["type"][2];
        $model = new User();

        if($model->load(Yii::$app->request->post()) and $model->validate()){
            $lastRow = User::find()->select('id')->orderBy(['id' => SORT_DESC])->one();
            $model->unique_id = str_pad($lastRow['id'] + 1, 7, 0, STR_PAD_LEFT);
            $model->type = self::TYPE;
            $model->status = User::STATUS_PENDING;

            $model->last_login = date("Y-m-d H:i:s");
            $model->setPassword($model->password);
            $model->generateAuthKey();
            $model->birthday = !empty($model->birthday) ? date("Y-m-d",strtotime($model->birthday)) : null;
            if($model->save(false)){
                Yii::$app->session->setFlash('register_success','Sizin məlumatlarınız təsdiq üçün göndərildi. Tezliklə əlaqə saxlayacağıq');
                return $this->redirect(["main/success"]);
//                if($model->status==User::STATUS_PENDING){
//                    Yii::$app->user->login($model, $this->rememberMe ? 3600 * 24 * 30 : 0);
//                    Yii::$app->session->setFlash('register_success','Siz uğurla qeydiyyatdan keçdiniz');
//                    return $this->redirect(Url::to(["main/success"]));
//                }else{
//                    Yii::$app->session->setFlash("warning","Sizin məlumatlarınız təsdiq üçün göndərildi. Tezliklə əlaqə saxlayacağıq");
//                    return $this->redirect(Url::to(["site/message"]));
//
//                }
            }
        }

        return $this->render('register',[
            'model' => $model,
            'enterprise_categories' => $enterprise_categories
        ]);

    }

    public function actionRegister2()
    {
        exit();
        $model = new User();

        if  (Yii::$app->request->isAjax) {
            if($model->load(Yii::$app->request->post())){

                if($model->validate()){
                    $model->last_login = date("Y-m-d H:i:s");
                    if($model->type == 0){
                        $model->status = User::STATUS_ACTIVE;
                    }else{
                        $model->status = User::STATUS_PENDING;
                    }

                    $model->setPassword($model->password);
                    $model->generateAuthKey();
                    $model->birthday = !empty($model->birthday) ? date("Y-m-d",strtotime($model->birthday)) : null;
                    $lastRow = User::find()->select('id')->orderBy(['id' => SORT_DESC])->one();
                    $model->unique_id = str_pad($lastRow['id'] + 1, 7, 0, STR_PAD_LEFT);
                    if($model->save(false)){
                        if($model->status==User::STATUS_ACTIVE){
                            Yii::$app->user->login($model, $this->rememberMe ? 3600 * 24 * 30 : 0);
                            return $this->redirect(["profile/index"]);
                        }elseif($model->status==User::STATUS_PENDING ){
                            Yii::$app->user->login($model, $this->rememberMe ? 3600 * 24 * 30 : 0);
                            Yii::$app->session->setFlash("warning","Sizin məlumatlarınız təsdiq üçün göndərildi. Tezliklə əlaqə saxlayacağıq");
                            return $this->redirect(["profile/index"]);
                        }
                    }
                }

//                $model->password = $model->repassword;

                return $this->renderAjax('/layouts/partials/modals/register_form',[
                    'model' => $model
                ]);
            }
        }
    }

    public function actionRegister3()
    {

        $this->layout = 'registration';

        /** Meta tags */
        $metaData["title"]         = 'İstifadəçi qeydiyyatı';
        $metaData["canonical"]     = Url::base('https').'/istifadeci-qeydiyyat';
        $metaData["page_type"]     = "website";
        $metaData["amp_status"]    = 0;
        $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];

        Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);
        /** Meta tags END */

        $model = new User();

        if($model->load(Yii::$app->request->post())){
            $model->type = 0;
            if($model->validate()){
                $model->phone_prefix = "994";
                $model->last_login = date("Y-m-d H:i:s");
                if($model->type == 0){
                    $model->status = User::STATUS_ACTIVE;
                }else{
                    $model->status = User::STATUS_PENDING;
                }

                $model->setPassword($model->password);
                $model->generateAuthKey();
                $model->birthday = !empty($model->birthday) ? date("Y-m-d",strtotime($model->birthday)) : null;

                $lastRow = User::find()->select('id')->orderBy(['id' => SORT_DESC])->one();
                $model->unique_id = str_pad($lastRow['id'] + 1, 7, 0, STR_PAD_LEFT);
                if($model->save(false)){
                    if($model->status==User::STATUS_ACTIVE){
                        Yii::$app->user->login($model, $this->rememberMe ? 3600 * 24 * 30 : 0);
                        return $this->redirect(["profile/index"]);
                    }elseif($model->status==User::STATUS_PENDING ){
                        Yii::$app->user->login($model, $this->rememberMe ? 3600 * 24 * 30 : 0);
                        Yii::$app->session->setFlash("warning","Sizin məlumatlarınız təsdiq üçün göndərildi. Tezliklə əlaqə saxlayacağıq");
                        return $this->redirect(["profile/index"]);
                    }
                }
            }
        }

        return $this->render('register3', [
            'model' => $model,
        ]);

    }
}
