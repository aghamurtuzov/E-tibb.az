<?php

namespace api\modules\general\controllers;


use Yii;
use yii\helpers\Url;


/**
 * Consultation API
 */
class RolesController extends MainController
{
    const TYPE = 1;
    public $modelClass = '';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     *
     * https://e-tibb.az/api/general/roles/role
     *
     */

    public function actionRole()
    {


        $superadmin = Yii::$app->authManager->createRole('SuperAdmin');

        $superadmin->description = 'SuperAdmin';

        Yii::$app->authManager->add($superadmin);


        $admin = Yii::$app->authManager->createRole('admin');
        $admin->description = 'Admin';
        Yii::$app->authManager->add($admin);
        
        return 123456;


    }


}
