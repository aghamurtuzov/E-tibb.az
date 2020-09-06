<?php

namespace api\modules\enterprise\controllers;

use yii;
use api\models\SiteDoctors;
use api\models\SiteSpecialists;
use api\models\SiteSosialLinks;
use api\modules\enterprise\controllers\MainController;

/**
 * General API
 */

class GeneralController extends MainController
{

    const TYPE = 1;
    public $modelClass = '';
    public $customPath = 'doctors';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Cins
     * https://e-tibb.az/api/enterprise/doctor/list-gender
     */
    public function actionListGender()
    {
        $result = SiteDoctors::getSex();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Mutexessisler
     * https://e-tibb.az/api/enterprise/doctor/specialists
     */
    public function actionSpecialists()
    {
        $result = SiteSpecialists::find()->orderBy('name')->all();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Sosial link tipleri
     * https://e-tibb.az/api/enterprise/doctor/list-sosial-link
     */
    public function actionListSosialLink()
    {
        $result = Yii::$app->params['allow.sosial_icons'];
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Nomre tipleri
     * https://e-tibb.az/api/enterprise/doctor/list-phone-number
     */
    public function actionListPhoneNumber()
    {
        $result = Yii::$app->params['allow.number_type'];
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

}
