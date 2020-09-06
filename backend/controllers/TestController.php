<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\Functions;
use backend\controllers\MainController;

/**
 * TestController implements the CRUD actions
 */
class TestController extends MainController
{
//    const TYPE = 3;
//    public $customPath = 'news';
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['*'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
 * Lists all SiteNews models.
 * @return mixed
 */
    public function actionIndex()
    {
        return $this->render('index');
    }


}
