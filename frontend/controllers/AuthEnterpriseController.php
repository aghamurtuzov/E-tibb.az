<?php
namespace frontend\controllers;

use api\modules\enterprise\models\DashboardApiModel;
use common\models\SiteDoctors;
use frontend\models\LoginForm;
use Yii;
use yii\helpers\Url;
use common\models\AuthEnterpriseForm;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\SiteEnterprises;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use frontend\models\Enterprise;
use frontend\models\EnterpriseModel;
use frontend\models\PackagesServicesModel;
use frontend\models\PricesModel;
use frontend\models\ServicesModel;
use frontend\models\Transactions;
use frontend\models\User;
use backend\models\SiteUsers;
use backend\models\SiteSosialLinks;
use backend\models\SitePhoneNumbers;
use backend\components\ImageUpload;
use backend\models\SiteGallery;
use backend\models\SiteAddresses;
use frontend\components\Menu;
use frontend\models\SiteTransaction;
use frontend\models\SiteTransactionDetails;
use backend\components\Functions;
use frontend\controllers\MilliCardController;
use backend\models\SiteDoctorFilesModel;
use frontend\components\SeoLib;

/**
 * Auth Enterprise controller
 */
class AuthEnterpriseController extends MainController
{

    public $menus;
    public $layout = 'registration';
    public $rememberMe = 1;
    public $customPath = 'enterprises';
    const  TYPE = 2;
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
        if (Yii::$app->request->get("id")=="1" || Yii::$app->request->get("id")=="6") {
            if (isset($action->id) && ($action->id == 'step2' || $action->id == 'transactions')) {
                if (Yii::$app->user->isGuest) {
                    $this->redirect(Url::to(["/obyekt-qeydiyyat"]));
                    return false;
                } else {
                    if (Yii::$app->user->identity->status != 2) {
                        $this->redirect(Url::to(["/profile"]));
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
        throw new NotFoundHttpException("404");
    }

    public function actionRegister($id)
    {
        if(!Yii::$app->user->isGuest)
        {
            if(Yii::$app->user->identity->status==2)
            {
                return $this->redirect(Url::to(["/obyekt-qeydiyyat-paketler"]));
            }
            else
            {
                return $this->redirect(Url::to(["/profile"]));
            }
        }

        if(isset($this->menus["id"][$id]))
        {
            $category = $this->menus["id"][$id];
            if($category["type"]!=self::TYPE){
                return $this->redirect(Yii::$app->params["site.url"]);
            }
        }

        $model     = new SiteEnterprises();
        $user      = new User();

        $model->category_id = $id;

        $errors = [];

        if($model->load(Yii::$app->request->post()))
        {
            $model->validate();
            $errors = $model->errors;
            $lastRow = User::find()->select('id')->orderBy(['id' => SORT_DESC])->one();
            $user->unique_id = str_pad($lastRow['id'] + 1, 7, 0, STR_PAD_LEFT);
            $user->email = $model->email;
            $user->name = $model->name;
            $user->phone_number = $model->contact_phone;
            $user->phone_prefix = "994";
            $user->status = 1;
            $user->created_at = time();
            $user->last_login = date("Y-m-d H:i:s");
            $user->type = self::TYPE;

            $user->setPassword($model->password);
            $user->generateAuthKey();

            $datetim2 = date('Y-m-d', strtotime("+1 month", strtotime(Yii::$app->params['current.date'])));
            $model->expires = $datetim2;
            $model->user_id = $user->id;
            $model->slug    = Functions::slugify($model->name,['transliterate' => true]);
            $model->status  = 0;
            $model->published_time = date('Y-m-d H:i:s');

            if($model->save())
            {
                /** User_id */
                if($user->save(false))
                {
                    $model2 = SiteEnterprises::findOne($model->id);
                    $model2->user_id = $user->id;
                    $model2->save(false);
                }

                /** Phone numbers */
                if (isset($model->contact_phone) && !empty($model->contact_phone)) {
                    $phone_numbers = new SitePhoneNumbers();
                    $phone_numbers->connect_id = $model->id;
                    $phone_numbers->number = $model->contact_phone;
                    $phone_numbers->number_type = 1;
                    $phone_numbers->type = self::TYPE;
                    $phone_numbers->prefix = "994";
                    $phone_numbers->save();
                }


                /** Login and redirect*/
                $login = new LoginForm();
                $login->email = $user->email;
                $login->password = $model->password;

                if($login->validate() && $login->login()){
                    $id   = Yii::$app->user->identity->id;
                    $type = Yii::$app->user->identity->type;

                    $getDoctor = SiteEnterprises::findOne(['user_id'=>Yii::$app->user->identity->id]);
                    $id = $getDoctor['id'];

                    if(empty($id))
                    {
                        Yii::$app->user->logout();
                        return false;
                    }

                    Yii::$app->session->set('userID',$id);
                    Yii::$app->session->set('userType',$type);
                    return $this->redirect(Yii::$app->params["site.url"]."admin/enterprise#/");
                }

                Yii::$app->session->setFlash('register_success','Sizin məlumatlarınız sistemə əlavə olunub. Tezliklə menecerlərimiz tərəfindən yoxlanışdan sonra məlumatlar kataloqda əksini tapacaq');
                return $this->redirect(['main/success']);
            }
            else
            {
                Yii::$app->session->setFlash('error','Məlumatın əlavə olunması zamanı xəta baş verdi');
            }

        };

        /** Meta tags */
        $metaData["title"]         = $this->menus["id"][$id]['name'].' qeydiyyat';
        $metaData["canonical"]     = Url::base('https').Yii::$app->request->url;
        $metaData["page_type"]     = "website";
        $metaData["amp_status"]    = 0;
        $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];

        Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);
        /** Meta tags END */

        return $this->render('register', [
            'model'                 => $model,
            'user'                  => $user,
            'cat_id'                => $id,
            'errors'                => $errors
        ]);
    }


    public function actionStep2()
    {
        $order_id     = date('ymdhis').rand(100,999);
        $packages     = [];
        $services     = [];
        $services_ids = [];
        $description  = null;
        $paymentTypes = Yii::$app->params['payment.type'];
        $paymentServicesTypes = Yii::$app->params['payment.ent.services.type'];

        $user_id    = Yii::$app->user->id;
        $enterprise = EnterpriseModel::getEnterpriseWithUser($user_id);
        //print_r($enterprise);
        if(!empty($user_id) and  $enterprise)
        {

            /** Package */
            $packages_all = PackagesServicesModel::getPackages(self::TYPE);
            if(!empty($packages_all))
            {
                foreach($packages_all as $key=>$package)
                {
                    $packages[$package["id"]] = $package;
                    $json_prices = $package["data"];
                    $prices = json_decode($json_prices, true);
                    //print_r($prices["prices"][$enterprise["category_id"]]);
                    //print_r($prices);
                    if(isset($prices["prices"][$enterprise["category_id"]]))
                    {
                        $packages[$package["id"]]["price"] = $prices["prices"][$enterprise["category_id"]];
                    }else{
                        $packages[$package["id"]]["price"] = $prices["price"];
                    }
                    //echo $enterprise["category_id"];
                }
            }

            /** Services */
            $services = PackagesServicesModel::getServices(self::TYPE);
            if(!empty($services))
            {
                foreach ($services as $key=>$service)
                {
                    $json_prices = $service["data"];
                    $prices = json_decode($json_prices, true);
                    if(isset($prices["prices"][$enterprise["category_id"]]))
                    {
                        $services[$key]["prices"] = $prices["prices"][$enterprise["category_id"]];
                    }else{
                        $services[$key]["prices"] = $prices["price"];
                    }
                }
            }

            /** get Post Data */
            if(isset($_POST["package"]) and intval($_POST["package"])>0)
            {
                $package_id = intval(Yii::$app->request->post('package'));
                if(!empty($package_id))
                {

                    $log_data = ["package" => $package_id];

                    $package_price = floatval($packages[$package_id]["price"]);
                    $totalPrice    = $package_price;

                    /** Transaction insert (Package) */
                    if(isset($paymentTypes[60000]))
                    {
                        $description = $paymentTypes[60000];
                        $this->saveTransactionDetails($order_id,$enterprise['id'],60000,$package_id,$packages[$package_id]['month'],$package_price);
                    }

                    /** get Services */
                    $getServices = Yii::$app->request->post('services');

                    if(!empty($getServices))
                    {

                        foreach($getServices as $ser)
                        {
                            $services_ids[] = $ser;
                        }

                        $getServiceMonth = Yii::$app->request->post('service_month');

                        if(!empty($getServiceMonth))
                        {
                            foreach($getServiceMonth as $month)
                            {
                                if(strpos($month,"-"))
                                {
                                    $exp = explode("-",$month);
                                    if(in_array($exp[0],$services_ids))
                                    {
                                        if(isset($paymentServicesTypes[$exp[0]]))
                                        {

                                            $log_data["services"][$exp[0]]["service"] = $exp[0];
                                            $log_data["services"][$exp[0]]["month"]   = $exp[1];
                                            $log_data["services"][$exp[0]]["price"]   = $exp[2];

                                            $totalPrice   = $totalPrice + floatval($log_data["services"][$exp[0]]["price"]);
                                            $payment_type = $exp[0];

                                            $description .= '.'.$paymentServicesTypes[$exp[0]];

                                            /** Transaction insert (Services) */
                                            $insertTransaction = $this->saveTransactionDetails($order_id,$enterprise['id'],$payment_type,$exp[0],$exp[1],$exp[2]);
                                            if(!$insertTransaction)
                                            {
                                                break;
                                            }
                                        }else{
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    };
                    //echo $totalPrice; exit();
                    /** Transaction insert (All) */
                    $log_data = json_encode($log_data);
                    $insertTransaction = $this->saveTransaction($order_id,$enterprise['id'],$log_data,$totalPrice);
                    if(!empty($insertTransaction))
                    {
                        $description = strtolower($description);
                        $getPayment = new MilliCardController($totalPrice,$order_id,$description);
                        $getPayment->pay();
                    }

                }
            }

        }else{
            throw new NotFoundHttpException(Yii::t('app',"Paketin alınması zamanı xəta baş verdi."));
        }

        return $this->render('step2', [
            'enterprise' => $enterprise,
            'packages'   => $packages,
            'services'  => $services
        ]);

    }

//    public function actionTransactions()
//    {
//        return $this->render('transactions');
//    }

    public function saveTransactionDetails($order_id,$connect_id,$payment_type,$payment_info,$quantity,$price)
    {
        $transaction = new SiteTransactionDetails();
        $transaction->order_id       = $order_id;
        $transaction->connect_id     = $connect_id;
        $transaction->type           = self::TYPE;
        $transaction->payment_type   = $payment_type;
        $transaction->payment_info   = $payment_info;
        $transaction->quantity       = $quantity;
        $transaction->price          = $price;
        $transaction->created_date   = date('Y-m-d H:i:s');
        $transaction->payment_method = 1;
        $transaction->status         = 0;
        $result = $transaction->save();
        return $result;
    }

    public function saveTransaction($order_id,$connect_id,$log_data,$total_price)
    {
        $transaction = new SiteTransaction();
        $transaction->order_id       = $order_id;
        $transaction->connect_id     = $connect_id;
        $transaction->type           = self::TYPE;
        $transaction->log_data       = $log_data;
        $transaction->total_price    = $total_price;
        $transaction->created_date   = date('Y-m-d H:i:s');
        $transaction->payment_method = 1;
        $transaction->status         = 0;
        $result = $transaction->save();
        return $result;
    }

}
