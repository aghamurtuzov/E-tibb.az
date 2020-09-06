<?php
namespace frontend\controllers;

use app\models\SiteComments;

use frontend\models\SiteCommentsModel;
use frontend\models\SiteConsultation;
use frontend\models\SiteConsultationModel;
use frontend\models\SiteDoctors;
use Yii;
use yii\data\Pagination;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use frontend\components\Menu;
use frontend\components\SeoLib;
use yii\web\NotFoundHttpException;
use backend\components\Functions;
use frontend\components\Specialist;
use frontend\models\SiteDoctorsModel;
use frontend\models\EnterpriseModel;
use yii\helpers\Url;
use frontend\models\PromotionModel;
use frontend\models\SiteDoctorFileModel;
use frontend\models\SiteCalling;
use frontend\models\WorkDaysModel;
use frontend\models\NewsModel;
/**
 * Site controller
 */
class DoctorController extends MainController
{

    public $spc;
    public $menus;
    public $data = null;
    public $menu_id = 4;
    public $layout = 'doctor';
    public $customPath = 'doctors';
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
     * Displays newspage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $keyword = $category = null;
        if(isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $keyword = Functions::xss_clean($_GET['keyword']);
        }

        if(isset($_GET['category']) && !empty($_GET['category'])) {
            $category = intval($_GET['category']);
        }

        $cache               = Yii::$app->cache;
        $page                = Yii::$app->request->get('page',0);
        $data['specialists'] = $this->spc['id'];
        $data['clinics']     = EnterpriseModel::getClinics(1);
        //$premium_doctors = array() ;
        $data['page_title']  = $this->menus['id'][$this->menu_id]['name'];
        $doctors_count       = SiteDoctorsModel::getCountDoctors($keyword,$category);
        $data['keyword'] = $keyword;
        $data['category'] = $category;

