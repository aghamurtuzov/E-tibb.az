<?php

namespace api\modules\general\controllers;

use api\models\AdminLog;
use api\modules\general\models\search\DonateSearch;
use yii;
use yii\web\UploadedFile;
use api\components\Functions;
use api\components\Pagination;
use api\components\ImageUpload;
use api\models\SiteAds;
use api\models\SiteUsers;
use api\modules\general\models\AdsApiModel;
use api\modules\general\controllers\MainController;

/**
 * Ads API
 */

class DonateController extends MainController
{

    public $modelClass = '';
    public $customPath = 'ads';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Ads info
     * https://e-tibb.az/api/general/donate/info/50?type=1
     */
    public function actionInfo()
    {

        $id = intval(Yii::$app->request->get('id'));

        $isBlood = intval(Yii::$app->request->get('type'));

        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $status = SiteAds::get_Status();


        $model  = new AdsApiModel();


//        return $isBlood;
        /** Ads info */
        $ads = AdsApiModel::Ads($id,$isBlood);

        if(empty($ads))
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $ads['text']     = strip_tags($ads['text']);
        $ads['image']     = Yii::$app->params['baseDomainUrl'].Yii::$app->params['path.upload']."/ads/small/".$ads['image'];
        $ads['status_name'] = $status[$ads['status']];
        $ads['blood_type']    = empty($ads['blood_type']) ? '' : $ads['blood_type'];

//        if(!empty($ads['user_id']))
//        {
//            $organizer = $model->getOrganizer($ads['user_id'],$ads['type']);
//            if(!empty($organizer))
//            {
//                $ads['user_info'] = $organizer;
//            }
//        }

        $ads = $this->ResultList($ads,'ads');



        return $this->response(200,"Məlumat mövcuddur",$ads);

    }

    /**
     * Ads
     * https://e-tibb.az/api/general/donate
     * id=1
     * type=1
     * page=1
     * count=5
     */
    public function actionIndex()
    {
        $id     = intval(Yii::$app->request->get('id'));
        $type   = intval(Yii::$app->request->get('type'));
        $status = SiteAds::get_Status();
        $type   = empty($type) ? 1 : $type;

        $model = new AdsApiModel();

        $statusType = (Yii::$app->request->get('status') == null) ? 'all' : Yii::$app->request->get('status');

        $totalCount = !empty($id) ? $model->AdsListCount($id,$type, $statusType) : $model->AdsCount($statusType);

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = !empty($id) ? $model->AdsList($id,$limits,$type,$statusType) : $model->AdsAll($limits, $statusType);

        if(!empty($list))
        {
            foreach($list as $key => $val)
            {
                $list[$key]['text']     = strip_tags($list[$key]['text']);
                $list[$key]['status_name'] = $status[$list[$key]['status']];
                $list[$key]['image']     = Yii::$app->params['baseDomainUrl'].Yii::$app->params['path.upload']."/ads/small/".$list[$key]['image'];
                $list[$key]['created_at'] = Yii::$app->formatter->asTime($list[$key]['created_at'],'php: d/m/Y H:i');
                $list[$key]['updated_at'] = Yii::$app->formatter->asTime($list[$key]['updated_at'],'php: d/m/Y H:i');


//                if(!empty($val['user_id']))
//                {
//                    $organizer = $model->getOrganizer($val['user_id'],$val['type']);
//                    if(!empty($organizer))
//                    {
//                        $list[$key]['user_info'] = $organizer;
//                    }
//                }
            }
        }

        $data['list'] = $this->ResultList($list,'ads');



        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages,$data);


