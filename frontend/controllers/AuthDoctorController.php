<?php

namespace frontend\controllers;

use common\models\SiteEnterprises;
use common\models\SiteDoctors;
use backend\models\SiteDoctorWorkplaces;
use backend\models\SiteDoctorSpecialist;
use backend\models\SiteSpecialists;
use backend\models\SiteDoctorFilesModel;
use backend\models\SiteUsers;
use backend\models\SiteSosialLinks;
use backend\models\SitePhoneNumbers;
use backend\components\ImageUpload;
use backend\models\SiteGallery;
use common\models\AuthEnterpriseForm;
use frontend\models\LoginForm;
use yii\web\UploadedFile;
use backend\models\SiteAddresses;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use frontend\components\Menu;
use yii\helpers\ArrayHelper;
use frontend\models\User;
use frontend\models\Usernew;
use common\models\SiteDoctorWorkplacesList;
use frontend\models\SiteDoctorsModel;
use frontend\models\PricesModel;
use frontend\models\ServicesModel;
use frontend\models\Transactions;
use frontend\models\PackagesServicesModel;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use frontend\controllers\MainController;
use frontend\models\SiteTransaction;
use frontend\models\SiteTransactionDetails;
use backend\components\Functions;
use frontend\controllers\MilliCardController;
use frontend\components\SeoLib;

/**
 * Auth Doctor controller
 */
class AuthDoctorController extends MainController
{

