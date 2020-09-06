<?php

namespace api\modules\general\controllers;

use yii;
use yii\web\Response;
use api\controllers\ApiController;
use yii\filters\AccessControl;
use backend\models\AdminUsers;

/**
 * Main API
 */

class MainController extends ApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

//        $behaviors['authenticator'] = [
//            'class' => CompositeAuth::className(),
//            'authMethods' => [
//                //HttpBasicAuth::className(),
//                HttpBearerAuth::className(),
//                //QueryParamAuth::className(),
//            ],
//        ];
//
//        $behaviors['access'] = [
//            'class' => AccessControl::className(),
//            'only' => ['*'],
//            'rules' => [
//                [
//                    'allow' => true,
//                    'roles' => ['@'],
//                ],
//            ],
//        ];
//
//        $behaviors['verbs'] = [
//            'class' => VerbFilter::className(),
//            'actions' => [
//                'list-status' => ['POST'],
//                'list-status' => ['POST'],
//            ],
//        ];
//
//        $behaviors['access'] = [    //This fails
//            'class' => AccessControl::className(),
//            'only' => ['can-access'],
//            'rules' => [
//                [
//                    'actions' => ['can-access'],
//                    'allow' => true,
//                    'roles' => ['@'],
//                ],
//            ],
//        ];

        $behaviors['contentNegotiator'] = [
            'class' => 'yii\filters\ContentNegotiator',
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;

    }

    public function actions()
    {
//        $key  = Yii::$app->session->get('gakey');
//        if(!empty($key))
//        {
//            $user = AdminUsers::find()->where(new \yii\db\Expression('FIND_IN_SET(:key, login_key)'))->addParams([':key' => $key])->one();
//            if(!empty($user)){ return true; }
//        }
//        exit();
    }

}