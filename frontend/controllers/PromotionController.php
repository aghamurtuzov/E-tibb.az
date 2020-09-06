<?php
namespace frontend\controllers;

use frontend\models\EnterpriseModel;
use frontend\models\MainModel;
use frontend\models\PromotionModel;
use frontend\models\SiteAdressesModel;
use frontend\models\SiteDoctorsModel;
use Yii;
use yii\helpers\Url;
use yii\data\Pagination;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use frontend\components\Menu;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\NotFoundHttpException;
use backend\components\Functions;
use frontend\components\SeoLib;
use frontend\controllers\MainController;

/**
 * Site controller
 */
class PromotionController extends MainController
{

    public $menus;
    public $seo;
    public $data = null;
    public $layout = 'promotions';
    public $customPath = 'promotions';
    public $menu_id = 18;

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

        $this->menus = $menus['list'];
        $this->seo   = new SeoLib();
        return $this->menus;

    }

    /**
     * Displays newspage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $keyword = null;
        if(isset($_GET['keyword']) && !empty($_GET['keyword']))
        {
            $keyword = Functions::xss_clean($_GET['keyword']);
        }

        $clinic_id = (isset($_GET['clinic']) ? intval($_GET['clinic']) : null);
        $doc_id = (isset($_GET['doctor']) ? intval($_GET['doctor']) : null);

        $p_object       = new PromotionModel();
        $total_count    = $p_object->getPromotionCount($keyword,$clinic_id,$doc_id,$keyword);
        $pagination     = new Pagination(['totalCount'=>$total_count]);
        $pagination->defaultPageSize = 15;
        $promotion_list = $p_object->getPromotions($keyword,$pagination->limit, $pagination->offset,$clinic_id,$doc_id);
        $promotion     = array();
//        if(!empty($promotion_list)){
            foreach ($promotion_list as $promotions){
                $promotion[$promotions['id']]['headline']  = $promotions['headline'];
                $promotion[$promotions['id']]['connect_id']= $promotions['connect_id'];
                $promotion[$promotions['id']]['type']      = $promotions['type'];
                $promotion[$promotions['id']]['photo']     = $promotions['photo'];
                $promotion[$promotions['id']]['discount']  = $promotions['discount'];
                $promotion[$promotions['id']]['price']     = $promotions['price'];
                $promotion[$promotions['id']]['price2']    = !empty($promotions['discount']) ? $promotions['price']-($promotions['price']*$promotions['discount'])/100 : null;
//                $promotion[$promotions['id']]['connect']   = empty($promotions['organizer'])? $p_object->getConnectName($promotions['type'],$promotions['connect_id'])['name'] : $promotions['organizer'];
//                $promotion[$promotions['id']]['connect_uri_slug']   = $p_object->getConnectName($promotions['type'],$promotions['connect_id'])['kat'];
                $promotion[$promotions['id']]['date']      = !empty($promotions['date']) ? date("d.m.Y", strtotime($promotions['date'])) : null;
                $promotion[$promotions['id']]['start']     = date("d.m.Y", strtotime($promotions['date_start']));
                $promotion[$promotions['id']]['url']       = Yii::$app->params['site.aksiya'].Functions::slugify($promotions['headline'],['transliterate' => true]).'-'.$promotions['id'];
            }

            /** Meta tags */
            Yii::$app->params['header.tags'] = $this->seo->make_page_header($this->getMetaData('Aksiyalar - '.Yii::$app->params['seo']['author'], '/aksiyalar'));
            /** Meta tags END */

            $header_datas =array();
            $header_datas['title']       = 'Aksiyalar';
            $header_datas['description'] = 'Aksiyalar';

            $header_datas['breadcrumb']  = array();
            $header_datas['breadcrumb'][0]['link'] = Url::base('https');
            $header_datas['breadcrumb'][0]['text'] = 'Ana Səhifə';
            $header_datas['breadcrumb'][1]['link'] = Url::base('https').'/aksiyalar';
            $header_datas['breadcrumb'][1]['text'] = 'Aksiyalar';

            Yii::$app->params['breadcrumb.schema'] = $this->seo->make_page_breadcrumb($header_datas['breadcrumb']);
            $enterprises = EnterpriseModel::getCategoryEnterprises(null,1);
            $doctors = SiteDoctorsModel::getAllDoctorsData();
            return $this->render('index',[
                'promotions'=> $promotion,
                'pagination'=> $pagination,
                'page_title'=> 'Aksiyalar',
                'customPath'=> $this->customPath,
                'breadcrumb'=> $this->menus['id'][$this->menu_id]['name'],
                'enterprises'=>$enterprises,
                'doctors' => $doctors,
                'keyword' => $keyword
            ]);
