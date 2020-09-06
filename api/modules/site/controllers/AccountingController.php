<?php

namespace api\modules\general\controllers;

use yii;
use api\models\SiteTransactionDetails;
use api\components\Pagination;
use api\components\Functions;
use yii\filters\AccessControl;
use api\models\PackagesServicesModel;
use api\modules\general\models\AccountingApiModel;
use api\modules\general\controllers\MainController;

/**
 * Accounting API
 */

class AccountingController extends MainController
{

    public $modelClass = '';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Serhler
     * https://e-tibb.az/api/general/accounting/cart-payments
     * https://e-tibb.az/api/doctor/accounting/cart-payments?page=1&count=5
     */
    public function actionCartPayments()
    {

        $model = new AccountingApiModel();

        $totalCount = $model->AllCartPaymentsCount();

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $model->AllCartPayments($limits);

        if(!empty($list))
        {

            $packages = Functions::customIndexArray(PackagesServicesModel::getPackages(),'id');
            $services = Functions::customIndexArray(PackagesServicesModel::getServices(),'id');

            if(!empty($packages) && !empty($services))
            {
                foreach($list as $key => $val)
                {
                    if(isset(Yii::$app->params['payment.type'][$val['payment_type']]))
                    {
                        $list[$key]['service_name'] = isset($packages[$val['payment_info']]['name']) ? $packages[$val['payment_info']]['name'] : null;
                    }else{
                        $list[$key]['service_name'] = isset($services[$val['payment_info']]['name']) ? $services[$val['payment_info']]['name'] : null;
                    }

                    $month   = intval($val['quantity']);
                    $time    = strtotime($list[$key]['payment_date']);
                    $endData = date("Y-m-d", strtotime("+{$month} month", $time));
                    $endData = Functions::getDatetime($endData,['type'=>'date','month'=>'no','combine'=>'.']);

                    $list[$key]['status_name'] = $val['status'] == 1 ? 'Ödənilib' : 'Ödənilməyib';
                    $list[$key]['payment_method'] = $val['payment_method'] == 0 ? 'Nağd' : 'Kart';
                    $list[$key]['package_name'] = $val['quantity'].' Aylıq';
                    $list[$key]['services_duration'] = Functions::getDatetime($list[$key]['payment_date'],['type'=>'date','month'=>'no','combine'=>'.']).' - '.$endData;

                    unset($list[$key]['type']);
                    unset($list[$key]['payment_type']);
                    unset($list[$key]['payment_info']);
                    unset($list[$key]['created_date']);
                }
            }

        }

        $data['list'] = $list;

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages,$data);

        return $this->response(200,"Məlumat mövcuddur",$result);
    }

}