<?php

namespace frontend\controllers;

use backend\components\Functions;
use backend\components\ImageUpload;
use frontend\models\SiteOrders;
use frontend\models\SiteTransaction;
use Yii;
use frontend\models\SiteAds;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * AdsController implements the CRUD actions for SiteAds model.
 */
class AdsController extends Controller
{

    public $layout = 'static';
    public $customPath = 'ads';
    const  TYPE = 0;
    public $seo;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'delete', 'view'],
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            if (!Yii::$app->user->isGuest) {
                                if (Yii::$app->user->identity->type != 1 and Yii::$app->user->identity->type != 2) {
                                    $this->redirect("");
                                    return false;
                                } elseif (Yii::$app->user->identity->status == 2 and Yii::$app->user->identity->type == 1) {
                                    $this->redirect(Url::to(['/']));
                                    return false;
                                } elseif (Yii::$app->user->identity->status == 2 and Yii::$app->user->identity->type == 2) {
                                    $this->redirect(Url::to(['/']));
                                    return false;
                                } else {
                                    return true;
                                }
                            }
                        },
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

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

//        $this->seo   = new SeoLib();

        return parent::beforeAction($action);

    }


    /**
     * Lists all SiteAds models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => SiteAds::find(),
        ]);

        return $this->render('index', [
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

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->user_id = Yii::$app->user->id;
            $model->slug = Functions::slugify($model['title'], ['transliterate' => true]);
            $model->type = Yii::$app->user->identity->type;
            $type_date = '';
            if ($model->premium_type == 1) $type_date = date('Y-m-d', strtotime('+3 day', time()));
            elseif ($model->premium_type == 2) $type_date = date('Y-m-d', strtotime('+7 day', time()));
            elseif ($model->premium_type == 3) $type_date = date('Y-m-d', strtotime('+30 day', time()));
            $model->premium_expiry = $type_date;
            if ($model->save()) {
                $photo = UploadedFile::getInstances($model, 'image');
                if (!empty($photo)) {
                    $imageUpload = new ImageUpload();
                    $uploadedFile = $imageUpload->saveFile($photo[0], [
                        'path.save' => $this->customPath,
                        'resize.img' => [350, 231],
                        'resize.thumb' => [401, 265]
                    ]);

                    $updatePhoto = $this->findModel($model->id);
                    $updatePhoto->image = $uploadedFile;
                    $updatePhoto->save(false);
                }
                Yii::$app->session->setFlash("success", "Elanınız ödəniş etdikdən sonra əlavə olunacaqdır.");

            } else {
                Yii::$app->session->setFlash("error", "Xəta");

            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionPayCash()
    {
        $id = Yii::$app->request->get('id', 0);
        $order = new SiteOrders();
        $order->ad_id = $id;
        $order->user_id = Yii::$app->user->id;
        $order->type = Yii::$app->user->identity->type;
        $order->description = "test";
        $order->price = 777;
        $order->pay_date = date('Y-m-d H:i:s', time());
        $order->pay_method = 0;
        if ($order->validate() && $order->save()) {
            Yii::$app->session->setFlash("success", "Ödənişiniz təsdiq olunduqdan elanınız sonra əlavə olunacaqdır.");
            return $this->redirect(['view', 'id' => $id]);
        } else {
            Yii::$app->session->setFlash("error", "Xəta");
            return $this->redirect(['view', 'id' => $id]);
        }


    }

    public function actionPayOnline()
    {
        $id = Yii::$app->request->get('id', 0);
        $premium_type = Yii::$app->request->get('premium_type', 0);
        if ($id > 0) {

            $order_id = date('ymdhis') . rand(100, 999);
            $user_id = Yii::$app->user->id;
            $user_type = Yii::$app->user->identity->type;

            $totalPrice = 777;

            $log_data["ad_id"] = $id;
            $log_data['price'] = 777;
            $log_data['premium_type'] = $premium_type;
            $log_data = json_encode($log_data);

            $description = $id . " elanin " . $this->replacePremiumType($premium_type);

            $saveOrder = $this->saveSiteOrder($id, $user_id, $user_type, $totalPrice, $description);
            $insertTransaction = $this->saveTransaction($order_id, $user_id, $log_data, $totalPrice);
            if (!empty($insertTransaction)) {
                $description = strtolower($description);
                $getPayment = new MilliCardController($totalPrice, $order_id, $description);
                $getPayment->pay();
            }
        }
    }

    public function saveTransaction($order_id, $connect_id, $log_data, $total_price)
    {
        $transaction = new SiteTransaction();
        $transaction->order_id = $order_id;
        $transaction->connect_id = $connect_id;
        $transaction->type = Yii::$app->user->identity->type;
        $transaction->order_type = self::TYPE;
        $transaction->log_data = $log_data;
        $transaction->total_price = $total_price;
        $transaction->created_date = date('Y-m-d H:i:s');
        $transaction->payment_method = 1;
        $transaction->status = 0;
        $result = $transaction->save();
        return $result;
    }

    private function saveSiteOrder($ad_id, $user_id, $user_type, $totalPrice, $description)
    {
        $order = new SiteOrders();
        $order->ad_id = $ad_id;
        $order->user_id = $user_id;
        $order->type = $user_type;
        $order->description = $description;
        $order->price = $totalPrice;
        $order->pay_date = date('Y-m-d H:i:s', time());
        $order->pay_method = 1;
        if ($order->validate() && $order->save()) {
            return true;
        }
    }

    private function replacePremiumType($num)
    {
        $types = [0 => 'Yoxdur', 1 => '3 gün', 2 => '1 həftə', 3 => '1 ay'];
        return str_replace($num, $types[$num], $types[$num]);
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
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->slug = Functions::slugify($model['title'], ['transliterate' => true]);
            $type_date = '';
            if ($model->premium_type == 1) $type_date = date('Y-m-d', strtotime('+3 day', time()));
            elseif ($model->premium_type == 2) $type_date = date('Y-m-d', strtotime('+7 day', time()));
            elseif ($model->premium_type == 3) $type_date = date('Y-m-d', strtotime('+30 day', time()));
            $model->premium_expiry = $type_date;
            if ($model->save()) {
                $photo = UploadedFile::getInstances($model, 'image');
                if (!empty($photo)) {
                    $imageUpload = new ImageUpload();
                    $uploadedFile = $imageUpload->saveFile($photo[0], [
                        'path.save' => $this->customPath,
                        'resize.img' => [350, 231],
                        'resize.thumb' => [401, 265]
                    ]);

                    $updatePhoto = $this->findModel($model->id);
                    $updatePhoto->image = $uploadedFile;
                    $updatePhoto->save(false);
                }
                Yii::$app->session->setFlash("success", "Elanınız yoxlanıldıqdan sonra əlavə olunacaqdır.");

            } else {
                Yii::$app->session->setFlash("error", "Xəta");

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
