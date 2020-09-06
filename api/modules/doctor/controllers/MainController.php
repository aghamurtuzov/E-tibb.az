<?php

namespace api\modules\doctor\controllers;

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

//        $behaviors['verbs'] = [
//            'class' => VerbFilter::className(),
//            'actions' => [
//                'list-status' => ['POST'],
//                'list-status' => ['POST'],
//            ],
//        ];

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
        $id = Yii::$app->session->get('userID');
//        if((isset(Yii::$app->user->identity->type) && Yii::$app->user->identity->type != 1) || empty($id))
//        {
//            exit();
//        }
    }

}