<?php
/**
 * Created by PhpStorm.
 * User: Taleh F
 * Date: 1/9/2019
 * Time: 11:43 AM
 */
namespace frontend\controllers;

use frontend\models\MainModel;
use frontend\models\NewsModel;
use frontend\models\SitemapModel;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use frontend\components\Menu;
use frontend\components\seo;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\NotFoundHttpException;
use frontend\models\SiteSpecialistsModel;
use backend\components\Functions;
use frontend\components\Specialist;
use frontend\models\SiteDoctorsModel;
use frontend\controllers\MainController;
use yii\helpers\Url;
use frontend\models\PromotionModel;
use frontend\components\SeoLib;

class RssController extends MainController
{
    public $menus;
    public $url_without_ssl;
    public $seo;
    public $spc;
    public $menu_id = 4;

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

    public function beforeAction($action)
    {
        $menus = ArrayHelper::toArray(new Menu());
        $this->menus = $menus['list'];

        $specialists = ArrayHelper::toArray(new Specialist());
        $this->spc = $specialists['specialists'];
        $this->seo = new SeoLib();
        $this->url_without_ssl = Yii::$app->params['seo']['url_without_ssl'];
        return $this->menus;

    }
    /**
     * @return Hekimlerin rss data-si
     */
    public function actionDoctors()
    {
        //test 22222
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');
        $ids        = null;
        $customPath = 'doctors';
        $model      = new SitemapModel();
        //$base_link      = Functions::slugify($spc['name'],['transliterate' => true]).'-'.$spc['id'];
        $default_pages                  = array();
        $default_pages["feed_url"]      = Url::base('https');
        $default_pages["title"]         = 'Həkimlər RSS';
        $default_pages["description"]   = "Həkimlər RSS description here";
        $default_pages["article"]       = array();

        /** Doctor count */
        $count = $model->getCountDoctors();

        /** Check doctor count */
        if(!empty($count))
        {
            if(isset($_GET["id"]) and intval($_GET["id"])>1){
                $page = intval($_GET["id"]);
                $id   = intval($_GET["id"]);
            }else {
                $page = 1;
                $id   = 1;
            }
            //pagination
            $limit        = 40000;
            $all_page     = ceil($count / $limit);
            if($page < 1){ $page = 1; }
            if($page > $all_page){ $page = $all_page; }
            $offset = ($page - 1)*$limit;
            //end pagination
            /** Doctor list */
            $doctors_datas = $model->getRssDoctors($offset,$limit);
            if(!empty($doctors_datas))
            {
                /** Doctor Specialists */
                foreach($doctors_datas as $key => $val){
                    $ids .= $val['id'].',';
                };
                $ids = trim($ids,',');
                if(!empty($ids))
                {
                    $data_specialists = SiteDoctorsModel::getSpecialistList($ids);
                }
                foreach ($doctors_datas as $key=>$value){
                    $spc_list      = null;
                    if(isset($data_specialists[$value['id']]) && !empty($data_specialists[$value['id']]))
                    {
                        $spc_list = $data_specialists[$value['id']];
                        $link = Functions::getDoctorLink($spc_list,$value['id'],$value['name']);
                    }
                    $default_pages["article"][$key]["title"]        = Functions::getCleanText($value['name']);
                    $default_pages["article"][$key]["description"]  = Functions::textLimit($value['about'],100);
                    $default_pages["article"][$key]["link"]         = Url::base('https').'/'.$link;
                    $default_pages["article"][$key]["pub_date"]     = date("r", strtotime($value['published_time']));

                    if(file_exists(Functions::getUploadUrl().$customPath.'/'.$value['photo'])){
                        $photo = Functions::getUploadUrl().$customPath.'/'.$value['photo'];
                        $default_pages["article"][$key]["image"]        = $photo;
                        $default_pages["article"][$key]["image_type"]   = $this->seo->getMIMEType($photo);
                        $default_pages["article"][$key]["image_size"]   = filesize($photo);
                    }else{
                        $default_pages["article"][$key]["image_type"]   = '';
                        $default_pages["article"][$key]["image"]        = '';
                        $default_pages["article"][$key]["image_type"]   = '';
                        $default_pages["article"][$key]["image_size"]   = '';
                    }
                }
            }
        }
        return $this->seo->make_rss_feed_datas($default_pages);
    }

    /**
     * @return Xeberlerin rss data-si
     */
    public function actionNews()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->getHeaders()->set('Content-Type', 'text/xml; charset=utf-8');
        $customPath = 'news';
        $model      = new SitemapModel();
        $default_pages                  = array();
        $default_pages["feed_url"]      = Url::base('https').'/xeberler';
        $default_pages["title"]         = 'Xəbərlər RSS';
        $default_pages["description"]   = "Xəbərlər RSS description here";
        $default_pages["article"]       = array();

