<?php
namespace frontend\controllers;

use frontend\models\Doctor;
use frontend\models\Enterprise;
use frontend\models\LoginForm;
use frontend\models\SiteCalling;
use frontend\models\User;
use frontend\models\SignupForm;
use frontend\models\UserSettingsForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use frontend\components\Menu;
use yii\helpers\ArrayHelper;
use backend\components\ImageUpload;
use yii\web\UploadedFile;
use \frontend\models\SiteConsultationModel;
use frontend\models\SiteDoctorsModel;
use backend\components\Functions;
use frontend\models\PromotionModel;
/**
 * Auth controller
 */
class ProfileController extends Controller
{

    public $menus;
    public $layout = 'static';
    public $customPath = 'users';
    const TYPE = 0;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions'=> ['index'],
                        'allow' => true
                    ],
                    [
                        'actions'=> ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['settings','index'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action){
                            if(!Yii::$app->user->isGuest)
                            {
                                if(Yii::$app->user->identity->type!= self::TYPE)
                                {
                                    $this->redirect("");
                                    return false;
                                }else{
                                    return true;
                                }
                            }
                        }
                    ]
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

//    public function behaviors()
//    {
//        return [
//            'access' => [
//                'class' => \yii\filters\AccessControl::className(),
//                'only' => ['index','settings','logout'],
//                'rules' => [
//
//                    // allow authenticated users
//                    [
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                    // everything else is denied
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
//        ];
//    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ]
        ];
    }

    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(Yii::$app->params["site.url"]);
        }

        $menus = ArrayHelper::toArray(new Menu());

        $this->menus = $menus['list'];
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $id = Yii::$app->user->id;
        $user_type = 0;
        $text = 'Xoş gəlmisiniz';

        if(isset(Yii::$app->user->identity->type)){
            $user_type = Yii::$app->user->identity->type;
        }

        if($user_type == 1)
        {
            return $this->redirect(Yii::$app->params["site.url"]."admin/doctor#/setting");
//            if(Yii::$app->user->identity->status==2){
//                $text = 'Sizin profiliniz təsdiqlənmək üçün göndərilmişdir. Yaxın zamanlarda sizinlə əlaqə saxlanılacaq';
//                return $this->redirect("/hekim-qeydiyyat-paketler");
//            }elseif(Yii::$app->user->identity->status==1){
//                return $this->redirect("/profil/sual-cavab");
//            }
        }elseif($user_type == 2) {
            return $this->redirect(Yii::$app->params["site.url"]."admin/enterprise");
//            if(Yii::$app->user->identity->status==2){
//                $text = 'Sizin profiliniz təsdiqlənmək üçün göndərilmişdir. Yaxın zamanlarda sizinlə əlaqə saxlanılacaq';
//            }elseif(Yii::$app->user->identity->status==1){
//                return $this->redirect("/profil/aksiyalar");
//            }
        }

        $data = User::getUserData($id);

        // Reservations
        $reserves = new SiteCalling();
        $reserves = $reserves->getReserveByUser($id);
        if(!empty($reserves)) {
            foreach($reserves as $key => $reserve) {
                $doctorSpec = SiteDoctorsModel::getDoctorSpecialist($reserve['doctor_id']);
                if($doctorSpec) {
                   $reserves[$key]['doctorSpec'] = $doctorSpec;
                }
                $reserves[$key]['date'] = explode(" ",Functions::getDatetime($reserve['date']));
                $workPlace = SiteDoctorsModel::getWorkplaces($reserve['doctor_id']);
                $reserves[$key]['workPlace'] = (!empty($workPlace)) ? $workPlace[0]['address'] : "-";
            }
        }

        return $this->render('index', [
            'text' => $text,
            'data' => $data,
            'customPath' => $this->customPath,
            'questions' => SiteConsultationModel::getQuestionsByUser($id),
            'reserves' => $reserves,
            'ownPromotions' => PromotionModel::getUserPromotionsProfile($id),
            'promotions' => PromotionModel::getPromotionsProfile(10),
            'used_promotions' => PromotionModel::getPromotionsUsed($id),
            'promotions2' => PromotionModel::getNotExpiredPromotions()
        ]);
    }

    public function actionSettings()
    {
        $model = new UserSettingsForm();

        $errors = [];

        if(isset(Yii::$app->user->id))
        {
            $id = intval(Yii::$app->user->id);
            $model = UserSettingsForm::findOne($id);
            if($model->load(Yii::$app->request->post()))
            {
                $photo = UploadedFile::getInstances($model, 'photo');
                if(count($photo)>0)
                {
                    $imageUpload = new ImageUpload();
                    $uploadedFile = $imageUpload->saveFile($photo[0], [
                        'path.save' => 'users',
                        'resize.img' => [150, 150],
                    ]);
                    if($uploadedFile) {
                        $model->photo = $uploadedFile;
                    } else {
                        $model->addError('photo', 'Şəkil üçün yalnış tip. Yalnız: png,jpg');
                    }
                }

                if(!empty($model->newpass) && empty($model->pass))
                {
                    $model->addError('pass', 'Köhnə şifrəni daxil edin');
                }

                $model->validate(null,false);

                if(count($model->errors)==0) {
                    $model->phone_prefix = 994;
                    $model->password = !empty($model->pass) && strlen($model->newpass)>=6 ? Yii::$app->security->generatePasswordHash($model->newpass) : Yii::$app->user->identity->password;
                    if($model->save()){
                        Yii::$app->session->setFlash("success","İstifadəçi məlumatları dəyişdirildi");
                    } else {
                        Yii::$app->session->setFlash('error', "Xəta baş verdi!");
                    }
                } else {
                    $errors = $model->errors;
                }
            }
            else
            {
                $errors = $model->errors;
            }
        }

        return $this->render('settings',[
            'model' => $model,
            'errors' => $errors
        ]);
    }

    public function actionLogout()
    {
        if(Yii::$app->request->isPost)
        {
            if(Yii::$app->user->logout()){
                return $this->goHome();
            }
        }
    }


}
