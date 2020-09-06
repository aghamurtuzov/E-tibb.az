<?php

namespace api\modules\general\controllers;

use yii;
use api\components\Pagination;
use api\modules\general\models\AppointmentApiModel;
use api\modules\general\controllers\MainController;

/**
 * Appointment API
 */

class AppointmentController extends MainController
{

    public $modelClass = '';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Randevular
     * https://e-tibb.az/api/general/appointment
     */
    public function actionIndex()
    {
        $model  = new AppointmentApiModel();
        $status = AppointmentApiModel::getStatus();

        $totalCount = $model->AppointmentsCount();

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $model->Appointments($limits);

        if(!empty($list))
        {
            foreach($list as $key => $val)
            {
                $list[$key]['status_name']   = $status[$list[$key]['status']];
            }
        }

        $data['list'] = $list;

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages,$data);

        return $this->response(200,"Məlumat mövcuddur",$result);
    }
}