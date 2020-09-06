<?php
namespace frontend\controllers;

use app\models\SiteComments;
use backend\models\SiteEnterprises;
use frontend\components\Specialist;
use frontend\models\EnterpriseModel;
use frontend\models\MainModel;
use frontend\models\PromotionModel;
use frontend\models\SiteAdressesModel;
use frontend\models\SiteCommentsModel;
use frontend\models\SiteDoctorsModel;
use frontend\models\SiteGalleryModel;
use frontend\models\SitePhoneNumbersModel;
use frontend\models\SiteSocialLinksModel;
use frontend\models\SiteSpecialistsModel;
use yii\helpers\Url;
use Yii;
use yii\base\InvalidParamException;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\components\Menu;
use backend\components\Functions;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use frontend\components\SeoLib;

/**
 * Site controller
 */
class EnterpriseController extends MainController
{

    public $spc;
    public $menus;
    public $seo;
    public $data = null;
    public $layout = 'enterprise';
    public $customPath = 'enterprises';

    /**
     * {@inheritdoc}
     */
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

        /*
        $request  = Yii::$app->request;

        if($request->post('searchForm') !== null)
        {
            $model = new SearchForm();
            if($model->load($request->post()) && $model->validate())
            {
                $q = Html::encode($model->q);
                return $this->redirect(Yii::$app->urlManager->createUrl(['/axtar','q' => $q]));
            }
        }
        */

        $menus = ArrayHelper::toArray(new Menu());

        $specialists = ArrayHelper::toArray(new Specialist());

        $this->menus = $menus['list'];

        $this->spc = $specialists['specialists'];

        $this->seo = new SeoLib();

        return $this->menus;

    }

    public function actionGetDoctors()
    {
        $request = Yii::$app->request;

        $get = $request->get();

        if ($request->isAjax) {

            if (intval($get['page'])) {
                $doctors = SiteDoctorsModel::getHospitalDoctors($get["row_id"],$get["page"]);
                return $this->renderAjax('ajax/doctors', ['doctors' => $doctors]);
            } else {
                throw new \yii\web\BadRequestHttpException;
            }
        } else {

            throw new \yii\web\BadRequestHttpException;
        }
    }

    public function actionIndex($id)
    {
        $keyword = null;
        if(isset($_GET['keyword']) && !empty($_GET['keyword']))
        {
            $keyword = Functions::xss_clean($_GET['keyword']);
        }

        $menus = $this->menus;

        if(isset($menus["id"][$id])){
            $category = $menus["id"][$id];
        }else{
            throw new NotFoundHttpException(Yii::t('app','Axtardığınız səhifə mövcud deyil.'));
        }
        $page_title = $category['name'];
        $page_slug  = $category['link'];
        $settings = $this->defaultSettings();

        if($category["settings"]!=""){
            $settings = json_decode($category["settings"],true);
        }

        if($settings["template"]==0){
            $view_page = 'index';
        }else{
            $view_page = 'index1';
        }

        $pages = new Pagination();
        $page  = 0;
        if(isset($_GET["page"]) and intval($_GET["page"])>1){
            $page = $_GET["page"];
        }

        //        premium
        if (intval($page)>1){
            $page_p = intval($page);
        }else {
            $page_p = 1;
        }
        $limit        = 6;
        $all_page     = ceil( EnterpriseModel::getPremiumEnterprisesCount($id) / $limit);
        if($page_p < 1){ $page_p = 1; }
        if($page_p > $all_page){ $page_p = $all_page; }
        $offset = ($page_p - 1)*$limit;
        $premium_enterprises       = EnterpriseModel::getPremiumEnterprises($id,$offset,$limit);
        //        premium

        $cache = Yii::$app->cache;

        $enterprises = $cache->get("category_enterprises_".$id."_".$page."_".$keyword);

        if($enterprises == false)
        {
            $limit = 21;
            $pages->totalCount         = EnterpriseModel::getcategoryEnterprisesCount($keyword,$id);
            $enterprises               = EnterpriseModel::getCategoryEnterprises($keyword,$id,$pages->offset,$limit);
//            echo '<pre>';var_dump($premium_enterprises);die;

//            $metaData["title"]         = $this->menus['id'][$id]['name'];
//            $metaData["description"]   = $this->menus['id'][$id]['name'];
//            $metaData["canonical"]     = Url::base(true).'/beledci/'.$this->menus['id'][$id]['link'].'-'.$id;
//            $metaData["page_type"]     = "website";
//            $metaData["amp_status"]    = 0;
//            $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];

//            Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);

            $cache->set("category_enterprises_".$id."_".$page."_".$keyword,$enterprises,60);
            $cache->set("category_enterprises_pages_".$id."_".$page,$pages,60);
        }
        else
        {
//            $metaData["title"]         = $this->menus['id'][$id]['name'];
//            $metaData["description"]   = $this->menus['id'][$id]['name'];
//            $metaData["canonical"]     = Url::base('https').'/beledci/'.$this->menus['id'][$id]['link'].'-'.$id;
//            $metaData["page_type"]     = "website";
//            $metaData["amp_status"]    = 0;
//            $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];
//            Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);
            $pages = $cache->get("category_enterprises_pages_".$id."_".$page);
        }

        /** Meta tags */
        $metaData["title"]         = $this->menus['id'][$id]['name'].' - '.Yii::$app->params['seo']['author'];
        $metaData["canonical"]     = Url::base('https').'/beledci/'.$this->menus['id'][$id]['link'].'-'.$id;
        $metaData["page_type"]     = "website";
        $metaData["amp_status"]    = 0;
        $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];
        Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);
        /** Meta tags END */

        //breadcrumb schema
            $header_datas =array();
            $header_datas['title']       = $this->menus['id'][$id]['name'];
            $header_datas['description'] = $this->menus['id'][$id]['content'];

            $header_datas['breadcrumb']  = array();
            $header_datas['breadcrumb'][0]['link'] = Url::base('https');
            $header_datas['breadcrumb'][0]['text'] = 'Ana Səhifə';
            $header_datas['breadcrumb'][1]['link'] = Url::base('https').'/beledci/'.$this->menus['id'][$id]['link'].'-'.$id;
            $header_datas['breadcrumb'][1]['text'] = $this->menus['id'][$id]['name'];

