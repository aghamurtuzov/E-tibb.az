<?php
namespace frontend\controllers;


/**
 * Site controller
 */
class CacheController extends MainController
{
    public function actionDelete()
    {
        \Yii::$app->cache->flush();
    }
}