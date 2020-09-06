<?php

namespace frontend\controllers;

use frontend\models\MainModel;
use frontend\models\NewsModel;
use frontend\models\SiteMapModel;
use Yii;


class SiteMapController extends MainController
{
    public function actions()
    {
        echo 'test';

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');
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
//    public function actionIndex()
//    {

//        $session = Yii::$app->session;
//
//        if(!$session->has('sitemap_index_pages'))
//        {
//            $session->set('sitemap_index_pages',time()-1200);
//        }
//
//        $map[] = ['loc'=>'pages','last_mod'=>$session->get('sitemap_index_pages')];
//
//        $allMenu = MainModel::getMenus();
//
//        if(!empty($allMenu))
//        {
//            foreach($allMenu as $key => $val)
//            {
//
////                return $val;
//
//                if($val['type'] == MainModel::MENU_TYPE_STATIC){
//                    $link = 'pages-'.$val['link'];
//                    $t = 3500;
//                }elseif($val['type'] == MainModel::MENU_TYPE_NEWS){
//                    $link = $val['link'];
//                    $t = 2500;
//                }else{
//                    continue;
//                }
//
//                if(!$session->has('sitemap_index_menu'.$val['id']))
//                {
//                    $session->set('sitemap_index_menu'.$val['id'],time()-$t+$val['id']);
//                }
//
//                $map[$key+1]['loc']      = $link;
//                $map[$key+1]['last_mod'] = $session->get('sitemap_index_menu'.$val['id']);
//            }
//        }

//        return Yii::$app->seo->makeSitemap($map);

//    }


}