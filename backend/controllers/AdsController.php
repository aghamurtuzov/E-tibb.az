<?php


namespace backend\controllers;

use backend\components\Functions;
use backend\components\ImageUpload;
use Yii;
use backend\models\SiteAds;

use backend\models\SiteAdsSearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use backend\controllers\MainController;

/**
 * AdsController implements the CRUD actions for SiteAds model.
 */
class AdsController extends MainController

{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


//    customs
    public function actionChangeStatus()
    {
        if(Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id', 0);
            $status = Yii::$app->request->post('status', 0);
            $data = $this->findModel($id);
            $data->status = ($status == 0 ? 1 : 0);
            if( $data->save())
            return $this->asJson('ok');
        }
    }

    /**
     * Lists all SiteAds models.
     * @return mixed
     */

    public function actionIndex()
    {

        $searchModel = new SiteAdsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    /**
     * Displays a single SiteAds model.
     * @param integer $id

     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SiteAds model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SiteAds();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SiteAds model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id

     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $image = $model->image;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//            echo '<pre>';var_dump($image );die;

            $model->slug = Functions::slugify($model['title'], ['transliterate' => true]);
            $type_date = '';
            if ($model->premium_type == 1) $type_date = date('Y-m-d', strtotime('+3 day', time()));
            elseif ($model->premium_type == 2) $type_date = date('Y-m-d', strtotime('+7 day', time()));
            elseif ($model->premium_type == 3) $type_date = date('Y-m-d', strtotime('+30 day', time()));
            $model->premium_expiry = $type_date;
            $model->image = $image;

            if ($model->save()) {
                $photo = UploadedFile::getInstances($model, 'image');
                if (!empty($photo)) {
                    $imageUpload = new ImageUpload();
                    $uploadedFile = $imageUpload->saveFile($photo[0], [
                        'path.save' => 'ads',
                        'resize.img' => [350, 231],
                        'resize.thumb' => [401, 265]
                    ]);

                    $updatePhoto = $this->findModel($model->id);
                    $updatePhoto->image = $uploadedFile;
                    $updatePhoto->save(false);
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SiteAds model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id

     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SiteAds model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id

     * @return SiteAds the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {

        if (($model = SiteAds::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}