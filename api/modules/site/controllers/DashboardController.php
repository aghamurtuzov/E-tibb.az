<?php

namespace api\modules\general\controllers;

use yii;
use api\modules\general\models\DashboardApiModel;
use api\modules\general\controllers\MainController;

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
     * https://e-tibb.az/api/general/dashboard
     */
    public function actionIndex()
    {
        $result['doctor']        = DashboardApiModel::DoctorsCount();
        $result['klinikalar']    = DashboardApiModel::EnterpriseCount(1);
        $result['aptekler']      = DashboardApiModel::EnterpriseCount(6);
        $result['aksiyalar']     = DashboardApiModel::PromotionCount();
        $result['suallar']       = DashboardApiModel::QuestionCount();
        $result['reyler']        = DashboardApiModel::CommentCount();
        $result['rezervasiya']   = DashboardApiModel::RezervasiyaCount();
        $result['mektublar']     = 0;
        $result['bloqlar']       = DashboardApiModel::NewsCount(34);
        $result['tibbi_maqaza']  = DashboardApiModel::EnterpriseCount(40);
        $result['muhasib']       = 0;
        $result['canlı']         = 0;
        $result['statik']        = 3;
        $result['istifadeciler'] = DashboardApiModel::UsersCount();
        $result['xeber']         = DashboardApiModel::NewsCount(37);
        $result['statistkia']    = 0;

        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Doctor Dashboard
     * https://e-tibb.az/api/general/dashboard/doctor
     * id = 123
     */
//    public function actionDoctor()
//    {
//        $id = intval(Yii::$app->request->get('id'));
//        if(empty($id))
//        {
//            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
//        }
//
//        $result['suallar']     = DashboardApiModel::QuestionCount($id);
//        $result['reyler']      = DashboardApiModel::CommentCount($id,1);
//        $result['rezervasiya'] = DashboardApiModel::RezervasiyaCount($id);
//        $result['balans']      = 0;
//
//        return $this->response(200,"Məlumat mövcuddur",$result);
//    }

}