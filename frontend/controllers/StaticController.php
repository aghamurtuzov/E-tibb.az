<?php
/**
 * Created by PhpStorm.
 * User: Taleh F
 * Date: 12/24/2018
 * Time: 3:06 PM
 */

namespace frontend\controllers;

use frontend\components\ImageUpload;
use backend\models\SiteNewsSearch;
use frontend\models\EnterpriseModel;
use frontend\models\MainModel;
use frontend\models\SiteDoctorsModel;
use Yii;
use yii\base\InvalidParamException;
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
use frontend\components\News;
use backend\components\Functions;
use app\models\SiteComments;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\helpers\Url;
use frontend\components\SeoLib;

class StaticController extends MainController
{

    public $seo;
    public $menus;
    public $data = null;
    public $layout = 'static';

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'layout' => false
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    public function beforeAction($action)
    {

        $this->seo = new SeoLib();

        $menus = ArrayHelper::toArray(new Menu());

        $this->menus = $menus['list'];

        return $this->menus;

    }

    public function actionIndex($slug = null)
    {
        $mainModel = new MainModel();
        if (!empty($this->menus['slug'][$slug])) {
            $thanks = SiteComments::getTwoComments();
            $counts['clinics'] = EnterpriseModel::getClinicCount();
            $counts['doctors'] = SiteDoctorsModel::getAllDoctors();
            $counts['news'] = SiteNewsSearch::getAllNews();
            $data['static'] = $this->menus['slug'][$slug][0];

            /** Meta tags */
            $metaData["title"] = $this->menus['slug'][$slug][0]['name'] . ' - ' . Yii::$app->params['seo']['author'];
            $metaData["canonical"] = Url::base('https') . '/' . $this->menus['slug'][$slug][0]['link'];
            $metaData["page_type"] = "website";
            $metaData["amp_status"] = 0;
            $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];
            Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);
            /** Meta tags END */

