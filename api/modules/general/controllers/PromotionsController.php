<?php

namespace api\modules\general\controllers;

use api\models\AdminLog;
use api\models\SiteDoctors;
use api\modules\general\models\search\PromotionsSearch;
use yii;
use yii\web\UploadedFile;
use api\components\Functions;
use api\components\Pagination;
use api\components\ImageUpload;
use api\models\SitePromotions;
use api\models\SiteUsers;
use api\models\SiteUsedPromocode;
use api\modules\general\models\PromotionsApiModel;
use api\modules\general\controllers\MainController;

/**
 * Promotions API
 */
class PromotionsController extends MainController
{

    public $modelClass = '';
    public $customPath = 'promotions';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Istifade olunmus promokodlar
     * https://e-tibb.az/api/general/promotions/used-list
     */
    public function actionUsedList()
    {

        $id = intval(Yii::$app->request->get('id'));
        $type = intval(Yii::$app->request->get('type'));
        $type = empty($type) ? 1 : $type;

        $model = new PromotionsApiModel();

        $totalCount = !empty($id) ? $model->UsedPromocodesListCount($id, $type) : $model->UsedPromocodesCount();

        if ($totalCount['count'] <= 0) {
            return $this->response(200, "Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = !empty($id) ? $model->UsedPromocodesList($id, $limits, $type) : $model->UsedPromocodes($limits);


        if (!empty($list)) {
            foreach ($list as $key => $val) {

                if (empty($val['connect_id'])) {
                    $list[$key]['byPromotion'] = $val['organizer'];
                } else {
                    $list[$key]['byPromotion'] = $model->getOrganizer($val['connect_id'], $val['type']);
                }

                if (!empty($val['user_id'])) {

                    $user = SiteUsers::findOne($val['user_id']);
                    if (!empty($user)) {
                        $list[$key]['used_userName'] = $user['name'];
                    }
                }
            }
        }

        $data['list'] = $list;

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages, $data);

        return $this->response(200, "Məlumat mövcuddur", $result);
    }

    /**
     * Istifade olunmus promokodlar search
     * https://e-tibb.az/api/general/promotions/used-list-search
     * https://e-tibb.az/api/general/promotions/used-list-search?page=1&count=5
     * status = 1
     * organizer = agha
     * date_start = 01.01.2020
     * date_end = 01.01.2020
     */
    public function actionUsedListSearch()
    {
        Yii::$app->db->schema->refresh();
        $model = new PromotionsSearch();
        $search = Yii::$app->request->get();
        $status = PromotionsSearch::get_Status();

        if ($model->load($search, '')) {

            $model->validate();
            if (!empty($model->errors)) {
                return $this->response(400, 'Məlumatın axtarılması zamanı xəta baş verdi', $model->errors);
            }

            $totalCount = $model->usedSearchCount($search);

            if ($totalCount['count'] <= 0) {
                return $this->response(400, "Heçbir məlumat tapılmadı");
            }

            $pagination = new Pagination(['totalCount' => $totalCount]);

            $limits = $pagination->getLimits();

            $result = $model->usedSearch($search, $limits);

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    if (empty($result[$key]['connect_id'])) {
                        $result[$key]['byPromotion'] = $result[$key]['organizer'];
                    } else {
                        if ($result[$key]['type']==1) {
                            $result[$key]['byPromotion'] = $result[$key]['doctor_name'];
                        } elseif($result[$key]['type']==2) {
                            $result[$key]['byPromotion'] = $result[$key]['enter_name'];
                        }
                    }

                    $result[$key]['promotion_headline'] = $result[$key]['headline'];
                    $result[$key]['used_userName'] = $result[$key]['user_name'];
                    $result[$key]['created_at'] = Yii::$app->formatter->asTime($result[$key]['created_at'], 'php: d/m/Y H:i');
                }
            }

            $data['list'] = $result;

            $pages = $pagination->getPaginationInfo();

            $result = array_merge($pages, $data);

            return $this->response(200, "Məlumat mövcuddur", $result);
        }
    }

    /**
     * Promokod yoxla
     * https://e-tibb.az/api/general/promotions/check
     * promocode = 30xx12
     */
    public function actionCheck()
    {
        $used = Yii::$app->request->post('used');
        $promocode = Yii::$app->request->post('promocode');
        if (empty($promocode)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        if (strpos($promocode, 'xx')) {
            $exp = explode('xx', $promocode);
            $promotion_id = intval($exp[0]);
            $user_id = intval($exp[1]);

            if ($promotion_id > 0 && $user_id > 0) {

                $promotion = $this->findModel($promotion_id);
                if (!empty($promotion)) {
                    $usedPromotion = SiteUsedPromocode::find()->where(['promotion_id' => $promotion_id, 'user_id' => $user_id])->one();
                    if (empty($usedPromotion)) {
                        $check = SiteUsers::findOne($user_id);
                        if (!empty($check)) {
                            $result['name'] = $check['name'];
                            $result['user_id'] = $check['id'];
                            $result['promocode'] = $promocode;
                            if (!empty($used)) {
                                $usedPromocode = new SiteUsedPromocode();
                                $usedPromocode->promocode = $promocode;
                                $usedPromocode->promotion_id = $promotion_id;
                                $usedPromocode->user_id = $user_id;
                                $usedPromocode->created_at = date("Y-m-d H:i:s");
                                $usedPromocode->save();
                                return $this->response(200, "Promokod istifadə olundu", $result);
                            } else {
                                return $this->response(200, "Promokod mövcuddur", $result);
                            }
                        }
                    } else {
                        return $this->response(400, "Təəssüfki daxil etdiyiniz promokod artıq istifadə olunub");
                    }
                }
            }
        }
        return $this->response(400, "Heçbir məlumat tapılmadı");
    }

    /**
     * Aksiya info
     * https://e-tibb.az/api/general/promotions/info/37
     */
    public function actionInfo()
    {
        $id = intval(Yii::$app->request->get('id'));
        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        $status = SitePromotions::get_Status();
        $model = new PromotionsApiModel();

        /** Aksiya info */
        $promotion = PromotionsApiModel::Promotion($id);
        if (empty($promotion)) {
            return $this->response(200, "Heçbir məlumat tapılmadı");
        }

        $promotion['byPromotion'] = $promotion['organizer'];
        //$promotion['content']     = strip_tags($promotion['content']);
        $promotion['status_name'] = $status[$promotion['status']];
        $promotion['discount'] = empty($promotion['discount']) ? '' : $promotion['discount'];
        if (!empty($promotion['connect_id'])) {
            $organizer = $model->getOrganizer($promotion['connect_id'], $promotion['type']);
            if (!empty($organizer)) {
                $promotion['byPromotion'] = $organizer;
            }
        }

        $promotion = $this->ResultList($promotion, 'promotions');

        return $this->response(200, "Məlumat mövcuddur", $promotion);

    }

    /**
     * Aksiyalar
     * https://e-tibb.az/api/general/promotions
     * id=1
     * type=1
     * page=1
     * count=5
     */
    public function actionIndex()
    {
        $id = intval(Yii::$app->request->get('id'));
        $type = intval(Yii::$app->request->get('type'));
        $status = SitePromotions::get_Status();
        $type = empty($type) ? 1 : $type;

        $model = new PromotionsApiModel();

        $statusType = (Yii::$app->request->get('status') == null) ? 'all' : Yii::$app->request->get('status');

        $totalCount = !empty($id) ? $model->PromotionsListCount($id, $type, $statusType) : $model->PromotionsCount($statusType);


        if ($totalCount['count'] <= 0) {
            return $this->response(200, "Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = !empty($id) ? $model->PromotionsList($id, $limits, $type, $statusType) : $model->Promotions($limits, $statusType);

        if (!empty($list)) {
            foreach ($list as $key => $val) {
                $list[$key]['byPromotion'] = $list[$key]['organizer'];
                $list[$key]['content'] = strip_tags($list[$key]['content']);
                $list[$key]['status_name'] = $status[$list[$key]['status']];
                $list[$key]['date_start'] = Yii::$app->formatter->asTime($list[$key]['date_start'], 'php:d M Y');
                $list[$key]['date_end'] = Yii::$app->formatter->asTime($list[$key]['date_end'], 'php:d M Y');
                if (!empty($val['connect_id'])) {
                    $organizer = $model->getOrganizer($val['connect_id'], $val['type']);
                    if (!empty($organizer)) {

                        $list[$key]['byPromotion'] = $organizer;
                    }
                }
            }
        }

        $data['list'] = $this->ResultList($list, 'promotions');

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages, $data);

        return $this->response(200, "Məlumat mövcuddur", $result);
    }

    /**
     * Aksiyalar search
     * https://e-tibb.az/api/general/promotions/search
     * https://e-tibb.az/api/general/promotions/search?page=1&count=5
     * organizer=basliq
     * date_start = 01.01.2020
     * status=1
     */
    public function actionSearch()
    {
        Yii::$app->db->schema->refresh();
        $model = new PromotionsSearch();
        $search = Yii::$app->request->get();
        $status = SitePromotions::get_Status();

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
                        $result[$key]['byPromotion'] = $result[$key]['organizer'];
                    }
                    else{
                        if ($result[$key]['type']==1){
                            $result[$key]['byPromotion'] = $result[$key]['doctor_name'];
                        }
                        elseif($result[$key]['type']==2){
                            $result[$key]['byPromotion'] = $result[$key]['enter_name'];
                        }
                    }
                    $result[$key]['status_name'] = $status[$result[$key]['status']];
                }
            }

            $data['list'] = $this->ResultList($result, 'promotions');

            $pages = $pagination->getPaginationInfo();

            $result = array_merge($pages, $data);

            return $this->response(200, "Məlumat mövcuddur", $result);
        }
    }

    /**
     * Aksiya yaratmaq
     * https://e-tibb.az/api/general/promotions/create
     */
    public function actionCreate()
    {
        Yii::$app->db->schema->refresh();
        $model = new SitePromotions();
        $post = Yii::$app->request->post();

        if (!empty($post) && $model->load($post, '')) {

            $mode = 'add';

            $model->slug = Functions::slugify($model->headline);
            $model->date = date('Y-m-d');

            if (!empty($model->organizer)) {
                $model->connect_id = 0;
                $model->type = 0;
            }

            $model->validate();

            /** Check Main image **/
            $photo = UploadedFile::getInstanceByName('photo');
            if (empty($photo)) {
                $model->addError('files', 'Şəkil elave edin');
            } else {
                $imageUpload = new ImageUpload();
                if (!$imageUpload->validate($photo)) {
                    $model->addError('files', 'Şəkilin yüklənməsi zamanı xəta baş verdi');
                }
            }

            if (!empty($model->errors)) {
                return $this->response(400, 'Məlumatın əlavə olunması zamanı xəta baş verdi', $model->errors);
            }

            if ($model->save()) {
                /** Type */

                if (!empty($model->connect_id)) {
                    if ($model->type == 1) {
                        $model->change_Promotion($model->connect_id, $model->type, $mode);
                    } else if ($model->type == 2) {
                        $model->change_Promotion($model->connect_id, $model->type, $mode);

                    }
                }

                /** Main image **/
                if (!empty($photo)) {
                    $imageUpload = new ImageUpload();
                    $uploadedFile = $imageUpload->saveFile($photo, [
                        'path.save' => $this->customPath,
                        'resize.img' => [350, 231],
                        'resize.thumb' => [401, 265]
                    ]);
                    $updatePhoto = $model;
                    $updatePhoto->photo = $uploadedFile;
                    $updatePhoto->save(false);
                }

                AdminLog::write(['id' => $model->id, 'name' => $model->headline]);

                return $this->response(200, 'Məlumat uğurla əlavə olundu');
            }

        }

        return $this->response(400, 'Məlumatın əlavə olunması zamanı xəta baş verdi');
    }

    /**
     * Aksiya duzelis et
     * https://e-tibb.az/api/general/promotions/edit
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

        if (empty($model)) {
            return $this->response(200, "Heçbir məlumat tapılmadı");
        }

        if (!empty($post) && $model->load($post, '')) {
            $mode = 'edit';
            $model->date_start = date("Y-m-d", strtotime($model->date_start));
            $model->date_end = date("Y-m-d", strtotime($model->date_end));


            if (!empty($model->organizer)) {
                $model->connect_id = 0;
                $model->type = 0;

            }

            $model->validate();

            /** Check main image */
            $deletedImages = $model->deletedImages;
            $photo = UploadedFile::getInstanceByName('photo');
            if (empty($photo)) {
                if (empty($oldModel->photo) || !empty($deletedImages)) {
                    $model->addError('files', 'Şəkil elave edin');
                }
            } else {
                $imageUpload = new ImageUpload();
                if (!$imageUpload->validate($photo)) {
                    $model->addError('files', 'Şəkilin yüklənməsi zamanı xəta baş verdi');
                }
            }

            /** Delete main image */
            if (empty($model->errors)) {
                if (!empty($deletedImages)) {
                    if (!empty($oldModel->photo)) {
                        $imageUpload = new ImageUpload();
                        $imageUpload->deleteFile([$this->customPath . '/' . $oldModel->photo]);
                        $imageUpload->deleteFile([$this->customPath . '/small/' . $oldModel->photo]);

                        $updatePhoto = $this->findModel($model->id);
                        $updatePhoto->photo = '';
                        $updatePhoto->save(false);
                    }
                }
            }

            if (!empty($model->errors)) {
                return $this->response(400, 'Məlumatın əlavə olunması zamanı xəta baş verdi', $model->errors);
            }

            if (empty($photo)) {
                $model->photo = $oldModel->photo;
            }

            if ($model->save(false)) {
                if (!empty($model->connect_id)) {
                    if ($model->type == 1) {
                        if ($oldModel->connect_id != $model->connect_id) {
                            $model->change_Promotion($model->connect_id, $model->type, $mode, $oldModel->connect_id);
                        }
                    } else if ($model->type == 2) {
                        if ($oldModel->connect_id != $model->connect_id) {
                            $model->change_Promotion($model->connect_id, $model->type, $mode, $oldModel->connect_id);
                        }
                    }
                }

                /** Main image **/
                if (!empty($photo)) {
                    $imageUpload = new ImageUpload();
                    $uploadedFile = $imageUpload->saveFile($photo, [
                        'path.save' => $this->customPath,
                        'resize.img' => [350, 231],
                        'resize.thumb' => [401, 265]
                    ]);
                    $updatePhoto = $model;
                    $updatePhoto->photo = $uploadedFile;
                    $updatePhoto->save(false);
                }

                AdminLog::write(['id' => $model->id, 'name' => $model->headline]);

                return $this->response(200, 'Məlumat uğurla yeniləndi');
            }
        }

        return $this->response(400, 'Məlumatın yenilənməsi zamanı xəta baş verdi');
    }

    /**
     * Aksiya status
     * https://e-tibb.az/api/general/promotions/status
     */
    public function actionStatus()
    {
        $result = SitePromotions::get_Status();
        return $this->response(200, "Məlumat mövcuddur", $result);
    }

    /**
     * Aksiya tipleri
     * https://e-tibb.az/api/general/promotions/types
     */
    public function actionTypes()
    {
        $result = SitePromotions::get_Type();
        return $this->response(200, "Məlumat mövcuddur", $result);
    }

