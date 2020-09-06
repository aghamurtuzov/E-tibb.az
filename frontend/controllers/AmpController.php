<?php

namespace frontend\controllers;

use Yii;
use frontend\controllers\MainController;
use yii\helpers\ArrayHelper;
use frontend\components\Menu;
use frontend\components\Specialist;
use frontend\components\SeoLib;
use yii\helpers\Url;
use frontend\models\NewsModel;
use yii\web\NotFoundHttpException;
use frontend\models\SiteDoctorsModel;
use frontend\models\PromotionModel;
use backend\components\Functions;
use frontend\models\EnterpriseModel;
use app\models\SiteComments;

class AmpController extends MainController
{
    public $menus;
    public $seo;
    public $spc;
    public $layout = 'amp';
    public $doctorMenuId = 4;
    public $customPathDoctor = 'doctors';
    public $customPathEnterprise = 'enterprises';

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

        return parent::beforeAction($action);
    }

    public function actionSingleNews($slug,$id)
    {
        if(!empty($id))
        {
            $newsModel = new NewsModel();
            $newsInfo  = $newsModel->getSingleNews($id);
            if(!empty($newsInfo))
            {
                $data['news']              = $newsInfo;
                $data['news']['category']  = isset($this->menus['id'][$data['news']['category_id']]['name']) ? $this->menus['id'][$data['news']['category_id']]['name'] : null;
                $data['news']['url']       = Url::base(true).'/'.Yii::$app->params['site.post_uri'].Functions::slugify($data['news']['headline']).'-'.$id;

                $metaData["title"]         = $newsInfo['headline'];
                $metaData["description"]   = $newsInfo['content'];
                $metaData["canonical"]     = $data['news']['url'];
                $metaData["page_type"]     = "article";
                $metaData["amp_status"]    = 0;
                $metaData["published_time"]= strtotime($newsInfo['datetime']);
                $metaData["modified_time"] = strtotime($newsInfo['datetime']);
                $metaData["article_image"] = Url::base(true).'/upload/news/'.$newsInfo['photo'];
                $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];

                Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);

                return $this->render('single_news',['data' => $data]);
            }else{
                throw new NotFoundHttpException(Yii::t('app','Axtardığınız səhifə mövcud deyil.'));
            }
        }else{
            throw new NotFoundHttpException(Yii::t('app','Axtardığınız səhifə mövcud deyil.'));
        }
    }

    public function actionSingleDoctor($id,$currentTab = 'sual-ver')
    {

        $tabs = [];

        /** Global settings */
        if(isset($this->menus['id'][$this->doctorMenuId]))
        {
            $settings = json_decode($this->menus['id'][$this->doctorMenuId]['settings'],true);
        }else{ throw new NotFoundHttpException(Yii::t('app','Xəta! Məlumatın göstərilməsi zamanı xəta baş verdi')); }

        /** Doctor */
        $data['doctor'] = SiteDoctorsModel::getDoctorInfo($id);

        if(!empty($data['doctor']))
        {

            /** Specialists */
            $data['specialist'] = SiteDoctorsModel::getDoctorSpecialist($id);

            if(!empty($data['specialist']))
            {
                if(!empty($data['specialist'][0]['settings']))
                {
                    $settings = json_decode($data['specialist'][0]['settings'],true);
                }
            }

            /** Make tabs */
            if(!empty($settings) && isset($settings['pages']))
            {
                foreach($settings['pages'] as $key => $val)
                {
                    $tabs[$val['link']] = $val;
                }
            }

            /** Promotion */
            if(!empty($data['doctor']['promotion']))
            {
                $data['promotions'] = PromotionModel::getPromotionWithConnectId($id,1);
            }

            $data['customPath'] = $this->customPathDoctor;
            $data['doctor']['photo_url'] = !empty($data['doctor']['photo']) ? Functions::getUploadUrl().Yii::$app->params['path.doctor'].'/'.$data['doctor']['photo'] : Yii::$app->params['site.defaultThumbDoctor'];
            $data['doctor']['url'] = Url::base(true).'/'.Functions::getDoctorLink($data['specialist'],$id,$data['doctor']['name']);

            $metaData["title"]         = $data['doctor']['name'];
            $metaData["description"]   = $data['doctor']['name'];
            $metaData["canonical"]     = $data['doctor']['url'];
            $metaData["page_type"]     = "article";
            $metaData["amp_status"]    = 0;
            $metaData["published_time"]= strtotime($data['doctor']["published_time"]);
            $metaData["modified_time"] = strtotime($data['doctor']["modified_time"]);
            $metaData["article_image"] = $data['doctor']['photo_url'];
            $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];

            Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);

            return $this->render('single_doctor',[
                'data' => $data,
                'tabPages' => $settings['pages']
            ]);

        }else{ return $this->showError(); };

    }

    public function actionSingleEnterprise($id,$doc='haqqinda')
    {

        $model = EnterpriseModel::getEnterprise($id);
        $pageData = $this->pageData($model["category_id"],$model["name"],$id);

        $page_title = $pageData["page_title"];
        $settings = $pageData["settings"];
        $category = $pageData["category"];
        $pages = $pageData["pages"];

        // Page structure
        if(isset($pages[$doc])){
            $page_type = $pages[$doc]["type"];
            $page_type_title = $pages[$doc]["name"];
            $page_type_link = $pages[$doc]["link"];
        }else{
            $page_type = $settings["pages"][0]["page_type"];
            $page_type_link = $settings["pages"][0]["page_link"];
            $page_type_title = $settings["pages"][0]["name"];
        }

        if(isset($settings["pages"]) and count($settings["pages"])<=1){
            if(count($settings["pages"])<1){
                // Page structure
                $page_type = 'about';
                $page_type_title = 'Haqqında';
                $page_type_link = 'haqqinda';
            }else{
                $page_type = $settings["pages"][0]["page_type"];
                $page_type_link = $settings["pages"][0]["page_link"];
                $page_type_title = $settings["pages"][0]["name"];
            }
        }

        if(intval($model["promotion"])>0){
            $data['promotions'] = PromotionModel::getPromotionWithConnectId($id,2);
        }

        $data['enterprise'] = $model;

        $data['customPath'] = $this->customPathEnterprise;
        $data['enterprise']['photo_url'] = !empty($data['enterprise']['photo']) ? Functions::getUploadUrl().Yii::$app->params['path.enterprises'].'/'.$data['enterprise']['photo'] : Yii::$app->params['site.defaultThumb'];
        $data['enterprise']['url'] = Yii::$app->params["site.url"].Functions::slugify($category["name"]).'/'.$model["id"].'-'.Functions::slugify($model["name"]);
        $data['enterprise']['cat_name'] = $category['name'];

        $metaData["title"]         = $data['enterprise']['name'];
        $metaData["description"]   = $data['enterprise']['name'];
        $metaData["canonical"]     = $data['enterprise']['url'];
        $metaData["page_type"]     = "article";
        $metaData["amp_status"]    = 0;
        $metaData["published_time"]= strtotime($data['enterprise']["published_time"]);
        $metaData["modified_time"] = strtotime($data['enterprise']["modified_time"]);
        $metaData["article_image"] = $data['enterprise']['photo_url'];
        $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];

        Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);

        return $this->render('single_enterprise',[
            'data' => $data,
            'tabPages' => $pages
        ]);

    }

    protected function pageData($category_id,$name,$id=0)
    {
        $category = [];
        $category_name = ' - ';


        $menus = $this->menus;

        $page_title = $name;

        $settings = $this->defaultSettings();
        if(isset($menus["id"][$category_id])){
            $category = $menus["id"][$category_id];
            $category_name = $category['name'];
            if($category["settings"]!=""){
                $settings = json_decode($category["settings"],true);
            }
        }

        $pages = [];
        foreach ($settings["pages"] as $p){
            if(isset($p["page_link"])){
                $pages[$p["page_link"]]["name"] = $p["name"];
                $pages[$p["page_link"]]["type"] = $p["page_type"];
                $pages[$p["page_link"]]["link"] = $p["page_link"];
            }
        }

        $breadcrumbs = [
            0 => ["url" => Url::home(),"name" => "Ana səhifə"],
            1 => ["url" => "beledci/".Functions::slugify($category_name,['transliterate'=>true])."-".$category_id,"name" => $category_name],
        ];

        $data["category"] = $category;
        $data["breadcrumbs"] = $breadcrumbs;
        $data["settings"] = $settings;
        $data["page_title"] = $page_title;
        $data["pages"] = $pages;

        return $data;

    }

    protected function defaultSettings()
    {
        $settings = [
            "template" => 0,
            "pages" => [
                [
                    "name" => "Haqqında",
                    "page_type" => "about",
                    "page_link" => "haqqinda"
                ],
                [
                    "name" => "Həkimlər",
                    "page_type" => "doctors",
                    "page_link" => "hekimler"
                ],[
                    "name" => "Xidmətlər",
                    "page_type" => "services",
                    "page_link" => "xidmetler"
                ],
                [
                    "name" => "Rəylər",
                    "page_type" => "comments",
                    "page_link" => "reyler"
                ],
                [
                    "name" => "Əlaqə",
                    "page_type" => "contact",
                    "page_link" => "elaqe"
                ]
            ]
        ];

        return $settings;
    }

}
