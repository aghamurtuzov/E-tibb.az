<?php
namespace frontend\controllers;

use backend\components\ImageUpload;
use frontend\models\SiteAds;
//use http\Url;
use Yii;
use yii\data\Pagination;
use backend\components\Functions;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\helpers\Url;
use frontend\components\SeoLib;

/**
 * Site controller
 */
class BloodController extends MainController
{

    public $layout = 'static';
    public $customPath = 'ads';
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
        $this->seo   = new SeoLib();
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionBlood()
    {
        $bloodType = new SiteAds();
        $keyword = $blood_type = null;
        if(isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $keyword = Functions::xss_clean($_GET['keyword']);
        }

        if(isset($_GET['blood_type']) && !empty($_GET['blood_type'])) {
            $blood_type = Functions::xss_clean($_GET['blood_type']);
        }

        $pages = new Pagination();
        $pages->totalCount      = SiteAds::getAdsCount(1, $keyword, $blood_type);
        $pages->defaultPageSize = 12;
        $data = SiteAds::getAds(1, $pages->limit, $pages->offset, $keyword, $blood_type);
        $dataSearch['keyword'] = $keyword;
        $dataSearch['blood_type'] = $blood_type;
        $select = $bloodType->getBloodTypes();
        Yii::$app->params['header.tags'] = $this->seo->make_page_header($this->getMetaData('Qan ver - '.Yii::$app->params['seo']['author'], "/qanver"));

        return $this->render('index', [
            'data' => $data,
            'dataSearch' => $dataSearch,
            'pages' => $pages,
            'select' => $select
        ]);
    }

    public function actionIndex()
    {
        $ads_object    = new SiteAds();
        $request        = Yii::$app->request;
        $ads_id        = intval($request->get('id'));
        $slug_link      = $request->get('slug');

        if(!empty($ads_id)) {
            $single_ads    = $ads_object->getSingleAds(1,$ads_id);
            if(!empty($single_ads)){
                Yii::$app->params['header.tags'] = $this->seo->make_page_header($this->getMetaData('Qan ver - '.Yii::$app->params['seo']['author'],"/qanver/".$slug_link."-".$ads_id));
                return $this->render('view',[
                    "data" => $single_ads,
                    'customPath'  => $this->customPath,
                ]);
            }
        }

        throw new NotFoundHttpException(Yii::t('app','Axtardığınız səhifə mövcud deyil.'));
    }

    public function actionAddBlood() {
        $model = new SiteAds();
        $errors = [];
        $success = false;

        if ($model->load(Yii::$app->request->post())) {
            $photo = UploadedFile::getInstances($model, 'image');

            if (count($photo)>0) {
                $imageUpload = new ImageUpload();
                $uploadedFile = $imageUpload->saveFile($photo[0], [
                    'path.save' => 'ads',
                    'resize.img' => [350, 231],
                    'resize.thumb' => [401, 265]
                ]);

                if($uploadedFile) {
                    $model->image = $uploadedFile;
                } else {
                    $model->addError('image', 'Şəkil üçün yalnış tip. Yalnız: png,jpg');
                }
            }

            if(!$model->blood_type>0){
                $model->addError('blood_type', 'Qan qrupu boş olmamalıdır');
            }

            $model->validate();

            if(count($model->errors)==0) {
                $model->user_id = (Yii::$app->user->isGuest) ? 0 : Yii::$app->user->id;;
                $model->is_blood = 1;
                $model->phone = '994'.substr($model->phone,1);
                $model->slug = Functions::slugify($model['title'], ['transliterate' => true]);
                $model->type = (Yii::$app->user->id != null ? Yii::$app->user->identity->type : '');

                if($model->save(false)) {
                    $success = true;
                    Yii::$app->session->setFlash("success", "Elanınız əlavə olundu, təsdiqləndikdən sonra aktiv olunacaq.");
                    return $this->redirect('qanver');
                }
                else {
                    Yii::$app->session->setFlash("error", "Xəta");
                }
            } else {
                $errors = $model->errors;
            }
        } else {
            $errors = $model->errors;
        }

        Yii::$app->params['header.tags'] = $this->seo->make_page_header($this->getMetaData('Qan elani ver - '.Yii::$app->params['seo']['author'],"/qan-elani-ver"));
        return $this->render('addBlood',[
            'model' => $model,
            'errors' => $errors,
            'success' => $success
        ]);
    }

    protected function findModel($id) {
        if (($model = SiteAds::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}