//    /**
//     * Aksiya sil
//     * https://e-tibb.az/api/general/promotions/del
//     * ids array
//     */
//    public function actionDel()
//    {
//        $ids = Yii::$app->request->post('ids');
//        if (empty($ids)) {
//            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
//        }
//
//        $mode = 'delete';
//        foreach ($ids as $key => $id) {
//            $model = PromotionsApiModel::Promotion($id);
//            if (!empty($model)) {
//                $update = PromotionsApiModel::PromotionsDelete($id);
//                if ($update) {
//                    if (!empty($model['connect_id'])) {
//                        $sp = new SitePromotions();
//                        if ($model['type'] == 1) {
//                            $sp->change_Promotion($model['connect_id'], $model['type'], $mode);
//                        } else if ($model['type'] == 2) {
//                            $sp->change_Promotion($model['connect_id'], $model['type'], $mode);
//                        }
//                    }
//                }
//            }
//        }
//        return $this->response(200, "Aksiya(lar) silindi");
//    }

    protected function findModel($id)
    {
        if (($model = SitePromotions::findOne($id)) !== null) {
            return $model;
        }
    }


    // Delete functions

    /**
     * Aksiya sil
     * https://e-tibb.az/api/general/promotions/delete-one
     * method: POST
     * id: integer
     */
    public function actionDeleteOne()
    {
        $id = Yii::$app->request->post('id');
        if ($id) {
            $promotion = PromotionsApiModel::PromotionFind($id);
            if (!empty($promotion)) {
                PromotionsApiModel::PromotionsDelete($id);
                AdminLog::write(['id' => $promotion['id'], 'name' => $promotion['headline']]);

                return $this->response(200, "Məlumat silindi");
            }
        }

        return $this->response(404, "Bu nömrəli Aksiya tapilmadı");
    }


    /**
     * Aksiya toplu sil
     * https://e-tibb.az/api/general/promotions/all-delete
     * method: POST
     * id: array
     */
    public function actionAllDelete()
    {
        $ids = Yii::$app->request->post('id');
        if ($ids && is_array($ids)) {
            foreach ($ids as $id) {
                if (is_numeric($id)) {
                    $promotion = PromotionsApiModel::PromotionFind($id);
                    if (!empty($promotion)) {
                        PromotionsApiModel::PromotionsDelete($id);
                        AdminLog::write(['id' => $promotion['id'], 'name' => $promotion['headline']]);
                    }
                }
            }

            return $this->response(200, "Məlumat silindi");
        } else {
            return $this->response(400, "Id sahəsi vacibdir");
        }

        return $this->response(404, "Bu nömrəli Aksiya tapilmadı");
    }


    /**
     * Aksiya daimi sil
     * https://e-tibb.az/api/general/promotions/base-delete-one
     * method: POST
     * id: integer
     */
    public function actionBaseDeleteOne()
    {
        if (Yii::$app->userCheck->can("superadmin")) {
            $id = Yii::$app->request->post('id');
            if ($id) {
                $promotion = PromotionsApiModel::PromotionFind($id);
                if (!empty($promotion)) {
                    PromotionsApiModel::PromotionDeletePermanently($id);
                    AdminLog::write(['id' => $promotion['id'], 'name' => $promotion['headline']]);
                    return $this->response(200, "Məlumat silindi");
                }
            }

            return $this->response(404, "Bu nömrəli Aksiya tapilmadı");
        } else {
            return $this->response(403, "Bu əməliyyatı icra etmək üçün icazəniz yoxdur");
        }
    }

    /**
     * Aksiya daimi sil
     * https://e-tibb.az/api/general/promotions/all-base-delete
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
                        $promotion = PromotionsApiModel::PromotionFind($id);
                        if (!empty($promotion)) {
                            PromotionsApiModel::PromotionDeletePermanently($id);
                            AdminLog::write(['id' => $promotion['id'], 'name' => $promotion['headline']]);
                        }
                    }
                }

                return $this->response(200, "Məlumat silindi");
            } else {
                return $this->response(400, "Id sahəsi vacibdir");
            }

            return $this->response(404, "Bu nömrəli Aksiya tapilmadı");
        } else {
            return $this->response(403, "Bu əməliyyatı icra etmək üçün icazəniz yoxdur");
        }
    }

}