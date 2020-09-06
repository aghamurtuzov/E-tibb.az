<?php

namespace api\modules\enterprise\controllers;

use api\models\SiteDoctorsModel;
use api\modules\enterprise\controllers\MainController;
use api\models\PackagesServicesModel;
use api\models\SiteTransactionDetails;
use api\models\SiteTransaction;
use yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use api\components\Functions;
use api\models\EnterpriseModel;
use api\controllers\MilliCardController;

/**
 * News API
 */

class PayController extends MainController
{

    public $modelClass = '';
    const  TYPE = 2;

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Pay action
     * https://e-tibb.az/api/enterprise/pay/create
     */
    public function actionCreate()
    {

        $order_id     = date('ymdhis').rand(100,999);
        $packages     = [];
        $services     = [];
        $services_ids = [];
        $description  = null;
        $paymentTypes = Yii::$app->params['payment.type'];
        $paymentServicesTypes = Yii::$app->params['payment.ent.services.type'];

        $user_id  = Yii::$app->user->id; //Yii::$app->user->id; 686
        $enterprise   = SiteDoctorsModel::getEntWithUser($user_id);

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
                    //print_r($prices);
                    $packages[$package["id"]]["price"] = $prices["price"];
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
                    $services[$key]["prices"] = $prices["price"];
                }
            }

            /** get Post Data */
            if(isset($_POST["package"]) and intval($_POST["package"])>0)
            {
                //var_dump($_POST); die();
                $package_id = intval(Yii::$app->request->post('package'));
                if(!empty($package_id))
                {

                    $log_data = ["package" => $package_id];

                    $package_price = floatval($packages[$package_id]["price"]);
                    $totalPrice    = $package_price;


                    /** Transaction insert (Package) */
                    if(isset($paymentTypes[50000]))
                    {
                        $description = $paymentTypes[50000];
                        $this->saveTransactionDetails($order_id,$enterprise['id'],50000,$package_id,$packages[$package_id]['month'],$package_price);
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
                    //echo $description; exit();
                    /** Transaction insert (All) */
                    $log_data = json_encode($log_data);
                    $insertTransaction = $this->saveTransaction($order_id,$enterprise['id'],$log_data,$totalPrice);
                    if(!empty($insertTransaction))
                    {
                        $description = strtolower($description);
                        $getPayment = new MilliCardController($totalPrice,$order_id,$description);
                        $redirect_url = $getPayment->pay();

                        return $this->response(200,"Link generated", $redirect_url);
                    }
                }
            }

        }else{
            return $this->response(200,"Heçbir məlumat tapılmadı");
            throw new NotFoundHttpException(Yii::t('app',"Paketin alınması zamanı xəta baş verdi."));
        }
    }

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

    public function actionGetPackages()
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
                //print_r($prices);
                $packages[$package["id"]]["price"] = $prices["price"];
            }

            return $this->response(200,"Məlumat mövcuddur",$packages);
        }
    }

    public function actionGetServices()
    {
        /** Services */
        $services = PackagesServicesModel::getServices(self::TYPE);
        if(!empty($services))
        {
            foreach ($services as $key=>$service)
            {
                $json_prices = $service["data"];
                $prices = json_decode($json_prices, true);

//                $services[$key]["prices"] = $prices["price"];

//                $i = 1;
                $j = 0;
                foreach ($prices["price"] as $k=>$price)
                {
                    $services[$key]['prices'][$j]['month'] = $k;
                    $services[$key]['prices'][$j]['price'] = $price;

                    $j++;
//                    $i++;

                }
            }

            return $this->response(200,"Məlumat mövcuddur",$services);
        }
    }

//    protected function findModel($id)
//    {
//        $connectID = Yii::$app->session->get('userID');
//        if(($model = SiteNews::find()->where(['id'=>$id,'connect_id'=>$connectID,'type'=>NewsApiModel::TYPE_DOCTOR])->one()) !== null){
//            return $model;
//        }
//        throw new NotFoundHttpExcept('The requested page does not exist.');
//    }

}