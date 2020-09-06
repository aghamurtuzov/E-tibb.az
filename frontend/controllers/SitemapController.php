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
use frontend\models\SiteMenus;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use frontend\components\Menu;
use frontend\components\Seo;
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

class SitemapController extends MainController
{
    public $menus;
    public $spc;
    public $menu_id = 4;

    public function actions()
    {
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

    public function beforeAction($action)
    {
        $menus = ArrayHelper::toArray(new Menu());
        $this->menus = $menus['list'];

        $specialists = ArrayHelper::toArray(new Specialist());
        $this->spc = $specialists['specialists'];

        return $this->menus;

    }

    public function actionIndex()
    {
        $seo = new Seo();
        $model = new SitemapModel();
        $default_pages = array();
        $default_pages[0]["loc"] = Url::base('https') . '/sitemap-pages.xml';
        $default_pages[0]["last_mod"] = time();
        $default_pages[1]["loc"] = Url::base('https') . '/sitemap-hekimler.xml';
        $default_pages[1]["last_mod"] = time();
        $sitemap_datas = $model->getSiteMapMenus();
        foreach ($sitemap_datas as $key => $value) {
            if (!$key == 0) {
                $link = $value['link'];
//                if ($value['parent'] == null and $value['type'] != 1) {
//                    $link = 'sitemap-' . $value['link'] . '.xml';
//                    $default_pages[$key]["loc"] = Url::base('https') . '/' . $link;
//                    $default_pages[$key]["last_mod"] = time();
//                }
                if ($value['type'] == 2) {
                    $link = 'sitemap-obyekt-' . $value['link'] . '.xml';
                    $default_pages[$key]["loc"] = Url::base('https') . '/' . $link;
                    $default_pages[$key]["last_mod"] = time();
                } elseif ($value['type'] == 3) {
                    $link = 'sitemap-xeberler-' . $value['link'] . '.xml';
                    $default_pages[$key]["loc"] = Url::base('https') . '/' . $link;
                    $default_pages[$key]["last_mod"] = time();
                } elseif ($value['type'] == 4) {
                    $link = 'sitemap-magaza-' . $value['link'] . '.xml';
                    $default_pages[$key]["loc"] = Url::base('https') . '/' . $link;
                    $default_pages[$key]["last_mod"] = time();
                }

            }
        }
        return $seo->site_map_pages($default_pages);
    }

    /**
     * @return Hekimlerin specialities xml data-si
     */

    public function actionDoctorIndex()
    {
        $seo = new Seo();
        $model = new SitemapModel();
        $default_pages = array();
        $sitemap_datas = $model->getSpecialist();
        foreach ($sitemap_datas as $key => $value) {
            if ($value['count'] > 0) {
                $link = 'sitemap-ixtisas-' . $value['slug'] . '.xml';
                $default_pages[$key]["loc"] = Url::base('https') . '/' . $link;
                $default_pages[$key]["last_mod"] = time();
            }
        }
        return $seo->site_map_pages($default_pages);
    }

    public function actionDoctors()
    {

        $seo = new Seo();
        $model = new SitemapModel();
        $default_pages = array();
        $sitemap_datas = $model->getSpecialist();
        foreach ($sitemap_datas as $key => $value) {
            if ($value['count'] > 0) {
                $link = 'sitemap-' . $value['slug'] . '.xml';
                $default_pages[$key]["loc"] = Url::base('https') . '/' . $link;
                $default_pages[$key]["last_mod"] = time();

            }
        }
        return $seo->site_map_url($default_pages);
    }

    public function actionBaseObyekt()
    {
        $slug = Yii::$app->request->get("slug");
        $seo = new Seo();
        $model = new SitemapModel();
        $menu = $model->getEnterpriseMenu($slug);
        $default_pages = array();
        $sitemap_datas = $model->getEnterprise($menu['id']);
        foreach ($sitemap_datas as $key => $value) {
            $default_pages[$key]["loc"] = Url::base('https') . '/' . $slug . '/' . $value['id'] . '-' . $value['slug'];
            $default_pages[$key]["last_mod"] = time();
        }
        return $seo->site_map_url($default_pages);
    }

    public function actionBaseNews()
    {
        $slug = Yii::$app->request->get("slug");
        $seo = new Seo();
        $model = new SitemapModel();
        $menu = $model->getEnterpriseMenu($slug);
        $default_pages = array();
        $sitemap_datas = $model->getNews($menu['id']);
        foreach ($sitemap_datas as $key => $value) {
            $default_pages[$key]["loc"] = Url::base('https') . '/xeber/' . $value['slug'] . '-' . $value['id'];
            $default_pages[$key]["last_mod"] = time();
        }
        return $seo->site_map_url($default_pages);
    }

    public function actionNews()
    {
        $seo = new Seo();
        $model = new SitemapModel();
        $default_pages = array();
        $sitemap_datas = $model->getNewsAll();
        foreach ($sitemap_datas as $key => $value) {
            $default_pages[$key]["loc"] = Url::base('https') . '/xeber/' . $value['slug'] . '-' . $value['id'];
            $default_pages[$key]["last_mod"] = time();

        }
        return $seo->site_map_url($default_pages);
    }

    public function actionBaseSpecialty()
    {
        $slug = Yii::$app->request->get("slug");
        $seo = new Seo();
        $model = new SitemapModel();
        $default_pages = array();
        $sitemap_datas = $model->getSpecialistDoctors($slug);
        foreach ($sitemap_datas as $key => $value) {
            $default_pages[$key]["loc"] = Url::base('https') . '/' . $value['specialist'] . '/' . $value['link'] . '-' . $value['id'];
            $default_pages[$key]["last_mod"] = time();
        }
        return $seo->site_map_url($default_pages);
    }

    public function actionPages()
    {
//        $model = new SitemapModel();
        $seo = new Seo();
        $default_pages = array();

        $link = array();

        $sitemap_datas = SiteMenus::find()->with('sub')->where(['parent' => 0])->all();

        foreach ($sitemap_datas as $key => $value) {
            $link[] = Url::base('https') . '/' . $value['link'];
            if (count($value->sub) > 0) {
                foreach ($value->sub as $val) {
                    $link[] = Url::base('https') . '/' . $value['link'] . '/' . $val['link'];
                }
            }
        }
        foreach ($link as $key => $a) {
            $default_pages[$key]["loc"] = $a;
            $default_pages[$key]["last_mod"] = time();
        }
        return $seo->site_map_url($default_pages);

    }

}