//            Yii::$app->params['breadcrumb.schema'] = $this->seo->make_page_breadcrumb($header_datas['breadcrumb']);
        //
        $categoryResult         = EnterpriseModel::getCategory($id);

        return $this->render($view_page,[
            'enterprises' => $enterprises,
            'premium'   => $premium_enterprises,
            'enterprise_category_id' => $id,
            'pages' => $pages,
            'page_title' => $page_title,
            'page_slug' => $page_slug,
            'cat_id' => $id,
            'category' => $categoryResult,
            'keyword' => $keyword
        ]);
    }

    public function actionPremiumEnterprise($id)
    {
        $menus = $this->menus;

        if(isset($menus["id"][$id])){
            $category = $menus["id"][$id];
        }else{
            throw new NotFoundHttpException(Yii::t('app','Axtardığınız səhifə mövcud deyil.'));
        }
        $page_title = $category['name'];
        $page_slug  = $category['link'];

        $page  = Yii::$app->request->get('page',0);


        $pages = new Pagination();
        $pages->defaultPageSize = 6;

        $cache = Yii::$app->cache;

        $metaData["title"]         = $this->menus['id'][$id]['name'];
        $metaData["description"]   = $this->menus['id'][$id]['name'];
        $metaData["canonical"]     = Url::base('https').'/beledci/'.$this->menus['id'][$id]['link'].'-'.$id;
        $metaData["page_type"]     = "website";
        $metaData["amp_status"]    = 0;
        $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];

        $enterprisesCheck = $cache->get("category_premium_enterprises_".$id."_".$page);

        if($enterprisesCheck == false)
        {
            $pages->totalCount         = EnterpriseModel::getPremiumEnterprisesCount($id);
            $enterprises               = EnterpriseModel::getPremiumEnterprises($id,$pages->offset,$pages->limit);

            $cache->set("category_premium_enterprises_".$id."_".$page,$enterprises,60);
            $cache->set("category_premium_enterprises_pages_".$id."_".$page,$pages,60);
        }
        else
        {

            $pages = $cache->get("category_premium_enterprises_pages_".$id."_".$page);
            $enterprises = $cache->get("category_premium_enterprises_".$id."_".$page);
        }

        $categoryResult         = EnterpriseModel::getCategory($id);

        //breadcrumb schema
        $header_datas =array();
        $header_datas['title']       = $this->menus['id'][$id]['name'];
        $header_datas['description'] = $this->menus['id'][$id]['content'];

        $header_datas['breadcrumb']  = array();
        $header_datas['breadcrumb'][0]['link'] = Url::base('https');
        $header_datas['breadcrumb'][0]['text'] = 'Ana Səhifə';
        $header_datas['breadcrumb'][1]['link'] = Url::base('https').'/beledci/'.$this->menus['id'][$id]['link'].'-'.$id;
        $header_datas['breadcrumb'][1]['text'] = $this->menus['id'][$id]['name'];

        return $this->render('premium',[
            'enterprises' => $enterprises,
            'enterprise_category_id' => $id,
            'pages' => $pages,
            'page_title' => $page_title,
            'page_slug' => $page_slug,
            'cat_id' => $id,
            'category' => $categoryResult
        ]);
    }

    /**
     *axtaris oobyekt
     */
    public  function actionSearch()
    {

        $model      =   new EnterpriseModel();
        $request    =   Yii::$app->request;
        if($request->isGet)
        {
            $search_data  = $request->get('axtar');
            $search_id    = $request->get('kateqoriya');
            $check_value  = isset($_GET['vaxt']) ? 1 : 0;
            $search_where['like'] = $search_data;
            $search_where['id']   = $search_id;
            $search_where['check']= $check_value;
            $menus = $this->menus;
            if(!empty($search_id)){
                $category = $menus["id"][$search_id];
            }else{
                throw new NotFoundHttpException(Yii::t('app','Axtardığınız səhifə mövcud deyil.'));
            }
            $page_title = $category['name'];
            $settings   = $this->defaultSettings();

            if($category["settings"]!=""){
                $settings = json_decode($category["settings"],true);
            }
            if($settings["template"]==0){
                $view_page = 'index';
            }
            else
            {
                $view_page = 'index1';
            }
            if(!empty($search_where))
            {
                $search_count = $model->findSearchEnterpriseCount($search_where);
                if($search_count>0)
                {
                    $pagination  = new Pagination(['totalCount'=>$search_count]);
                    $pagination->defaultPageSize = 15;
                    $search_datas = $model->find_SearchEnterprises($search_where,$pagination->limit,$pagination->offset);
                    if(!empty($search_datas))
                    {
//                        $metaData["title"]         = $page_title;
//                        $metaData["description"]   = $page_title;
//                        $metaData["canonical"]     = "http://e-tibb.az/";
//                        $metaData["page_type"]     = "article";
//                        $metaData["amp_status"]    = 1;
//                        $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];
//
//                        Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);

                        //print_r($search_datas); die();
                        return $this->render($view_page,[
                            'enterprises'=> $search_datas,
                            'pages'     =>  $pagination,
                            'customPath'=>  $this->customPath,
                            'page_title'=>  $page_title,
                        ]);
                    }
                    else
                    {
                        $msg = 'Axtardığınız məlumat tapılmadı';
                        $type= 'error';
                        return $this->showError($msg,$type,$this->layout);
                    }
                }
                else
                {
                    $msg = 'Axtardığınız məlumat tapılmadı';
                    $type= 'error';
                    return $this->showError($msg,$type,$this->layout);
                }
            }
            else
            {
                $msg = 'Formu Doldurun';
                $type= 'error';
                return $this->showError($msg,$type,$this->layout);
            }
        }
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


    public function actionAbout($id,$doc='haqqinda')
    {
        $model = $this->findModel($id);
        $e_object = new EnterpriseModel();
        $pageData = $this->pageData($model["category_id"],$model["name"],$id);

        $page_title = $pageData["page_title"];
        $settings = $pageData["settings"];
        $breadcrumbs = $pageData["breadcrumbs"];
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

        $singleUrl                 = Url::base('https').'/'.$category['link'].'/'.$model["id"].'-'.Functions::slugify($model["name"],['transliterate'=>true]);
        $metaData["title"]         = $page_title.' - '.Yii::$app->params['seo']['author'];
        $metaData["description"]   = $model['about'];
        $metaData["canonical"]     = $singleUrl;
        $metaData["page_type"]     = "article";
        $metaData["amp_status"]    = 0;
        $metaData["amp_url"]       = $singleUrl.'/amp';
        $metaData["published_time"]= strtotime($model['published_time']);
        $metaData["modified_time"] = strtotime($model['modified_time']);
        $metaData["article_image"] = Url::base('https').'/upload/enterprises/'.$model['photo'];
        $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];

        Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);

        //breadcrumb schema

            $header_datas =array();
            $header_datas['title']       = $page_title;
            $header_datas['description'] = $model['about'];
            $header_datas['breadcrumb']  = array();

            $header_datas['breadcrumb'][0]['link'] = Url::base('https');
            $header_datas['breadcrumb'][0]['text'] = 'Ana Səhifə';
            $header_datas['breadcrumb'][1]['link'] = Url::base('https').'/beledci/'.$category['link'].'-'.$model["category_id"];
            $header_datas['breadcrumb'][1]['text'] = $category['name'];
            $header_datas['breadcrumb'][2]['link'] = $singleUrl;
            $header_datas['breadcrumb'][2]['text'] = $page_title;

            Yii::$app->params['breadcrumb.schema'] = $this->seo->make_page_breadcrumb($header_datas['breadcrumb']);
        //

        //article schema
            $about['category_datas'] = [$category['name'],Url::base('https').'/beledci/'.$category['link'].'-'.$model["category_id"]];
            $about['title']          = $page_title;
            $about['description']    = $model['about'];
            $about['post_image']     = Url::base('https').'/upload/enterprises/'.$model['photo'];
            $about['posted_at']      = date('M d, Y', strtotime($model['published_time']));
            $about['created_at']     = $model['published_time'];
            $about['updated_at']     = $model['modified_time'];
            $about['author']         = Yii::$app->params['seo']['author'];
            $about['author_desc']    = Yii::$app->params['seo']['author_desc'];
            $about['author_image']   = Url::base('https/').Yii::$app->params['seo']['author_img'];
            $about['author_url']     = Yii::$app->params['seo']['article_publisher'];
            $about['slug']           = $category['link'].'/'.$model["id"].'-'.Functions::slugify($model["name"],['transliterate'=>true]);
            $about['main_url']       = Url::base('https').'/beledci/'.$category['link'].'-'.$model["category_id"];
            $about['rating_value']   = '4.6';//$model['rating'];
            $about['review_count']   = $model['review_count'];
            if($e_object->get_next_post($id))
                $about['next_post']      = $e_object->get_next_post($id);
            if($e_object->get_prev_post($id))
                $about['prev_post']      = $e_object->get_prev_post($id);
            Yii::$app->params['article.schema'] = $this->seo->make_article_schema(json_decode(json_encode($about), true), Url::base('https').'/beledci/'.$category['link'].'-'.$model["category_id"],'/amp');
        //


        $comment = new SiteComments();
        $comment->connect_id = $id;
        $comment->type = 2;

        //$data['comment'] = $comment;

        $href = Yii::$app->params["site.url"].Functions::slugify($category["name"],['transliterate'=>true]).'/'.$model["id"].'-'.Functions::slugify($model["name"],['transliterate'=>true])."/".$page_type_link;

        $promotions = [];
        if(intval($model["promotion"])>0){
            $promotions  = PromotionModel::getPromotionWithConnectId($id,2);
        }

        if($category['id']==6)
            $view = 'view2';
        else
            $view = 'view';

        $specialists = $this->spc['id'];
        
        return $this->render($view,[
            'model' => $model,
            'page_title' => $page_title,
            'breadcrumbs' => $breadcrumbs,
            'customPath' => $this->customPath,
            'category' => $category,
            'pages' => $pages,
            'page_type' => $page_type,
            'page_type_title' => $page_type_title,
            'settings' => $settings,
            'comment' => $comment,
            'promotions' => $promotions,
            'href' => $href,
            'specialists' => $specialists
        ]);
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

    protected function findModel($id)
    {
        if (($model = EnterpriseModel::getEnterprise($id)) != false) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app','Axtardığınız səhifə mövcud deyil.'));
        }
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
}
