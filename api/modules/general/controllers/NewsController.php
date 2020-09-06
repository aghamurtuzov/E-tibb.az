<?php

namespace api\modules\general\controllers;

use api\models\AdminLog;
use api\modules\general\models\search\NewsSearch;
use api\models\SitePhotoGallery;

use DateTime;
use yii;
use yii\web\UploadedFile;
use api\components\Pagination;
use api\components\ImageUpload;
use api\components\Functions;
use api\models\SiteMenus;
use api\modules\general\models\NewsApiModel;
use api\modules\general\models\SiteNews;
use api\modules\general\controllers\MainController;
use api\models\PhotoGalleryApiModel;

/**
 * News API
 */
class NewsController extends MainController
{

    public $modelClass = '';
    public $customPath = 'news';
    public $galleryPath = 'gallery\news';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * News
     * https://e-tibb.az/api/general/news
     * id=1
     * page=1
     * count=5
     */
    public function actionIndex()
    {
        Yii::$app->formatter->timeZone = 'Asia/Baku';
        date_default_timezone_set('Asia/Baku');
        $model = new NewsApiModel();
        $status = SiteNews::get_Status();

        $id = intval(Yii::$app->request->get('id'));

        $type = (Yii::$app->request->get('type') == null) ? 'all' : Yii::$app->request->get('type');

        $totalCount = !empty($id) ? $model->NewsByIdCount($id, $type) : $model->NewsCount($type);

        if ($totalCount['count'] <= 0) {
            return $this->response(200, "Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = !empty($id) ? $model->NewsByIdList($id, $limits, $type) : $model->NewsList($limits, $type);

        if (!empty($list)) {
            foreach ($list as $key => $val) {
                if (empty($list[$key]['connect_id'])) {
                    $list[$key]['name'] = 'E-tibb.az';
                } else {
                    if ($list[$key]['doctor_name']==0) {
                        $list[$key]['name'] = $list[$key]['doctor_name'];
                    } elseif ($list[$key]['doctor_name']==1)  {
                        $list[$key]['name'] = $list[$key]['enter_name'];
                    }
                }
                $list[$key]['status_name'] = $status[$list[$key]['status']];
                $date=date_create($list[$key]['datetime']);
                $list[$key]['datetime'] = date_format($date,"Y/m/d H:i");

            }
        }

        $data['list'] = $this->ResultList($list, 'news');

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages, $data);

        return $this->response(200, "Məlumat mövcuddur", $result);
    }

    /**
     * News
     * https://e-tibb.az/api/general/news/search
     * https://e-tibb.az/api/general/news/search?page=1&count=5
     * id=1
     * headline=basliq
     * datetime=2019-11-16
     * status = 1
     * category_id = 37 (news) / 34 (blog)
     */
    public function actionSearch()
    {
        Yii::$app->db->schema->refresh();
        $model = new NewsSearch();
        $search = Yii::$app->request->get();
        $status = SiteNews::get_Status();

        if ($model->load($search, '')) {

            $model->validate();
            if (!empty($model->errors)) {
                return $this->response(400, 'Məlumatın axtarılması zamanı xəta baş verdi', $model->errors);
            }

            $totalCount = $model->searchCount($search);

            if ($totalCount['count'] <= 0) {
                return $this->response(400, "Heçbir məlumat tapılmadı");
            }

            $pagination = new Pagination(['totalCount' => $totalCount]);

            $limits = $pagination->getLimits();

            $result = $model->search($search, $limits);

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    if (empty($result[$key]['connect_id'])) {
                        $result[$key]['name'] = 'E-tibb.az';
                    } else {
                        if ($result[$key]['type']==0) {
                            $result[$key]['name'] = $result[$key]['doctor_name'];
                        } elseif($result[$key]['type']==1) {
                            $result[$key]['name'] = $result[$key]['enter_name'];
                        }
                    }
                    $result[$key]['status_name'] = $status[$result[$key]['status']];
                    $result[$key]['datetime'] = Yii::$app->formatter->asTime($result[$key]['datetime'], 'php: d/m/Y H:i');
                }
            }

            $data['list'] = $this->ResultList($result, 'news');

            $pages = $pagination->getPaginationInfo();

            $result = array_merge($pages, $data);

            return $this->response(200, "Məlumat mövcuddur", $result);
        }
    }

    /**
     * Xeber info
     * https://e-tibb.az/api/general/news/info/1013
     */
    public function actionInfo()
    {
        $id = intval(Yii::$app->request->get('id'));

        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        $status = SiteNews::get_Status();

        /** News info */
        $news = NewsApiModel::News($id);

        if (empty($news)) {
            return $this->response(200, "Heçbir məlumat tapılmadı");
        }

        $news['status_name'] = $status[$news['status']];

        $news['gallery_images'] = PhotoGalleryApiModel::GetGalleryImagesByConnect($news['id'], SitePhotoGallery::TYPE_NEWS);

        $news = $this->ResultList($news, 'news', null, $this->galleryPath);

        return $this->response(200, "Məlumat mövcuddur", $news);
    }

    /**
     * Xeber yaratmaq
     * https://e-tibb.az/api/general/news/create
     */
    public function actionCreate()
    {
        $model = new SiteNews();

        $post = Yii::$app->request->post();

        Yii::$app->db->schema->refresh();

        if (!empty($post) && !empty($post) && $model->load($post, '')) {

            $model->published_time = date('Y-m-d H:i:s');
            $model->modified_time = date('Y-m-d H:i:s');

            $model->slug = Functions::slugify($model->headline);

            $model->validate();

            /** Check Main image **/
            $photo = UploadedFile::getInstanceByName('files');

            if (empty($photo)) {
                $model->addError('files', 'Şəkil elave edin');

            } else {
                $imageUpload = new ImageUpload();
                if (!$imageUpload->validate($photo)) {
                    $model->addError('files', 'Şəkilin yüklənməsi zamanı xəta baş verdi');
                }
            }

            /** Check Gallery Images **/
            $photos = UploadedFile::getInstancesByName('images');
            if (!empty($photos)) {
                foreach ($photos as $item) {
                    $imageUpload = new ImageUpload();
                    if (!$imageUpload->validate($item)) {
                        $model->addError('files', 'Şəkilin yüklənməsi zamanı xəta baş verdi');
                    }
                }
            }

            if (!empty($model->errors)) {
                return $this->response(400, 'Məlumatın əlavə olunması zamanı xəta baş verdi', $model->errors);
            }

            if ($model->save()) {
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

                $images = [];
                /** Photo gallery */
                if (!empty($photos)) {
                    foreach ($photos as $photo) {
                        $imageUpload = new ImageUpload();
                        $uploadedFile = $imageUpload->saveFile($photo, [
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
                        $this->saveGallery($image, $model->id);
                    }
                }

                AdminLog::write(['id' => $model->id, 'name' => $model->headline]);

                return $this->response(200, 'Məlumat uğurla əlavə olundu');

            }
        }

        return $this->response(400, 'Məlumatın əlavə olunması zamanı xəta baş verdi');
    }

    public function saveGallery($image, $connect_id)
    {
        $photoGallery = new SitePhotoGallery();
        $photoGallery->name = $image;
        $photoGallery->type = $photoGallery::TYPE_NEWS;
        $photoGallery->connect_id = $connect_id;
        $photoGallery->created_at = date("Y-m-d H:i:s");
        $photoGallery->save(false);
    }

    /**
     * Xeber duzelis et
     * https://e-tibb.az/api/general/news/edit
     * deletedImages 0 | 1
     */

    public function actionEdit()
    {

        $id = intval(Yii::$app->request->post('id'));

        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        $model = $this->findModel($id);
        $oldModel = $this->findModel($id);
        $post = Yii::$app->request->post();


        if (!empty($post) && $model->load($post, '')) {
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

            if (!empty($model->errors)) {
                return $this->response(400, 'Məlumatın əlavə olunması zamanı xəta baş verdi', $model->errors);
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


            if ($model->save(false)) {
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

                $images = [];
                /** Photo gallery */
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

                AdminLog::write(['id' => $model->id, 'name' => $model->headline]);

                return $this->response(200, 'Məlumat uğurla yeniləndi');
            }
        }

        return $this->response(400, 'Məlumatın yenilənməsi zamanı xəta baş verdi');
    }

    /**
     * Xeber status
     * https://e-tibb.az/api/general/news/status
     */
    public function actionStatus()
    {
        $result = SiteNews::get_Status();
        return $this->response(200, "Məlumat mövcuddur", $result);
    }

    /**
     * Xeber kateqoriyalari
     * https://e-tibb.az/api/general/news/categories
     */
    public function actionCategories()
    {
        $result = SiteMenus::find()->where('id != :id and type = :type', ['id' => 34, 'type' => 3])->all();
        if (empty($result)) {
            return $this->response(200, "Heçbir məlumat tapılmadı");
        }
        return $this->response(200, "Məlumat mövcuddur", $result);
    }

//    /**
//     * Xeber sil
//     * https://e-tibb.az/api/general/news/del
//     * ids array
//     */
//    public function actionDel()
//    {
//        $ids = Yii::$app->request->post('ids');
//        if (empty($ids)) {
//            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
//        }
//
//        foreach ($ids as $key => $id) {
//            $update = NewsApiModel::NewsDelete($id);
//            if (empty($update)) {
//                return $this->response(400, "Məlumatın silinməsi zamanı xəta baş verdi");
//            }
//        }
//        return $this->response(200, "Melumat(lar) silindi");
//    }

    protected function findModel($id)
    {
        if (($model = SiteNews::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // Delete functions

    /**
     * Xeber sil
     * https://e-tibb.az/api/general/news/delete-one
     * method: POST
     */
    public function actionDeleteOne()
    {
        $id = Yii::$app->request->post('id');
        if ($id) {
            $news = NewsApiModel::NewsFind($id);
            if (!empty($news)) {
                NewsApiModel::NewsDelete($id);
                AdminLog::write(['id' => $news['id'], 'name' => $news['headline']]);
                return $this->response(200, "Məlumat silindi");
            }
        }

        return $this->response(404, "Bu nömrəli xəbər tapilmadı");
    }


    /**
     * Xeberler toplu sil
     * https://e-tibb.az/api/general/news/all-delete
     * method: POST
     * id: array
     */
    public function actionAllDelete()
    {
        $ids = Yii::$app->request->post('id');
        if ($ids && is_array($ids)) {
            foreach ($ids as $id) {
                if (is_numeric($id)) {
                    $news = NewsApiModel::NewsFind($id);
                    if (!empty($news)) {
                        NewsApiModel::NewsDelete($id);
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

    /**
     * Xeber daimi sil
     * https://e-tibb.az/api/general/news/base-delete-one
     * method: POST
     */
    public function actionBaseDeleteOne()
    {

        if (Yii::$app->userCheck->can("superadmin")) {
            $id = Yii::$app->request->post('id');
            if ($id) {
                $news = NewsApiModel::NewsFind($id);
                if (!empty($news)) {
                    NewsApiModel::NewsDeletePermanently($id);
                    AdminLog::write(['id' => $news['id'], 'name' => $news['headline']]);
                    return $this->response(200, "Məlumat silindi");
                }
            }
            return $this->response(404, "Bu nömrəli xəbər tapilmadı");
        } else {
            return $this->response(403, "Bu əməliyyatı icra etmək üçün icazəniz yoxdur");
        }

        return $this->response(404, "Bu nömrəli xəbər tapilmadı");

    }

    /**
     * Xeberleri daimi sil
     * https://e-tibb.az/api/general/news/all-base-delete
     * method: POST
     * id: array
     */
    public function actionAllBaseDelete()
    {
        if (Yii::$app->userCheck->can("superadmin")) {
            $ids = Yii::$app->request->post('id');
            if ($ids && is_array($ids)) {
                foreach ($ids as $id) {
                    if (is_numeric($id)) {
                        $news = NewsApiModel::NewsFind($id);
                        if (!empty($news)) {
                            NewsApiModel::NewsDeletePermanently($id);
                            AdminLog::write(['id' => $news['id'], 'name' => $news['headline']]);
                        }
                    }
                }
                return $this->response(200, "Məlumat silindi");
            } else {
                return $this->response(400, "Ids sahəsi vacibdir");
            }
            return $this->response(404, "Bu nömrəli xəbər tapilmadı");
        } else {
            return $this->response(403, "Bu əməliyyatı icra etmək üçün icazəniz yoxdur");
        }

        return $this->response(404, "Bu nömrəli xəbər tapilmadı");
    }

}
