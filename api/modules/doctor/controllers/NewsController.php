<?php

namespace api\modules\doctor\controllers;

use api\models\AdminDoctorLog;
use api\modules\doctor\models\search\BlogSearch;
use yii;
use api\models\AdminLog;
use yii\web\UploadedFile;
use api\components\Pagination;
use api\components\ImageUpload;
use api\components\Functions;
use api\models\SiteMenus;
use api\modules\doctor\models\NewsApiModel;
use api\modules\doctor\models\SiteNews;
use api\modules\doctor\controllers\MainController;
use api\models\SitePhotoGallery;
use api\models\PhotoGalleryApiModel;
/**
 * News API
 */

class NewsController extends MainController
{

    public $modelClass = '';
    public $customPath = 'news';
    public $galleryPath = 'gallery/news';
    public $blogID = 34;

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * News
     * https://e-tibb.az/api/doctor/news
     * id=1
     * page=1
     * count=5
     */
    public function actionIndex()
    {

        $model = new NewsApiModel();
        $status = SiteNews::get_Status();

        $id = $this->blogID;
        $connectID = Yii::$app->session->get('userID');

        $totalCount = $model->NewsByDoctorCount($id,$connectID,NewsApiModel::TYPE_DOCTOR);

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $model->NewsByDoctorList($id,$limits,$connectID,NewsApiModel::TYPE_DOCTOR);

        if(!empty($list))
        {
            foreach($list as $key => $val)
            {
                $list[$key]['status_name'] = $status[$list[$key]['status']];
                $list[$key]['datetime'] = Yii::$app->formatter->asTime($list[$key]['datetime'],'php: d M Y');
            }
        }

        $data['list'] = $this->ResultList($list,'news');

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages,$data);

