<?php

namespace frontend\controllers;

use frontend\models\MainModel;

use frontend\models\SiteDoctorsModel;
use frontend\models\SiteSliderModel;
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
use frontend\components\Specialist;

use frontend\components\News;

use backend\components\Functions;

use frontend\models\PromotionModel;

use yii\helpers\Url;

use frontend\components\SeoLib;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $menus;
    public $spc;
    public $seo;
    public $data = null;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'accept-password-reset', 'success-password-reset', 'request-password-reset', 'reset-password', 'error', 'tinymce'],
                        'allow' => true
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

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
        $menus = ArrayHelper::toArray(new Menu());
        $specialists = ArrayHelper::toArray(new Specialist());
        $this->menus = $menus['list'];
        $this->spc = $specialists['specialists'];
        $this->seo = new SeoLib();
        return parent::beforeAction($action);
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */

    public function actionIndex()
    {
        $mainModel = new MainModel();
        $promotionModel = new PromotionModel();
        $data['specialists'] = $this->spc['id'];
        $data['promotions'] = $mainModel->getPromotions();
        $data['vip_doctors'] = $mainModel->getPremiumDoctors();
        $data['comments'] = $mainModel->getSiteComments(5);
        $data['videos'] = $mainModel->getVideos();
        //print_r($data['vip_doctors']); exit();
        if (!empty($data['promotions'])) {
            foreach ($data['promotions'] as $key => $val) {
                $data['promotions'][$key]['organizer'] = empty($val['organizer']) ? $promotionModel->getConnectName($val['type'], $val['connect_id'])['name'] : $val['organizer'];
            }
        }
        $newsList = ArrayHelper::toArray(new News());
        $data['newsList'] = $newsList['newsList'];
        $data['newsListBlog'] = $mainModel->getNewsBlog(5);
        $data['menus'] = $this->menus;
        Yii::$app->view->params['header_class'] = 'carousel-over';
        $firstObjectCat = isset($data['menus']['type'][2][1]) ? $data['menus']['type'][2][1] : null;

//        if (!empty($firstObjectCat)) {
//            $settings = json_decode($firstObjectCat["settings"], true);
//            $data['enterprises'] = $mainModel->getEnterprises($firstObjectCat['id'], $settings['template'] == 0 ? 8 : 4);
//        }

        /** Meta tags */
        $metaData["canonical"] = Url::base('https');
        $metaData["page_type"] = "website";
        $metaData["amp_status"] = 0;
        $metaData["robots_action"] = Yii::$app->params['seo']["site_index_status"];

        Yii::$app->params['header.tags'] = $this->seo->make_page_header($metaData);
        /** Meta tags END */

        //breadcrumb data
//            $header_datas =array();
//            $header_datas['title']       = Yii::$app->params['seo']['site_title'];
//            $header_datas['description'] = Yii::$app->params['seo']['site_description'];
//            $header_datas['breadcrumb']  = array();
//            $header_datas['breadcrumb'][0]['link'] = Url::base('https');
//            $header_datas['breadcrumb'][0]['text'] = 'Ana Səhifə';
//
//            Yii::$app->params['breadcrumb.schema'] = $this->seo->make_page_breadcrumb($header_datas['breadcrumb']);

        //Yii::$app->params['site_google_searcbox.schema'] = $this->seo->make_google_searchbox();

        $data['sliders'] = SiteSliderModel::getSlider(1);

        //end
        return $this->render('index', ['data' => $data]);

    }

    public function actionYeniPanel()
    {
        return 'OK';
    }

//    public function actionEnterprise()

//    {

//        return $this->render('enterprise');

//    }


    /**
     * Logs in a user.
     *
     * @return mixed
     */

    public function actionLogin()

    {

        //return $this->redirect(Url::to['qeydiyyat']);

        /*

        if (!Yii::$app->user->isGuest) {

            return $this->goHome();

        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            return $this->goBack();

        } else {

            $model->password = '';

            return $this->render('login', [

                'model' => $model,

            ]);

        }

        */

    }


    /**
     * Logs out the current user.
     *
     * @return mixed
     */

    public function actionLogout()

    {

        Yii::$app->user->logout();

        return $this->redirect(Yii::$app->params["site.url"]);

    }



    /**
     * Displays contact page.
     *
     * @return mixed
     */

//    public function actionContact()

//    {

//        $model = new ContactForm();

//        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

//            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {

//                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');

//            } else {

//                Yii::$app->session->setFlash('error', 'There was an error sending your message.');

//            }

//            return $this->refresh();

//        } else {

//            return $this->render('contact', [

//                'model' => $model,

//            ]);

//        }

//    }


//    public function actionMessage()

//    {

//        $this->layout = 'enterprise';

//        return $this->render('message', [

//        ]);

//    }

    /**
     * Displays about page.
     *
     * @return mixed
     */

//    public function actionAbout()

//    {

//        return $this->render('about');

//    }

    /**
     * Signs user up.
     *
     * @return mixed
     */

//    public function actionSignup()

//    {

//        $model = new SignupForm();

//        if ($model->load(Yii::$app->request->post())) {

//            if ($user = $model->signup()) {

//                if (Yii::$app->getUser()->login($user)) {

//                    return $this->goHome();

//                }

//            }

//        }

//        return $this->render('signup', [

//            'model' => $model,

//        ]);

//    }


    public function actionAcceptPasswordReset()

    {

        $this->layout = 'static';

        return $this->render('acceptPasswordReset');

    }


    public function actionSuccessPasswordReset()

    {

        $this->layout = 'static';

        return $this->render('successPasswordReset');

    }


    /**
     * Requests password reset.
     *
     * @return mixed
     */

    public function actionRequestPasswordReset()

    {


//        if(isset($_COOKIE['aaa']))
//        {
//            phpinfo();
//        }

        $this->layout = 'static';

        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post())) {
            $model->validate();
//            $model->phone_number = '994' . substr($model->phone_number, 1);
            if ($model->sendPhone()) {

                Yii::$app->session->setFlash('success-pass-reset', 'Şifrəni yeniləmək üçün telefon nömrənizə link göndərilmişdir.');

                return $this->redirect(['site/request-password-reset']);

            }

        }

        return $this->render('requestPasswordResetToken', [

            'model' => $model,

        ]);

    }


    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */

    public function actionResetPassword($token)

    {

        $this->layout = 'static';

        try {

            $model = new ResetPasswordForm($token);


        } catch (InvalidParamException $e) {

            throw new BadRequestHttpException($e->getMessage());

        }


        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {

            Yii::$app->session->setFlash('success-pass-reset', 'Şifrəniz uğurla yeniləndi. Hesabınıza daxil ola bilərsiniz');

            return $this->redirect(['/parol-yenilenmesi']);

        }


        return $this->render('resetPassword', [

            'model' => $model,

        ]);


    }



    public function actionTinymce()
    {
        echo "asdasd";
    }

}