    public $menus;
    public $layout = 'doctor_layout';
    public $rememberMe = 1;
    public $customPath = 'doctors';
    const  TYPE = 1;
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
            ]
        ];
    }

    public function beforeAction($action)
    {

        if (isset($action->id) && ($action->id == 'step2' || $action->id == 'transactions')) {
            if (Yii::$app->user->isGuest) {
                $this->redirect(Url::to(["/hekim-qeydiyyat"]));
                return false;
            } else {
                if (Yii::$app->user->identity->status != 2) {
                    $this->redirect(Url::to(["/profil"]));
                    return false;
                }
                if (Yii::$app->user->identity->type != self::TYPE) {
                    $this->redirect(Yii::$app->params["site.url"]);
                    return false;
                }
            }
        }

        $menus = ArrayHelper::toArray(new Menu());
        $this->menus = $menus['list'];
        $this->seo = new SeoLib();
        //return parent::beforeAction($action);
        return $this->menus;

    }

    public function actionRegister2()
    {
        exit();
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->status == 2) {
                return $this->redirect(Url::to(["/hekim-qeydiyyat-paketler"]));
            } else {
                return $this->redirect(Url::to(["/profile"]));
            }
        }

        $model = new SiteDoctors();
        $user = new User();

        $enterprise_categories = $this->menus["type"][2];

        if ($model->load(Yii::$app->request->post())) {
            /** Check Mobil Number */
            if (isset($model->mobile_numbers[0]['number']) && empty($model->mobile_numbers[0]['number'])) {
                $model->addError('mobile_numbers', 'Mobil nömrələr xanasını boş buraxmayın');
            }
            /** Create User */
            if (strlen($model->name) < 3) {
                $model->addError('name', 'Ən az 3 hərf mümkündür.');
            } else {
                $user->name = $model->name;
            }

            $lastRow = User::find()->select('id')->orderBy(['id' => SORT_DESC])->one();
            $user->unique_id = str_pad($lastRow['id'] + 1, 7, 0, STR_PAD_LEFT);
            $user->email = $model->email;
            $user->phone_number = $model->mobile_numbers[0]['number'];
            $user->status = 0;
            $user->created_at = time();
            $user->last_login = date("Y-m-d H:i:s");
            $user->type = self::TYPE;
            $user->birthday = date("Y-m-d", strtotime($model->birthday));
//            if($model->password != $model->repassword){
//                //Yii::$app->session->setFlash('error','Şifrə ilə təkrar şifrə eyni deyil');
//                $user->addError('repassword','Şifrə ilə təkrar şifrə eyni deyil');
//            }
            $user->setPassword($model->password);
            $user->generateAuthKey();


//            if($user->save())
//            {
            $datetim2 = date('Y-m-d', strtotime("+1 month", strtotime(Yii::$app->params['current.date'])));

            $model->expires = $datetim2;
            $model->slug = Functions::slugify($model->name, ['transliterate' => true]);
            $model->status = 0;
            $model->published_time = date('Y-m-d H:i:s');
            $model->modified_time = date('Y-m-d H:i:s');

//                $model->experience1 = date("Y-m-d",strtotime($model->experience1));

            if ($model->home_doctor) {
                $model->feature = 1;
            }

            if ($model->child_doctor) {
                $model->feature = 2;
            }

            if ($model->home_doctor && $model->child_doctor) {
                $model->feature = 3;
            }

            if (isset($model->mobile_numbers[0]['number']) && !empty($model->mobile_numbers[0]['number'])) {
                //echo 'User iffed';
                $count = isset($model->phone_numbers) ? count($model->phone_numbers) : 0;
                foreach ($model->mobile_numbers as $key => $val) {
                    if (!empty($val['number'])) {
                        $model->phone_numbers[$count + $key]['number'] = $val["number"];
                        $model->phone_numbers[$count + $key]['type'] = 1;
                    }
                }
            }

            if ($model->save()) {
                /** User_id */
                if ($user->save()) {
                    $model2 = SiteDoctors::findOne($model->id);
                    $model2->user_id = $user->id;
                    $model2->save(false);
                }

                //echo 'Model saved';
                /** Specialists */
                if (is_array($model->specialists)) {
                    foreach ($model->specialists as $key => $val) {
                        $doctors_specialist = new SiteDoctorSpecialist();
                        $doctors_specialist->doctor_id = $model->id;
                        $doctors_specialist->specialist_id = $val;
                        if ($doctors_specialist->save()) {
                            $siteSpc = SiteSpecialists::find()->where(['id' => $val])->one();
                            $siteSpc->count = $siteSpc->count + 1;
                            $siteSpc->save();
                        }
                    }
                }

                /** Sosial links */
                if (isset($model->sosial_links[0]['link']) && !empty($model->sosial_links[0]['link'])) {
                    foreach ($model->sosial_links as $key => $val) {
                        if (!empty($val['link'])) {
                            $sosial_links = new SiteSosialLinks();
                            $sosial_links->connect_id = $model->id;
                            $sosial_links->link = $val['link'];
                            $sosial_links->link_type = $val['type'];
                            $sosial_links->type = self::TYPE;
                            $sosial_links->save();
                        }
                    }
                }

                /** Phone numbers */
                if (isset($model->phone_numbers[0]['number']) && !empty($model->phone_numbers[0]['number'])) {
                    foreach ($model->phone_numbers as $key => $val) {
                        if (!empty($val['number'])) {
                            $phone_numbers = new SitePhoneNumbers();
                            $phone_numbers->connect_id = $model->id;
                            $phone_numbers->number = $val['number'];
                            //$phone_numbers->number_type = $val['type'];
                            $phone_numbers->number_type = isset($val['type']) ? $val['type'] : 0;
                            $phone_numbers->type = self::TYPE;
                            $phone_numbers->save();
                        }
                    }
                }

                /** Work places  */
                if (isset($model->workplaces_list[0]['name']) && !empty($model->workplaces_list[0]['name'])) {
                    foreach ($model->workplaces_list as $key => $val) {
                        $workplaces = new SiteDoctorWorkplacesList();
                        $workplaces->name = $val['name'];
                        $workplaces->address = $val['address'];
                        $workplaces->doctor_id = $model->id;
                        $workplaces->save();
                    }
                }

                /** Main image & Photosession */
                $photos = UploadedFile::getInstances($model, 'files');

                if (!empty($photos)) {
                    foreach ($photos as $key => $photo) {
                        $imageUpload = new ImageUpload();
                        if ($key != 0) {
                            $uploadedFile = $imageUpload->saveFile($photo, [
                                'path.save' => $this->customPath,
                                'resize.img' => [708, 420],
                                'resize.thumb' => [185, 110]
                            ]);

                            $updatePhoto = $model;
                            $updatePhoto->photo = $uploadedFile;
                            $updatePhoto->save(false);
                        } else {

                            $uploadedFile = $imageUpload->saveFile($photo, [
                                'path.save' => $this->customPath,
                                'resize.img' => [185, 185],
                                'resize.thumb' => [137, 137]
                            ]);

                            $updatePhoto = $model;
                            $updatePhoto->photo = $uploadedFile;
                            $updatePhoto->save(false);
                        }

                    }
                }

                /** Diplomas*/
                $photos2 = UploadedFile::getInstances($model, 'dp_files');

                if (!empty($photos2)) {
                    foreach ($photos2 as $key => $photo) {
                        $imageUpload = new ImageUpload();
                        $uploadedFile = $imageUpload->saveFile($photo, [
                            'path.save' => $this->customPath,
                            'resize.img' => [708, 420],
                            'resize.thumb' => [185, 110]
                        ]);
                        if (!empty($uploadedFile)) {
                            $files = new SiteDoctorFilesModel();
                            $files->file_photo = $uploadedFile;
                            $files->user_type = 1;
                            $files->connect_id = $model->id;
                            $files->type = 1;
                            $files->save();
                        }

                    }
                }

                /** Certificate*/
                $photos3 = UploadedFile::getInstances($model, 'ct_files');

                if (!empty($photos3)) {
                    foreach ($photos3 as $key => $photo) {
                        $imageUpload = new ImageUpload();
                        $uploadedFile = $imageUpload->saveFile($photo, [
                            'path.save' => $this->customPath,
                            'resize.img' => [708, 420],
                            'resize.thumb' => [185, 110]
                        ]);
                        if (!empty($uploadedFile)) {
                            $files = new SiteDoctorFilesModel();
                            $files->file_photo = $uploadedFile;
                            $files->connect_id = $model->id;
                            $files->user_type = 1;
                            $files->type = 2;
                            $files->save();
                        }

                    }
                }

                //exit('HELLO WORLD');

                Yii::$app->session->setFlash('register_success', 'Sizin məlumatlarınız sistemə əlavə olunub. Tezliklə menecerlərimiz tərəfindən yoxlanışdan sonra məlumatlar kataloqda əksini tapacaq');

//                    Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);

//                    return $this->redirect(Url::to(['profil/sual-cavab']));
                return $this->redirect(['main/success']);

            } else {
                Yii::$app->session->setFlash('error', 'Məlumatın əlavə olunması zamanı xəta baş verdi');
            }

//            }
//            else{
//                $error_array = $user->errors;
//                if(isset($error_array["email"])){ $model->addError("email","Bu email artıq qeydiyyatdan keçib"); }
//                Yii::$app->session->setFlash('error','Məlumatın əlavə olunması zamanı xəta baş verdi');
//            }

        }

        return $this->render('register', [
            'model' => $model,
            'user' => $user,
            'enterprise_categories' => $enterprise_categories,
        ]);

    }

    public function actionRegister()
    {

        $model = new SiteDoctors();
        $user = new User();

        if ($model->load(Yii::$app->request->post())) {
            $o_date = date("Y");
            $da = date("Y", strtotime($model->birthday));
            if (($o_date - $da) >= 23) {

                /** Create User */

                $lastRow = User::find()->select('id')->orderBy(['id' => SORT_DESC])->one();

                $user->unique_id = str_pad($lastRow['id'] + 1, 7, 0, STR_PAD_LEFT);
                $user->email = $model->email;
                $user->name = $model->name;
                $user->phone_number = $model->phone;
                $user->phone_prefix = "994";
                $user->status = 1;
                $user->created_at = time();
                $user->last_login = date("Y-m-d H:i:s");
                $user->type = self::TYPE;
                $user->birthday = date("Y-m-d", strtotime($model->birthday));


                $user->setPassword($model->password);
                $user->generateAuthKey();

                $datetim = date('Y-m-d', strtotime("+1 month", strtotime(Yii::$app->params['current.date'])));
                $model->expires = $datetim;
                $model->slug = Functions::slugify($model->name, ['transliterate' => true]);
                $model->status = 0;
                $model->published_time = date('Y-m-d H:i:s');
                $model->modified_time = date('Y-m-d H:i:s');
                $model->degree = 1;

                $model->validate();

                /** Check Main image **/
                $photo = UploadedFile::getInstance($model, 'files');
                if (empty($photo)) {
                    $model->addError('files', 'Şəkil əlavə edin');
                } else {
                    $imageUpload = new ImageUpload();
                    if (!$imageUpload->validate($photo)) {
                        $model->addError('files', 'Şəkilin yüklənməsi zamanı xəta baş verdi');
                    }
                }


                if (!empty($model->errors)) {
                    return $this->render('register', [
                        'model' => $model,
                        'user' => $user
                    ]);
                }

                if ($model->save()) {
                    /** User_id */
                    if ($user->save(false)) {
                        $model2 = SiteDoctors::findOne($model->id);
                        $model2->user_id = $user->id;
                        $model2->save(false);
                    }

                    /** Main image **/
                    //if (isset($_COOKIE['aaa'])) {
                        if (!empty($photo)) {
                            $imageUpload = new ImageUpload();
                            $uploadedFile = $imageUpload->saveFile($photo, [
                                'path.save' => 'doctors',
                                'resize.img' => [300, 265],
                                'resize.thumb' => [120, 106]
                            ]);
                            $updatePhoto = $model;
                            $updatePhoto->photo = $uploadedFile;
                            $updatePhoto->save(false);
                        }
                    //}

                    /** Specialists */
                    if (!empty($model->specialists)) {
                        $doctors_specialist = new SiteDoctorSpecialist();
                        $doctors_specialist->doctor_id = $model->id;
                        $doctors_specialist->specialist_id = $model->specialists;
                        $doctors_specialist->save();
                    }
//                    if (is_array($model->specialists)) {
//                        foreach ($model->specialists as $key => $val) {
//                            $doctors_specialist = new SiteDoctorSpecialist();
//                            $doctors_specialist->doctor_id = $model->id;
//                            $doctors_specialist->specialist_id = $val;
//                            if ($doctors_specialist->save()) {
//                                $siteSpc = SiteSpecialists::find()->where(['id' => $val])->one();
//                                $siteSpc->count = $siteSpc->count + 1;
//                                $siteSpc->save();
//                            }
//                        }
//                    }

                    /** Phone numbers */
                    if (isset($model->phone) && !empty($model->phone)) {
                        $phone_numbers = new SitePhoneNumbers();
                        $phone_numbers->connect_id = $model->id;
                        $phone_numbers->number = $model->phone;
                        $phone_numbers->number_type = 1;
                        $phone_numbers->type = self::TYPE;
                        $phone_numbers->prefix = "994";
                        $phone_numbers->save();
                    }

                    /** Login and redirect*/
                    $login = new LoginForm();
                    $login->email = $user->email;
                    $login->password = $model->password;

                    if ($login->validate() && $login->login()) {
                        $id = Yii::$app->user->identity->id;
                        $type = Yii::$app->user->identity->type;

                        $getDoctor = SiteDoctors::findOne(['user_id' => Yii::$app->user->identity->id]);
                        $id = $getDoctor['id'];

                        if (empty($id)) {
                            Yii::$app->user->logout();
                            return false;
                        }

                        Yii::$app->session->set('userID', $id);
                        Yii::$app->session->set('userType', $type);
                        return $this->redirect(Yii::$app->params["site.url"]."admin/doctor#/setting");
                    }

                    Yii::$app->session->setFlash('register_success', 'Sizin məlumatlarınız sistemə əlavə olunub. Tezliklə menecerlərimiz tərəfindən yoxlanışdan sonra məlumatlar kataloqda əksini tapacaq');
                    return $this->redirect(['main/success']);
                } else {
                    Yii::$app->session->setFlash('error', 'Məlumatın əlavə olunması zamanı xəta baş verdi');
                }

            } else {
                $model->addError('birthday', '23 yaşdan kiçik olmamalıdır');
            }
        }

        /** Meta tags */
        $metaData["title"] = 'Həkim qeydiyyatı';
        $metaData["canonical"] = Url::base('https') . Yii::$app->request->url;
        $metaData["page_type"] = "website";
        $metaData["amp_status"] = 0;
        $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];
        Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);
        /** Meta tags END */

        return $this->render('register', [
            'model' => $model,
            'user' => $user
        ]);

    }

    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->login()) {
                return $this->redirect(Url::to(["profile/index"]));
            }
        }
        return $this->render('login', [
            'model' => $model
        ]);
    }

    public function actionStep2()
    {
        exit();
        $order_id = date('ymdhis') . rand(100, 999);
        $packages = [];
        $services = [];
        $services_ids = [];
        $description = null;
        $paymentTypes = Yii::$app->params['payment.type'];
        $paymentServicesTypes = Yii::$app->params['payment.doctor.services.type'];

        $user_id = Yii::$app->user->id;
        $doctor = SiteDoctorsModel::getDoctorWithUser($user_id);

        if (!empty($user_id) and $doctor) {

            /** Package */
            $packages_all = PackagesServicesModel::getPackages(self::TYPE);
            if (!empty($packages_all)) {
                foreach ($packages_all as $key => $package) {
                    $packages[$package["id"]] = $package;
                    $json_prices = $package["data"];
                    $prices = json_decode($json_prices, true);
                    //print_r($prices);
                    $packages[$package["id"]]["price"] = $prices["price"];
                }
            }

            /** Services */
            $services = PackagesServicesModel::getServices(self::TYPE);
            if (!empty($services)) {
                foreach ($services as $key => $service) {
                    $json_prices = $service["data"];
                    $prices = json_decode($json_prices, true);
                    $services[$key]["prices"] = $prices["price"];
                }
            }

            /** get Post Data */
            if (isset($_POST["package"]) and intval($_POST["package"]) > 0) {
                //var_dump($_POST); die();
                $package_id = intval(Yii::$app->request->post('package'));
                if (!empty($package_id)) {

                    $log_data = ["package" => $package_id];

                    $package_price = floatval($packages[$package_id]["price"]);
                    $totalPrice = $package_price;

                    /** Transaction insert (Package) */
                    if (isset($paymentTypes[50000])) {
                        $description = $paymentTypes[50000];
                        $this->saveTransactionDetails($order_id, $doctor['id'], 50000, $package_id, $packages[$package_id]['month'], $package_price);
                    }

                    /** get Services */
                    $getServices = Yii::$app->request->post('services');
                    if (!empty($getServices)) {

                        foreach ($getServices as $ser) {
                            $services_ids[] = $ser;
                        }

                        $getServiceMonth = Yii::$app->request->post('service_month');
                        if (!empty($getServiceMonth)) {
                            foreach ($getServiceMonth as $month) {
                                if (strpos($month, "-")) {
                                    $exp = explode("-", $month);
                                    if (in_array($exp[0], $services_ids)) {
                                        if (isset($paymentServicesTypes[$exp[0]])) {
                                            $log_data["services"][$exp[0]]["service"] = $exp[0];
                                            $log_data["services"][$exp[0]]["month"] = $exp[1];
                                            $log_data["services"][$exp[0]]["price"] = $exp[2];

                                            $totalPrice = $totalPrice + floatval($log_data["services"][$exp[0]]["price"]);
                                            $payment_type = $exp[0];
                                            $description .= '.' . $paymentServicesTypes[$exp[0]];
                                            /** Transaction insert (Services) */
                                            $insertTransaction = $this->saveTransactionDetails($order_id, $doctor['id'], $payment_type, $exp[0], $exp[1], $exp[2]);
                                            if (!$insertTransaction) {
                                                break;
                                            }
                                        } else {
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    };
                    //echo $description; exit();
                    /** Transaction insert (All) */
                    $log_data = json_encode($log_data);
                    $insertTransaction = $this->saveTransaction($order_id, $doctor['id'], $log_data, $totalPrice);
                    if (!empty($insertTransaction)) {
                        $description = strtolower($description);
                        $getPayment = new MilliCardController($totalPrice, $order_id, $description);
                        $getPayment->pay();
                    }

                }

            }

        } else {
            throw new NotFoundHttpException(Yii::t('app', "Paketin alınması zamanı xəta baş verdi."));
        }

        return $this->render('step2', [
            'doctor' => $doctor,
            'packages' => $packages,
            'services' => $services
        ]);

    }

//    public function actionTransactions()
//    {
//        return $this->render('transactions');
//    }

    public function saveTransactionDetails($order_id, $connect_id, $payment_type, $payment_info, $quantity, $price)
    {
        exit();
        $transaction = new SiteTransactionDetails();
        $transaction->order_id = $order_id;
        $transaction->connect_id = $connect_id;
        $transaction->type = self::TYPE;
        $transaction->payment_type = $payment_type;
        $transaction->payment_info = $payment_info;
        $transaction->quantity = $quantity;
        $transaction->price = $price;
        $transaction->created_date = date('Y-m-d H:i:s');
        $transaction->payment_method = 1;
        $transaction->status = 0;
        $result = $transaction->save();
        return $result;
    }

    public function saveTransaction($order_id, $connect_id, $log_data, $total_price)
    {
        exit();
        $transaction = new SiteTransaction();
        $transaction->order_id = $order_id;
        $transaction->connect_id = $connect_id;
        $transaction->type = self::TYPE;
        $transaction->log_data = $log_data;
        $transaction->total_price = $total_price;
        $transaction->created_date = date('Y-m-d H:i:s');
        $transaction->payment_method = 1;
        $transaction->status = 0;
        $result = $transaction->save();
        return $result;
    }

}