        return $this->response(200,"Məlumat mövcuddur",$result);
    }


    /**
     * Blog search
     * https://e-tibb.az/api/doctor/news/search
     * https://e-tibb.az/api/doctor/news/search?page=1&count=5
     * id = 313
     * headline = basliq
     * date = 2020-07-07
     * status=1
     */
    public function actionSearch()
    {
        Yii::$app->db->schema->refresh();
        $model = new BlogSearch();
        $search = Yii::$app->request->get();

        if ($model->load($search, '')) {

            $model->validate();
            if (!empty($model->errors)) {
                return $this->response(400, 'Məlumatın axtarılması zamanı xəta baş verdi', $model->errors);
            }

            $totalCount = $model->searchCount($search);

            if($totalCount['count'] <= 0)
            {
                return $this->response(400,"Heçbir məlumat tapılmadı");
            }

            $pagination = new Pagination(['totalCount' => $totalCount]);

            $limits = $pagination->getLimits();

            $result = $model->search($search,$limits);

            $data['list'] = $result;

            $pages = $pagination->getPaginationInfo();

            $result = array_merge($pages,$data);

            return $this->response(200, "Məlumat mövcuddur", $result);
        }
    }

    /**
     * Xeber info
     * https://e-tibb.az/api/doctor/news/info/1013
     */
    public function actionInfo()
    {

        $id     = intval(Yii::$app->request->get('id'));
        $connectID = Yii::$app->session->get('userID');
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $status = SiteNews::get_Status();

        /** News info */
        $news = NewsApiModel::NewsByDoctor($id,$connectID,NewsApiModel::TYPE_DOCTOR);
        if(empty($news))
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $news['content']     = strip_tags($news['content']);
        $news['status_name'] = $status[$news['status']];
        $news['gallery_images'] = PhotoGalleryApiModel::GetGalleryImagesByConnect($news['id'], SitePhotoGallery::TYPE_NEWS);
        $news = $this->ResultList($news,'news');

        return $this->response(200,"Məlumat mövcuddur",$news);

    }

    /**
     * Xeber yaratmaq
     * https://e-tibb.az/api/doctor/news/create
     */
    public function actionCreate()
    {
        $model = new SiteNews();
        $post = Yii::$app->request->post();

        Yii::$app->db->schema->refresh();

        if(!empty($post) && $model->load($post,''))
        {

            $model->connect_id  = Yii::$app->session->get('userID');
            $model->type        = NewsApiModel::TYPE_DOCTOR;
            $model->category_id = $this->blogID;
            $model->status      = NewsApiModel::STATUS_DEACTIVE;

            $model->published_time = date('Y-m-d H:i:s');
            $model->modified_time  = date('Y-m-d H:i:s');

            $model->slug = Functions::slugify($model->headline);

            $model->validate();

            /** Check Main image **/
            $photo = UploadedFile::getInstanceByName('files');
            if(empty($photo))
            {
                $model->addError('files','Şəkil elave edin');
            }else{
                $imageUpload = new ImageUpload();
                if(!$imageUpload->validate($photo))
                {
                    $model->addError('files','Şəkilin yüklənməsi zamanı xəta baş verdi');
                }
            }

            /** Check Gallery Images **/
            $photos = UploadedFile::getInstancesByName('images');
            if(!empty($photos)) {
                foreach($photos as $item) {
                    $imageUpload = new ImageUpload();
                    if(!$imageUpload->validate($item)) {
                        $model->addError('files','Şəkilin yüklənməsi zamanı xəta baş verdi');
                    }
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
                        'resize.img'=>[350,231],
                        'resize.thumb'=>[401,265]
                    ]);
                    $updatePhoto = $model;
                    $updatePhoto->photo = $uploadedFile;
                    $updatePhoto->save(false);

                }

                $images  = [];
                /** Photo gallery */
                if(!empty($photos)) {
                    foreach ($photos as $photo) {
                        $imageUpload = new ImageUpload();
                        $uploadedFile = $imageUpload->saveFile($photo, [
                            'path.save' => $this->galleryPath,
                            'resize.img' => [900, 500],
                            'resize.thumb' => [401, 265]
                        ]);
                        if($uploadedFile) {
                            $images[] = $uploadedFile;
                        }
                    }
                }

                if($images) {
                    foreach ($images as $image) {
                        $this->saveGallery($image, $model->id);
                    }
                }
                $data = SiteNews::newsFind($model->id);
                AdminDoctorLog::write(['id' => $model->id, 'name' => $data['headline']]);
                return $this->response(200,'Məlumat uğurla əlavə olundu');
            }

        }

        return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi');
    }

    /**
     * Xeber duzelis et
     * https://e-tibb.az/api/doctor/news/edit
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

        if(!empty($post) && $model->load($post,''))
        {
            $model->connect_id  = Yii::$app->session->get('userID');
            $model->type        = NewsApiModel::TYPE_DOCTOR;
            $model->category_id = $this->blogID;
            $model->status      = $oldModel->status;
            $model->modified_time = date('Y-m-d H:i:s');
            $model->validate();

            /** Check main image */
            $photo = UploadedFile::getInstanceByName('files');
            $deletedImages = $model->deletedImages;
            if (empty($photo)) {
                if(empty($oldModel->photo) || !empty($deletedImages))
                {
                    $model->addError('files','Şəkil elave edin');
                }
            } else {
                $imageUpload = new ImageUpload();
                if (!$imageUpload->validate($photo)) {
                    $model->addError('files', 'Şəkilin yüklənməsi zamanı xəta baş verdi');
                }
            }

            /** Check gallery images */
            $newGalleryImages = UploadedFile::getInstancesByName('images');
            if (!empty($newGalleryImages)) {
                foreach ($newGalleryImages as $item) {
                    $imageUpload = new ImageUpload();
                    if (!$imageUpload->validate($item)) {
                        $model->addError('files', 'Şəkilin yüklənməsi zamanı xəta baş verdi');
                    }
                }
            }

            if(!empty($model->errors))
            {
                return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi',$model->errors);
            }

            /** Delete main image */
            if (empty($model->errors)) {
                if(!empty($deletedImages)) {
                    if(!empty($oldModel->photo)) {
                        $imageUpload = new ImageUpload();
                        $imageUpload->deleteFile([$this->customPath . '/' . $oldModel->photo], 'upload');
                        $imageUpload->deleteFile([$this->customPath . '/small/' . $oldModel->photo], 'upload');

                        $updatePhoto = $this->findModel($model->id);
                        $updatePhoto->photo = '';
                        $updatePhoto->save(false);
                    }
                }
            }


            /** Delete gallery images*/
            $deletedGalleryImages = Yii::$app->request->post('deletedGalleryImages');
            $oldGalleryImagesIds = [];
            $deletedImagesIds = [];
            if(!empty($deletedGalleryImages)) {
                foreach ($deletedGalleryImages as $val) {
                    if (!empty($val)) {
                        $deletedImagesIds[] = $val;
                    }
                }
            }
            $deletedGalleryImages = $deletedImagesIds;
            if (empty($model->errors)) {
                if (!empty($deletedGalleryImages)) {
                    $deletedGalleryImages = PhotoGalleryApiModel::GetGalleryImagesByConnectAndIds($id, implode(',', $deletedGalleryImages));

                    if(!empty($deletedGalleryImages)) {
                        foreach ($deletedGalleryImages as $galleryImage) {
                            $oldGalleryImagesIds[] = $galleryImage['id'];
                            $imageUpload = new ImageUpload();
                            $imageUpload->deleteFile([$this->galleryPath . '/' . $galleryImage['name']], 'upload');
                            $imageUpload->deleteFile([$this->galleryPath . '/small/' . $galleryImage['name']], 'upload');
                        }
                    }
                }
            }

            if (!empty($oldGalleryImagesIds)) {
                PhotoGalleryApiModel::DeleteImagesByIds(implode(',', $oldGalleryImagesIds));
            }

            if($model->save(false))
            {

                /** Main image **/
                if (!empty($photo)) {
                    $imageUpload = new ImageUpload();
                    $uploadedFile = $imageUpload->saveFile($photo, [
                        'path.save' => $this->customPath,
                        'resize.img' => [900, 500],
                        'resize.thumb' => [401, 265]
                    ]);
                    $updatePhoto = $model;
                    $updatePhoto->photo = $uploadedFile;
                    $updatePhoto->save(false);
                }

                /** Photo gallery */
                $images = [];
                if (!empty($newGalleryImages)) {
                    foreach ($newGalleryImages as $item) {
                        $imageUpload = new ImageUpload();
                        $uploadedFile = $imageUpload->saveFile($item, [
                            'path.save' => $this->galleryPath,
                            'resize.img' => [900, 500],
                            'resize.thumb' => [401, 265]
                        ]);
                        if ($uploadedFile) {
                            $images[] = $uploadedFile;
                        }
                    }
                }

                if ($images) {
                    foreach ($images as $image) {
                        $this->saveGallery($image, $id);
                    }
                }
                $data = SiteNews::newsFind($id);
                AdminDoctorLog::write(['id' => $id, 'name' => $data['headline']]);

                return $this->response(200,'Məlumat uğurla yeniləndi');
            }
        }

        return $this->response(400,'Məlumatın yenilənməsi zamanı xəta baş verdi');
    }


    public function saveGallery($image, $connect_id) {
        $photoGallery = new SitePhotoGallery();
        $photoGallery->name = $image;
        $photoGallery->type = $photoGallery::TYPE_NEWS;
        $photoGallery->connect_id = $connect_id;
        $photoGallery->created_at = date("Y-m-d H:i:s");
        $photoGallery->save(false);
    }


    /**
     * Xeber status
     * https://e-tibb.az/api/doctor/news/status
     */
    public function actionStatus()
    {
        $result = SiteNews::get_Status();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Xeber kateqoriyalari
     * https://e-tibb.az/api/doctor/news/categories
     */
    public function actionCategories()
    {
        $result = SiteMenus::find()->where(['type'=>3])->all();
        if(empty($result))
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Xeber sil
     * https://e-tibb.az/api/doctor/news/del
     * ids array
     */
    public function actionDel()
    {
        $ids       = Yii::$app->request->post('ids');
        $connectID = Yii::$app->session->get('userID');
        if(empty($ids))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        foreach($ids as $key => $id)
        {
            $update = NewsApiModel::NewsByDoctorDelete($id,$connectID,NewsApiModel::TYPE_DOCTOR);
            if(empty($update))
            {
                return $this->response(400,"Məlumatın silinməsi zamanı xəta baş verdi");
            }
        }
        return $this->response(200,"Melumat(lar) silindi");
    }

    protected function findModel($id)
    {
        $connectID = Yii::$app->session->get('userID');
        if(($model = SiteNews::find()->where(['id'=>$id,'connect_id'=>$connectID,'type'=>NewsApiModel::TYPE_DOCTOR])->one()) !== null){
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // Delete functions

    /**
     * Xeber sil
     * https://e-tibb.az/api/doctor/news/delete-one
     * method: POST
     */
    public function actionDeleteOne() {
        $id = intval(Yii::$app->request->post('id'));
        if($id) {
            $connectID = Yii::$app->session->get('userID');
            $news = NewsApiModel::NewsByDoctor($id,$connectID,NewsApiModel::TYPE_DOCTOR);
            if(!empty($news)) {
                NewsApiModel::NewsByDoctorDelete($id,$connectID,NewsApiModel::TYPE_DOCTOR);
                AdminLog::write(['id' => $id, 'name' => $news['headline']]);

                return $this->response(200, "Məlumat silindi");
            }
        }

        return $this->response(404, "Bu nömrəli xəbər tapilmadı");
    }


    /**
     * Xeberler toplu sil
     * https://e-tibb.az/api/doctor/news/all-delete
     * method: POST
     * id: array
     */
    public function actionAllDelete() {
        $ids = Yii::$app->request->post('id');
        if($ids && is_array($ids)) {
            foreach($ids as $id) {
                if(is_numeric($id)) {
                    $connectID = Yii::$app->session->get('userID');
                    $news = NewsApiModel::NewsByDoctor($id,$connectID,NewsApiModel::TYPE_DOCTOR);
                    if(!empty($news)) {
                        NewsApiModel::NewsByDoctorDelete($id,$connectID,NewsApiModel::TYPE_DOCTOR);
                        AdminLog::write(['id' => $news['id'], 'name' => $news['headline']]);
                    }
                }
            }
            return $this->response(200, "Məlumat silindi");
        } else {
            return $this->response(400, "Id sahəsi vacibdir");
        }

        return $this->response(404, "Bu nömrəli xəbər tapilmadı");
    }


}