<?php

namespace api\modules\doctor\controllers;

use yii;
use api\modules\doctor\models\StaticApiModel;
use api\modules\doctor\controllers\MainController;

/**
 * User API
 */

class StaticController extends MainController
{

    public $modelClass = '';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Static
     * https://e-tibb.az/api/doctor/static/info?name=Haqqımızda
     */
    public function actionInfo()
    {

        $name = Yii::$app->request->get('name');
        if(empty($name))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $name = strip_tags($name);

        $result = StaticApiModel::info($name);
        if(empty($result))
        {
            return $this->response(400,"Məlumat mövcud deyil");
        }

        return $this->response(200,"Məlumat mövcuddur",$result);
    }

}