<?phpnamespace backend\controllers;use Yii;use backend\models\SitePromotions;use backend\models\SiteEnterprises;use backend\models\Sms;use backend\models\Email;use backend\models\SiteEnterprisesSearch;use yii\web\Controller;use yii\web\NotFoundHttpException;use yii\filters\VerbFilter;use backend\models\SiteGallery;use backend\models\SiteSosialLinks;use yii\filters\AccessControl;use backend\models\SiteUsers;use backend\components\ImageUpload;use backend\models\SitePhoneNumbers;use yii\web\UploadedFile;use backend\models\SiteAddresses;use backend\models\SiteDoctors;use backend\models\SiteDoctorsSearch;use backend\components\Functions;use backend\models\SiteDoctorSpecialist;use backend\models\SiteDoctorWorkplaces;use backend\controllers\MainController;/** * EnterprisesController implements the CRUD actions for SiteEnterprises model. */class EnterprisesController extends MainController{    const TYPE = 2;    public $customPath = 'enterprises';    /**     * {@inheritdoc}     */    public function behaviors()    {        return [            'access' => [                'class' => AccessControl::className(),                'only' => ['*'],                'rules' => [                    [                        'allow' => true,                        'roles' => ['@'],                    ],                ],            ],            'verbs' => [                'class' => VerbFilter::className(),                'actions' => [                    'delete' => ['POST'],                    'deletemore'=>['POST']                ],            ],        ];    }    /**     * Lists all SiteEnterprises models.     * @return mixed     */    public function actionIndex()    {        $searchModel = new SiteEnterprisesSearch();        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);        return $this->render('index', [            'searchModel' => $searchModel,            'dataProvider' => $dataProvider,            'customPath' => $this->customPath        ]);    }    /**     * Displays a single SiteDoctors model.     * @param integer $id     * @return mixed     * @throws NotFoundHttpException if the model cannot be found     */    public function actionView($id)    {        $model = $this->findModel($id);        $workers = SiteEnterprises::getWorkers($model->id);        //print_r($workers);        $promotions = SitePromotions::find()->where(['connect_id'=>$model->id,'type'=>2])->all();        //print_r($promotions);        return $this->render('view', [            'model' => $model,            'user'  => SiteUsers::findOne($model->user_id),            'promotions'=>$promotions,            'workers'=>$workers,            //'transactions'=>$doctor_transactions,            'customPath' => $this->customPath,            'smsModel' => new Sms(),            'emailModel' => new Email()        ]);    }    /**     * Creates a new SiteEnterprises model.     * If creation is successful, the browser will be redirected to the 'view' page.     * @return mixed     */    public function actionCreate()    {        return $this->redirect(['index']);        //exit();//        $model = new SiteEnterprises();//        $obj_id= null;        /** Site users binded info *///        if(Yii::$app->request->isGet)//        {//            $obj_id = intval(Yii::$app->request->get('id'));////            if(!empty($obj_id))//            {//                $obj_data    = SiteUsers::find()->where(['id'=>$obj_id])->one();//                $model->name = $obj_data['name'];//                $model->phone_numbers[0]['number'] = $obj_data['phone_number'];//            }//        }//        if(Yii::$app->request->post($model->getClassName()) && $model->load(Yii::$app->request->post()))//        {//            $datetime = date('Y-m-d', strtotime("-1 day", strtotime(Yii::$app->params['current.date'])));////            $model->expires = $datetime;////            if($model->catdirilma){ $jsonData['catdirilma'] = 1; }////            if($model->saat24){ $jsonData['saat24'] = 1; }////            if($model->eve_caqiris){ $jsonData['eve_caqiris'] = 1; }////            if(isset($jsonData))//            {//                $model->feature = json_encode($jsonData);//            }////            if($model->save())//            {//                /** Site user account status update *///                if(!empty($obj_id))//                {//                    $obj_model          = SiteUsers::findOne($obj_id);//                    $obj_model->status  = 1;//                    $obj_model->save();//                }////                /** Sosial links *///                if(isset($model->sosial_links[0]['link']) && !empty($model->sosial_links[0]['link']))//                {//                    foreach($model->sosial_links as $key => $val)//                    {//                        if(!empty($val['link']))//                        {//                            $sosial_links             = new SiteSosialLinks();//                            $sosial_links->connect_id = $model->id;//                            $sosial_links->link       = $val['link'];//                            $sosial_links->link_type  = $val['type'];//                            $sosial_links->type       = self::TYPE;//                            $sosial_links->save();//                        }//                    }//                }////                /** Phone numbers *///                if(isset($model->phone_numbers[0]['number']) && !empty($model->phone_numbers[0]['number']))//                {//                    foreach($model->phone_numbers as $key => $val)//                    {//                        if(!empty($val['number']))//                        {//                            $phone_numbers              = new SitePhoneNumbers();//                            $phone_numbers->connect_id  = $model->id;//                            $phone_numbers->number      = $val['number'];//                            $phone_numbers->number_type = $val['type'];//                            $phone_numbers->type        = self::TYPE;//                            $phone_numbers->save();//                        }//                    }//                }////                /** Addresses *///                if(isset($model->addresses) && !empty($model->addresses))//                {//                    foreach($model->addresses as $key => $val)//                    {//                        if(!empty($val['name']))//                        {//                            $address             = new SiteAddresses();//                            $address->connect_id = $model->id;//                            $address->address    = $val['name'];//                            $address->type       = self::TYPE;//                            $address->save();//                        }//                    }//                }////                /** Main image & Photosession *///                $photos = UploadedFile::getInstances($model,'files');////                if(!empty($photos))//                {//                    foreach($photos as $key => $photo)//                    {//                        $imageUpload = new ImageUpload();//                        if($key != 0)//                        {//                            $uploadedFile = $imageUpload->saveFile($photo,[//                                'path.save'=>$this->customPath,//                                'resize.img'=>[708,420],//                                'resize.thumb'=>[185,110]//                            ]);//                        }else{////                            $uploadedFile = $imageUpload->saveFile($photo,[//                                'path.save'=>$this->customPath,//                                'resize.img'=>[185,185],//                                'resize.thumb'=>[121,121]//                                /*'resize.thumb'=>[390,186]*///                            ]);////                            $updatePhoto        = $this->findModel($model->id);//                            $updatePhoto->photo = $uploadedFile;//                            $updatePhoto->save(false);////                        }////                        if(!empty($uploadedFile))//                        {//                            $gallery             = new SiteGallery();//                            $gallery->photo      = $uploadedFile;//                            $gallery->connect_id = $model->id;//                            $gallery->type       = self::TYPE;//                            $gallery->main       = $key != 0 ? 0: 1;//                            $gallery->save();//                        }////                    }//                }////                Yii::$app->session->setFlash('success','Məlumat əlavə olundu');//            }else{//                Yii::$app->session->setFlash('error','Məlumatın əlavə olunması zamanı xəta baş verdi');//            }////            return $this->redirect(['index']);////        };////        return $this->render('create', [//            'model' => $model,//            'customPath' => $this->customPath//        ]);    }    /**     * Updates an existing SiteEnterprises model.     * If update is successful, the browser will be redirected to the 'view' page.     * @param integer $id     * @return mixed     * @throws NotFoundHttpException if the model cannot be found     */    public function actionUpdate($id)    {        $model = $this->findModel($id);        $user_model = SiteUsers::findOne($model->user_id);        if($model->load(Yii::$app->request->post()) && Yii::$app->request->post($model->getClassName()))        {            if(!empty($user_model)){                if(!empty($model->contact_name)) {$user_model->name          = $model->contact_name;}                if(!empty($model->contact_phone)){$user_model->phone_number = $model->contact_phone;}                $user_model->save();            }            if($model->catdirilma){ $jsonData['catdirilma'] = 1; }            if($model->saat24){ $jsonData['saat24'] = 1; }            if($model->eve_caqiris){ $jsonData['eve_caqiris'] = 1; }            $model->feature = isset($jsonData) ? json_encode($jsonData) : null;            /** Sosial links */            if(isset($model->sosial_links) && !empty($model->sosial_links))            {                $added_sosial_links   = !empty($model->added_sosial_links) ? json_decode(base64_decode($model->added_sosial_links),true) : [];                $sosial_links         = !empty($model->sosial_links) ? $model->sosial_links : [];                $max                  = max(count($added_sosial_links),count($sosial_links));                for($x=0;$x<$max;$x++)                {                    if(isset($added_sosial_links[$x]['type']))                    {                        if(!empty($sosial_links[$x]['link']))                        {                            if(($added_sosial_links[$x]['type'] != $sosial_links[$x]['type']) || ($added_sosial_links[$x]['link'] != $sosial_links[$x]['link']))                            {                                $upd_sosial_links = SiteSosialLinks::findOne($added_sosial_links[$x]['id']);                                $upd_sosial_links->link       = $sosial_links[$x]['link'];                                $upd_sosial_links->link_type  = $sosial_links[$x]['type'];                                $upd_sosial_links->save(false);                            }                        }else{                            $SosialLinksDelete = SiteSosialLinks::findOne($added_sosial_links[$x]['id']);                            if(!empty($SosialLinksDelete)){ $SosialLinksDelete->delete(); }                        }                    }elseif(isset($sosial_links[$x]['type']) && !empty($sosial_links[$x]['link'])){                        $ins_sosial_links             = new SiteSosialLinks();                        $ins_sosial_links->connect_id = $model->id;                        $ins_sosial_links->link       = $sosial_links[$x]['link'];                        $ins_sosial_links->link_type  = $sosial_links[$x]['type'];                        $ins_sosial_links->type       = self::TYPE;                        $ins_sosial_links->save();                    }                };            }            /** Phone numbers */            if(isset($model->phone_numbers) && !empty($model->phone_numbers))            {                $added_phone_numbers  = !empty($model->added_phone_numbers) ? json_decode(base64_decode($model->added_phone_numbers),true) : [];                $phone_numbers        = !empty($model->phone_numbers) ? $model->phone_numbers : [];                $max                  = max(count($added_phone_numbers),count($phone_numbers));                for($x=0;$x<$max;$x++)                {                    if(isset($added_phone_numbers[$x]['type']))                    {                        if(!empty($phone_numbers[$x]['number']))                        {                            if(($added_phone_numbers[$x]['type'] != $phone_numbers[$x]['type']) || ($added_phone_numbers[$x]['number'] != $phone_numbers[$x]['number']))                            {                                $upd_phone_numbers = SitePhoneNumbers::findOne($added_phone_numbers[$x]['id']);                                $upd_phone_numbers->number       = $phone_numbers[$x]['number'];                                $upd_phone_numbers->number_type  = $phone_numbers[$x]['type'];                                $upd_phone_numbers->save(false);                            }                        }else{                            $del_phone_numbers = SitePhoneNumbers::findOne($added_phone_numbers[$x]['id']);                            if(!empty($del_phone_numbers)){ $del_phone_numbers->delete(); }                        }                    }elseif(isset($phone_numbers[$x]['type']) && !empty($phone_numbers[$x]['number'])){                        $ins_phone_numbers = new SitePhoneNumbers();                        $ins_phone_numbers->connect_id  = $model->id;                        $ins_phone_numbers->number      = $phone_numbers[$x]['number'];                        $ins_phone_numbers->number_type = $phone_numbers[$x]['type'];                        $ins_phone_numbers->type        = self::TYPE;                        $ins_phone_numbers->save();                    }                };            }            /** Addresses */            if(isset($model->addresses) && !empty($model->addresses))            {                $added_addresses  = !empty($model->added_addresses) ? json_decode(base64_decode($model->added_addresses),true) : [];                $addresses        = !empty($model->addresses) ? $model->addresses : [];                $max              = max(count($added_addresses),count($addresses));                for($x=0;$x<$max;$x++)                {                    if(isset($added_addresses[$x]['name']))                    {                        if(!empty($addresses[$x]['name']))                        {                            if(($added_addresses[$x]['name'] != $addresses[$x]['name']))                            {                                $upd_address = SiteAddresses::findOne($added_addresses[$x]['id']);                                $upd_address->address = $addresses[$x]['name'];                                $upd_address->save(false);                            }                        }else{                            $del_addresses = SiteAddresses::findOne($added_addresses[$x]['id']);                            if(!empty($del_addresses)){ $del_addresses->delete(); }                        }                    }elseif(isset($addresses[$x]['name']) && !empty($addresses[$x]['name'])){                        $ins_address             = new SiteAddresses();                        $ins_address->connect_id = $model->id;                        $ins_address->address    = $addresses[$x]['name'];                        $ins_address->type       = self::TYPE;                        $ins_address->save();                    }                };            }            /** Main image & Photosession */            $deletedImages = $model->deletedImages;            $mainImage     = $model->mainImage;            if(!empty($deletedImages))            {                if(strpos($deletedImages,','))                {                    $deletedImages = explode(',',$deletedImages);                }                $delete = SiteGallery::find()->where(['in','id',$deletedImages])->andWhere(['connect_id'=>$model->id,'type'=>self::TYPE])->all();                if(!empty($delete))                {                    foreach($delete as $key => $val)                    {                        $imageUpload = new ImageUpload();                        $imageUpload->deleteFile([$this->customPath.'/'.$val['photo']]);                        $imageUpload->deleteFile([$this->customPath.'/small/'.$val['photo']]);                        if($val['main'] == 1)                        {                            $mainImage          = null;                            $updatePhoto        = $this->findModel($model->id);                            $updatePhoto->photo = '';                            $updatePhoto->save(false);                        }                        $val->delete();                    }                }            }            $photos = UploadedFile::getInstances($model,'files');            if(!empty($photos))            {                foreach($photos as $key => $photo)                {                    $imageUpload = new ImageUpload();                    if(empty($mainImage) && $key == 0)                    {                        $uploadedFile = $imageUpload->saveFile($photo,[                            'path.save'=>$this->customPath,                            'resize.img'=>[185,185],                            'resize.thumb'=>[121,121]                        ]);                        $updatePhoto        = $this->findModel($model->id);                        $updatePhoto->photo = $uploadedFile;                        $updatePhoto->save(false);                    }else{                        $uploadedFile = $imageUpload->saveFile($photo,[                            'path.save'=>$this->customPath,                            'resize.img'=>[708,420],                            'resize.thumb'=>[185,110]                        ]);                    }                    if(!empty($uploadedFile))                    {                        $gallery             = new SiteGallery();                        $gallery->photo      = $uploadedFile;                        $gallery->connect_id = $model->id;                        $gallery->type       = self::TYPE;                        $gallery->main       = empty($mainImage) && $key == 0 ? 1 : 0;                        $gallery->save();                    }                }            }            $diploma = UploadedFile::getInstances($model,'diploma_file');            if(!empty($diploma))            {                $imageUpload = new ImageUpload();                $uploadedFile = $imageUpload->saveFile($diploma[0],[                    'path.save'=>'diplomas',                    'resize.img'=>[708,420],                    'resize.thumb'=>[185,110]                ]);                $updatePhoto        = $model;                $updatePhoto->diploma_file = $uploadedFile;                $updatePhoto->save(false);            }            if($model->save())            {                Yii::$app->session->setFlash('success','Məlumatlar yeniləndi');            }else{                Yii::$app->session->setFlash('error','Xəta baş verdi');            }            return $this->redirect(['index']);        }        return $this->render('update', [            'model' => $model,            'user'  => $user_model,            'customPath' => $this->customPath        ]);    }    /**     * Deletes an existing SiteDoctors model.     * If deletion is successful, the browser will be redirected to the 'index' page.     * @param integer $id     * @return mixed     * @throws NotFoundHttpException if the model cannot be found     */    public function actionDelete($id)    {        $model = $this->findModel($id);        $imageUpload = new ImageUpload();        $imageUpload->deleteFile([$this->customPath.'/'.$model->photo]);        $imageUpload->deleteFile([$this->customPath.'/small/'.$model->photo]);        $gallery = SiteGallery::find()->where(['connect_id'=>$model->id,'type'=>self::TYPE])->all();        if(!empty($gallery))        {            foreach($gallery as $key => $val)            {                $imageUpload->deleteFile([$this->customPath.'/'.$val['photo']]);                $imageUpload->deleteFile([$this->customPath.'/small/'.$val['photo']]);                $val->delete();            }        }        $sosiallinks = SiteSosialLinks::find()->where(['connect_id'=>$model->id,'type'=>self::TYPE])->all();        if(!empty($sosiallinks))        {            foreach($sosiallinks as $key => $val)            {                $val->delete();            }        }        $numbers = SitePhoneNumbers::find()->where(['connect_id'=>$model->id,'type'=>self::TYPE])->all();        if(!empty($numbers))        {            foreach($numbers as $key => $val)            {                $val->delete();            }        }        $siteAddresses = SiteAddresses::find()->where(['connect_id'=>$model->id,'type'=>self::TYPE])->all();        if(!empty($siteAddresses))        {            foreach($siteAddresses as $key => $val)            {                $val->delete();            }        }        if($model->delete()){            Yii::$app->session->setFlash('success','Məlumat silindi.');        }else{            Yii::$app->session->setFlash('error','Məlumat silinmədi');        }        return $this->redirect(['index']);    }    public function actionDeletemore()    {        $ids = Yii::$app->request->post('del_check');        if(!empty($ids))        {            foreach ($ids as $id)            {                $this->actionDelete($id);            }            Yii::$app->session->setFlash('success','Məlumatlar silindi.');        }else{            Yii::$app->session->setFlash('error','Heç bir seçim edilməyib.');        }        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);    }    /**     * Finds the SiteEnterprises model based on its primary key value.     * If the model is not found, a 404 HTTP exception will be thrown.     * @param integer $id     * @return SiteEnterprises the loaded model     * @throws NotFoundHttpException if the model cannot be found     */    protected function findModel($id)    {        if (($model = SiteEnterprises::findOne($id)) !== null) {            return $model;        }        throw new NotFoundHttpException('The requested page does not exist.');    }}