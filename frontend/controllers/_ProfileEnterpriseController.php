<?php
namespace frontend\controllers;

use backend\components\Functions;
use common\models\SiteEnterprises;
use frontend\components\ProfileLinks;
use frontend\models\Enterprise;
use frontend\models\EnterpriseModel;
use frontend\models\PricesModel;
use frontend\models\Services;
use frontend\models\ServicesModel;
use frontend\models\SiteEnterpriseEmployers;
use frontend\models\Transactions;
use frontend\models\User;
use backend\models\SiteUsers;
use backend\models\SiteSosialLinks;
use backend\models\SitePhoneNumbers;
use backend\components\ImageUpload;
use backend\models\SiteGallery;
use common\models\AuthEnterpriseForm;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
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
class ProfileEnterpriseController extends MainController
{

    public $menus;
    public $pages;
    public $typeModel;
    public $layout = 'static';
    public $rememberMe = 1;
    public $customPath = 'enterprises';
    const  TYPE = 2;

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

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['settings','services','workers','doctor-update','doctor-delete'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action){
                            //return !Yii::$app->user->isGuest;
                            if(Yii::$app->user->isGuest){
                                $this->redirect(Url::to(['auth-enterprise/register']));
                                return false;
                            }else{
                                if(Yii::$app->user->identity->status == 1)
                                {
                                    return Yii::$app->user->identity->type == self::TYPE;
                                }elseif(Yii::$app->user->identity->status == 2){
                                    $this->redirect(Url::to(['auth-enterprise/step2']));
                                    return false;
                                }
                            }
                        }
                    ]
                ],
            ]
        ];
    }

    public function beforeAction($action)
    {
        /*if(!Yii::$app->user->isGuest and Yii::$app->user->identity->status==2){
            $this->redirect(Url::to(["auth-enterprise/step2"]));
            return false;
        }*/
        $menus              = ArrayHelper::toArray(new Menu());
        $this->menus        = $menus['list'];

        $pages              = new ProfileLinks();
        $this->pages        = $pages->list;
        $this->typeModel    = $pages->model;

        return parent::beforeAction($action);
    }

    public function ObjectDoctors($obj_id)
    {
        $user_type = 2;
        if(!empty(Yii::$app->user->id) and $obj_id == Yii::$app->user->id){
            if(isset(Yii::$app->user->identity->type)){
                $user_type = Yii::$app->user->identity->type;
            }
        }
        $o_object       = new SiteEnterpriseEmployers();
        $total_count    = $o_object->getEmployersCount($obj_id);
        $pagination     = new Pagination(['totalCount'=>$total_count]);
        $pagination->defaultPageSize = 10;
        $employer_list = $o_object->getEmployers($obj_id,$pagination->limit,$pagination->offset);
        $employer      = array();
        if(!empty($employer_list)){
            foreach ($employer_list as $e){
                $employer[$e['id']]['id']        = $e['id'];
                $employer[$e['id']]['name']      = $e['name'];
                $employer[$e['id']]['study']     = $e['study'];
                $employer[$e['id']]['specs']     = $e['specialty'];
                $employer[$e['id']]['exp']       = $e['experience'];
                $employer[$e['id']]['photo']     = $e['photo'];
            }
            $data = ['employers'=>$employer, 'pagination'=>$pagination];
            return $data;
        }else{
            $data = false;
            return $data;
        }

    }

    public function actionSettings()
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(Url::to("obyekt-qeydiyyat"));
        }elseif(Yii::$app->user->identity->status==2){
            return $this->redirect(Url::to("auth-enterprise/step2"));
        }

        $user_id = Yii::$app->user->id;
        $enterprise = SiteEnterprises::getEnterpriseWithId($user_id);

        if(empty($enterprise))
        {
            return $this->showError('Axtardığınız səhifə tapılmadı');
        }

        $enterprise_categories = $this->menus["type"][2];

        if(isset($this->menus["id"][$enterprise->category_id])){
            $category = $this->menus["id"][$enterprise->category_id];
            if($category["type"]!=self::TYPE){
                return $this->redirect(Yii::$app->params["site.url"]);
            }
        }

        $model     = $enterprise;
        $user      = User::findIdentity($user_id);
        $model->contact_name = $user->name;
        $model->contact_phone = $user->phone_number;
        $model->email = $user->email;
        $model->about = html_entity_decode(strip_tags($model->about));
        if($model->load(Yii::$app->request->post()) && Yii::$app->request->post($model->getClassName()))
        {
            if($model->catdirilma){ $jsonData['catdirilma'] = 1; }

            if($model->saat24){ $jsonData['saat24'] = 1; }

            if($model->eve_caqiris){ $jsonData['eve_caqiris'] = 1; }

            $model->feature = isset($jsonData) ? json_encode($jsonData) : null;


            /** Addresses */
            if(isset($model->addresses) && !empty($model->addresses))
            {
                $added_addresses  = !empty($model->added_addresses) ? json_decode(base64_decode($model->added_addresses),true) : [];
                $addresses        = !empty($model->addresses) ? $model->addresses : [];
                $max              = max(count($added_addresses),count($addresses));

                for($x=0;$x<$max;$x++)
                {
                    if(isset($added_addresses[$x]['name']))
                    {
                        if(!empty($addresses[$x]))
                        {
                            if(($added_addresses[$x]['name'] != $addresses[$x]))
                            {
                                echo $x."<br />";
                                $upd_address = SiteAddresses::findOne($added_addresses[$x]['id']);
                                $upd_address->address = $addresses[$x];
                                $upd_address->save(false);

                            }
                        }else{
                            $del_addresses = SiteAddresses::findOne($added_addresses[$x]['id']);
                            if(!empty($del_addresses)){ $del_addresses->delete(); }
                        }
                    }elseif(isset($addresses[$x]) && !empty($addresses[$x])){

                        $ins_address             = new SiteAddresses();
                        $ins_address->connect_id = $model->id;
                        $ins_address->address    = $addresses[$x];
                        $ins_address->type       = self::TYPE;
                        $ins_address->save();

                    }
                };
            }


            /** Sosial links */
            if(isset($model->sosial_links) && !empty($model->sosial_links))
            {

                $added_sosial_links   = !empty($model->added_sosial_links) ? json_decode(base64_decode($model->added_sosial_links),true) : [];
                $sosial_links         = !empty($model->sosial_links) ? $model->sosial_links : [];
                $max                  = max(count($added_sosial_links),count($sosial_links));
                $added_sosial_links2  = [];
                $sosial_links2        = [];
                $max = 6;
                foreach($added_sosial_links as $key=>$value){
                    $added_sosial_links2[$value["type"]] = $value;
                }
                $added_sosial_links = $added_sosial_links2;

                foreach($sosial_links as $key=>$value){
                    $sosial_links2[$value["type"]] = $value;
                }
                $sosial_links = $sosial_links2;

                for($x=0;$x<$max;$x++)
                {
                    if(isset($added_sosial_links[$x]['type']))
                    {
                        if(!empty($sosial_links[$x]['link']))
                        {
                            if(($added_sosial_links[$x]['type'] != $sosial_links[$x]['type']) || ($added_sosial_links[$x]['link'] != $sosial_links[$x]['link']))
                            {
                                $upd_sosial_links = SiteSosialLinks::findOne($added_sosial_links[$x]['id']);
                                $upd_sosial_links->link       = $sosial_links[$x]['link'];
                                $upd_sosial_links->link_type  = $sosial_links[$x]['type'];
                                $upd_sosial_links->save(false);
                            }
                        }else{
                            $SosialLinksDelete = SiteSosialLinks::findOne($added_sosial_links[$x]['id']);
                            if(!empty($SosialLinksDelete)){ $SosialLinksDelete->delete(); }
                        }
                    }elseif(isset($sosial_links[$x]['type']) && !empty($sosial_links[$x]['link'])){

                        $ins_sosial_links             = new SiteSosialLinks();
                        $ins_sosial_links->connect_id = $model->id;
                        $ins_sosial_links->link       = $sosial_links[$x]['link'];
                        $ins_sosial_links->link_type  = $sosial_links[$x]['type'];
                        $ins_sosial_links->type       = self::TYPE;
                        $ins_sosial_links->save();

                    }

                };

            }

            /** Phone numbers */
            if(isset($model->phone_numbers) && !empty($model->phone_numbers))
            {
                $added_phone_numbers  = !empty($model->added_phone_numbers) ? json_decode(base64_decode($model->added_phone_numbers),true) : [];
                $phone_numbers        = !empty($model->phone_numbers) ? $model->phone_numbers : [];
                $max                  = max(count($added_phone_numbers),count($phone_numbers));

                for($x=0;$x<$max;$x++)
                {
                    if(isset($added_phone_numbers[$x]['type']))
                    {
                        if(!empty($phone_numbers[$x]))
                        {
                            if(($added_phone_numbers[$x]['number'] != $phone_numbers[$x]))
                            {

                                    $upd_phone_numbers = SitePhoneNumbers::findOne($added_phone_numbers[$x]['id']);
                                $upd_phone_numbers->number       = $phone_numbers[$x];
                                $upd_phone_numbers->number_type  = 0;
                                $upd_phone_numbers->save(false);
                            }
                        }else{
                            $del_phone_numbers = SitePhoneNumbers::findOne(["id" =>$added_phone_numbers[$x]['id'],"number_type" => 0]);
                            if(!empty($del_phone_numbers)){ $del_phone_numbers->delete(); }
                        }
                    }elseif(!empty($phone_numbers[$x])){
                        $ins_phone_numbers = new SitePhoneNumbers();
                        $ins_phone_numbers->connect_id  = $model->id;
                        $ins_phone_numbers->number      = $phone_numbers[$x];
                        $ins_phone_numbers->number_type = 0;
                        $ins_phone_numbers->type        = self::TYPE;
                        $ins_phone_numbers->save();

                    }

                };

            }


            /** Mobile numbers */
            if(isset($model->mobile_numbers) && !empty($model->mobile_numbers))
            {
                $added_mobile_numbers  = !empty($model->added_mobile_numbers) ? json_decode(base64_decode($model->added_mobile_numbers),true) : [];
                $mobile_numbers        = !empty($model->mobile_numbers) ? $model->mobile_numbers : [];
                $max                  = max(count($added_mobile_numbers),count($mobile_numbers));
                for($x=0;$x<$max;$x++)
                {
                    if(isset($added_mobile_numbers[$x]['type']))
                    {
                        if(!empty($mobile_numbers[$x]))
                        {
                            if(($added_mobile_numbers[$x]['number'] != $mobile_numbers[$x]))
                            {

                                $upd_phone_numbers = SitePhoneNumbers::findOne($added_mobile_numbers[$x]['id']);
                                $upd_phone_numbers->number       = $mobile_numbers[$x];
                                $upd_phone_numbers->number_type  = 1;
                                $upd_phone_numbers->save(false);
                            }
                        }else{
                            $del_phone_numbers = SitePhoneNumbers::findOne(["id" =>$added_mobile_numbers[$x]['id'],"number_type" => 1]);
                            if(!empty($del_phone_numbers)){ $del_phone_numbers->delete(); }
                        }
                    }elseif(!empty($mobile_numbers[$x])){
                        $ins_phone_numbers = new SitePhoneNumbers();
                        $ins_phone_numbers->connect_id  = $model->id;
                        $ins_phone_numbers->number      = $mobile_numbers[$x];
                        $ins_phone_numbers->number_type = 1;
                        $ins_phone_numbers->type        = self::TYPE;
                        $ins_phone_numbers->save();

                    }

                };

            }



            /** Main image & Photosession */
            $deletedImages = $model->deletedImages;
            $mainImage     = $model->mainImage;

            if(!empty($deletedImages))
            {
                if(strpos($deletedImages,','))
                {
                    $deletedImages = explode(',',$deletedImages);
                }

                $delete = SiteGallery::find()->where(['in','id',$deletedImages])->andWhere(['connect_id'=>$model->id,'type'=>self::TYPE])->all();
                if(!empty($delete))
                {
                    foreach($delete as $key => $val)
                    {
                        $imageUpload = new ImageUpload();
                        $imageUpload->deleteFile([$this->customPath.'/'.$val['photo']]);
                        $imageUpload->deleteFile([$this->customPath.'/small/'.$val['photo']]);
                        if($val['main'] == 1)
                        {
                            $mainImage          = null;
                            $updatePhoto        = $this->findModel($model->id);
                            $updatePhoto->photo = '';
                            $updatePhoto->save(false);
                        }
                        $val->delete();
                    }
                }
            }

            $photos = UploadedFile::getInstances($model,'files');

            if(!empty($photos))
            {
                foreach($photos as $key => $photo)
                {
                    $imageUpload = new ImageUpload();
                    if(empty($mainImage) && $key == 0)
                    {
                        $uploadedFile = $imageUpload->saveFile($photo,[
                            'path.save'=>$this->customPath,
                            'resize.img'=>[185,185],
                            'resize.thumb'=>[121,121]
                        ]);

                        $updatePhoto        = $this->findModel($model->id);
                        $updatePhoto->photo = $uploadedFile;
                        $updatePhoto->save(false);

                    }else{
                        $uploadedFile = $imageUpload->saveFile($photo,[
                            'path.save'=>$this->customPath,
                            'resize.img'=>[708,420],
                            'resize.thumb'=>[185,110]
                        ]);
                    }

                    if(!empty($uploadedFile))
                    {
                        $gallery             = new SiteGallery();
                        $gallery->photo      = $uploadedFile;
                        $gallery->connect_id = $model->id;
                        $gallery->type       = self::TYPE;
                        $gallery->main       = empty($mainImage) && $key == 0 ? 1 : 0;
                        $gallery->save();
                    }

                }
            }
            $diploma = UploadedFile::getInstances($model,'diploma_file');

            if (!empty($diploma)) {
                $imageUpload = new ImageUpload();
                $uploadedFile = $imageUpload->saveFile($diploma[0], [
//                                'path.save'=>$this->customPath,
                    'path.save' => 'diplomas',
                    'resize.img' => [708, 420],
                    'resize.thumb' => [185, 110]
                ]);
                $updatePhoto = $model;
                $updatePhoto->diploma_file = $uploadedFile;
                $updatePhoto->save(false);
            }
            if($model->save())
            {
                $user->name = $model->contact_name;
                $user->phone_number = $model->contact_phone ;
                $user->email = $model->email;
                $user->birthday =  date("Y-m-d",strtotime($model->birthday));

                if(!empty($model->password) and !empty($model->repassword) and trim($model->password)==trim($model->repassword)){
                    $user->setPassword($model->password);
                    $user->generateAuthKey();
                }

                $user->save();
                Yii::$app->session->setFlash('success','Məlumatlar yeniləndi');
            }else{
                Yii::$app->session->setFlash('error','Xəta baş verdi');
            }

            //return $this->redirect(['index']);

        }

        return $this->render('settings', [
            'model'                 => $model,
            'user'                  => $user,
            'enterprise_categories' => $enterprise_categories,
            'customPath' => $this->customPath
        ]);
    }

    public function getPages($model)
    {
        return  $this->pages;

        /*$category_id = $model->category_id;

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

        return $pages;*/
    }

    // Services
    public function actionServices()
    {
        $page_type   = 'services';
        $user_id     = Yii::$app->user->id;
        $model       = $this->findObject($user_id);
        $pages       = $this->getPages($model);

        if(Yii::$app->request->isPost)
        {
            if ($model->load(Yii::$app->request->post()))
            {
                //$model->services_prices = Yii::$app->request->post('services_prices');
                if($model->save(false)){
                    Yii::$app->session->setFlash("success","Xidmət Uğurla Yerləşdirildi");
                }else{
                    echo $model->getErrors();
                }
            }
        }
        return $this->render('services',[
            'model'     => $model,
            'pages'     => $pages,
            'page_type' => $page_type
        ]);
    }
    // services end


    // Workers begin
    public function actionWorkers()
    {
        $page_type   = 'workers';
        $user_id     = Yii::$app->user->id;
        $enterprise  = $this->findObject($user_id);
        $pages       = $this->getPages($enterprise);
        if(!empty(Yii::$app->user->identity->type) or !empty(Yii::$app->user->id) ){
            $obj_type = Yii::$app->user->identity->type;
            $obj_name = Yii::$app->user->identity->name;
            $obj_id   = Yii::$app->user->id;
        }
        if ($this->ObjectDoctors($enterprise->id)!= false){
            $employers  = $this->ObjectDoctors($enterprise->id)['employers'];
            $pagination = $this->ObjectDoctors($enterprise->id)['pagination'];

        }else{
            $employers  = null;
            $pagination = new Pagination();
        }
        $model = new SiteEnterpriseEmployers();
        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) and $model->validate() ){
                $model->status      = 1;
                $model->connect_id  = $enterprise->id;
                if($model->save()){
                    $photos = UploadedFile::getInstances($model, 'photo');
                    if(!empty($photos))
                    {
                        foreach($photos as $key => $photo)
                        {
                            $imageUpload = new ImageUpload();
                            if($key == 0)
                            {
                                $uploadedFile = $imageUpload->saveFile($photo,[
                                    'path.save'  => $this->customPath,
                                    'resize.img' => [185,185],
                                    'resize.thumb'=>[137,137]
                                ]);
                                $updatePhoto        = $this->findEmployer($model->id);
                                $updatePhoto->photo = $uploadedFile;
                                $updatePhoto->save(false);
                            }
                        }
                    }
                    Yii::$app->session->setFlash("success","Mütəxəssis Uğurla Yerləşdirildi");
                }else{
                    Yii::$app->session->setFlash("danger","Mütəxəssis Yerləşdirilmədi");
                }
                //$this->redirect(['profil/mutexessisler']);
                return Yii::$app->getResponse()->redirect(['profil/mutexessisler']);
            }
        }

        return $this->render('workers',[
            'model'     =>$model,
            'enterprise'=> $enterprise,
            'employers' => $employers,
            'pagination'=> $pagination,
            'pages'     => $pages,
            'page_type' => $page_type
        ]);
    }

    public function actionDoctorUpdate($id)
    {
        $page_type   = 'workers';
        $user_id     = Yii::$app->user->id;
        $enterprise  = $this->findObject($user_id);
        $pages       = $this->getPages($enterprise);

        if(!empty(Yii::$app->user->identity->type) or !empty(Yii::$app->user->id) )
        {
            $obj_type = Yii::$app->user->identity->type;
            $obj_name = Yii::$app->user->identity->name;
            $obj_id   = Yii::$app->user->id;
        }

        if ($this->ObjectDoctors($enterprise->id)!= false)
        {
            $employers  = $this->ObjectDoctors($enterprise->id)['employers'];
            $pagination = $this->ObjectDoctors($enterprise->id)['pagination'];
        }
        else
        {
            $employers  = null;
            $pagination = new Pagination();
        }
        $model    = $this->findEmployer($id);
        $oldModel = $this->findEmployer($id);
        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) and $model->validate() ){
                $model->status      = 1;
                $model->connect_id  = $enterprise->id;
                if($model->save()){
                    $photos = UploadedFile::getInstances($model, 'photo');
                    if(!empty($photos))
                    {
                        foreach($photos as $key => $photo)
                        {
                            $imageUpload = new ImageUpload();
                            if($key == 0)
                            {
                                $uploadedFile = $imageUpload->saveFile($photo,[
                                    'path.save'  => $this->customPath,
                                    'resize.img' => [185,185],
                                    'resize.thumb'=>[137,137]
                                ]);
                                $updatePhoto        = $this->findEmployer($id);
                                $updatePhoto->photo = $uploadedFile;
                                $updatePhoto->save(false);
                            }
                        }
                    }
                    else
                    {
                        $updatePhoto        = $this->findEmployer($id);
                        $updatePhoto->photo = $oldModel->photo;
                        $updatePhoto->save(false);
                    }
                    Yii::$app->session->setFlash("success","Mütəxəssis Uğurla Redaktə Edildi");
                }else{
                    Yii::$app->session->setFlash("danger","Mütəxəssis Redaktə Edilmədi");
                }
                //$this->redirect(['profil/mutexessisler']);
                return Yii::$app->getResponse()->redirect(['profil/mutexessisler']);
            }
        }
        return $this->render('workers',[
            'model'=>$model,
            'enterprise'    => $enterprise,
            'employers'     => $employers,
            'pagination'    => $pagination,
            'pages'         => $pages,
            'page_type'     => $page_type
        ]);
    }

    public function actionDoctorDelete()
    {
        if(Yii::$app->request->isAjax){
            $id = intval(Yii::$app->request->post('del_id'));
            if(!empty($id)){
                $model = $this->findEmployer($id);
                $model->delete();
                return 'Yes';
            }
        }
    }

    protected function findEmployer($id)
    {
        if (($model = SiteEnterpriseEmployers::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // Workers end


    protected function findModel($id)
    {
        if (($model = SiteEnterprises::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    protected function findObject($id){
        if(!$this->typeModel){
            if (($model = SiteEnterprises::findOne(['user_id' => $id])) !== null) {
                return $model;
            }
            throw new NotFoundHttpException('The requested page does not exist.');
        }else{
            return $this->typeModel;
        }
    }

}