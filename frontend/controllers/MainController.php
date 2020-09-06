<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;

/**
* Ajax controller
*/
class MainController extends Controller
{

    public function showError($msg = null,$type = null, $layout= 'main')
    {
        $this->layout = $layout;

        $data['type'] = !empty($type) ? $type : 'error';
        $data['msg']  = !empty($msg) ? $msg : 'Axtardığınız məlumat tapılmadı';

        return $this->render('/page/error',['data'=>$data]);
    }

    public function actionSuccess()
    {
        return $this->render('/page/success');

    }

    protected function getMetaData($title, $url = "") {
        $metaData = [];
        $metaData["title"]         = $title;
        $metaData["canonical"]     = Url::base('https').$url;
        $metaData["page_type"]     = "website";
        $metaData["amp_status"]    = 0;
        $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];
        return $metaData;
    }


}