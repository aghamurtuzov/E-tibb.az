<?php

namespace api\modules\doctor\controllers;

use yii;
use api\modules\doctor\models\DashboardApiModel;
use api\modules\doctor\controllers\MainController;

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
     * https://e-tibb.az/api/doctor/dashboard
     */
    public function actionIndex()
    {
        $doctorID = Yii::$app->session->get('userID');

        $result['aksiyalar']   = DashboardApiModel::PromotionCount($doctorID,1);
        $result['rezervasiya'] = DashboardApiModel::RezervasiyaCount($doctorID);
        $result['bloqlar']     = DashboardApiModel::NewsCount($doctorID,1,34);
        $result['reyler']      = DashboardApiModel::CommentCount($doctorID,1);
        $result['suallar']     = DashboardApiModel::QuestionCount($doctorID);

        return $this->response(200,"Məlumat mövcuddur",$result);
    }

}