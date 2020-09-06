<?php  

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\models\SiteNews;
use backend\models\SiteNewsSearch;
use backend\models\SiteMenus;
use backend\models\SiteMenusSearch;
use backend\models\SiteGallery;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\ImageUpload; 
use backend\components\Functions;
use yii\web\UploadedFile;
use backend\controllers\MainController;

/**
 * NewsController implements the CRUD actions for SiteNews model.
 */
class NewsController extends MainController
{
    const TYPE = 3;
    public $customPath = 'news';
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['*'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SiteNews models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SiteNewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'customPath'   => $this->customPath,
        ]);
    }

    /**
     * Displays a single SiteNews model.
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
     * Creates a new SiteNews model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SiteNews();

        if (Yii::$app->request->post($model->getClassName()) && $model->load(Yii::$app->request->post())) {
            //$datetime = date('Y-m-d');
            //$model->datetime = $datetime;
            $date = $model->datetime;
            $hour = Yii::$app->request->post('hour');
            $model->datetime = $date." ".$hour;
            if(empty($model->slug)){
                $model->slug = Functions::slugify($model->headline,['transliterate' => true]);
            }
            $photos = UploadedFile::getInstances($model,'files');
            if($model->save())
            {
                if(!empty($photos))
                {
                    foreach($photos as $key => $photo)
                    {
                        $imageUpload = new ImageUpload();
                        if($key != 0)
                        {
                            $uploadedFile = $imageUpload->saveFile($photo,[
                                'path.save'=>$this->customPath,
                                'resize.img'=>[708,467],
                                'resize.thumb'=>[185,122]//110
                            ]);
                        }else{
                            $uploadedFile = $imageUpload->saveFile($photo,[
                                'path.save'=>$this->customPath,
                                'resize.img'=>[708,467],
                                'resize.thumb'=>[350,231]
                            ]);
                            $updatePhoto        = $this->findModel($model->id);
                            $updatePhoto->photo = $uploadedFile;
                            $updatePhoto->save(false);
                        }
                        if(!empty($uploadedFile))
                        {
                            $gallery             = new SiteGallery();
                            $gallery->photo      = $uploadedFile;
                            $gallery->connect_id = $model->id;
                            $gallery->type       = self::TYPE;
                            $gallery->main       = $key != 0 ? 0: 1;
                            $gallery->save();
                        }
                    }
                }
                Yii::$app->session->setFlash('success','Məlumat əlavə olundu');
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SiteNews model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ( $model->load(Yii::$app->request->post()) ) {
            $date = $model->datetime;
            $hour = Yii::$app->request->post('hour');
            $model->datetime = $date . " " . $hour;
            if ($model->save()){
                $deletedImages = $model->deletedImages;
                $mainImage = $model->mainImage;

                if (!empty($deletedImages)) {
                    if (strpos($deletedImages, ',')) {
                        $deletedImages = explode(',', $deletedImages);
                    }

                    $delete = SiteGallery::find()->where(['in', 'id', $deletedImages])->andWhere(['connect_id' => $model->id, 'type' => self::TYPE])->all();
                    if (!empty($delete)) {
                        foreach ($delete as $key => $val) {
                            $imageUpload = new ImageUpload();
                            $imageUpload->deleteFile([$this->customPath . '/' . $val['photo']]);
                            $imageUpload->deleteFile([$this->customPath . '/small/' . $val['photo']]);
                            if ($val['main'] == 1) {
                                $mainImage = null;
                                $updatePhoto = $this->findModel($model->id);
                                $updatePhoto->photo = '';
                                $updatePhoto->save(false);
                            }
                            $val->delete();
                        }
                    }
                }

                $photos = UploadedFile::getInstances($model, 'files');

                if (!empty($photos)) {
                    foreach ($photos as $key => $photo) {
                        $imageUpload = new ImageUpload();
                        if (empty($mainImage) && $key == 0) {
                            $uploadedFile = $imageUpload->saveFile($photo, [
                                'path.save' => $this->customPath,
                                'resize.img' => [708, 467],
                                'resize.thumb' => [350, 231]
                            ]);

                            $updatePhoto = $this->findModel($model->id);
                            $updatePhoto->photo = $uploadedFile;
                            $updatePhoto->save(false);

                        } else {
                            $uploadedFile = $imageUpload->saveFile($photo, [
                                'path.save' => $this->customPath,
                                'resize.img' => [708, 467],//420
                                'resize.thumb'=>[185,122]//110
                            ]);
                        }

                        if (!empty($uploadedFile)) {
                            $gallery = new SiteGallery();
                            $gallery->photo = $uploadedFile;
                            $gallery->connect_id = $model->id;
                            $gallery->type = self::TYPE;
                            $gallery->main = empty($mainImage) && $key == 0 ? 1 : 0;
                            $gallery->save();
                        }

                    }
                }
            }
            if($model->save())
            {
                Yii::$app->session->setFlash('success','Məlumatlar yeniləndi');
            }else{
                Yii::$app->session->setFlash('error','Xəta baş verdi');
            }

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'customPath' => $this->customPath
        ]);
    }

    /**
     * Deletes an existing SiteNews model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);

        $imageUpload = new ImageUpload();

        $imageUpload->deleteFile([$this->customPath.'/'.$model->photo]);
        $imageUpload->deleteFile([$this->customPath.'/small/'.$model->photo]);

        $gallery = SiteGallery::find()->where(['connect_id'=>$model->id,'type'=>self::TYPE])->all();

        if(!empty($gallery))
        {
            foreach($gallery as $key => $val)
            {
                $imageUpload->deleteFile([$this->customPath.'/'.$val['photo']]);
                $val->delete();
            }
        }

        if($model->delete()){
            Yii::$app->session->setFlash('success','Məlumat silindi.');
        }else{
            Yii::$app->session->setFlash('error','Məlumat silinmədi');
        }

        return $this->redirect(['index']);
    }

    public function actionDeletemore()
    {

        $ids = Yii::$app->request->post('del_check');

        if(!empty($ids))
        {
            foreach ($ids as $id)
            {
                $this->actionDelete($id);
            }
            Yii::$app->session->setFlash('success','Məlumatlar silindi.');
        }else{
            Yii::$app->session->setFlash('error','Heç bir seçim edilməyib.');
        }

        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);

    }

    /**
     * Finds the SiteNews model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SiteNews the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SiteNews::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
