<?php
namespace frontend\controllers;

use app\models\SiteComments;
use frontend\models\MainModel;
use frontend\models\NewsModel;
use frontend\models\SiteCommentsModel;
use frontend\models\SiteConsultation;
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

use yii\helpers\Url;
use frontend\components\SeoLib;

/**
 * Site controller
 */
class NewsController extends MainController
{
    public $menus;
    public $seo;
    public $data = null;
    public $layout = 'main';
    public $customPath = 'news';

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

        $this->seo = new SeoLib();

        return parent::beforeAction($action);

    }

    public function actionIndex()
    {
        $menuID = 28;
        $id = (isset($_GET['id']) && !empty($_GET['id'])) ? intval($_GET['id']) : null;
        $keyword = Yii::$app->request->get('keyword');

        if(!empty($id)) {
            $menuID = $id;
        }

        $news_object    = new NewsModel();
        $tags = $news_object->getKeywords();
        $most_read = $news_object->getMostRead();
        $not_in_str = Yii::$app->params['news.not_in'];
        $where = (!empty($id)) ? $id : $not_in_str;

        if(!empty($not_in_str)){
            //$total_count = $news_object->getXeberlerCount(implode(',',$not_in_str), $id, $keyword);
            $total_count = $news_object->getNewsCount($where, $keyword);
            $pagination = new Pagination(['totalCount' => $total_count]);
            $pagination->defaultPageSize = 15;
            $news_list = $news_object->getNews($where, $keyword, $pagination->limit, $pagination->offset);

            $cat_id = 0;

            if(isset($_GET['id']) && !empty($_GET['id']))
            {
                $cat_id = intval($_GET['id']);
            }

            //$news_list = $news_object->getXeberler($not_in_str, $pagination->limit, $pagination->offset, $id, $keyword);

            if(!empty($news_list)){
                $header_datas =array();
                $header_datas['title']       = $this->menus['id'][$menuID]['name'];
                $header_datas['description'] = $this->menus['id'][$menuID]['content'];
                $header_datas['breadcrumb']  = array();
                $header_datas['breadcrumb'][0]['link'] = Url::base('https');
                $header_datas['breadcrumb'][0]['text'] = 'Ana səhifə';
                $header_datas['breadcrumb'][1]['link'] = Url::base('https').'/xeberler';
                $header_datas['breadcrumb'][1]['text'] = $this->menus['id'][$menuID]['name'];
                Yii::$app->params['breadcrumb.schema'] = $this->seo->make_page_breadcrumb($header_datas['breadcrumb']);

                /** Meta tags */
                $metaData["title"] = $this->menus['id'][$menuID]['name'].' - '.Yii::$app->params['seo']['author'];
                $metaData["canonical"]     = Url::base('https').'/xeberler';
                if(!empty($id)) {
                    $metaData["canonical"] = Url::base('https').'/xeberler/'.$this->menus['id'][$menuID]['link'].'-'.$this->menus['id'][$menuID]['id'];
                }
                $metaData["page_type"]     = "website";
                $metaData["amp_status"]    = 0;
                $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];
                Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);
                /** Meta tags END */

                return $this->render('index',[
                    'news'      => $news_list,
                    'pagination'=> $pagination,
                    'customPath'=> $this->customPath,
                    'breadcrumb'=> $this->menus['id'][28]['name'],
                    'tags'      => $tags,
                    'most_read' => $most_read,
                    'keyword'   => $keyword,
                    'cat_id'   => $cat_id
                ]);
            } else {
                return $this->showError();
            }
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Axtardığınız səhifə mövcud deyil.'));
        }
    }

    public function actionSingle()
    {
        $news_object    = new NewsModel();
        $tags = $news_object->getKeywords();
        $most_read = $news_object->getMostRead();
        $request = Yii::$app->request;
        $news_id = intval($request->get('id'));
//        $comments = SiteComments::getAllComments($news_id);
        if(!empty($news_id))
        {
            $this->checkView($news_id);
            $single_news    = $news_object->getSingleNews($news_id);
            if(!empty($single_news)){

                $galllery_news  = $news_object->getNewsGallery($news_id);
                $related_news   = $news_object->getRelatedNews($single_news['category_id'], $single_news['id']);

                $post['next']   = $news_object->getNextNews($news_id);
                $post['prev']   = $news_object->getPrevNews($news_id);

                if(empty($galllery_news)){
                    $galllery_news = false;
                }
                if(empty($related_news)){
                    $related_news = false;
                }

//                $main_url  = '/n-'.$this->menus['id'][$single_news['category_id']]['link'].'-'.$single_news['category_id'];
                $newsUrl   = Url::base('https').'/'.Yii::$app->params['site.post_uri'].Functions::slugify($single_news['headline']).'-'.$single_news['id'];

                //breadcrumb schema
//                    $header_datas =array();
//                    $header_datas['title']       = $single_news['headline'];
//                    $header_datas['description'] = $single_news['content'];
//                    $header_datas['breadcrumb']  = array();
//                    $header_datas['breadcrumb'][0]['link'] = Url::base('https');
//                    $header_datas['breadcrumb'][0]['text'] = 'Ana səhifə';
//
//                    $header_datas['breadcrumb'][1]['link'] = $main_url;
//                    $header_datas['breadcrumb'][1]['text'] = $this->menus['id'][$single_news['category_id']]['name'];
//
//                    $header_datas['breadcrumb'][2]['link'] = $newsUrl;
//                    $header_datas['breadcrumb'][2]['text'] = $single_news['headline'];
//                    Yii::$app->params['breadcrumb.schema'] = $this->seo->make_page_breadcrumb($header_datas['breadcrumb']);

                /** Meta tags */
                $metaData["title"]         = $single_news['headline'].' - '.Yii::$app->params['seo']['author'];
                $metaData["description"]   = $single_news['content'];
                $metaData["article_keywords"] = $single_news['keywords'];
                $metaData["canonical"]     = $newsUrl;
                $metaData["page_type"]     = "article";
                //$metaData["amp_status"]    = 0;
                //$metaData["amp_url"]       = $newsUrl.'/amp';
                $metaData["published_time"]= strtotime($single_news['published_time']);
                $metaData["modified_time"] = strtotime($single_news['modified_time']);
                $metaData["article_image"] = Url::base('https').'/upload/news/'.$single_news['photo'];
                $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];

                Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);
                /** Meta tags END */

                //article schema
//                    $news['category_datas'] = [$this->menus['id'][$single_news['category_id']]['name'],$this->menus['id'][$single_news['category_id']]['link']];
//                    $news['post_image']     = Functions::getUploadUrl().$this->customPath.'/small/'.$single_news['photo'];
//                    $news['posted_at']      = date('M d, Y', strtotime($single_news['datetime']));
//                    $news['author']         = Yii::$app->params['seo']['author'];
//                    $news['author_desc']    = Yii::$app->params['seo']['author_desc'];
//                    $news['author_image']   = Url::base('https').Yii::$app->params['seo']['author_img'];
//                    $news['author_url']     = Yii::$app->params['seo']['article_publisher'];
//                    $news['slug']           = '/'.Yii::$app->params['site.post_uri'].Functions::slugify($single_news['headline']).'-'.$single_news['id'];
//                    $news['title']          = $single_news['headline'];
//                    $news['main_url']       = $main_url;
//                    $news['rating_value']   = $single_news['rating_value'];
//                    $news['review_count']   = $single_news['review_count'];
//                    if($news_object->get_next_post($news_id))
//                        $news['next_post']      = $news_object->get_next_post($news_id);
//                    if($news_object->get_prev_post($news_id))
//                        $news['prev_post']      = $news_object->get_prev_post($news_id);

//                    Yii::$app->params['article.schema'] = $this->seo->make_article_schema(json_decode(json_encode($news), true), $main_url,'');


//                $comment = new SiteComments();

                return $this->render('single_news',[
                    'news'        => $single_news,
                    'gallery'     => $galllery_news,
                    'related_news'=> $related_news,
                    'customPath'  => $this->customPath,
                    'breadcrumb'  => $this->menus['id'][$single_news['category_id']]['name'],
//                    'comment_model'=> $comment,
//                    'comments'    => $comments,
                    'tags'      => $tags,
                    'most_read' => $most_read,
                    'post'      => $post
                ]);

            }else{
                throw new NotFoundHttpException(Yii::t('app','Axtardığınız səhifə mövcud deyil.'));
            }
        }else{
            throw new NotFoundHttpException(Yii::t('app','Axtardığınız səhifə mövcud deyil.'));
        }

    }

    public function checkView($news_id)
    {
        $session = Yii::$app->session;
        $model   = new NewsModel();
        $re_news = array($news_id);
        $view    = $model->getNewsView($news_id)['news_read'];
        if($session->has('readed_news'))
        {
            $readed_news = $session->get('readed_news');
            if(!empty($readed_news))
            {
                if(!in_array($news_id,$readed_news))
                {
                    array_push($readed_news,$news_id);
                    $view = $view + 1;
                    $model->setNewsView($view,$news_id);
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
        else
        {
            $session->set('readed_news',$re_news);
            $view = $view + 1;
            $model->setNewsView($view,$news_id);
            return true;
        }
    }

//    public function actionAddComment()
//    {
//        $model = new SiteComments();
//
//        if($model->load(Yii::$app->request->post()) and $model->validate() ) {
//            $model->comment = Functions::filterWord($model->comment);
//            $model->datetime = date("Y-m-d H:i:s");
//            $model->status = 0;
//            $model->type = 3;
//            $model->positive = 0;
//            if($model->save()){
//                Yii::$app->session->setFlash("success","Rəyiniz göndərildi");
//
//            }
//            return $this->redirect(Yii::$app->request->referrer);
//        }
//
//        echo '<pre>';
//        var_dump(Yii::$app->request->post());die;
//    }

    //    public function actionCategory()
//    {
//        echo 1;die();
//        $news_object    = new NewsModel();
//        $main_model     = new MainModel();
//        $request        = Yii::$app->request;
//        $slug_link      = $request->get('slug');
//        $category_id    = $main_model->getSlugId($slug_link);
//
//        if(!empty($category_id)){
//            $total_count    = $news_object->getNewsCount($category_id);
//            $pagination     = new Pagination(['totalCount'=>$total_count]);
//            $pagination->defaultPageSize = 15;
//            $news_list      = $news_object->getNews($category_id,$pagination->limit,$pagination->offset);
//            if(!empty($news_list)){
//
//                $metaData["title"]         = $this->menus['id'][$category_id]['name'];
//                $metaData["description"]   = $this->menus['id'][$category_id]['name'];
//                $metaData["canonical"]     = Url::base(true).'/kateqoriya/'.$this->menus['id'][$category_id]['link'];
//                $metaData["page_type"]     = "website";
//                $metaData["amp_status"]    = 0;
//                $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];
//
//                Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);
//                //breadcrumb data
//                    $header_datas =array();
//                    $header_datas['title']       = $this->menus['id'][$category_id]['name'];
//                    $header_datas['description'] = $this->menus['id'][$category_id]['description'];
//
//                    $header_datas['breadcrumb']  = array();
//                    $header_datas['breadcrumb'][0]['link'] = Url::base('https');
//                    $header_datas['breadcrumb'][0]['text'] = 'Ana səhifə';
//                    $header_datas['breadcrumb'][1]['link'] = Url::base('https').'/n-'.$this->menus['id'][$category_id]['link'].'-'.$category_id;
//                    $header_datas['breadcrumb'][1]['text'] = $this->menus['id'][$category_id]['name'];
//
//                    Yii::$app->params['breadcrumb.schema'] = $this->seo->make_page_breadcrumb($header_datas['breadcrumb']);
//                //end
//
//                return $this->render('index',[
//                    'news'      => $news_list,
//                    'pagination'=> $pagination,
//                    'customPath'=> $this->customPath,
//                    'breadcrumb'=> $this->menus['id'][$category_id]['name'],
//                ]);
//
//            }else{
//                return $this->showError();
//            }
//        }else{
//            throw new NotFoundHttpException(Yii::t('app','Axtardığınız səhifə mövcud deyil.'));
//        }
//    }
}
