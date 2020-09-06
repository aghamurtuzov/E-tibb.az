<?php
/**
 * Created by PhpStorm.
 * User: Taleh F
 * Date: 1/7/2019
 * Time: 4:13 PM
 */
namespace frontend\controllers;

use common\models\SiteDoctors;
use common\models\SiteEnterprises;
use frontend\components\ProfileLinks;
use frontend\models\MainModel;
use frontend\models\PromotionForm;
use frontend\models\PromotionModel;
use backend\models\SitePromotions;
use frontend\models\Doctor;
use frontend\models\Enterprise;
use frontend\models\LoginForm;
use frontend\models\User;
use frontend\models\SignupForm;
use frontend\models\UserSettingsForm;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use frontend\components\Menu;
use yii\helpers\ArrayHelper;

use backend\components\ImageUpload;
use backend\components\Functions;
use yii\web\UploadedFile;
use frontend\components\SeoLib;

/**
 * Profile Operation Promotion From Controller
 */

class PromotionFormController extends Controller
{
    public $menus;
    public $pages;
    public $typeModel;
    public $data        = null;
    public $layout      = 'static';
    public $customPath  = 'promotions';
    public $seo;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','settings','logout','create-promotion','payment'],
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action){
                            if(!Yii::$app->user->isGuest) {
                                if(Yii::$app->user->identity->type!=1 and Yii::$app->user->identity->type!=2)
                                {
                                    $this->redirect("");
                                    return false;
                                }elseif(Yii::$app->user->identity->status == 2 and Yii::$app->user->identity->type==1){
                                    $this->redirect(Url::to(['auth-doctor/step2']));
                                    return false;
                                }elseif(Yii::$app->user->identity->status == 2 and Yii::$app->user->identity->type==2){
                                    $this->redirect(Url::to(['auth-enterprise/step2']));
                                    return false;
                                }else{
                                    return true;
                                }
                            }
                        },
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'layout' => false
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    public function beforeAction($action)
    {

        $menus = ArrayHelper::toArray(new Menu());

        $this->menus = $menus['list'];

        $pages = new ProfileLinks();
        $this->pages  = $pages->list;
        $this->typeModel  = $pages->model;
        $this->seo   = new SeoLib();

        return parent::beforeAction($action);

    }


    public function userPromotions($user_id)
    {
        $p_object       = new PromotionModel();
        $total_count    = $p_object->getUserPromotionCount($user_id);
        $pagination     = new Pagination(['totalCount'=>$total_count]);
        $pagination->defaultPageSize = 10;
        $promotion_list = $p_object->getUserPromotions($user_id,$pagination->limit,$pagination->offset);
        $promotion     = array();
        if(!empty($promotion_list)){
            foreach ($promotion_list as $promotions){
                $promotion[$promotions['id']]['id']        = $promotions['id'];
                $promotion[$promotions['id']]['headline']  = $promotions['headline'];
                $promotion[$promotions['id']]['connect_id']= $promotions['connect_id'];
                $promotion[$promotions['id']]['type']      = $promotions['type'];
                $promotion[$promotions['id']]['photo']     = $promotions['photo'];
                $promotion[$promotions['id']]['discount']  = $promotions['discount'];
                $promotion[$promotions['id']]['price']     = $promotions['price'];
                $promotion[$promotions['id']]['price2']    = $promotions['price']-($promotions['price']*$promotions['discount'])/100;
                $promotion[$promotions['id']]['connect']   = $p_object->getConnectName($promotions['type'],$promotions['connect_id'])['name'];
                $promotion[$promotions['id']]['connect_uri_slug']   = $p_object->getConnectName($promotions['type'],$promotions['connect_id'])['kat'];
                $promotion[$promotions['id']]['start']     = date("d.m.Y", strtotime($promotions['date_start']));
                $promotion[$promotions['id']]['end']       = date("d.m.Y", strtotime($promotions['date_end']));
                $promotion[$promotions['id']]['url']       = Yii::$app->params['site.aksiya'].Functions::slugify($promotions['headline'],['transliterate' => true]).'-'.$promotions['id'];
            }
            $data = ['promotions'=>$promotion, 'pagination'=>$pagination];
            return $data;
        }else{
            $data = false;
            return $data;
        }

    }

    public function getPagesEnterprise($model)
    {
        $category_id = $model->category_id;
        $pages = [];
        $menus = $this->menus;
        // Default settings
        $settings = $menus["id"][5]["settings"];
        if(isset($menus["id"][$category_id])){
            $category = $menus["id"][$category_id];
            if($category["settings"]!=""){
                $settings = $category["settings"];
            }
        }
        $settings = json_decode($settings,true);
        if(isset($settings["profile"])){
            $pages = $settings["profile"];
        }
        return $pages;
    }

    public function getPagesDoctor($model=null)
    {
        $pages = [];
        $menus = $this->menus;

        // Default settings
        $settings = $menus["id"][4]["settings"];

        $settings = json_decode($settings,true);
        if(isset($settings["profile"])){
            $pages = $settings["profile"];
        }

        return $pages;
    }

    public function actionCreatePromotion()
    {
        $model = new PromotionForm();
        $user_type = 0;
        $user_name = '';
        $user_id   = 0;
        if(!empty(Yii::$app->user->identity->type) or !empty(Yii::$app->user->id) ){
            $user_type = Yii::$app->user->identity->type;
            $user_name = Yii::$app->user->identity->name;
            $user_id   = Yii::$app->user->id;
        }

        $typeModel = false;
        if($user_type== User::TYPE_DOCTOR){
            if(!$this->typeModel){
                $typeModel = $this->typeModel;
            }else{
                $typeModel = Doctor::findOne(['user_id' => $user_id]);
            }

        }elseif($user_type == User::TYPE_ENTERPRISE){
            if(!$this->typeModel){
                $typeModel = $this->typeModel;
            }else{
                $typeModel = SiteEnterprises::findOne(['user_id' => $user_id]);
            }

        }

        if($model->load(Yii::$app->request->post()) and $model->validate())
        {
//            echo  '<pre>';var_dump($model);die;
            if($model->date != ''){
                $dates = explode('/',$model->date);
                $model->date_start = date("Y-m-d",strtotime($dates[0]));
                $model->date_end = date("Y-m-d",strtotime($dates[1]));
            }
            if (!empty($model->phones)){
                $model->phones = implode(',',$model->phones);
            }
            $last_id = SitePromotions::find()->orderBy(['id' => SORT_DESC])->one();
            $mode = 'add';
//            $model->date_start  = date("Y-m-d",strtotime($model->date_start));
//            $model->date_end    = date("Y-m-d",strtotime($model->date_end));
            $model->slug = Functions::slugify($model->headline,['transliterate' => true]);
            $model->promo_code = ($model->promo_type != 0 ? Functions::promoCodeGenerator($model->promo_type,$last_id->id) : null);
            $model->status = 2;
            if(!empty($model->organizer)){
                $model->connect_id = $typeModel->id;
                $model->type       = $user_type;
            }
            if($user_type==0){
                $model->organizer = $user_name;
                $model->type      = $user_type;
                $model->connect_id= $typeModel->id;
            }elseif ($user_type==1){
                $model->organizer = $user_name;
                $model->type      = 1;
                $model->connect_id= $typeModel->id;
            }elseif ($user_type==2){
                $model->organizer = $user_name;
                $model->type      = 2;
                $model->connect_id= $typeModel->id;
            }

            if($model->save(false)){
                if ($model->type == 1) {
                    SitePromotions::change_Promotion($model->connect_id,$model->type,$mode);
                }else if($model->type != 1 and $model->type!=null){
                    SitePromotions::change_Promotion($model->connect_id,$model->type,$mode);
                }
                $photos = UploadedFile::getInstances($model, 'photo');
                if(!empty($photos))
                {
                    foreach($photos as $key => $photo)
                    {
                        $imageUpload = new ImageUpload();
                        if($key != 0)
                        {
                            $uploadedFile = $imageUpload->saveFile($photo,[
                                'path.save'     => $this->customPath,
                                'resize.img'    => [708,467],
                                'resize.thumb'  => [185,122]
                            ]);
                        }else{
                            $uploadedFile = $imageUpload->saveFile($photo,[
                                'path.save'     => $this->customPath,
                                'resize.img'    => [350,231],
                                'resize.thumb'  => [401,265]
                            ]);

                            $updatePhoto        = $this->findModel($model->id);
                            $updatePhoto->photo = $uploadedFile;
                            $updatePhoto->save(false);
                        }
                    }
                }
                Yii::$app->session->setFlash("success","Aksiyanız Uğurla Yerləşdirildi");
                return Yii::$app->getResponse()->redirect(['profil/aksiyalar']);
            }
        }
        return $this->render('create',[
            'model' => $model
        ]);
    }

    public function actionIndex()
    {
        $user_type = 0;
        $user_name = '';
        $user_id   = 0;
        if(!empty(Yii::$app->user->identity->type) or !empty(Yii::$app->user->id) ){
            $user_type = Yii::$app->user->identity->type;
            $user_name = Yii::$app->user->identity->name;
            $user_id   = Yii::$app->user->id;
        }

        $typeModel = false;
        $page_type = 'promotions';
        $pages     = false;
        $tabs      = '_tabs';
        if($user_type== User::TYPE_DOCTOR){
            if(!$this->typeModel){
                $typeModel = $this->typeModel;
            }else{
                $typeModel = Doctor::findOne(['user_id' => $user_id]);
            }
            $pages = $this->pages;
            $tabs = '@frontend/views/profile-doctor/_tabs';

        }elseif($user_type == User::TYPE_ENTERPRISE){
            if(!$this->typeModel){
                $typeModel = $this->typeModel;
            }else{
                $typeModel = SiteEnterprises::findOne(['user_id' => $user_id]);
            }
            $pages = $this->pages;
            $tabs = '@frontend/views/profile-enterprise/_tabs';

        }

        if ($this->userPromotions($typeModel->id)!= false){
            $promotions = $this->userPromotions($typeModel->id)['promotions'];
            $pagination = $this->userPromotions($typeModel->id)['pagination'];

        }else{
            $promotions = null;
            $pagination = new Pagination();
        }
        $model = new PromotionForm();
        if($model->load(Yii::$app->request->post()) and $model->validate())
        {
//            echo  '<pre>';var_dump($model);die;

            $last_id = SitePromotions::find()->orderBy(['id' => SORT_DESC])->one();
            $mode = 'add';
            $model->date_start  = date("Y-m-d",strtotime($model->date_start));
            $model->date_end    = date("Y-m-d",strtotime($model->date_end));
            $model->slug = Functions::slugify($model->headline,['transliterate' => true]);
            $model->promo_code = ($model->promo_type != 0 ? Functions::promoCodeGenerator($model->promo_type,$last_id->id) : null);
            if(!empty($model->organizer)){
                $model->connect_id = $typeModel->id;
                $model->type       = $user_type;
            }
            if($user_type==0){
                $model->organizer = $user_name;
                $model->type      = $user_type;
                $model->connect_id= $typeModel->id;
            }elseif ($user_type==1){
                $model->organizer = $user_name;
                $model->type      = 1;
                $model->connect_id= $typeModel->id;
            }elseif ($user_type==2){
                $model->organizer = $user_name;
                $model->type      = 2;
                $model->connect_id= $typeModel->id;
            }
            if($model->save()){
                if ($model->type == 1) {
                    SitePromotions::change_Promotion($model->connect_id,$model->type,$mode);
                }else if($model->type != 1 and $model->type!=null){
                    SitePromotions::change_Promotion($model->connect_id,$model->type,$mode);
                }
                $photos = UploadedFile::getInstances($model, 'photo');
                if(!empty($photos))
                {
                    foreach($photos as $key => $photo)
                    {
                        $imageUpload = new ImageUpload();
                        if($key != 0)
                        {
                            $uploadedFile = $imageUpload->saveFile($photo,[
                                'path.save'     => $this->customPath,
                                'resize.img'    => [708,467],
                                'resize.thumb'  => [185,122]
                            ]);
                        }else{
                            $uploadedFile = $imageUpload->saveFile($photo,[
                                'path.save'     => $this->customPath,
                                'resize.img'    => [350,231],
                                'resize.thumb'  => [401,265]
                            ]);

                            $updatePhoto        = $this->findModel($model->id);
                            $updatePhoto->photo = $uploadedFile;
                            $updatePhoto->save(false);
                        }
                    }
                }
                Yii::$app->session->setFlash("success","Aksiyanız Uğurla Yerləşdirildi");
                return Yii::$app->getResponse()->redirect(['profil/aksiyalar']);
            }
        }

        return $this->render('index',[
            'model' => $model,
            'promotions'=> $promotions,
            'pagination'=> $pagination,
            'typeModel' => $typeModel,
            'pages' => $pages,
            'page_type' => $page_type,
            'tabs' => $tabs
        ]);

    }

    public function actionUpdatePromotion($id)
    {
        $user_type = 0;
        $user_name = '';
        $user_id   = 0;
        //echo Yii::$app->user->id;
        if(!empty(Yii::$app->user->identity->type) or !empty(Yii::$app->user->id) ){
            $user_type = Yii::$app->user->identity->type;
            $user_name = Yii::$app->user->identity->name;
            $user_id   = Yii::$app->user->id;
        }
        $typeModel = false;
        $page_type = 'promotions';
        $pages = false;
        $tabs = '_tabs';
        if($user_type== User::TYPE_DOCTOR){
            if(!$this->typeModel){
                $typeModel = $this->typeModel;
            }else{
                $typeModel = Doctor::findOne(['user_id' => $user_id]);
            }
            $pages = $this->pages;
            $tabs = '@frontend/views/profile-doctor/_tabs';

        }elseif($user_type == User::TYPE_ENTERPRISE){
            if(!$this->typeModel){
                $typeModel = $this->typeModel;
            }else{
                $typeModel = SiteEnterprises::findOne(['user_id' => $user_id]);
            }
            $pages = $this->pages;
            $tabs = '@frontend/views/profile-enterprise/_tabs';

        }

        $model    = $this->findModel($id);
        $oldModel = $this->findModel($id);
        $mode     = 'edit';
        $edited_id= $model->connect_id;

        if ($this->userPromotions(Yii::$app->user->id )!= false){
            $promotions = $this->userPromotions($typeModel->id)['promotions'];
            $pagination = $this->userPromotions($typeModel->id)['pagination'];
        }else{
            $promotions = null;
            $pagination = new Pagination();
        }

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) and $model->validate())
            {
                $model->organizer   = $oldModel->organizer;
                $model->type        = $oldModel->type;
                $model->connect_id  = $oldModel->connect_id;
                $model->date_start  = date("Y-m-d",strtotime($model->date_start));
                $model->date_end    = date("Y-m-d",strtotime($model->date_end));
                $model->promo_code = ($model->promo_type != 0 ? Functions::promoCodeGenerator($model->promo_type,$model->id) : 0);
                if ($model->type == 1) {
                    if($edited_id!=$model->connect_id)
                        SitePromotions::change_Promotion($model->connect_id,$model->type,$mode,$edited_id);
                }else{
                    if($edited_id!=$model->connect_id)
                        SitePromotions::change_Promotion($model->connect_id,$model->type,$mode,$edited_id);
                }
                if($model->save())
                {
                    /** Main image */
                    $photos = UploadedFile::getInstances($model,'photo');
                    if(!empty($photos))
                    {
                        foreach($photos as $key => $photo)
                        {
                            $imageUpload = new ImageUpload();
                            if(empty($mainImage) && $key == 0)
                            {
                                $uploadedFile = $imageUpload->saveFile($photo,[
                                    'path.save'=>$this->customPath,
                                    'resize.thumb'=>[120,120]
                                ]);
                                $updatePhoto        = $this->findModel($model->id);
                                $updatePhoto->photo = $uploadedFile;
                                $updatePhoto->save(false);
                            }else{
                                $uploadedFile = $imageUpload->saveFile($photo,[
                                    'path.save'=>$this->customPath,
                                    'resize.thumb'=>[120,77]
                                ]);
                            }
                        }
                    }else{
                        $updatePhoto        = $this->findModel($id);
                        $updatePhoto->photo = $oldModel->photo;
                        $updatePhoto->save(false);
                    }
                    Yii::$app->session->setFlash("success","Aksiya məlumatları dəyişdirildi");
                }else{
                    Yii::$app->session->setFlash("danger","Aksiya məlumatları Redaktə Edilmədi");
                }
//                $this->redirect(['profil/aksiyalar']);
                return Yii::$app->getResponse()->redirect(['profil/aksiyalar']);
            }
        }
        return $this->render('index',[
            'model' => $model,
            'promotions'=> $promotions,
            'pagination'=> $pagination,
            'typeModel' => $typeModel,
            'pages'     => $pages,
            'page_type' => $page_type,
            'tabs'      => $tabs
        ]);
    }

    public function actionDeletePromotion()
    {
        if(Yii::$app->request->isAjax){
            $id = intval(Yii::$app->request->post('del_id'));
            if(!empty($id)){
                $model       = $this->findModel($id);
                $imageUpload = new ImageUpload();
                $imageUpload->deleteFile([$this->customPath.'/'.$model->photo]);
                $imageUpload->deleteFile([$this->customPath.'/small/'.$model->photo]);
                $this->findModel($id)->delete();
                //Yii::$app->session->setFlash("danger","Aksiyanız Uğurla Silindi");
                return 'Yes';
            }
        }
    }

    public function actionPayment()
    {
        return $this->render('success');
    }
    protected function findModel($id)
    {
        if (($model = PromotionForm::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

}