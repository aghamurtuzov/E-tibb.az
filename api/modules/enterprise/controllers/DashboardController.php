<?php

namespace api\modules\enterprise\controllers;

use yii;
use api\modules\enterprise\models\DashboardApiModel;
use api\modules\enterprise\controllers\MainController;

/**
 * Dashboard API
 */

class DashboardController extends MainController
{

    public $modelClass = '';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Dashboard
     * https://e-tibb.az/api/enterprise/dashboard
     */
    public function actionIndex()
    {
        $enterpriseID = Yii::$app->session->get('userID');

        $result['hekimler']  = DashboardApiModel::DoctorsCount($enterpriseID);
        $result['aksiyalar'] = DashboardApiModel::PromotionCount($enterpriseID,2);
        $result['reyler']    = 0;
        $result['bloqlar']   = DashboardApiModel::NewsCount($enterpriseID,2,34);
        $result['xeberler']  = DashboardApiModel::NewsCount($enterpriseID,2,37);

        return $this->response(200,"Məlumat mövcuddur",$result);
    }

}