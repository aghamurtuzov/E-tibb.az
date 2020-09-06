<?php



namespace backend\controllers;



use Yii;

use yii\filters\AccessControl;

use backend\models\SiteDoctors;

use backend\models\SiteEnterprises;

use backend\models\BaseUpload;

use backend\models\SitePromotions;

use backend\models\SitePromotionsSearch;

use yii\web\Controller;

use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;

use backend\components\ImageUpload;

use backend\components\Functions;

use yii\web\UploadedFile;

use backend\controllers\MainController;



/**

 * PromotionsController implements the CRUD actions for SitePromotions model.

 */

class PromotionsController extends MainController

{

    /**

     * {@inheritdoc}

     */

    public $customPath = 'promotions';

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

     * Lists all SitePromotions models.

     * @return mixed

     */

    public function actionIndex()

    {

        $searchModel = new SitePromotionsSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);



        return $this->render('index', [

            'searchModel' => $searchModel,

            'dataProvider' => $dataProvider,

            'customPath' => $this->customPath,

        ]);

    }





    /**

     * Creates a new SitePromotions model.

     * If creation is successful, the browser will be redirected to the 'view' page.

     * @return mixed

     */

    public function actionCreate()

    {

        $model = new SitePromotions();



        if ($model->load(Yii::$app->request->post())) {

            //print_r(Yii::$app->request->post()); die();

            $mode = 'add';

            $model->date_start  = date("Y-m-d",strtotime($model->date_start));

            $model->date_end    = date("Y-m-d",strtotime($model->date_end));

            if(!empty($model->organizer)){

                $model->connect_id = null;

                $model->type       = null;

            }

            if ($model->save()) {

                if ($model->type == 1) {

                    $model->change_Promotion($model->connect_id,$model->type,$mode);

                }else if($model->type == 2){

                    $model->change_Promotion($model->connect_id,$model->type,$mode);

                }

                $photos = UploadedFile::getInstances($model, 'photo');

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

                                'resize.thumb'=>[185,122]

                            ]);

                        }else{

                            $uploadedFile = $imageUpload->saveFile($photo,[

                                'path.save'=>$this->customPath,

                                'resize.img'=>[350,231],

                                'resize.thumb'=>[401,265]

                            ]);

                            $updatePhoto        = $this->findModel($model->id);

                            $updatePhoto->photo = $uploadedFile;

                            $updatePhoto->save(false);

                        }



                    }

                }

                return $this->redirect(['index']);

            }

        }

        return $this->render('create', [

            'model' => $model,

        ]);

    }



    /**

     * Updates an existing SitePromotions model.

     * If update is successful, the browser will be redirected to the 'view' page.

     * @param integer $id

     * @return mixed

     * @throws NotFoundHttpException if the model cannot be found

     */

    public function actionUpdate($id)

    {

        $model    = $this->findModel($id);

        $oldModel = $this->findModel($id);



        $mode = 'edit';

        $edited_id = $model->connect_id;



        if($model->load(Yii::$app->request->post()) && $model->save())

        {

            $model->date_start  = date("Y-m-d",strtotime($model->date_start));

            $model->date_end    = date("Y-m-d",strtotime($model->date_end));

            if ($model->type == 1) {

                if($edited_id!=$model->connect_id)

                    $model->change_Promotion($model->connect_id,$model->type,$mode,$edited_id);

            }else if($model->type == 2){

                if($edited_id!=$model->connect_id)

                    $model->change_Promotion($model->connect_id,$model->type,$mode,$edited_id);

            }



            /** Main image */

            $photos = UploadedFile::getInstances($model,'photo');

            if(!empty($photos))
            {
                foreach($photos as $key => $photo)
                {
                    $imageUpload = new ImageUpload();
                    if(empty($mainImage) && $key == 0)
                    {
                        $uploadedFile = $imageUpload->saveFile($photo,[
                            'path.save'=>$this->customPath,
                            'resize.img'=>[350,231],
                            'resize.thumb'=>[401,265]
                        ]);
                        $updatePhoto        = $this->findModel($model->id);
                        $updatePhoto->photo = $uploadedFile;
                        $updatePhoto->save(false);
                    }else{
                        $uploadedFile = $imageUpload->saveFile($photo,[
                            'path.save'=>$this->customPath,
                            'resize.img'=>[708,467],
                            'resize.thumb'=>[185,122]
                        ]);
                    }
                }
            }else{
                $updatePhoto        = $this->findModel($id);
                $updatePhoto->photo = $oldModel->photo;
                $updatePhoto->save(false);
            }
            return $this->redirect(['index']);
        }



        return $this->render('update', [

            'model' => $model,

            'customPath' => $this->customPath

        ]);

    }



    /**

     * Deletes an existing SitePromotions model.

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







        $this->findModel($id)->delete();



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

     * Finds the SitePromotions model based on its primary key value.

     * If the model is not found, a 404 HTTP exception will be thrown.

     * @param integer $id

     * @return SitePromotions the loaded model

     * @throws NotFoundHttpException if the model cannot be found

     */

    protected function findModel($id)

    {

        if (($model = SitePromotions::findOne($id)) !== null) {

            return $model;

        }

        throw new NotFoundHttpException('The requested page does not exist.');

    }



    public function actionList()
    {
        $model  = new SitePromotions();
        $out    = array();
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $request    =   Yii::$app->request;
        if ($request->isPost){
            $type = $request->post('get_option');
            if($type == 1){
                $doctor = SiteDoctors::find()->orderBy(['name' => SORT_ASC])
                    ->asArray()
                    ->all();
                foreach ($doctor as $d){
                    //echo '<option value="'.$d['id'].'">'.$d['name'].'</option>';
                    $out [$d['id']]['key']  = $d['id'];
                    $out [$d['id']]['name'] = $d['name'];
                }
            }else{
                $enterprises = SiteEnterprises::find()->orderBy(['name' => SORT_ASC])
                    ->asArray()
                    ->all();
                foreach ($enterprises as $e){
                    $out [$e['id']]['key']   = $e['id'];
                    $out [$e['id']]['name']  = $e['name'];
                }
            }
        }
        return $out;
    }

}

