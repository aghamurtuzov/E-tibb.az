<?php

namespace api\modules\general\controllers;

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

class AdsController extends MainController
{

    public $modelClass = '';
    public $customPath = 'ads';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Ads info
     * https://e-tibb.az/api/general/ads/info/50?type=1
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

        if(strpos($ads['user_info']," "))
        {
            $explodeUser = explode(" ",$ads['user_info']);
            $name = $explodeUser[0];
            $surname = $explodeUser[1];
        }
        else
        {
            $name = $ads['user_info'];
            $surname = '';
        }

        $ads['name'] = $name;
        $ads['surname'] = $surname;

        unset($ads['user_info']);

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
     * https://e-tibb.az/api/general/ads
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

        $search = null;
        if(isset($_GET['search']) && !empty($_GET['search']))
        {
            if(strlen($_GET['search'])>=3)
                $search = strip_tags(Yii::$app->request->get('search'));
            else
                return $this->response(200,"Axtarış üçün minimum 3 simvol daxil edin.");
        }

        $model = new AdsApiModel();

        $totalCount = !empty($id) ? $model->AdsListCount($id,$type,$search) : $model->AdsCount($search);

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = !empty($id) ? $model->AdsList($id,$limits,$type,$search) : $model->AdsAll($limits,$search);

        if(!empty($list))
        {
            foreach($list as $key => $val)
            {
                $list[$key]['text']     = strip_tags($list[$key]['text']);
                $list[$key]['status_name'] = $status[$list[$key]['status']];
                $list[$key]['image']     = Yii::$app->params['baseDomainUrl'].Yii::$app->params['path.upload']."/ads/small/".$list[$key]['image'];

                if(strpos($list[$key]['user_info']," "))
                {
                    $explodeUser = explode(" ",$list[$key]['user_info']);
                    $name = $explodeUser[0];
                    $surname = $explodeUser[1];
                }
                else
                {
                    $name = $list[$key]['user_info'];
                    $surname = '';
                }

                $list[$key]['name'] = $name;
                $list[$key]['surname'] = $surname;

                unset($list[$key]['user_info']);


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
     * Ads yaratmaq
     * https://e-tibb.az/api/general/ads/create
     */
    public function actionCreate()
    {
        Yii::$app->db->schema->refresh();
        $model = new SiteAds();
        $post = Yii::$app->request->post();

        if(!empty($post) && $model->load($post,''))
        {
            $model->slug = Functions::slugify($model->title);

            $model->user_info = trim(trim($model->name)." ".trim($model->surname));

            $model->validate();

            /** Check Main image **/
            $photo = UploadedFile::getInstanceByName('image');
            if(empty($photo))
            {
                $model->addError('image','Şəkil elave edin');
            }else{
                $imageUpload = new ImageUpload();
                if(!$imageUpload->validate($photo))
                {
                    $model->addError('image','Şəkilin yüklənməsi zamanı xəta baş verdi');
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
                    $updatePhoto->image = $uploadedFile;
                    $updatePhoto->save(false);
                }

                return $this->response(200,'Məlumat uğurla əlavə olundu');
            }

        }

        return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi');
    }

    /**
     * Ads duzelis et
     * https://e-tibb.az/api/general/ads/edit
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

        if(!empty($post) && $model->load($post,''))
        {
            $model->validate();

            $model->user_info = trim(trim($model->name)." ".trim($model->surname));

            /** Check main image */
            $deletedImages = $model->deletedImages;
            $photo = UploadedFile::getInstanceByName('image');
            if(empty($photo))
            {
                if(empty($oldModel->image) || !empty($deletedImages))
                {
                    $model->addError('image','Şəkil elave edin');
                }
            }else{
                $imageUpload = new ImageUpload();
                if(!$imageUpload->validate($photo))
                {
                    $model->addError('image','Şəkilin yüklənməsi zamanı xəta baş verdi');
                }
            }

            /** Delete main image */
            if(empty($model->errors))
            {
                if(!empty($deletedImages))
                {
                    if(!empty($oldModel->image))
                    {
                        $imageUpload = new ImageUpload();
                        $imageUpload->deleteFile([$this->customPath.'/'.$oldModel->image]);
                        $imageUpload->deleteFile([$this->customPath.'/small/'.$oldModel->image]);

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
                $model->image = $oldModel->image;
            }

            if($model->save(false))
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
                    $updatePhoto->image = $uploadedFile;
                    $updatePhoto->save(false);
                }

                return $this->response(200,'Məlumat uğurla yeniləndi');

            }
        }

        return $this->response(400,'Məlumatın yenilənməsi zamanı xəta baş verdi');
    }

    /**
     * Ads status
     * https://e-tibb.az/api/general/ads/status
     */
    public function actionStatus()
    {
        $result = SiteAds::get_Status();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Ads status
     * https://e-tibb.az/api/general/ads/bloods
     */
    public function actionBloods()
    {
        $bloodType = new SiteAds();
        $result = $bloodType->getBloodTypes();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Ads tipleri
     * https://e-tibb.az/api/general/ads/types
     */
    public function actionTypes()
    {
        $result = SiteAds::get_Type();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Ads sil
     * https://e-tibb.az/api/general/ads/del
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

}