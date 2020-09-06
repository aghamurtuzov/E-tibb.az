<?php

namespace frontend\controllers;

use Yii;
use frontend\models\MainModel;
use backend\components\Functions;
use frontend\controllers\MilliCardController;

class PanelController extends MainController
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionTest()
    {
        return $this->renderPartial('index');
    }
}
