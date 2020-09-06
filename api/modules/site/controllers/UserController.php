<?php

namespace api\modules\site\controllers;

use yii;
//use yii\web\UploadedFile;
use api\components\Pagination;
//use api\components\ImageUpload;
use api\components\Functions;
//use api\models\SiteMenus;
//use api\modules\general\models\NewsApiModel;
//use api\modules\general\models\SiteNews;
//use api\modules\general\controllers\MainController;
//use api\models\SiteSlider;
//use api\models\SliderApiModel;
//use api\modules\site\models\User;
use api\modules\site\models\User;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;

/**
 * User API
 */
class UserController extends MainController
{

    public $modelClass = '';
    public $customPath = 'user';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Istifadeci yaratmaq
     * https://e-tibb.az/api/front/user/create
     * name:Java
     * phone_number:9942003040
     * email:java@tibb.tibb
     * birthday:2010-10-10
     * password:424261
     * repassword:424261
     */
    public function actionCreate()
    {
        $model = new User();
        $post = Yii::$app->request->post();

        Yii::$app->db->schema->refresh();

        if(!empty($post) && $model->load($post,''))
        {
            $model->type = User::TYPE_USER;
            $model->status = User::STATUS_ACTIVE;
            $model->last_login = date("Y-m-d H:i:s");
            $model->generateAuthKey();
            $model->birthday = date("Y-m-d",strtotime($model->birthday));

            $lastRow = User::find()->select('max(`id`)')->scalar();
            $model->unique_id = str_pad($lastRow + 1, 7, 0, STR_PAD_LEFT);

            $model->validate();

            if(!empty($model->errors))
            {
                return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi',$model->errors);
            }

            $model->setPassword($model->password);

            if($model->save(false))
            {
                return $this->response(200,'Məlumat uğurla əlavə olundu');
            }
        }

        return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi');
    }

    /**
     * Istifadeci duzelis et
     * https://e-tibb.az/api/front/user/edit
     * id: 123
     * name:Java
     * phone_number:9942003040
     * birthday:2010-10-10
     * password:424261
     * repassword:424261
     */
    public function actionEdit()
    {
        Yii::$app->db->schema->refresh();
        $id = intval(Yii::$app->request->post('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $login = User::findByApiKey(Yii::$app->request->headers->get('loginkey'));
        if(empty($login))
        {
            return $this->response(400,'İstifadəçi daxil olmayıb');
        }

        $model    = $this->findModel($id);
        $oldModel = $this->findModel($id);
        $post = Yii::$app->request->post();

        if(!empty($post) && !empty($post) && $model->load($post,''))
        {

            $model->email = $oldModel->email;

            if(empty($model->password))
            {
                $model->password = $oldModel->password;
                $model->repassword = $oldModel->password;
            }

            $model->validate();

            if(empty($model->repassword) && !empty($model->password))
            {
                $model->addError('repassword', 'Təkrar şifrəni daxil edin');
            }

            if(!empty($model->errors))
            {
                return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi',$model->errors);
            }

            if(!empty($model->password) && !empty($model->repassword))
            {
                $model->setPassword($model->password);
            }

            if($model->save(false))
            {
                return $this->response(200,'Məlumat uğurla yeniləndi');
            }
        }

        return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi');
    }

    /**
     * Istifadeci login
     * https://e-tibb.az/api/front/user/login
     * email: api@etibb.test
     * password: 2884261
     */
    public function actionLogin()
    {
        Yii::$app->db->schema->refresh();

        $model = new LoginForm();
        if($model->load(Yii::$app->request->post(),''))
        {

            $model->validate();

            if(!empty($model->errors))
            {
                return $this->response(400,'İstifadəçinin daxil olması zamanı xəta baş verdi',$model->errors);
            }

            $login = User::findByApiKey(Yii::$app->request->headers->get('loginkey'));
            if(!empty($login))
            {
                $api_key = Yii::$app->request->headers->get('token');
                return $this->response(200,'İstifadəçi daxil olub',$login,null,['api_key'=>$api_key]);
            }

            if($model->login())
            {
                $id = Yii::$app->user->identity->id;
                $api_token = Yii::$app->security->generateRandomString(32).$id;

                $user = $this->findModel($id,true);
                $user->api_token = $api_token;
                $user->save(false);

                return $this->response(200,'İstifadəçi daxil oldu',null,null,['loginkey'=>$api_token]);
            }
        }

        return $this->response(400,'Məlumatın işlənməsi zamanı xəta baş verdi');
    }

//    public function actionToken()
//    {
//        $key = Functions::JCode();
//        //$key = 'a3NSR2t0QjN6MDJiYml0ZTAyckpGRXRzdWI1dg.NDc3NzE5MzdiMmJlYmU3N2YxMzRkYzlhMWVmNWZmNTM.ITMxEzNyMzM4UTM';
//        $decode = Functions::JCodeDecode($key);
//        echo $key;
//        exit();
//    }

    /**
     * Istifadeci info
     * https://e-tibb.az/api/front/user/info
     */
    public function actionInfo()
    {
        $login = User::findByApiKey(Yii::$app->request->headers->get('loginkey'));
        if(!empty($login))
        {
            return $this->response(200,'İstifadəçi məlumatı',$login);
        }
        return $this->response(400,'İstifadəçi daxil olmayıb');
    }

    /**
     * Istifadeci sifre yenileme
     * https://e-tibb.az/api/front/user/request-password-reset
     * email: test@etibb.az
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if($model->load(Yii::$app->request->post(),'') && $model->validate())
        {
            if($model->sendEmail())
            {
                return $this->response(200,'Şifrəni yeniləmək üçün e-poçt ünvanınıza link göndərilmişdir.');
            }else{
                return $this->response(400,'E-poçt ünvanınıza məlumatların göndərilməsi zamanı xəta baş verdi.');
            }
        }
        return $this->response(400,'Məlumatın işlənməsi zamanı xəta baş verdi');
    }

    protected function findModel($id,$filter = false)
    {
        if($filter === true)
        {
            $model = User::find()->select('id,unique_id,name,email,birthday,phone_number,last_login,photo')->where(['id'=>$id,'status'=>[1]])->one();
        }else{
            $model = User::find()->where(['id'=>$id,'status'=>[1]])->one();
        }
        if($model !== null){
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

}