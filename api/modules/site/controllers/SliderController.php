<?php

namespace api\modules\site\controllers;

use yii;
use yii\web\UploadedFile;
use api\components\Pagination;
use api\components\ImageUpload;
use api\components\Functions;
//use api\models\SiteMenus;
//use api\modules\general\models\NewsApiModel;
//use api\modules\general\models\SiteNews;
//use api\modules\general\controllers\MainController;
use api\models\SiteSlider;
use api\models\SliderApiModel;

/**
 * Slider API
 */

class SliderController extends MainController
{

    public $modelClass = '';
    public $customPath = 'slider';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * https://e-tibb.az/api/site/slider
     * id=1
     * page=1
     * count=5
     */
    public function actionIndex()
    {

        exit('ok');
        $model = new SliderApiModel();
        $status = SiteSlider::get_Status();

        $totalCount = $model->SliderCount();

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $model->Slider($limits);

        if(!empty($list))
        {
            foreach($list as $key => $val)
            {
                $list[$key]['status_name'] = $status[$list[$key]['status']];
            }
        }

        $data['list'] = $this->ResultList($list,'slider');

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages,$data);

        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Slider info
     * https://e-tibb.az/api/general/slider/info/1013
     */
    public function actionInfo()
    {
        $id = intval(Yii::$app->request->get('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $status = SiteSlider::get_Status();

        /** Slider info */
        $info = SiteSlider::find()->where(['id' => $id])->asArray()->one();
        if(empty($info))
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $info['status_name'] = $status[$info['status']];

        $info = $this->ResultList($info,'slider');

        unset($info['thumb']);

        return $this->response(200,"Məlumat mövcuddur",$info);

    }

    /**
     * Slider yaratmaq
     * https://e-tibb.az/api/general/slider/create
     * name
     * photo
     * text1
     * text2
     * text3
     * url
     * status
     */
    public function actionCreate()
    {
        $model = new SiteSlider();
        $post = Yii::$app->request->post();

        Yii::$app->db->schema->refresh();

        if(!empty($post) && !empty($post) && $model->load($post,''))
        {

            $model->validate();

            /** Check Main image **/
            $photo = UploadedFile::getInstanceByName('photo');
            if(empty($photo))
            {
                $model->addError('photo','Şəkil elave edin');
            }else{
                $imageUpload = new ImageUpload();
                if(!$imageUpload->validate($photo))
                {
                    $model->addError('photo','Şəkilin yüklənməsi zamanı xəta baş verdi');
                }
            }

            if(!empty($model->errors))
            {
                return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi',$model->errors);
            }

            if($model->save())
            {

                /** Main image **/
                if(!empty($photo))
                {
                    $imageUpload = new ImageUpload();
                    $uploadedFile = $imageUpload->saveFile($photo, [
                        'path.save' => $this->customPath,
                        //'resize.img'=>[350,231],
                        //'resize.thumb'=>[401,265]
                    ]);
                    $updatePhoto = $model;
                    $updatePhoto->photo = $uploadedFile;
                    $updatePhoto->save(false);
                }

                return $this->response(200,'Məlumat uğurla əlavə olundu');
            }

        }

        return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi');
    }

    /**
     * Slider duzelis et
     * https://e-tibb.az/api/general/slider/edit
     * deletedImages 0 | 1
     */
    public function actionEdit()
    {
        $id = intval(Yii::$app->request->post('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $model    = $this->findModel($id);
        $oldModel = $this->findModel($id);
        $post     = Yii::$app->request->post();

        if(!empty($post) && !empty($post) && $model->load($post,''))
        {
            $model->validate();

            /** Check main image */
            $deletedImages = $model->deletedImages;
            $photo = UploadedFile::getInstanceByName('photo');
            if(empty($photo))
            {
                if(empty($oldModel->photo) || !empty($deletedImages))
                {
                    $model->addError('photo','Şəkil elave edin');
                }
                $model->photo = $oldModel->photo;
            }else{
                $imageUpload = new ImageUpload();
                if(!$imageUpload->validate($photo))
                {
                    $model->addError('photo','Şəkilin yüklənməsi zamanı xəta baş verdi');
                }
            }

            /** Delete main image */
            if(empty($model->errors))
            {
                if(!empty($deletedImages))
                {
                    if(!empty($oldModel->photo))
                    {
                        $imageUpload = new ImageUpload();
                        $imageUpload->deleteFile([$this->customPath.'/'.$oldModel->photo]);
//                        $imageUpload->deleteFile([$this->customPath.'/small/'.$oldModel->photo]);
                        $updatePhoto = $this->findModel($model->id);
                        $updatePhoto->photo = '';
                        $updatePhoto->save(false);
                    }
                }
            }

            if(!empty($model->errors))
            {
                return $this->response(400,'Məlumatın yenilənməsi zamanı xəta baş verdi',$model->errors);
            }

            if($model->save(false))
            {

                /** Main image **/
                if(!empty($photo))
                {
                    $imageUpload = new ImageUpload();
                    $uploadedFile = $imageUpload->saveFile($photo, [
                        'path.save' => $this->customPath,
                        //'resize.img'=>[350,231],
                        //'resize.thumb'=>[401,265]
                    ]);
                    $updatePhoto = $model;
                    $updatePhoto->photo = $uploadedFile;
                    $updatePhoto->save(false);
                }

                return $this->response(200,'Məlumat uğurla yeniləndi');

            }
        }

        return $this->response(400,'Məlumatın yenilənməsi zamanı xəta baş verdi');
    }

    /**
     * Slider status
     * https://e-tibb.az/api/general/slider/status
     */
    public function actionStatus()
    {
        $result = SiteSlider::get_Status();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Slider sil
     * https://e-tibb.az/api/general/news/del
     * ids array
     */
    public function actionDel()
    {
        $ids = Yii::$app->request->post('ids');
        if(empty($ids))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        foreach($ids as $key => $id)
        {
            $del = SiteSlider::findOne($id);
            if(empty($del))
            {
                return $this->response(400,"Məlumatın silinməsi zamanı xəta baş verdi");
            }else{
                $del->delete();
            }
        }
        return $this->response(200,"Melumat(lar) silindi");
    }

    protected function findModel($id)
    {
        if(($model = SiteSlider::findOne($id)) !== null){
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

}