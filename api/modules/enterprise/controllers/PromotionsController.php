<?php

namespace api\modules\enterprise\controllers;

use api\models\AdminLog;
use api\modules\enterprise\models\search\PromotionsSearch;
use yii;
use yii\web\UploadedFile;
use api\components\Functions;
use api\components\Pagination;
use api\components\ImageUpload;
use api\models\SitePromotions;
use api\models\SiteUsers;
use api\models\SiteUsedPromocode;
use api\modules\enterprise\models\PromotionsApiModel;
use api\modules\enterprise\controllers\MainController;

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
        $id = Yii::$app->session->get('userID');

        $model = new PromotionsApiModel();

        $totalCount = !empty($id) ? $model->UsedPromocodesListCount($id,PromotionsApiModel::TYPE_ENTERPRISE) : $model->UsedPromocodesCount();

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = !empty($id) ? $model->UsedPromocodesList($id,$limits,PromotionsApiModel::TYPE_ENTERPRISE) : $model->UsedPromocodes($limits);

        if(!empty($list))
        {
            foreach($list as $key => $val)
            {
                $list[$key]['created_at'] = Yii::$app->formatter->asTime($list[$key]['created_at'],'php: d M Y');
            }
        }

        $data['list'] = $list;

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages,$data);

        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Promokod yoxla
     * https://e-tibb.az/api/enterprise/promotions/check
     * promocode = 30xx12
     */
    public function actionCheck()
    {
        $used = Yii::$app->request->post('used');
        $promocode = Yii::$app->request->post('promocode');
        if(empty($promocode))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        if(strpos($promocode,'xx'))
        {
            $exp = explode('xx',$promocode);
            $promotion_id = intval($exp[0]);
            $user_id = intval($exp[1]);
            if($promotion_id>0 && $user_id>0)
            {
                $promotion = $this->findModel($promotion_id);
                if(!empty($promotion))
                {
                    $usedPromotion = SiteUsedPromocode::find()->where(['promotion_id'=>$promotion_id,'user_id'=>$user_id])->one();
                    if(empty($usedPromotion))
                    {
                        $check = SiteUsers::findOne($user_id);
                        if(!empty($check))
                        {
                            $result['name'] = $check['name'];
                            $result['user_id'] = $check['id'];
                            $result['promocode'] = $promocode;
                            if(!empty($used))
                            {
                                $usedPromocode = new SiteUsedPromocode();
                                $usedPromocode->promocode    = $promocode;
                                $usedPromocode->promotion_id = $promotion_id;
                                $usedPromocode->user_id      = $user_id;
                                $usedPromocode->created_at   = date("Y-m-d H:i:s");
                                $usedPromocode->save();
                                return $this->response(200,"Promokod istifadə olundu",$result);
                            }else{
                                return $this->response(200,"Promokod mövcuddur",$result);
                            }
                        }
                    }else{
                        return $this->response(400,"Təəssüfki daxil etdiyiniz promokod artıq istifadə olunub");
                    }
                }
            }
        }
        return $this->response(400,"Heçbir məlumat tapılmadı");
    }

    /**
     * Aksiya info
     * https://e-tibb.az/api/enterprise/promotions/info/37
     */
    public function actionInfo()
    {
        $connectID = intval(Yii::$app->request->get('id'));
        $userId = Yii::$app->session->get('userID');
        if(empty($connectID))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $status = SitePromotions::get_Status();
        $model  = new PromotionsApiModel();

        /** Aksiya info */
        $promotion = PromotionsApiModel::PromotionByConnect($connectID,$userId,PromotionsApiModel::TYPE_ENTERPRISE);
        if(empty($promotion))
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $promotion['byPromotion'] = $promotion['organizer'];
        //$promotion['content']     = strip_tags($promotion['content']);
        $promotion['status_name'] = $status[$promotion['status']];
        $promotion['discount']    = empty($promotion['discount']) ? '' : $promotion['discount'];
        if(!empty($promotion['connect_id']))
        {
            $organizer = $model->getOrganizer($promotion['connect_id'],$promotion['type']);
            if(!empty($organizer))
            {
                $promotion['byPromotion'] = $organizer;
            }
        }

        $promotion = $this->ResultList($promotion,'promotions');

        return $this->response(200,"Məlumat mövcuddur",$promotion);

    }

    /**
     * Aksiyalar search
     * https://e-tibb.az/api/enterprise/promotions/search
     * https://e-tibb.az/api/enterprise/promotions/search?page=1&count=5
     * date_start = 01.01.2020
     * date_end = 01.01.2020
     * status=1
     */
    public function actionSearch()
    {
        Yii::$app->db->schema->refresh();
        $model = new PromotionsSearch();
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
     * Istifade olunmus promokodlar search
     * https://e-tibb.az/api/enterprise/promotions/used-list-search
     * https://e-tibb.az/api/enterprise/promotions/used-list-search?page=1&count=5
     * date_start = 01.01.2020
     */
    public function actionUsedListSearch()
    {
        Yii::$app->db->schema->refresh();
        $model = new PromotionsSearch();
        $search = Yii::$app->request->get();

        if ($model->load($search, '')) {

            $model->validate();
            if (!empty($model->errors)) {
                return $this->response(400, 'Məlumatın axtarılması zamanı xəta baş verdi', $model->errors);
            }

            $totalCount = $model->usedSearchCount($search);

            if($totalCount['count'] <= 0)
            {
                return $this->response(400,"Heçbir məlumat tapılmadı");
            }

            $pagination = new Pagination(['totalCount' => $totalCount]);

            $limits = $pagination->getLimits();

            $result = $model->usedSearch($search,$limits);

            $data['list'] = $result;

            $pages = $pagination->getPaginationInfo();

            $result = array_merge($pages,$data);

            return $this->response(200, "Məlumat mövcuddur", $result);
        }
    }

    /**
     * Aksiyalar
     * https://e-tibb.az/api/enterprise/promotions
     * id=1
     * type=1
     * page=1
     * count=5
     */
    public function actionIndex()
    {
        $id     = intval(Yii::$app->request->get('id'));
        $type   = intval(Yii::$app->request->get('type'));
        $enterpriseID = Yii::$app->session->get('userID');

        if(empty($id))
        {
            $id = $enterpriseID;
            $type = PromotionsApiModel::TYPE_ENTERPRISE;
        }

        $status = SitePromotions::get_Status();
        $type   = empty($type) ? 1 : $type;

        $model = new PromotionsApiModel();

        $totalCount = $type == PromotionsApiModel::TYPE_ENTERPRISE ? $model->PromotionsListCount($id,$type) : $model->PromotionsListCount2($enterpriseID,$id,$type);

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $type == PromotionsApiModel::TYPE_ENTERPRISE ? $model->PromotionsList($id,$limits,$type) : $model->PromotionsList2($enterpriseID,$id,$limits,$type);

        if(!empty($list))
        {
            foreach($list as $key => $val)
            {
                $list[$key]['byPromotion'] = $list[$key]['organizer'];
                $list[$key]['content']     = strip_tags($list[$key]['content']);
                $list[$key]['status_name'] = $status[$list[$key]['status']];
                if(!empty($val['connect_id']))
                {
                    $organizer = $model->getOrganizer($val['connect_id'],$val['type']);
                    if(!empty($organizer))
                    {
                        $list[$key]['byPromotion'] = $organizer;
                    }
                }
            }
        }

        $data['list'] = $this->ResultList($list,'promotions');

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages,$data);

        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Aksiya yaratmaq
     * https://e-tibb.az/api/enterprise/promotions/create
     */
    public function actionCreate()
    {
        Yii::$app->db->schema->refresh();
        $model = new SitePromotions();
        $post = Yii::$app->request->post();

        if(!empty($post) && !empty($post) && $model->load($post,''))
        {

            $mode = 'add';

            $model->slug = Functions::slugify($model->headline);
            $model->date = date('Y-m-d');

            $model->type = PromotionsApiModel::TYPE_ENTERPRISE;
            $model->connect_id = Yii::$app->session->get('userID');

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
                /** Type */
                if($model->type == 1)
                {
                    $model->change_Promotion($model->connect_id,$model->type,$mode);
                }else if($model->type == 2){
                    $model->change_Promotion($model->connect_id,$model->type,$mode);
                }

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

                return $this->response(200,'Məlumat uğurla əlavə olundu');
            }

        }

        return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi');
    }

    /**
     * Aksiya duzelis et
     * https://e-tibb.az/api/enterprise/promotions/edit
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

        if(empty($model))
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        if(!empty($post) && !empty($post) && $model->load($post,''))
        {
            $mode = 'edit';
            $model->date_start  = date("Y-m-d",strtotime($model->date_start));
            $model->date_end    = date("Y-m-d",strtotime($model->date_end));

            $model->status = $oldModel->status;

            $model->type = PromotionsApiModel::TYPE_ENTERPRISE;
            $model->connect_id = Yii::$app->session->get('userID');

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
                        $imageUpload->deleteFile([$this->customPath.'/small/'.$oldModel->photo]);

                        $updatePhoto = $this->findModel($model->id);
                        $updatePhoto->photo = '';
                        $updatePhoto->save(false);
                    }
                }
            }

            if(!empty($model->errors))
            {
                return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi',$model->errors);
            }

            if(empty($photo))
            {
                $model->photo = $oldModel->photo;
            }

            if($model->save(false))
            {
                if($model->type == 1)
                {
                    if($oldModel->connect_id != $model->connect_id)
                    {
                        $model->change_Promotion($model->connect_id,$model->type,$mode,$oldModel->connect_id);
                    }
                }else if($model->type == 2){
                    if($oldModel->connect_id != $model->connect_id)
                    {
                        $model->change_Promotion($model->connect_id,$model->type,$mode,$oldModel->connect_id);
                    }
                }

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

                return $this->response(200,'Məlumat uğurla yeniləndi');

            }
        }

        return $this->response(400,'Məlumatın yenilənməsi zamanı xəta baş verdi');
    }

    /**
     * Aksiya status
     * https://e-tibb.az/api/enterprise/promotions/status
     */
    public function actionStatus()
    {
        $result = SitePromotions::get_Status();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Aksiya tipleri
     * https://e-tibb.az/api/enterprise/promotions/types
     */
    public function actionTypes()
    {
        $result = SitePromotions::get_Type();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Aksiya sil
     * https://e-tibb.az/api/enterprise/promotions/del
     * ids array
     */
    public function actionDel()
    {
        $ids    = Yii::$app->request->post('ids');
        $connectID = Yii::$app->session->get('userID');
        if(empty($ids))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $mode  = 'delete';
        foreach($ids as $key => $id)
        {
            $model = PromotionsApiModel::PromotionByConnect($id,$connectID,PromotionsApiModel::TYPE_ENTERPRISE);
            if(!empty($model))
            {
                $update = PromotionsApiModel::PromotionsDelete($id);
                if($update)
                {
                    $sp = new SitePromotions();
                    if($model['type'] == 1)
                    {
                        $sp->change_Promotion($model['connect_id'],$model['type'],$mode);
                    }else if($model['type'] == 2){
                        $sp->change_Promotion($model['connect_id'],$model['type'],$mode);
                    }
                }
            }
        }
        return $this->response(200,"Aksiya(lar) silindi");
    }

    protected function findModel($id)
    {
        $connectID = Yii::$app->session->get('userID');
        if(($model = SitePromotions::find()->where(['id'=>$id,'connect_id'=>$connectID,'type'=>PromotionsApiModel::TYPE_ENTERPRISE])->one()) !== null){
            return $model;
        }
    }


    // Delete functions

    /**
     * Aksiya sil
     * https://e-tibb.az/api/doctor/promotions/delete-one
     * method: POST
     */
    public function actionDeleteOne() {
        $id = intval(Yii::$app->request->post('id'));
        if($id) {
            $connectID = Yii::$app->session->get('userID');
            $data = PromotionsApiModel::PromotionByConnect($id,$connectID,PromotionsApiModel::TYPE_DOCTOR);

            if(!empty($data)) {
                PromotionsApiModel::PromotionsDelete($id);
                AdminLog::write(['id' => $id, 'name' => $data['headline']]);

                return $this->response(200, "Məlumat silindi");
            }
        }

        return $this->response(404, "Bu nömrəli aksiya tapilmadı");
    }


    /**
     * Aksiyalari toplu sil
     * https://e-tibb.az/api/doctor/promotions/all-delete
     * method: POST
     * id: array
     */
    public function actionAllDelete() {
        $ids = Yii::$app->request->post('id');
        if($ids && is_array($ids)) {
            foreach($ids as $id) {
                if(is_numeric($id)) {
                    $connectID = Yii::$app->session->get('userID');
                    $data = PromotionsApiModel::PromotionByConnect($id,$connectID,PromotionsApiModel::TYPE_DOCTOR);

                    if(!empty($data)) {
                        PromotionsApiModel::PromotionsDelete($id);
                        AdminLog::write(['id' => $id, 'name' => $data['headline']]);
                    }
                }
            }
            return $this->response(200, "Məlumat silindi");
        } else {
            return $this->response(400, "Id sahəsi vacibdir");
        }

    }

}