            return $this->render('single_static', [
                'data' => $data,
                'breadcrumb' => $this->menus['slug'][$slug][0]['name'],
                'thanks' => $thanks,
                'counts' => $counts
            ]);
        } else {
            return $this->showError();
        }

    }

    public function actionBloodAbout($slug = null)
    {
        $mainModel = new MainModel();
        if (!empty($this->menus['slug'][$slug])) {
            $thanks = SiteComments::getTwoComments();
            $counts['clinics'] = EnterpriseModel::getClinicCount();
            $counts['doctors'] = SiteDoctorsModel::getAllDoctors();
            $counts['news'] = SiteNewsSearch::getAllNews();
            $data['static'] = $this->menus['slug'][$slug][0];

            /** Meta tags */
            $metaData["title"] = $this->menus['slug'][$slug][0]['name'] . ' - ' . Yii::$app->params['seo']['author'];
            $metaData["canonical"] = Url::base('https') . '/' . $this->menus['slug'][$slug][0]['link'];
            $metaData["page_type"] = "website";
            $metaData["amp_status"] = 0;
            $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];
            Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);
            /** Meta tags END */

            return $this->render('blood-about', [
                'data' => $data,
                'breadcrumb' => $this->menus['slug'][$slug][0]['name'],
                'thanks' => $thanks,
                'counts' => $counts
            ]);
        } else {
            return $this->showError();
        }

    }

    public function actionDonorAbout($slug = null)
    {
        $mainModel = new MainModel();
        if (!empty($this->menus['slug'][$slug])) {
            $thanks = SiteComments::getTwoComments();
            $counts['clinics'] = EnterpriseModel::getClinicCount();
            $counts['doctors'] = SiteDoctorsModel::getAllDoctors();
            $counts['news'] = SiteNewsSearch::getAllNews();
            $data['static'] = $this->menus['slug'][$slug][0];

            /** Meta tags */
            $metaData["title"] = $this->menus['slug'][$slug][0]['name'] . ' - ' . Yii::$app->params['seo']['author'];
            $metaData["canonical"] = Url::base('https') . '/' . $this->menus['slug'][$slug][0]['link'];
            $metaData["page_type"] = "website";
            $metaData["amp_status"] = 0;
            $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];
            Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);
            /** Meta tags END */

            return $this->render('donor-about', [
                'data' => $data,
                'breadcrumb' => $this->menus['slug'][$slug][0]['name'],
                'thanks' => $thanks,
                'counts' => $counts
            ]);
        } else {
            return $this->showError();
        }

    }

    public function actionDonorWhatAbout($slug = null)
    {
        $mainModel = new MainModel();
        if (!empty($this->menus['slug'][$slug])) {
            $thanks = SiteComments::getTwoComments();
            $counts['clinics'] = EnterpriseModel::getClinicCount();
            $counts['doctors'] = SiteDoctorsModel::getAllDoctors();
            $counts['news'] = SiteNewsSearch::getAllNews();
            $data['static'] = $this->menus['slug'][$slug][0];

            /** Meta tags */
            $metaData["title"] = $this->menus['slug'][$slug][0]['name'] . ' - ' . Yii::$app->params['seo']['author'];
            $metaData["canonical"] = Url::base('https') . '/' . $this->menus['slug'][$slug][0]['link'];
            $metaData["page_type"] = "website";
            $metaData["amp_status"] = 0;
            $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];
            Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);
            /** Meta tags END */

            return $this->render('donor-what-about', [
                'data' => $data,
                'breadcrumb' => $this->menus['slug'][$slug][0]['name'],
                'thanks' => $thanks,
                'counts' => $counts
            ]);
        } else {
            return $this->showError();
        }

    }

    public function actionContact()
    {
        $data['static'] = $this->menus['slug']['elaqe'][0];

        /** Meta tags */
        $metaData["title"] = $this->menus['slug']['elaqe'][0]['name'] . ' - ' . Yii::$app->params['seo']['author'];
        $metaData["canonical"] = Url::base('https') . '/' . $this->menus['slug']['elaqe'][0]['link'];
        $metaData["page_type"] = "website";
        $metaData["amp_status"] = 0;
        $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];
        Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);
        /** Meta tags END */

        return $this->render('contact', [
            'data' => $data,
            'breadcrumb' => $this->menus['slug']['elaqe'][0]['name'],
        ]);
    }

    public function actionRules()
    {
        $slug = Yii::$app->request->get('slug');

        $slug = trim($slug);

        $slugArr = ['hekim', 'klinika', 'aptek', 'aksiya'];

        if (strlen($slug) == 0)
            $slug = $slugArr[0];

        if (!in_array($slug, $slugArr))
            throw new NotFoundHttpException(Yii::t('app', 'Axtardığınız səhifə mövcud deyil.'));

        /** Meta tags */
        $metaData["title"] = ucfirst($slug) . ' - ' . Yii::$app->params['seo']['author'];
        $metaData["canonical"] = Url::base('https') . '/qaydalar/' . $slug;
        $metaData["page_type"] = "website";
        $metaData["amp_status"] = 0;
        $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];
        Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);
        /** Meta tags END */

        return $this->render('rules', [
            'slug' => $slug
        ]);
    }

    public function actionTinymce()
    {
        // Allowed origins to upload images
        $accepted_origins = array("http://localhost", "http://107.161.82.130", "http://codexworld.com");

// Images upload path
        $imageFolder = "upload/others/";
        reset($_FILES);
        $temp = current($_FILES);
        $photo = UploadedFile::getInstanceByName('file');
        if (is_uploaded_file($temp['tmp_name'])) {
//            if(isset($_SERVER['HTTP_ORIGIN'])){
//                // Same-origin requests won't set an origin. If the origin is set, it must be valid.
//                if(in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)){
//                    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
//                }else{
//                    header("HTTP/1.1 403 Origin Denied");
//                    return;
//                }
//            }

            // Sanitize input
            if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
                header("HTTP/1.1 400 Invalid file name.");
                return;
            }

            // Verify extension
            if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
                header("HTTP/1.1 400 Invalid extension.");
                return;
            }

            // Accept upload if there was no origin, or if it is an accepted origin
            $filetowrite = $imageFolder . $temp['name'];
//            move_uploaded_file($temp['tmp_name'], $filetowrite);

            $imageUpload = new ImageUpload();
            $uploadedFile = $imageUpload->saveFile($photo, [
                'path.save' => 'others'
            ]);

            // Respond to the successful upload with JSON.
            echo json_encode(array('location' => $uploadedFile));
        } else {
            // Notify editor that the upload failed
            header("HTTP/1.1 500 Server Error");
        }
    }

}