        /** Meta tags */
        $metaData["title"]         = $this->menus['id'][$this->menu_id]['name'].' - '.Yii::$app->params['seo']['author'];
        $metaData["canonical"]     = Url::base('https').'/'.$this->menus['id'][$this->menu_id]['link'];
        $metaData["page_type"]     = "website";
        $metaData["amp_status"]    = 0;
        $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];
        Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);
        /** Meta tags END */

        if($doctors_count > 0)
        {
            $pages = new Pagination(['totalCount' => $doctors_count]);
            $pages->defaultPageSize = 6;
//            $data['premium_doctors'] = [];
            // Doctors list
//            if(!$cache->get('all_doctors_'.$page))
//            {
                $doctors = SiteDoctorsModel::getDoctors($pages->offset,$pages->limit,$keyword,$category);
//                foreach ($doctors as $doc){
//                    if($doc['is_premium']==1)
//                    {
//                        $data['premium_doctors'][] = $doc;
//                    }
//                    else if($doc['is_premium']==0){
//                        $data['doctors'][] = $doc;
//                    }
//                }
//                $cache->set('all_doctors_'.$page,$doctors,60);
//                $cache->set('premium_doctors'.$page,$data['premium_doctors'],60);
//            }

            $data['premium_doctors'] = SiteDoctorsModel::getPremiumDoctors($pages->offset,$pages->limit);

            $data['doctors'] = $doctors;
//            $data['premium_doctors'] = $data['premium_doctors'];

            $data['pages']   = $pages;

        }
        return $this->render('index',['data'=>$data]);
    }

    public function actionDoctors($cat,$id)
    {
        $ids   = null;
        $id    = intval($id);
        $page  = Yii::$app->request->get('page',0);

        /** Check ID */
        if(isset($this->spc['id'][$id]))
        {
            $spc = $this->spc['id'][$id];
        }else{
            throw new NotFoundHttpException(Yii::t('app','Axtardığınız səhifə mövcud deyil.'));
        }
       // print_r($spc);
        $cache = Yii::$app->cache;

        /** Doctor count */
        if(!$cache->get('doctors_count_list'.$id))
        {
            $count = SiteDoctorsModel::getCountDoctorsWithSpec($id);
            $cache->set('doctors_count_list'.$id,$count,120);
        }
        else
        {
            $count = $cache->get('doctors_count_list'.$id);
        }

        /** Check doctor count */
        if(!empty($count))
        {
            $pages = new Pagination(['totalCount' => $count]);
            $pages->defaultPageSize = 6;

            /** Doctor list */
            if(!$cache->get('doctors_list_'.$id.'_'.$page))
            {
                $data['doctors'] = SiteDoctorsModel::getDoctorsWithSpec($id,$pages->offset,$pages->limit);
                $cache->set('doctors_list_'.$id.'_'.$page,$data['doctors'],120);
            }
            else
            {
                $data['doctors'] = $cache->get('doctors_list_'.$id.'_'.$page);
            }

            $data['doctorsForPremium'] = SiteDoctorsModel::getDoctorsWithSpec($id,0,100);

            if(!empty($data['doctorsForPremium']))
            {
                $data['premium_doctors'] = [];

                foreach ($data['doctorsForPremium'] as $doc)
                {
                    if($doc['is_premium']==1)
                    {
                        $data['premium_doctors'][] = $doc;
                    }
                }

                //print_r($spc);
                //metadata
                $metaData["title"]         = $spc['name'];
                $metaData["description"]   = $spc['description'];
                $metaData["canonical"]     = Url::base(true).'/'.$spc['slug'].'-'.$spc['id'];
                $metaData["page_type"]     = "website";
                $metaData["amp_status"]    = 0;
                $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];
                Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);
                //
                //breadcrumb schema
                $header_datas =array();
                $header_datas['title']       = $spc['name'];
                $header_datas['description'] = $spc['description'];

                $header_datas['breadcrumb']  = array();
                $header_datas['breadcrumb'][0]['link'] = Url::base('https');
                $header_datas['breadcrumb'][0]['text'] = 'Ana Səhifə';
                $header_datas['breadcrumb'][1]['link'] = Url::base('https').'/'.$this->menus['id'][$this->menu_id]['link'];
                $header_datas['breadcrumb'][1]['text'] = $this->menus['id'][$this->menu_id]['name'];
                $header_datas['breadcrumb'][2]['link'] = Url::base('https').'/'.$spc['slug'].'-'.$spc['id'];
                $header_datas['breadcrumb'][2]['text'] = $spc['name'];
                Yii::$app->params['breadcrumb.schema'] = $this->seo->make_page_breadcrumb($header_datas['breadcrumb']);

                $data['pagenation'] = $pages;
                $data['page_title'] = $spc['name'];
                $data['cat_id'] = $id;

                $data['specialists'] = $this->spc['id'];
                $data['clinics']     = EnterpriseModel::getClinics(1);

                return $this->render('doctors',['data'=>$data]);

            }else{ return $this->showError(); };
        }
        else{ return $this->showError(); };
    }

    public function actionDoctorInfo($cat,$id,$currentTab = 'sual-ver')
    {
        $tabs = [];
        //$this->layout = 'doctor_layout';
        /** Global settings */
        if(isset($this->menus['id'][$this->menu_id])) {
            $settings = json_decode($this->menus['id'][$this->menu_id]['settings'],true);
        } else {
            throw new NotFoundHttpException(Yii::t('app','Xəta! Məlumatın göstərilməsi zamanı xəta baş verdi'));
        }

        /** Doctor */
        $data['doctor'] = SiteDoctorsModel::getDoctorInfo($id);
        $d_object = new SiteDoctorsModel();
        if(!empty($data['doctor']))
        {
            /** Specialists */
            $data['specialist'] = SiteDoctorsModel::getDoctorSpecialist($id);

            if(!empty($data['specialist'])) {
                if(!empty($data['specialist'][0]['settings'])) {
                    $settings = json_decode($data['specialist'][0]['settings'],true);
                }

                $data['breadcrumb'] = [
                    ['name' => "Ana səhifə",'link' => Yii::$app->params['site.url']],
                    ['name' => $data['specialist'][0]['name'],'link' => Yii::$app->params['site.url'].$data['specialist'][0]['slug'].'-'.$data['specialist'][0]['id']]
                ];
            }

            //$spc_list                = $data['specialists'][$data['doctor']['id']];
            $doc_link                  = Url::base('https').'/'.Functions::getDoctorLink($data['specialist'],$data['doctor']['id'],$data['doctor']['name']); //Functions::getDoctorLink($spc_list,$data['doctor']['id'],$data['doctor']['name']);

            /** Meta tags */
            $metaData["title"]         = $data['doctor']['name'].' - '.Yii::$app->params['seo']['author'];
            $metaData["description"]   = $data['doctor']['about'];
            $metaData["canonical"]     = $doc_link;
            $metaData["page_type"]     = "article";
            //$metaData["amp_status"]    = 1;
            //$metaData["amp_url"]       = $doc_link.'/amp';
            $metaData["published_time"]= strtotime($data['doctor']['published_time']);
            $metaData["modified_time"] = strtotime($data['doctor']['modified_time']);
            $metaData["article_image"] = Url::base('https').'/upload/doctors/'.$data['doctor']['photo'];
            $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];

            Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);
            /** Meta tags END */

            //breadcrumb schema
            $header_datas =array();
            $header_datas['title']       = $data['doctor']['name'];
            $header_datas['description'] = $data['doctor']['about'];

            $header_datas['breadcrumb']  = array();
            $header_datas['breadcrumb'][0]['link'] = Url::base('https');
            $header_datas['breadcrumb'][0]['text'] = 'Ana Səhifə';
            $header_datas['breadcrumb'][1]['link'] = Url::base('https').'/'.$this->menus['id'][$this->menu_id]['link'];
            $header_datas['breadcrumb'][1]['text'] = $this->menus['id'][$this->menu_id]['name'];
            $header_datas['breadcrumb'][2]['link'] = Url::base('https').'/'.$data['specialist'][0]['slug'].'-'.$data['specialist'][0]['id'];
            $header_datas['breadcrumb'][2]['text'] = $data['specialist'][0]['name'];
            $header_datas['breadcrumb'][3]['link'] = $doc_link;
            $header_datas['breadcrumb'][3]['text'] = $data['doctor']['name'];

            Yii::$app->params['breadcrumb.schema'] = $this->seo->make_page_breadcrumb($header_datas['breadcrumb']);

            //article schema
            $about['category_datas'] = [$data['specialist'][0]['name'],Url::base('https').'/'.$data['specialist'][0]['slug'].'-'.$data['specialist'][0]['id']];
            $about['title']          = $data['doctor']['name'];
            $about['description']    = $data['doctor']['about'];
            $about['post_image']     = Url::base(true).'/upload/doctors/'.$data['doctor']['photo'];
            $about['posted_at']      = date('M d, Y', strtotime($data['doctor']['published_time']));
            $about['created_at']     = strtotime($data['doctor']['published_time']);
            $about['updated_at']     = strtotime($data['doctor']['modified_time']);
            $about['author']         = Yii::$app->params['seo']['author'];
            $about['author_desc']    = Yii::$app->params['seo']['author_desc'];
            $about['author_image']   = Url::base('https/').Yii::$app->params['seo']['author_img'];
            $about['author_url']     = Yii::$app->params['seo']['article_publisher'];
            $about['slug']           = Functions::getDoctorLink($data['specialist'],$data['doctor']['id'],$data['doctor']['name']);
            $about['main_url']       = Url::base('https').'/'.$data['specialist'][0]['slug'].'-'.$data['specialist'][0]['id'];
            $about['rating_value']   = '4.7';//$model['rating'];
            $about['review_count']   = $data['doctor']['review_count'];
            if($d_object->get_next_post($id))
                $about['next_post']      = $d_object->get_next_post($id);
            if($d_object->get_prev_post($id))
                $about['prev_post']      = $d_object->get_prev_post($id);
            Yii::$app->params['article.schema'] = $this->seo->make_article_schema(json_decode(json_encode($about), true), $about['main_url'],'/amp');

            /** Make tabs */
            if(!empty($settings) && isset($settings['pages'])) {
                foreach($settings['pages'] as $key => $val) {
                    $tabs[$val['link']] = $val;
                }
            }

            /** Check tab link */
            if(isset($tabs[$currentTab])) {
                $tab['name'] = $tabs[$currentTab]['name'];
                $tab['link'] = $tabs[$currentTab]['link'];
                $tab['type'] = $tabs[$currentTab]['type'];
            } else {
                $tab['name'] = $settings['pages'][0]['name'];
                $tab['link'] = $settings['pages'][0]['link'];
                $tab['type'] = $settings['pages'][0]['type'];
            }

            /** Promotion */
            if(!empty($data['doctor']['promotion'])) {
                $data['promotions'] = PromotionModel::getPromotionWithConnectId($id,1);
            }

            $data['customPath'] = $this->customPath;

            /** Comment Form */
            $comment = new SiteComments();
            $comment->connect_id = $id;
            $comment->type = 1;
            $data['comment'] = $comment;

            /** Consultation */
            $info = ['id'=>$id];
            $consultation = new SiteConsultation();
            $consultation->info = base64_encode(json_encode($info));
            $data['consultation'] = $consultation;

            /** QuickChat */
            $quickChat = [];
            $quickChat['isCurrentDoctor'] = !Yii::$app->user->isGuest && (Yii::$app->user->identity->id == $data['doctor']['user_id']) ? true : false;
            $quickChat['questionsCount']  = SiteConsultationModel::getQuestionsCount($data['doctor']['id'], $quickChat['isCurrentDoctor']);
            $quickChat['questions']  = SiteConsultationModel::getQuestions($data['doctor']['id'], $quickChat['isCurrentDoctor'],0);

            /** Certificates */
            $certificates_data = [];
            $certificates = SiteDoctorFileModel::getFiles($data['doctor']['id'],2);
            $certificates_data['certificatesList'] = SiteDoctors::ResultList($certificates,'doctors');

            $diploms = SiteDoctorFileModel::getFiles($data['doctor']['id'],1);
            $certificates_data['diplomsList'] = SiteDoctors::ResultList($diploms,'doctors');

            /** Comments*/
            $comments_data = [];
            $comments_data['commentsCount'] = SiteCommentsModel::getCommentsCount($data['doctor']['id'], 1);
            $comments_data['comments'] = SiteCommentsModel::getComments($data['doctor']['id'], 1);

            /** Degree */
            $sidebar_data = [];
            $sidebar_data['degree'] = SiteDoctors::getDegree();
            $sidebar_data['specialists'] = SiteDoctorsModel::getDoctorSpecialist($data['doctor']['id']);
            //$workDaysData = $this->getWorkDays($data['doctor']['id']);
            //$sidebar_data['workdays'] = $workDaysData['workdays'];
            //$sidebar_data['closed_times'] = $workDaysData['closed_times'];

            /** Blog Posts */
            $blog_data = [];
            $news = new NewsModel();
            $blog_data['total_count']    = $news->getNewsCountByDoctor($data['doctor']['id']);
            $blog_data['pagination']     = new Pagination(['totalCount' => $blog_data['total_count']]);
            $blog_data['pagination']->defaultPageSize = 6;
            $blog_data['news_list'] = $news->getNewsByDoctor($data['doctor']['id'], $blog_data['pagination']->limit, $blog_data['pagination']->offset);

            return $this->render('doctor_page',[
                'data'=>$data,
                'currentTab'=>$tab,
                'tabPages'=>$settings['pages'],
                'quickChat' => $quickChat,
                'certificates_data' => $certificates_data,
                'comments_data' => $comments_data,
                'sidebar_data' => $sidebar_data,
                'blog_data' => $blog_data
            ]);

        } else {
            return $this->showError();
        }
    }

    private function getWorkDays($doc_id) {
        $date_y = date('Y-m-d');
        $w_model = new WorkDaysModel();
        if (!empty($doc_id)) {
            $workdays = $w_model->getUserWorkday($doc_id, $date_y);
            if (!empty($workdays))
                $workdays = explode(',', $workdays['workdays']);
            else
                $workdays = array();
            $closed_times = SiteCalling::getSuitTimes($doc_id, $date_y);

            $closed_time = array();
            if (!empty($closed_times)) {
                foreach ($closed_times as $ct) {
                    $closed_time[] = $ct->time;
                }
            }
        } else {
            $workdays = array();
            $closed_times = array();
        }

        return [
            'workdays' => $workdays,
            'closed_times' => $closed_times,
        ];
    }

    public function actionPremium()
    {
        $cache               = Yii::$app->cache;
        $page                = Yii::$app->request->get('page',0);
        $data['specialists'] = $this->spc['id'];
        $data['clinics']     = EnterpriseModel::getClinics(1);
        //$premium_doctors = array() ;
        //print_r($data['specialists']); exit();
        $data['page_title']  = $this->menus['id'][$this->menu_id]['name'];
        $doctors_count       = SiteDoctorsModel::getCountPremiumDoctors();
        if($doctors_count > 0)
        {
            $pages = new Pagination(['totalCount' => $doctors_count]);
            $pages->defaultPageSize = 6;
            $data['premium_doctors'] = [];
            // Doctors list
//            if(!$cache->get('all_doctors_'.$page))
//            {
            $doctors = SiteDoctorsModel::getPremiumDoctors($pages->offset,$pages->limit);
            foreach ($doctors as $doc){
                if($doc['is_premium']==1)
                {
                    $data['premium_doctors'][] = $doc;
                }
//                    else if($doc['is_premium']==0){
//                        $data['doctors'][] = $doc;
//                    }
            }
//                $cache->set('all_doctors_'.$page,$doctors,60);
//                $cache->set('premium_doctors'.$page,$data['premium_doctors'],60);
//            }
            $data['doctors'] = $doctors;
//            $data['premium_doctors'] = $data['premium_doctors'];
            ///print_r($data['doctors']); exit();
            $data['pages']   = $pages;
            //print_r($data['pages']); exit();
        }

        return $this->render('premium',['data'=>$data]);
    }

    public function actionGetComments()
    {
        $request = Yii::$app->request;

        $get = $request->get();

        if ($request->isAjax) {

            if (intval($get['page'])) {
                $comments = SiteCommentsModel::getComments($get["row_id"],1,$get["page"]);
                return $this->renderAjax('ajax/comments', ['comments' => $comments]);

            } else {

                throw new \yii\web\BadRequestHttpException;
            }
        } else {

            throw new \yii\web\BadRequestHttpException;
        }
    }

}
