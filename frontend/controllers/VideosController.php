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
class VideosController extends MainController
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
        $menus = ArrayHelper::toArray(new Menu());
        $this->menus = $menus['list'];
        $this->seo = new SeoLib();

        return parent::beforeAction($action);
    }


    public function actionIndex()
    {
//        $menuID = 28;
        $news_object    = new NewsModel();
        $not_in         = Yii::$app->params['news.not_in'];
        $not_in_str     = '';
        foreach ( $not_in as $in) {
            $not_in_str .= $in.',';
        }
        $not_in_str = rtrim($not_in_str,',');
        if(!empty($not_in_str)){
            $total_count    = $news_object->getVideosCount($not_in_str);
            $pagination     = new Pagination(['totalCount'=>$total_count]);
            $pagination->defaultPageSize = 16;
            $news_list      = $news_object->getVideos($not_in_str,$pagination->limit,$pagination->offset);
            if(!empty($news_list)){

                //breadcrumb data
//                $header_datas =array();
//                $header_datas['title']       = $this->menus['id'][$menuID]['name'];
//                $header_datas['description'] = $this->menus['id'][$menuID]['content'];
//
//                $header_datas['breadcrumb']  = array();
//                $header_datas['breadcrumb'][0]['link'] = Url::base('https');
//                $header_datas['breadcrumb'][0]['text'] = 'Ana səhifə';
//                $header_datas['breadcrumb'][1]['link'] = Url::base('https').'/xeberler';
//                $header_datas['breadcrumb'][1]['text'] = $this->menus['id'][$menuID]['name'];
//
//                Yii::$app->params['breadcrumb.schema'] = $this->seo->make_page_breadcrumb($header_datas['breadcrumb']);
//
//                //end
//
//
//                $metaData["title"]         = $this->menus['id'][$menuID]['name'];
//                $metaData["description"]   = $this->menus['id'][$menuID]['name'];
//                $metaData["canonical"]     = Url::base(true).'/'.$this->menus['id'][$menuID]['link'];
//                $metaData["page_type"]     = "article";
//                $metaData["amp_status"]    = 1;
//                $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];

//                Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);

                return $this->render('index',[
                    'news'      => $news_list,
                    'pagination'=> $pagination,
                    'customPath'=> $this->customPath,
                    'breadcrumb'=> 'Videolar',
                ]);
            }else{
                return $this->showError();
            }
        }else
            throw new NotFoundHttpException(Yii::t('app','Axtardığınız səhifə mövcud deyil.'));
    }

}