        return $this->response(200,"Məlumat mövcuddur",$result);
    }


    /**
     * Ads search
     * https://e-tibb.az/api/general/donate/search
     * https://e-tibb.az/api/general/donate/search?page=1&count=5
     * date = 01.01.2020
     * status = 1
     */
    public function actionSearch()
    {
        Yii::$app->db->schema->refresh();
        $model = new DonateSearch();
        $search = Yii::$app->request->get();
        $status = SiteAds::get_Status();


        if ($model->load($search, '')){
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

            if(!empty($result))
            {
                foreach($result as $key => $val)
                {
                    $result[$key]['text']     = strip_tags($result[$key]['text']);
                    $result[$key]['status_name'] = $status[$result[$key]['status']];
                    $result[$key]['image']     = Yii::$app->params['baseDomainUrl'].Yii::$app->params['path.upload']."/ads/small/".$result[$key]['image'];
                    $result[$key]['created_at'] = Yii::$app->formatter->asTime($result[$key]['created_at'],'php: d/m/Y H:i');
                    $result[$key]['updated_at'] = Yii::$app->formatter->asTime($result[$key]['updated_at'],'php: d/m/Y H:i');

                }
            }

            $data['list'] = $this->ResultList($result,'ads');

            $pages = $pagination->getPaginationInfo();

            $result = array_merge($pages,$data);

            return $this->response(200, "Məlumat mövcuddur", $result);
        }

    }



    /**
     * Ads yaratmaq
     * https://e-tibb.az/api/general/donate/create
     */
    public function actionCreate()
    {

        Yii::$app->db->schema->refresh();
        $model = new SiteAds();
        $post = Yii::$app->request->post();

        if(!empty($post) && $model->load($post,''))
        {
            $model->validate();

            $model->slug = Functions::slugify($model->title);
            $model->phone_prefix = "994";
            $model->email = trim($model->email);

//            /** Check Main image **/
//            $photo = UploadedFile::getInstanceByName('image');
//            if(empty($photo))
//            {
//                $model->addError('image','Şəkil elave edin');
//            }else{
//                $imageUpload = new ImageUpload();
//                if(!$imageUpload->validate($photo))
//                {
//                    $model->addError('image','Şəkilin yüklənməsi zamanı xəta baş verdi');
//                }
//            }

            if(!empty($model->errors))
            {
                return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi',$model->errors);
            }

            if($model->save(false))
            {
//                /** Main image **/
//                if(!empty($photo))
//                {
//                    $imageUpload = new ImageUpload();
//                    $uploadedFile = $imageUpload->saveFile($photo, [
//                        'path.save' => $this->customPath,
//                        'resize.img'=>[350,231],
//                        'resize.thumb'=>[401,265]
//                    ]);
//                    $updatePhoto = $model;
//                    $updatePhoto->image = $uploadedFile;
//                    $updatePhoto->save(false);
//                }

                AdminLog::write(['id' => $model->id, 'name' => $model->user_info]);

                return $this->response(200,'Məlumat uğurla əlavə olundu');
            }

        }

        return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi');
    }

    /**
     * Ads duzelis et
     * https://e-tibb.az/api/general/donate/edit
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
            return $this->response(400,"Heçbir məlumat tapılmadı");
        }

        if(!empty($post) && $model->load($post,''))
        {
            $model->validate();
            $model->phone_prefix = "994";
            $model->email = trim($model->email);

            /** Check main image */
//            $deletedImages = $model->deletedImages;
//            $photo = UploadedFile::getInstanceByName('image');
//            if(empty($photo))
//            {
//                if(empty($oldModel->image) || !empty($deletedImages))
//                {
//                    $model->addError('image','Şəkil elave edin');
//                }
//            }else{
//                $imageUpload = new ImageUpload();
//                if(!$imageUpload->validate($photo))
//                {
//                    $model->addError('image','Şəkilin yüklənməsi zamanı xəta baş verdi');
//                }
//            }

            /** Delete main image */
//            if(empty($model->errors))
//            {
//                if(!empty($deletedImages))
//                {
//                    if(!empty($oldModel->image))
//                    {
//                        $imageUpload = new ImageUpload();
//                        $imageUpload->deleteFile([$this->customPath.'/'.$oldModel->image]);
//                        $imageUpload->deleteFile([$this->customPath.'/small/'.$oldModel->image]);
//
//                        $updatePhoto = $this->findModel($model->id);
//                        $updatePhoto->photo = '';
//                        $updatePhoto->save(false);
//                    }
//                }
//            }

            if(!empty($model->errors))
            {
                return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi',$model->errors);
            }

