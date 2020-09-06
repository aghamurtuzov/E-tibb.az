<?php
namespace frontend\controllers;

use frontend\models\MainModel;
use frontend\models\NewsModel;
use frontend\models\SearchModel;
use frontend\models\EnterpriseModel;
use frontend\models\PromotionModel;
use frontend\models\SiteAdressesModel;
use Yii;
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
use frontend\models\SiteSpecialistsModel;
use frontend\models\SiteDoctorsModel;
use frontend\components\Specialist;
use frontend\components\SeoLib;

/**
 * Site controller
 */
class SearchController extends MainController
{

    public $menus;
    public $spc;
    public $data = null;
    public $layout = 'search';
    public $customPath = 'search';
    public $seo;


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
        $menus = ArrayHelper::toArray(new Menu());

        $specialists = ArrayHelper::toArray(new Specialist());

        $this->menus = $menus['list'];

        $this->spc = $specialists['specialists'];
        $this->seo = new SeoLib();

        return $this->menus;
    }

    /**
     * Displays newssearch.
     *
     * @return mixed
     */

    public function actionIndex()
    {
        $ids            = null;
        $request        = Yii::$app->request;
        $page           = $request->get('page',0);
        $search_where   = [];
        $customPath     = 'news';
        if($request->isGet){
            $model      = new SearchModel();
            $seach_data = $request->get('SearchForm');
            if ( (isset($seach_data['cs']) && !empty($seach_data['cs'])) && (isset($seach_data['q']) && !empty($seach_data['q']))  )
            {
                $search_where['like'] = Functions::xss_clean($seach_data['q']);
                $search_where['id']   = $seach_data['cs'];
            }
            else if( ( !isset($seach_data['cs']) or empty($seach_data['cs']) ) and !empty($seach_data['q']) )
            {
                $search_where['like'] = Functions::xss_clean($seach_data['q']);
            }
            else if(( isset($seach_data['cs']) and !empty($seach_data['cs']) ) and empty($seach_data['q']))
            {
                $search_where['id']   = $seach_data['cs'];
            }
            else if( (!empty($seach_data['cs']) and empty($seach_data['q'])) and $seach_data['c']==2 )
            {
                $search_where['id']   =  33;
                $seach_data['cs']     =  33;
            }
            else if( empty($seach_data['cs']) and empty($seach_data['q']) )
            {
                $search_where = [];
            }

            $metaData["title"]         = ($this->menus['id'][$seach_data['cs']]['name'] == null ? 'Axtarış' : $this->menus['id'][$seach_data['cs']]['name']);
            $metaData["description"]   = ($this->menus['id'][$seach_data['cs']]['name'] == null ? 'Axtarış' : $this->menus['id'][$seach_data['cs']]['name']);
            $metaData["canonical"]     = "http://e-tibb.az/axtar";
            $metaData["page_type"]     = "article";
            $metaData["amp_status"]    = 1;
            $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];

            Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);


            switch ($seach_data['c']) {
                case 1:
                    $total_count = $model->getSearchNewsCount($search_where);
                    if($total_count>0){
                        $pagination  = new Pagination(['totalCount'=>$total_count]);
                        $pagination->defaultPageSize = 15;
                        $search_datas = $model->get_SearchNews($search_where,$pagination->limit,$pagination->offset);
                        if(!empty($search_datas)){
                            return $this->render('../news/index',[
                                'news'=> $search_datas,
                                'formdata' =>$seach_data,
                                'pagination'=> $pagination,
                                'customPath'=> $customPath,
                                'breadcrumb'=> 'Axtar',
                            ]);
                        }else{
                            $msg = 'Axtardığınız məlumat tapılmadı';
                            $type= 'error';
                            return $this->showError($msg,$type,'search');
                        }
                    }else{
                        $msg = 'Axtardığınız məlumat tapılmadı';
                        $type= 'error';
                        return $this->showError($msg,$type,'search');
                    }
                    break;
                case 2:
                    $customPath  = 'doctors';
                    $total_count = $model->getSearchDoctorCount($search_where);
                    if( isset( $this->spc['id'][$seach_data['cs']] ) and !empty($this->spc['id'][$seach_data['cs']]) )
                    {
                        $data['page_title'] = $this->spc['id'][$seach_data['cs']]['name'];
                    }else{
                        $data['page_title'] = 'Bütün Kateqoriyalar';
                        //throw new NotFoundHttpException(Yii::t('app','Axtardığınız səhifə mövcud deyil.'));
                    }
                    //print_r($total_count); die();
                    if($total_count>0) {
                        $pagination = new Pagination(['totalCount' => $total_count]);
                        $cache = Yii::$app->cache;
                        $pagination->defaultPageSize = 12;


                        $search_datas = $model->get_SearchDoctors($search_where, $pagination->limit, $pagination->offset);
                        //print_r($search_datas); die();
                        if (!empty($search_datas)){
                            foreach($search_datas as $key => $val){ $ids .= $val['id'].','; };
                            $ids = trim($ids,',');
                            if(!empty($ids))
                            {
                                if(!$cache->get('doctors_specialists_'.$seach_data['cs'].'_'.$page))
                                {
                                    $data['specialists'] = SiteDoctorsModel::getSpecialistList($ids);
                                    $cache->set('doctors_specialists_'.$seach_data['cs'].'_'.$page,$data['specialists'],120);
                                }
                                else
                                {
                                    $data['specialists'] = $cache->get('doctors_specialists_'.$seach_data['cs'].'_'.$page);
                                }
                            }
                            $data['doctors']    = $search_datas;
                            $data['pagenation'] = $pagination;
                            return $this->render('../doctor/doctors',[
                                'data'      => $data,
                                'formdata'  => $seach_data,
                                'customPath'=> $customPath,
                            ]);
                        }else{
                            $msg = 'Axtardığınız məlumat tapılmadı';
                            $type= 'error';
                            return $this->showError($msg,$type,'search');
                        }
                    }else{
                        $msg = 'Axtardığınız məlumat tapılmadı';
                        $type= 'error';
                        return $this->showError($msg,$type,'search');
                    }
                    break;
                case 3:
                    $customPath  = 'enterprises';
                    $total_count = $model->getSearchEnterpriseCount($search_where);
                    if($total_count>0){
                        $pagination  = new Pagination(['totalCount'=>$total_count]);
                        $pagination->defaultPageSize = 12;
                        $search_datas = $model->get_SearchEnterprises($search_where,$pagination->limit,$pagination->offset);
                        if (!empty($search_datas)) {
                            if($seach_data['cs'] == Yii::$app->params['search.type']){
                                $customPath = 'enterprises2';
                            }
                            if(empty($seach_data['cs'])){
                                $seach_data['cs'] = 33;
                            }
                            return $this->render('../enterprise/index',[
                                'enterprises'=> $search_datas,
                                'formdata'  =>  $seach_data,
                                'pages'     =>  $pagination,
                                'customPath'=>  $customPath,
                                'page_title'=>  $this->menus['id'][$seach_data['cs']]['name'],
                            ]);
                        }else{
                            $msg = 'Axtardığınız məlumat tapılmadı';
                            $type= 'error';
                            return $this->showError($msg,$type,'search');

                        }
                    }else{
                        $msg = 'Axtardığınız məlumat tapılmadı';
                        $type= 'error';
                        return $this->showError($msg,$type,'search');
                    }
                    break;
                default:
                    $customPath  = 'enterprises';
                    $total_count = $model->getSearchEnterpriseCount($search_where);
                    if($total_count>0){
                        $pagination  = new Pagination(['totalCount'=>$total_count]);
                        $pagination->defaultPageSize = 12;
                        $search_datas = $model->get_SearchEnterprises($search_where,$pagination->limit,$pagination->offset);
                        if (!empty($search_datas)) {
                            if(empty($seach_data['cs'])){
                                $seach_data['cs'] = 33;
                            }
                            return $this->render('../enterprise/index',[
                                'enterprises'=> $search_datas,
                                'formdata'  =>  $seach_data,
                                'pages'     =>  $pagination,
                                'customPath'=>  $customPath,
                                'page_title'=>  $this->menus['id'][$seach_data['cs']]['name'],
                            ]);
                        }else{
                            $msg = 'Axtardığınız məlumat tapılmadı';
                            $type= 'error';
                            return $this->showError($msg,$type,'search');

                        }
                    }else{
                        $msg = 'Axtardığınız məlumat tapılmadı';
                        $type= 'error';
                        return $this->showError($msg,$type,'search');
                    }
                    break;
            }
        }else{
            $customPath  = 'enterprises';
            $pages = new Pagination();
            $page = 0;
            $seach_data['c'] = 3;
            $seach_data['cs'] = 33;
            if(isset($_GET["page"]) and intval($_GET["page"])>1){
                $page = $_GET["page"];
            }
            $cache = Yii::$app->cache;

            $enterprises = $cache->get("category_enterprises_".$seach_data['cs']."_".$page);
            if($enterprises == false){
                $pages->totalCount  =  EnterpriseModel::getcategoryEnterprisesCount($seach_data['cs']);
                $enterprises        =  EnterpriseModel::getCategoryEnterprises($seach_data['cs'],$pages->offset,$pages->limit);
                $cache->set("category_enterprises_".$seach_data['cs']."_".$page,$enterprises,120);
                $cache->set("category_enterprises_pages_".$seach_data['cs']."_".$page,$pages,120);
            }else{
                $pages = $cache->get("category_enterprises_pages_".$seach_data['cs']."_".$page);
            }
            return $this->render('../enterprise/index',[
                'enterprises'=> $enterprises,
                'formdata'  =>  $seach_data,
                'pages'     =>  $pages,
                'customPath'=>  $customPath,
                'page_title'=>  $this->menus['id'][$seach_data['cs']]['name'],
            ]);
        }

    }

    public function actionCatlist()
    {
        $model = new SearchModel();
        $request    =   Yii::$app->request;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($request->isPost){
            $type = $request->post('get_option');
            switch ($type) {
                case 1:
                    $news           = $model->getSwitcher(3);
                    $data['title']  = 'Bütün Xəbərlər';
                    $data['cat']    = $news;
                    return json_encode($data);
                    break;
                case 2:
                    $doctor         = SiteSpecialistsModel::getSpecialistSearch();
                    $data['title']  = 'Bütün Həkimlər';
                    $data['cat']    = $doctor;
                    return json_encode($data);
                    break;
                case 3:
                    $enterprises    = $model->getSwitcher(2);
                    $data['title']  = 'Bütün Obyektlər';
                    $data['cat']    = $enterprises;
                    return json_encode($data);
                    break;
                default:
                    $news           = $model->getSwitcher(3);
                    $data['title']  = 'Bütün Xəbərlər';
                    $data['cat']    = $news;
                    return json_encode($data);
                    break;
            }
        }
    }

}
