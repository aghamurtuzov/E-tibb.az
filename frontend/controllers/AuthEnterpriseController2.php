<?php
namespace frontend\controllers;

use backend\models\SiteEnterprises;
use backend\models\SiteUsers;
use backend\models\SiteSosialLinks;
use backend\models\SitePhoneNumbers;
use backend\components\ImageUpload;
use backend\models\SiteGallery;
use common\models\AuthEnterpriseForm;
use yii\web\UploadedFile;
use backend\models\SiteAddresses;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use frontend\components\Menu;
use yii\helpers\ArrayHelper;


/**
* Auth controller
*/
class AuthEnterpriseController extends Controller
{

    public $menus;
    public $layout = 'static';
    public $rememberMe = 1;
    const TYPE = 2;

    public $customPath = 'enterprises';
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
            return $this->redirect(Url::to(["profile/index"]));
        }
        $menus = ArrayHelper::toArray(new Menu());

        $this->menus = $menus['list'];

        return $this->menus;
    }


    public function actionRegister()
    {
        $model = new AuthEnterpriseForm();
        if($model->load(Yii::$app->request->post())){ // and $model->validate()
            var_dump($model);
        }



        return $this->render('register', [
        'model' => $model
        ]);
    }

    public function actionCreate()
    {
        $model = new SiteEnterprises();
        $obj_id= null;

        /** Site users binded info */
        if(Yii::$app->request->isGet)
        {
            $obj_id = intval(Yii::$app->request->get('id'));

            if(!empty($obj_id))
            {
                $obj_data    = SiteUsers::find()->where(['id'=>$obj_id])->one();
                $model->name = $obj_data['name'];
                $model->phone_numbers[0]['number'] = $obj_data['phone_number'];
            }
        }
        if(Yii::$app->request->post($model->getClassName()) && $model->load(Yii::$app->request->post()))
        {
            $datetime = date('Y-m-d', strtotime("-1 day", strtotime(Yii::$app->params['current.date'])));

            $model->expires = $datetime;

            if($model->catdirilma){ $jsonData['catdirilma'] = 1; }

            if($model->saat24){ $jsonData['saat24'] = 1; }

            if($model->eve_caqiris){ $jsonData['eve_caqiris'] = 1; }

            if(isset($jsonData))
            {
                $model->feature = json_encode($jsonData);
            }

            if($model->save())
            {
                /** Site user account status update */
                if(!empty($obj_id))
                {
                    $obj_model          = SiteUsers::findOne($obj_id);
                    $obj_model->status  = 1;
                    $obj_model->save();
                }

                /** Sosial links */
                if(isset($model->sosial_links[0]['link']) && !empty($model->sosial_links[0]['link']))
                {
                    foreach($model->sosial_links as $key => $val)
                    {
                        if(!empty($val['link']))
                        {
                            $sosial_links             = new SiteSosialLinks();
                            $sosial_links->connect_id = $model->id;
                            $sosial_links->link       = $val['link'];
                            $sosial_links->link_type  = $val['type'];
                            $sosial_links->type       = self::TYPE;
                            $sosial_links->save();
                        }
                    }
                }

                /** Phone numbers */
                if(isset($model->phone_numbers[0]['number']) && !empty($model->phone_numbers[0]['number']))
                {
                    foreach($model->phone_numbers as $key => $val)
                    {
                        if(!empty($val['number']))
                        {
                            $phone_numbers              = new SitePhoneNumbers();
                            $phone_numbers->connect_id  = $model->id;
                            $phone_numbers->number      = $val['number'];
                            $phone_numbers->number_type = $val['type'];
                            $phone_numbers->type        = self::TYPE;
                            $phone_numbers->save();
                        }
                    }
                }

                /** Addresses */
                if(isset($model->addresses) && !empty($model->addresses))
                {
                    foreach($model->addresses as $key => $val)
                    {
                        if(!empty($val['name']))
                        {
                            $address             = new SiteAddresses();
                            $address->connect_id = $model->id;
                            $address->address    = $val['name'];
                            $address->type       = self::TYPE;
                            $address->save();
                        }
                    }
                }

                /** Main image & Photosession */
                $photos = UploadedFile::getInstances($model,'files');

                if(!empty($photos))
                {
                    foreach($photos as $key => $photo)
                    {
                        $imageUpload = new ImageUpload();
                        if($key != 0)
                        {
                            $uploadedFile = $imageUpload->saveFile($photo,[
                                'path.save'=>$this->customPath,
                                'resize.img'=>[708,420],
                                'resize.thumb'=>[185,110]
                            ]);
                        }else{
                            $uploadedFile = $imageUpload->saveFile($photo,[
                                'path.save'=>$this->customPath,
                                'resize.img'=>[185,185],
                                'resize.thumb'=>[121,121]
                                /*'resize.thumb'=>[390,186]*/
                            ]);

                            $updatePhoto        = $model;
                            $updatePhoto->photo = $uploadedFile;
                            $updatePhoto->save(false);

                        }

                        if(!empty($uploadedFile))
                        {
                            $gallery             = new SiteGallery();
                            $gallery->photo      = $uploadedFile;
                            $gallery->connect_id = $model->id;
                            $gallery->type       = self::TYPE;
                            $gallery->main       = $key != 0 ? 0: 1;
                            $gallery->save();
                        }

                    }
                }

                Yii::$app->session->setFlash('success','Məlumat əlavə olundu');
            }else{
                Yii::$app->session->setFlash('error','Məlumatın əlavə olunması zamanı xəta baş verdi');
            }

            return $this->redirect(['index']);

        };

        return $this->render('create', [
            'model' => $model,
            'customPath' => $this->customPath
        ]);
    }





}