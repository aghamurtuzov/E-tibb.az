<?php
namespace frontend\controllers;

/**
 * Site controller
 */
class VideoController extends MainController
{

    public $layout = false;
    /**
     * {@inheritdoc}
     */
    public function actions()
    {

    }

    public function actionDoctor()
    {
        return $this->render('doctor');
    }

    public function actionClient()
    {
        return $this->render('doctor');
    }

}