//            if(empty($photo))
//            {
//                $model->image = $oldModel->image;
//            }

            if($model->save(false))
            {
                AdminLog::write(['id' => $model->id, 'name' => $model->user_info]);
                /** Main image **/
//                if(!empty($photo))
//                {
//                    $imageUpload = new ImageUpload();
//                    $uploadedFile = $imageUpload->saveFile($photo, [
//                        'path.save' => $this->customPath,
//                        'resize.img'=>[350,231],
//                        'resize.thumb'=>[401,265]
//                    ]);
//                    $updatePhoto = $model;
//                    $updatePhoto->image = $uploadedFile;
//                    $updatePhoto->save(false);
//                }

                return $this->response(200,'Məlumat uğurla yeniləndi');

            }
        }

        return $this->response(400,'Məlumatın yenilənməsi zamanı xəta baş verdi');
    }

    /**
     * Ads status
     * https://e-tibb.az/api/general/donate/status
     */
    public function actionStatus()
    {
        $result = SiteAds::get_Status();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Ads status
     * https://e-tibb.az/api/general/donate/bloods
     */
    public function actionBloods()
    {
        $bloodType = new SiteAds();
        $result = $bloodType->getBloodTypes();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Ads tipleri
     * https://e-tibb.az/api/general/donate/types
     */
    public function actionTypes()
    {
        $result = SiteAds::get_Type();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Ads sil
     * https://e-tibb.az/api/general/donate/del
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
            $model = AdsApiModel::Ads($id);
            if(!empty($model))
            {
                AdsApiModel::AdsDelete($id);
            }
        }
        return $this->response(200,"Aksiya(lar) silindi");
    }

    protected function findModel($id)
    {
        if(($model = SiteAds::findOne($id)) !== null){
            return $model;
        }
    }



    // Delete functions
    /**
     * Ads sil
     * https://e-tibb.az/api/general/donate/delete-one/{id}
     * method: DELETE
     * id: integer
     */
    public function actionDeleteOne() {
        $id = Yii::$app->request->post('id');
        if($id) {
            $data = AdsApiModel::AdsFind($id);
            if(!empty($data)) {
                AdsApiModel::AdsDelete($id);
                AdminLog::write(['id' => $data['id'], 'name' => $data['user_info']]);

                return $this->response(200, "Məlumat silindi");
            }
        }

        return $this->response(404, "Bu nömrəli elan tapilmadı");
    }


    /**
     * Ads toplu sil
     * https://e-tibb.az/api/general/donate/all-delete
     * method: POST
     * id: array
     */
    public function actionAllDelete() {
        $ids = Yii::$app->request->post('id');
        if($ids && is_array($ids)) {
            foreach($ids as $id) {
                if(is_numeric($id)) {
                    $data = AdsApiModel::AdsFind($id);
                    if(!empty($data)) {
                        AdsApiModel::AdsDelete($id);
                        AdminLog::write(['id' => $data['id'], 'name' => $data['user_info']]);
                    }
                }
            }

            return $this->response(200, "Məlumat silindi");
        } else {
            return $this->response(400, "Id sahəsi vacibdir");
        }

        return $this->response(404, "Bu nömrəli elan tapilmadı");
    }



    /**
     * Ads daimi sil
     * https://e-tibb.az/api/general/donate/base-delete-one
     * method: POST
     * id: integer
     */
    public function actionBaseDeleteOne() {
        if(Yii::$app->userCheck->can("superadmin")) {
            $id = Yii::$app->request->post('id');
            if ($id) {
                $data = AdsApiModel::AdsFind($id);
                if (!empty($data)) {
                    AdsApiModel::AdsDeletePermanently($id);
                    AdminLog::write(['id' => $data['id'], 'name' => $data['user_info']]);
                    return $this->response(200, "Məlumat silindi");
                }
            }

            return $this->response(404, "Bu nömrəli elan tapilmadı");
        } else {
            return $this->response(403, "Bu əməliyyatı icra etmək üçün icazəniz yoxdur");
        }
    }

    /**
     * Ads daimi sil
     * https://e-tibb.az/api/general/donate/all-base-delete
     * method: POST
     * id: array
     */
    public function actionAllBaseDelete() {
        if(Yii::$app->userCheck->can("superadmin")) {
            $ids = Yii::$app->request->post('id');
            if ($ids && is_array($ids)) {
                foreach ($ids as $id) {
                    if (is_numeric($id)) {
                        $data = AdsApiModel::AdsFind($id);
                        if (!empty($data)) {
                            AdsApiModel::AdsDeletePermanently($id);
                            AdminLog::write(['id' => $data['id'], 'name' => $data['user_info']]);
                        }
                    }
                }

                return $this->response(200, "Məlumat silindi");
            } else {
                return $this->response(400, "Id sahəsi vacibdir");
            }

            return $this->response(404, "Bu nömrəli elan tapilmadı");
        } else {
            return $this->response(403, "Bu əməliyyatı icra etmək üçün icazəniz yoxdur");
        }
    }



}