        if(isset($_GET["id"]) and intval($_GET["id"])>1){
            $page = intval($_GET["id"]);
            $id   = intval($_GET["id"]);
        }else {
            $page = 1;
            $id   = 1;
        }
        //pagination
            $total_count  = $model->getXeberlerCount();
            $limit        = 40000;
            $all_page     = ceil($total_count / $limit);
            if($page < 1){ $page = 1; }
            if($page > $all_page){ $page = $all_page; }
            $offset = ($page - 1)*$limit;
        //end pagination
            $news_datas = $model->getXeberler($limit,$offset);
            if(!empty($news_datas)){
                //print_r($news_datas); exit();
                foreach ($news_datas as $key=>$value){
                    $link = Yii::$app->params['site.post_uri'].Functions::slugify($value['headline'],['transliterate' => true]).'-'.$value['id'];
                    $default_pages["article"][$key]["title"]        = Functions::getCleanText($value['headline']);
                    $default_pages["article"][$key]["description"]  = Functions::textLimit($value['content'],100);
                    $default_pages["article"][$key]["link"]         = Url::base('https').'/'.$link;
                    $default_pages["article"][$key]["pub_date"]     = date("r", strtotime($value['datetime']));
                    if(file_exists(Functions::getUploadUrl().$customPath.'/small/'.$value['photo'])){
                        $photo = Functions::getUploadUrl().$customPath.'/small/'.$value['photo'];
                        $default_pages["article"][$key]["image"]        = $photo;
                        $default_pages["article"][$key]["image_type"]   = $this->seo->getMIMEType($photo);
                        $default_pages["article"][$key]["image_size"]   = filesize($photo);
                    }else{
                        $default_pages["article"][$key]["image_type"]   = '';
                        $default_pages["article"][$key]["image"]        = '';
                        $default_pages["article"][$key]["image_type"]   = '';
                        $default_pages["article"][$key]["image_size"]   = '';
                    }
                    //$default_pages["article"][$key]["comment_url"]  = "";
                }
            }
        return $this->seo->make_rss_feed_datas($default_pages);
    }

    /**
     * @return Aksiyalarin xml data-si
     */
    public function actionPromotions()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->getHeaders()->set('Content-Type', 'text/xml; charset=utf-8');

        $customPath = 'promotions';
        $model      = new SitemapModel();
        $default_pages                  = array();
        $default_pages["feed_url"]      = Url::base('https').'/aksiyalar';
        $default_pages["title"]         = 'Kampaniyalar RSS';
        $default_pages["description"]   = "Kampaniyalar RSS description here";
        $default_pages["article"]       = array();
        $promotion_datas                = $model->getPromotions();
        if(!empty($promotion_datas)){
            foreach ($promotion_datas as $key=>$value){
                $link = Yii::$app->params['site.aksiya'].Functions::slugify($value['headline'],['transliterate' => true]).'-'.$value['id'];
                $default_pages["article"][$key]["title"]        = Functions::getCleanText($value['headline']);
                $default_pages["article"][$key]["description"]  = Functions::textLimit($value['content'],100);
                $default_pages["article"][$key]["link"]         = Url::base('https').'/'.$link;
                $default_pages["article"][$key]["pub_date"]     = date("r", strtotime($value['date_start'])).' - '.date("r", strtotime($value['date_end']));
                if(file_exists(Functions::getUploadUrl().$customPath.'/small/'.$value['photo'])){
                    $photo = Functions::getUploadUrl().$customPath.'/'.$value['photo'];
                    $default_pages["article"][$key]["image"]        = $photo;
                    $default_pages["article"][$key]["image_type"]   = $this->seo->getMIMEType($photo);
                    $default_pages["article"][$key]["image_size"]   = filesize($photo);
                }else{
                    $default_pages["article"][$key]["image_type"]   = '';
                    $default_pages["article"][$key]["image"]        = '';
                    $default_pages["article"][$key]["image_type"]   = '';
                    $default_pages["article"][$key]["image_size"]   = '';
                }
            }
        }
        return $this->seo->make_rss_feed_datas($default_pages);
    }

    /**
     * @return Obyektlerin xml data-si
     */
    public function actionGuide()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');
        $customPath = 'enterprises';
        $category   = null;
        $customPath = 'doctors';
        $model = new SitemapModel();
        $menus = $this->menus;
        $default_pages                  = array();
        $default_pages["feed_url"]      = Url::base('https');
        $default_pages["title"]         = 'Beledci RSS';
        $default_pages["description"]   = "Beledci RSS description here";
        $default_pages["article"]       = array();
        $count = $model->getCountEnterprises();
        if(!empty($count)){
            if(isset($_GET["id"]) and intval($_GET["id"])>1){
                $page = intval($_GET["id"]);
                $id   = intval($_GET["id"]);
            }else {
                $page = 1;
                $id   = 1;
            }
            //pagination
            $limit        = 40000;
            $all_page     = ceil($count / $limit);
            if($page < 1){ $page = 1; }
            if($page > $all_page){ $page = $all_page; }
            $offset = ($page - 1)*$limit;
        }
        $enterprise_datas = $model->getRssEnterprises($offset,$limit);
        //print_r($enterprise_datas); exit();
        if(!empty($enterprise_datas)){
            foreach ($enterprise_datas as $key=>$value){
                $link = Url::to([Functions::slugify($category['link'],['transliterate'=>true]).'/'.$value["id"].'-'.Functions::slugify($value["name"],['transliterate'=>true])]);
                $default_pages["article"][$key]["title"]        = Functions::getCleanText($value['name']);
                $default_pages["article"][$key]["description"]  = Functions::textLimit($value['about'],100);
                $default_pages["article"][$key]["link"]         = Url::base('https').'/'.$link;
                $default_pages["article"][$key]["pub_date"]     = date("r", strtotime($value['published_time']));
                if(file_exists(Functions::getUploadUrl().$customPath.'/small/'.$value['photo'])){
                    $photo = Functions::getUploadUrl().$customPath.'/small/'.$value['photo'];
                    $default_pages["article"][$key]["image"]        = $photo;
                    $default_pages["article"][$key]["image_type"]   = $this->seo->getMIMEType($photo);
                    $default_pages["article"][$key]["image_size"]   = filesize($photo);
                }else{
                    $default_pages["article"][$key]["image_type"]   = '';
                    $default_pages["article"][$key]["image"]        = '';
                    $default_pages["article"][$key]["image_type"]   = '';
                    $default_pages["article"][$key]["image_size"]   = '';
                }
            }
        }
        return $this->seo->make_rss_feed_datas($default_pages);
    }




}