//        }else{
//            return $this->showError();
//        }
    }


    public  function actionSingle()
    {

        $p_object       = new PromotionModel();
        $request        = Yii::$app->request;
        $prom_id        = intval($request->get('id'));
        if(!empty($prom_id)) {
            $promotion    = $p_object->getSinglePromotion($prom_id);
            if(!empty($promotion)){
                $promotion['address'] = SiteAdressesModel::getAddresses($promotion["connect_id"],$promotion['type']);
                $promotion['phones']  = SiteAdressesModel::getMobilePhones($promotion["connect_id"],$promotion['type']);

                $promotion['discount'] = $promotion['discount']==0 ? null : $promotion['discount'];
                $promotion['price2'] = null;
                if (!empty($promotion['discount'])) {
                    $promotion['price2'] = $promotion['price'] - ($promotion['price'] * $promotion['discount']) / 100;
                }

                $promotion['dates']         = date("d.m.Y", strtotime($promotion['date_start'])).' - '.date("d.m.Y", strtotime($promotion['date_end']));
//                $promotion['connect_name']  = empty($promotion['organizer'])? $p_object->getConnectName($promotion['type'],$promotion['connect_id'])['name']:$promotion['organizer'];
//                $promotion['connect_photo'] = $p_object->getConnectName($promotion['type'],$promotion['connect_id'])['photo'];
                $pro_url = Url::base('https').'/'.Yii::$app->params['site.aksiya'].Functions::slugify($promotion['headline'],['transliterate' => true]).'-'.$promotion['id'];
                
                //breadcrumb schema
                $header_datas = [];
                $header_datas['title']       = $promotion['headline'];
                $header_datas['description'] = $promotion['content'];
                $header_datas['breadcrumb']  = [];
                $header_datas['breadcrumb']  = [];

                $header_datas['breadcrumb'][0]['link'] = Url::base('https');
                $header_datas['breadcrumb'][0]['text'] = 'Ana Səhifə';
                $header_datas['breadcrumb'][1]['link'] = Url::base('https').'/aksiyalar';
                $header_datas['breadcrumb'][1]['text'] = 'Aksiyalar';
                $header_datas['breadcrumb'][2]['link'] = Url::base('https').$pro_url;
                $header_datas['breadcrumb'][2]['text'] = $promotion['headline'];

                Yii::$app->params['breadcrumb.schema'] = $this->seo->make_page_breadcrumb($header_datas['breadcrumb']);

                //metadata
                $metaData["title"]         = $promotion['headline'].' - '.Yii::$app->params['seo']['author'];
                $metaData["description"]   = $promotion['content'];
                $metaData["canonical"]     = $pro_url;
                $metaData["article_section"]= 'Aksiyalar';
                $metaData["page_type"]     = "article";
                $metaData["amp_status"]    = 0;
                $metaData["amp_url"]       = $pro_url.'/amp';
                $metaData["published_time"]= strtotime($promotion['date_start']);
                $metaData["modified_time"] = strtotime($promotion['date_start']);
                $metaData["article_image"] = Functions::getUploadUrl().$this->customPath.'/small/'.$promotion['photo'];
                $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];

                Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);
                //article schema
                $promotion['category_datas'] = ['Aksiyalar', Url::base('https').'/aksiyalar'];
                $promotion['title']          = $promotion['headline'];
                $promotion['post_image']     = Functions::getUploadUrl().$this->customPath.'/'.$promotion['photo'];
                $promotion['posted_at']      = date('M d, Y', strtotime($promotion['date_start']));
                $promotion['author']         = Yii::$app->params['seo']['author'];
                $promotion['author_desc']    = Yii::$app->params['seo']['author_desc'];
                $promotion['author_image']   = Url::base('https').Yii::$app->params['seo']['author_img'];
                $promotion['author_url']     = Yii::$app->params['seo']['article_publisher'];
                $promotion['slug']           = $pro_url;
                $promotion['main_url']       = Url::base('https').'/aksiyalar';
                if($p_object->get_next_post($prom_id))
                    $promotion['next_post']      = $p_object->get_next_post($prom_id);
                if($p_object->get_prev_post($prom_id))
                    $promotion['prev_post']      = $p_object->get_prev_post($prom_id);
                    //Yii::$app->params['article.schema'] = $this->seo->make_article_schema(json_decode(json_encode($promotion), true), Url::base('https').'/aksiyalar','');

                return $this->render('single_promotion',[
                    'promotion'   => $promotion,
                    'page_title'  => $promotion['headline'],
                    'customPath'  => $this->customPath,
                    'breadcrumb'  => $this->menus['id'][$this->menu_id]['name'],
                ]);
            }else{
                throw new NotFoundHttpException(Yii::t('app','Axtardığınız aksiya mövcud deyil.'));
            }
        }
        else {
            throw new NotFoundHttpException(Yii::t('app','Axtardığınız aksiya mövcud deyil.'));
        }
    }

}
