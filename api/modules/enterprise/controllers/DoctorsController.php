<?php

namespace api\modules\enterprise\controllers;

use api\modules\enterprise\models\search\DoctorsSearch;
use yii;
use yii\web\UploadedFile;
use api\components\Pagination;
use api\models\SiteDoctors;
use api\models\SiteUsers;
use api\models\SiteDoctorSpecialist;
use api\models\SiteSpecialists;
use api\models\SiteSosialLinks;
use api\models\SitePhoneNumbers;
use api\models\SiteDoctorWorkplaces;
use api\models\SiteDoctorFilesModel;
use api\components\Functions;
use api\components\ImageUpload;
use api\models\SiteDoctorsModel;
use api\models\SitePhoneNumbersModel;
use api\models\SiteSocialLinksModel;
use api\models\PackagesServicesModel;
use api\modules\enterprise\models\DoctorsApiModel;
use api\modules\enterprise\models\GeneralApiModel;
use api\modules\enterprise\models\EnterpriseDoctor;
use api\modules\enterprise\controllers\MainController;

/**
 * Doctors API
 */

class DoctorsController extends MainController
{

    const TYPE = 1;
    public $modelClass = '';
    public $customPath = 'doctors';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
    
    /**
     * Hekimler
     * https://e-tibb.az/api/enterprise/doctors
     * https://e-tibb.az/api/enterprise/doctors?page=1&count=5
     */
    public function actionIndex()
    {
        $enterpriseID = Yii::$app->session->get('userID');

        $model = new DoctorsApiModel();

        $totalCount = $model->DoctorsListCount($enterpriseID);

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $status = SiteDoctors::get_Status();

        $list = $model->DoctorsList($enterpriseID,$limits);

        if(!empty($list))
        {
            foreach($list as $key => $val)
            {
                $list[$key]['status_name'] = $status[$list[$key]['status']];
            }
        }

        $data['list'] = $this->ResultList($list);

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages,$data);

        return $this->response(200,"Məlumat mövcuddur",$result);

    }

    /**
     * Hekim yaratmaq
     * https://e-tibb.az/api/enterprise/doctors/create
     *  name:Java
    //vip:0
    experience1:2017
    specialists[0]:29
    specialists[1]:47
    email:java@ss.aa
    home_doctor:0
    child_doctor:1
    sosial_links[0][link]:https://www.facebook.com/Torakal-Cerrah-Nurlan-Alizade-677507572695956/
    phone_numbers[0][type]:1
    phone_numbers[0][number]:2884261
    workplaces_list[0][name]:Merkezi gomruk hospitali
    workplaces_list[0][address]:Azerbaijan,Baku
    gender:1
    degree:1
    about:test
    birthday:2017-09-10
    skype:dfgdfgdfgdfg
     */
    public function actionCreate()
    {

        Yii::$app->db->schema->refresh();

        $enterpriseID = Yii::$app->session->get('userID');
        $model  = new SiteDoctors();
        $user   = new SiteUsers();
        $post = Yii::$app->request->post();

        if(!empty($post) && !empty($post) && $model->load($post,''))
        {

            /** Create User */
            $lastRow            = SiteUsers::find()->select('id')->orderBy(['id' => SORT_DESC])->one();
            $pass               = rand(111111,999999);
            $user->name         = $model->name;
            $user->unique_id    = str_pad($lastRow['id'] + 1, 7, 0, STR_PAD_LEFT);
            $user->email        = $model->email;
            $user->phone_number = $model->phone_numbers[0]['number'];
            $user->status       = 1;
            $user->created_at   = time();
            $user->last_login   = date("Y-m-d H:i:s");
            $user->type         = self::TYPE;
            $user->birthday     = $model->birthday;
            $user->password     = $pass;
            $user->setPassword($user->password);
            $user->generateAuthKey();

//            $user->validate();

            $datetime = date('Y-m-d', strtotime("+1 month", strtotime(date('Y-m-d'))));

            $model->expires         = $datetime;
            $model->slug            = Functions::slugify($model->name);
            $model->vip             = 0;
            $model->status          = 1;
            $model->published_time  = date('Y-m-d H:i:s');
            $model->modified_time   = date('Y-m-d H:i:s');

            if($model->home_doctor){ $model->feature = 1; }

            if($model->child_doctor){ $model->feature = 2; }

            if($model->home_doctor && $model->child_doctor){ $model->feature = 3; }

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

            /** Check Diplomas*/
            $diplomas = UploadedFile::getInstancesByName('dp_files');
            if(empty($diplomas))
            {
                $model->addError('dp_files','Diplom elave edin');
            }else{
                $imageUpload = new ImageUpload();
                foreach($diplomas as $key => $diplom)
                {
                    if(!$imageUpload->validate($diplom))
                    {
                        $model->addError('dp_files','Diplom yüklənməsi zamanı xəta baş verdi');
                    }
                }
            }

            if(!empty($model->errors))
            {
                return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi',$model->errors);
            }

            if($model->save())
            {
                /** User_id */
                if($user->save())
                {
                    $model2 = SiteDoctors::findOne($model->id);
                    $model2->user_id = $user->id;
                    $model2->save(false);
                }

                /** Doctor add enterprise */
                $AddEnterprise = new EnterpriseDoctor();
                $AddEnterprise->enterprise_id = $enterpriseID;
                $AddEnterprise->doctor_id = $model->id;
                $AddEnterprise->save();

                /** Specialists **/
                if (is_array($model->specialists)) {
                    foreach ($model->specialists as $key => $val) {
                        $doctors_specialist = new SiteDoctorSpecialist();
                        $doctors_specialist->doctor_id = $model->id;
                        $doctors_specialist->specialist_id = $val;
                        if ($doctors_specialist->save()) {
                            $siteSpc = SiteSpecialists::find()->where(['id' => $val])->one();
                            $siteSpc->count = $siteSpc->count + 1;
                            $siteSpc->save();
                        }
                    }
                }

                /** Sosial links **/
                if (isset($model->sosial_links[0]['link']) && !empty($model->sosial_links[0]['link'])) {
                    foreach ($model->sosial_links as $key => $val) {
                        if (!empty($val['link'])) {
                            $sosial_links = new SiteSosialLinks();
                            $sosial_links->connect_id = $model->id;
                            $sosial_links->link = $val['link'];
                            $sosial_links->link_type = $val['type'];
                            $sosial_links->type = self::TYPE;
                            $sosial_links->save();
                        }
                    }
                }

                /** Phone numbers **/
                if(isset($model->phone_numbers[0]['number']) && !empty($model->phone_numbers[0]['number'])) {
                    foreach ($model->phone_numbers as $key => $val) {
                        if (!empty($val['number'])){
                            $phone_numbers = new SitePhoneNumbers();
                            $phone_numbers->connect_id = $model->id;
                            $phone_numbers->number = $val['number'];
                            $phone_numbers->number_type = $val['type'];
                            $phone_numbers->type = self::TYPE;
                            $phone_numbers->save();
                        }
                    }
                }

                /** Work places  **/
                if (isset($model->workplaces_list) && !empty($model->workplaces_list)) {
                    SiteDoctorWorkplaces::deleteAll('doctor_id = :doctor', [':doctor' => $model->id]);
                    foreach ($model->workplaces_list as $val) {
                        $workplaces = new SiteDoctorWorkplaces();
                        if (!empty($val['name']) and !empty($val['address'])) {
                            $workplaces->name = !empty($val['name']) ? $val['name'] : null;
                            $workplaces->address = !empty($val['address']) ? $val['address'] : null;
                            $workplaces->doctor_id = $model->id;
                            $workplaces->save();
                        }
                    }
                }

                /** Main image **/
                if(!empty($photo))
                {
                    $imageUpload = new ImageUpload();
                    $uploadedFile = $imageUpload->saveFile($photo, [
                        'path.save' => $this->customPath,
                        'resize.img' => [300, 265],
                        'resize.thumb' => [120, 106]
                    ]);
                    $updatePhoto = $model;
                    $updatePhoto->photo = $uploadedFile;
                    $updatePhoto->save(false);
                }

                /** Diplomas */
                if(!empty($diplomas))
                {
                    Functions::uploadFiles($diplomas, $model->id, 1, $this->customPath,self::TYPE);
                }

                /** Certificates */
                $certificates = UploadedFile::getInstancesByName('ct_files');
                if(!empty($certificates))
                {
                    Functions::uploadFiles($certificates, $model->id, 2, $this->customPath,self::TYPE);
                }

                /** Doctor Add Enterprise */
                if(isset($model->hospital_id) && !empty($model->hospital_id))
                {
                    GeneralApiModel::EnterpriseAddDoctor($model->hospital_id,$model->id);
                }

                return $this->response(200,'Həkim uğurla əlavə olundu');

            }else{
                return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi');
            }

        }else{
            return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi');
        }

    }

    /**
     * Hekim duzelis et
     * https://e-tibb.az/api/enterprise/doctors/edit
     * id
     * deletedImages = 0 1
     * deletedDiplomas [107,108]
     * deletedCertificates [107,108]
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

            $model->email = $oldModel->email;

            $model->modified_time = date('Y-m-d H:i:s');

            if($model->home_doctor){ $model->feature = 1; }

            if($model->child_doctor){ $model->feature = 2; }

            if($model->home_doctor && $model->child_doctor){ $model->feature = 3; }

            $model->validate();

            /** Work places  */
            if(isset($model->workplaces_list) && !empty($model->workplaces_list) && empty($model->errors))
            {
                SiteDoctorWorkplaces::deleteAll('doctor_id = :doctor',[':doctor' => $model->id]);
                foreach($model->workplaces_list as $val)
                {
                    $workplaces            = new SiteDoctorWorkplaces();
                    if(!empty($val['name']) and !empty($val['address']))
                    {
                        $workplaces->name      = !empty($val['name']) ? $val['name'] : null;
                        $workplaces->address   = !empty($val['address']) ? $val['address'] : null;
                        $workplaces->doctor_id = $model->id;
                        $workplaces->save();
                    }
                }
            }

            /** Specialists */
            $spc_selected_options = $this->getSpecialist($model->id);
            $spc_selected_options = !empty($spc_selected_options) ? $spc_selected_options : [];
            $specialists          = !empty($model->specialists) ? $model->specialists : [];

            $spcDeleteOptions = array_diff($spc_selected_options,$specialists);
            $spcAddOptions    = array_diff($specialists,$spc_selected_options);

            if(!empty($spcAddOptions) && empty($model->errors))
            {
                foreach($spcAddOptions as $key => $val)
                {
                    $doctors_specialist                = new SiteDoctorSpecialist();
                    $doctors_specialist->doctor_id     = $model->id;
                    $doctors_specialist->specialist_id = $val;
                    if($doctors_specialist->save())
                    {
                        $siteSpc = SiteSpecialists::find()->where(['id'=>$val])->one();
                        $siteSpc->count = $siteSpc->count+1;
                        $siteSpc->save();
                    }
                }
            }

            if(!empty($spcDeleteOptions) && empty($model->errors))
            {
                foreach($spcDeleteOptions as $key => $val)
                {
                    $spcDelete = SiteDoctorSpecialist::find()->where(['doctor_id'=>$model->id,'specialist_id'=>$val])->one();
                    if(!empty($spcDelete))
                    {
                        $siteSpc = SiteSpecialists::find()->where(['id'=>$val])->one();
                        $siteSpc->count = $siteSpc->count-1;
                        if($siteSpc->count<0)
                        {
                            $siteSpc->count = 0;
                        }
                        $siteSpc->save();
                        $spcDelete->delete();
                    }
                }
            }

            /** Sosial links */
            if(isset($model->sosial_links) && !empty($model->sosial_links) && empty($model->errors))
            {
                $added_sosial_links = SiteSocialLinksModel::getSocialLinks($model->id,self::TYPE);
                $added_sosial_links = !empty($added_sosial_links) ? $added_sosial_links : [];
                $sosial_links       = !empty($model->sosial_links) && is_array($model->sosial_links) ? $model->sosial_links : [];
                $max                = max(count($added_sosial_links),count($sosial_links));

                for($x=0;$x<$max;$x++)
                {
                    if(isset($added_sosial_links[$x]['type']))
                    {
                        if(!empty($sosial_links[$x]['link']))
                        {
                            if(($added_sosial_links[$x]['type'] != $sosial_links[$x]['type']) || ($added_sosial_links[$x]['link'] != $sosial_links[$x]['link']))
                            {
                                $upd_sosial_links = SiteSosialLinks::findOne($added_sosial_links[$x]['id']);
                                $upd_sosial_links->link       = $sosial_links[$x]['link'];
                                $upd_sosial_links->link_type  = $sosial_links[$x]['type'];
                                $upd_sosial_links->save(false);
                            }
                        }else{
                            $SosialLinksDelete = SiteSosialLinks::findOne($added_sosial_links[$x]['id']);
                            if(!empty($SosialLinksDelete)){ $SosialLinksDelete->delete(); }
                        }
                    }elseif(isset($sosial_links[$x]['type']) && !empty($sosial_links[$x]['link'])){

                        $ins_sosial_links             = new SiteSosialLinks();
                        $ins_sosial_links->connect_id = $model->id;
                        $ins_sosial_links->link       = $sosial_links[$x]['link'];
                        $ins_sosial_links->link_type  = $sosial_links[$x]['type'];
                        $ins_sosial_links->type       = self::TYPE;
                        $ins_sosial_links->save();

                    }

                };

            }

            /** Phone numbers */
            if(isset($model->phone_numbers) && !empty($model->phone_numbers) && empty($model->errors))
            {
                $added_phone_numbers = SitePhoneNumbersModel::getPhones($model->id,self::TYPE);
                $added_phone_numbers = !empty($added_phone_numbers) ? $added_phone_numbers : [];
                $phone_numbers       = !empty($model->phone_numbers) && is_array($model->phone_numbers) ? $model->phone_numbers : [];
                $max                 = max(count($added_phone_numbers),count($phone_numbers));

                for($x=0;$x<$max;$x++)
                {
                    if(isset($added_phone_numbers[$x]['type']))
                    {
                        if(!empty($phone_numbers[$x]['number']))
                        {
                            if(($added_phone_numbers[$x]['type'] != $phone_numbers[$x]['type']) || ($added_phone_numbers[$x]['number'] != $phone_numbers[$x]['number']))
                            {
                                $upd_phone_numbers = SitePhoneNumbers::findOne($added_phone_numbers[$x]['id']);
                                $upd_phone_numbers->number       = $phone_numbers[$x]['number'];
                                $upd_phone_numbers->number_type  = $phone_numbers[$x]['type'];
                                $upd_phone_numbers->save(false);
                            }
                        }else{
                            $del_phone_numbers = SitePhoneNumbers::findOne($added_phone_numbers[$x]['id']);
                            if(!empty($del_phone_numbers)){ $del_phone_numbers->delete(); }
                        }
                    }elseif(isset($phone_numbers[$x]['type']) && !empty($phone_numbers[$x]['number'])){

                        $ins_phone_numbers = new SitePhoneNumbers();
                        $ins_phone_numbers->connect_id  = $model->id;
                        $ins_phone_numbers->number      = $phone_numbers[$x]['number'];
                        $ins_phone_numbers->number_type = $phone_numbers[$x]['type'];
                        $ins_phone_numbers->type        = self::TYPE;
                        $ins_phone_numbers->save();

                    }

                };
            }

            /** Check main image */
            $deletedImages = $model->deletedImages;
            $photo = UploadedFile::getInstanceByName('files');
            if(empty($photo))
            {
                if(empty($oldModel->photo) || !empty($deletedImages))
                {
                    $model->addError('files','Şəkil elave edin');
                }
            }else{
                $imageUpload = new ImageUpload();
                if(!$imageUpload->validate($photo))
                {
                    $model->addError('files','Şəkilin yüklənməsi zamanı xəta baş verdi');
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

            /** Check Diplomas */
            $diplomas = UploadedFile::getInstancesByName('dp_files');
            if(empty($diplomas))
            {
                $addedDiplomas = SiteDoctorFilesModel::find()->where(['connect_id'=>$model->id,'user_type'=>self::TYPE,'type'=>1])->all();
                if(count($addedDiplomas)<1 || (!empty($model->deletedDiplomas) && (count($addedDiplomas) == count($model->deletedDiplomas))))
                {
                    $model->addError('dp_files','Diplom elave edin');
                }
            }else{
                $imageUpload = new ImageUpload();
                foreach($diplomas as $key => $diplom)
                {
                    if(!$imageUpload->validate($diplom))
                    {
                        $model->addError('dp_files','Diplom yüklənməsi zamanı xəta baş verdi');
                    }
                }
            }

            if(!empty($model->errors))
            {
                return $this->response(400,'Məlumatın yenilənməsi zamanı xəta baş verdi',$model->errors);
            }

            if($model->save())
            {

                /** Update update */
                if(!empty($oldModel->user_id))
                {
                    $userUpdate = SiteUsers::findOne($model->user_id);
                    $userUpdate->birthday = $model->birthday;
                    $userUpdate->name = $model->name;
                    $userUpdate->phone_number = $model->phone_numbers[0]['number'];
                    $userUpdate->save(false);
                }

                /** Main image */
                if(!empty($photo))
                {
                    $imageUpload  = new ImageUpload();
                    $uploadedFile = $imageUpload->saveFile($photo,[
                        'path.save' => $this->customPath,
                        'resize.img' => [300, 265],
                        'resize.thumb' => [120, 106]
                    ]);
                    $updatePhoto = $model;
                    $updatePhoto->photo = $uploadedFile;
                    $updatePhoto->save(false);
                }

                /** Diplomas*/
                if(!empty($model->deletedDiplomas))
                {
                    Functions::deleteFiles(1,$model->deletedDiplomas,$this->customPath,$model->id);
                }

                if(!empty($diplomas))
                {
                    Functions::uploadFiles($diplomas, $model->id,1,$this->customPath,self::TYPE);
                }

                /** Certificates*/
                if(!empty($model->deletedCertificates))
                {
                    Functions::deleteFiles(2,$model->deletedCertificates,$this->customPath,$model->id);
                }

                $ctFiles = UploadedFile::getInstancesByName('ct_files');
                if(!empty($ctFiles))
                {
                    Functions::uploadFiles($ctFiles, $model->id,2,$this->customPath,self::TYPE);
                }

                return $this->response(200,'Məlumatın uğurla yeniləndi');
            }
        }

        return $this->response(400,'Məlumatın yenilənməsi zamanı xəta baş verdi');
    }

    public function getSpecialist($id)
    {
        $return = [];
        $data = SiteDoctorSpecialist::find()->where(['doctor_id'=>$id])->all();
        if(!empty($data))
        {
            foreach($data as $key => $val)
            {
                $return[] = $val['specialist_id'];
            }
        }
        return $return;
    }

//    public function getSosialLinks($id)
//    {
//        return SiteSosialLinks::find()->where(['connect_id'=>$id,'type'=>self::TYPE])->all();
//    }
//
//    public function getNumbers($id)
//    {
//        return SitePhoneNumbers::find()->where(['connect_id'=>$id,'type'=>self::TYPE])->all();
//    }

    /**
     * Hekim status
     * https://e-tibb.az/api/enterprise/doctors/list-status
    */
    public function actionListStatus()
    {
        $result = SiteDoctors::getStatus();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Hekim cins
     * https://e-tibb.az/api/enterprise/doctors/list-gender
     */
    public function actionListGender()
    {
        $result = SiteDoctors::getSex();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Akademik derece
     * https://e-tibb.az/api/enterprise/doctors/list-academic-degree
     */
    public function actionListAcademicDegree()
    {
        $result = SiteDoctors::getDegree();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Mutexessisler
     * https://e-tibb.az/api/enterprise/doctors/specialists
     */
    public function actionSpecialists()
    {
        $result = SiteSpecialists::find()->orderBy('name')->all();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Sosial link tipleri
     * https://e-tibb.az/api/enterprise/doctors/list-sosial-link
     */
    public function actionListSosialLink()
    {
        $result = Yii::$app->params['allow.sosial_icons'];
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Nomre tipleri
     * https://e-tibb.az/api/enterprise/doctors/list-phone-number
     */
    public function actionListPhoneNumber()
    {
        $result = Yii::$app->params['allow.number_type'];
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Hekim info
     * https://e-tibb.az/api/enterprise/doctors/info/245
     */
    public function actionInfo()
    {
        $enterpriseID = Yii::$app->session->get('userID');
        $doctorID = intval(Yii::$app->request->get('id'));
        if(empty($doctorID))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        /** Hekim info */
        $doctor = SiteDoctorsModel::getDoctorInfoByEnterprise($enterpriseID,$doctorID);
        if(!empty($doctor))
        {
            $data['doctor'] = $this->ResultList($doctor);
        }else{
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $user = SiteUsers::find()->where(['id'=>$data['doctor']['user_id']])->one();

        $data['doctor']['home_doctor'] = 0;
        $data['doctor']['child_doctor'] = 0;
        $data['doctor']['experience1'] = intval(date('Y'))-intval($data['doctor']['experience1']) > 0 && !empty($data['doctor']['experience1']) ? intval(date('Y'))-intval($data['doctor']['experience1']) : '';

        $feature = $data['doctor']['feature'];

        if($feature == 1){ $data['doctor']['home_doctor'] = 1; }

        if($feature == 2){ $data['doctor']['child_doctor'] = 1; }

        if($feature == 3)
        {
            $data['doctor']['home_doctor'] = 1;
            $data['doctor']['child_doctor'] = 1;
        }

        /** Hekim | User */
        $data['doctor']['birthday'] = $user['birthday'];

        /** Hekim | Mutexessis */
        $data['specialist'] = SiteDoctorsModel::getDoctorSpecialist($doctorID);

        /** Hekim | İs yerleri */
        $data['workplaces'] = SiteDoctorsModel::getWorkplaces($doctorID);

        /** Hekim | Telefon nomreleri */
        $data['phones'] = SitePhoneNumbersModel::getPhones($doctor["id"],self::TYPE);

        /** Hekim | Sosial linkler */
        $data['social_links'] = SiteSocialLinksModel::getSocialLinks($doctor["id"],self::TYPE);

        /** Hekim | Diplom */
        $diploms = SiteDoctorFilesModel::getFiles($doctorID,1);
        $data['diploms'] = !empty($diploms) ? $this->ResultList($diploms) : [];

        /** Hekim | Sertifikat */
        $certificate = SiteDoctorFilesModel::getFiles($doctorID,2);
        $data['certificate'] = !empty($certificate) ? $this->ResultList($certificate) : [];

        return $this->response(200,"Məlumat mövcuddur",$data);
    }

    /**
     * Hekim odenisler
     * https://e-tibb.az/api/enterprise/doctors/payments/130
     */
    public function actionPayments()
    {
        $enterpriseID = Yii::$app->session->get('userID');
        $doctorID = intval(Yii::$app->request->get('id'));
        if(empty($doctorID))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $model = new GeneralApiModel();

        $totalCount = $model->PaymentsListCount($enterpriseID,$doctorID,self::TYPE);

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $model->PaymentsList($enterpriseID,$doctorID,self::TYPE,$limits);

        if(!empty($list))
        {

            $packages = Functions::customIndexArray(PackagesServicesModel::getPackages(),'id');
            $services = Functions::customIndexArray(PackagesServicesModel::getServices(),'id');

            if(!empty($packages) && !empty($services))
            {
                foreach($list as $key => $val)
                {
                    if(isset(Yii::$app->params['payment.type'][$val['payment_type']]))
                    {
                        $list[$key]['service_name'] = $packages[$val['payment_info']]['name'];
                    }else{
                        $list[$key]['service_name'] = $services[$val['payment_info']]['name'];
                    }

                    $month   = intval($val['quantity']);
                    $time    = strtotime($list[$key]['payment_date']);
                    $endData = date("Y-m-d", strtotime("+{$month} month", $time));
                    $endData = Functions::getDatetime($endData,['type'=>'date','month'=>'no','combine'=>'.']);

                    $list[$key]['payment_method'] = $val['payment_method'] == 0 ? 'Nağd' : 'Kart';
                    $list[$key]['package_name'] = $val['quantity'].' Aylıq';
                    $list[$key]['services_duration'] = Functions::getDatetime($list[$key]['payment_date'],['type'=>'date','month'=>'no','combine'=>'.']).' - '.$endData;

                    unset($list[$key]['type']);
                    unset($list[$key]['payment_type']);
                    unset($list[$key]['payment_info']);
                    unset($list[$key]['created_date']);
                }
            }

        }

        $data['list'] = $list;

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages,$data);

        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Hekim hesab bloklamaq
     * https://e-tibb.az/api/enterprise/doctors/block
     *
     */
    public function actionBlock()
    {
        $enterpriseID = Yii::$app->session->get('userID');
        $doctorID = intval(Yii::$app->request->post('id'));
        if(empty($doctorID))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $update = DoctorsApiModel::ChangeStatus2($enterpriseID,$doctorID,DoctorsApiModel::STATUS_DEACTIVE);
        if($update)
        {
            return $this->response(200,"Həkimin hesabı donduruldu");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Hekim hesab aktiv etmek
     * https://e-tibb.az/api/enterprise/doctors/active
     */
    public function actionActive()
    {
        $enterpriseID = Yii::$app->session->get('userID');
        $doctorID = intval(Yii::$app->request->post('id'));
        if(empty($doctorID))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $update = DoctorsApiModel::ChangeStatus2($enterpriseID,$doctorID,DoctorsApiModel::STATUS_ACTIVE);
        if($update)
        {
            return $this->response(200,"Həkimin hesabı aktiv edildi");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    protected function findModel($id)
    {
        $enterpriseID = Yii::$app->session->get('userID');
        $model = SiteDoctors::find()
            ->leftjoin('site_enterprise_doctor','site_doctors.id=site_enterprise_doctor.doctor_id')
            ->where(['site_enterprise_doctor.enterprise_id'=>$enterpriseID,'site_enterprise_doctor.doctor_id'=>$id])
            ->one();
        if(($model) !== null) {
            $model = SiteDoctors::findOne($id);
            return $model;
        }
    }

    /**
     * Hekimler
     * https://e-tibb.az/api/enterprise/doctors/search
     * https://e-tibb.az/api/enterprise/doctors/search?page=1&count=5
     * name=agha
     * email=agha@mail.ru
     * number=0555555555
     * code=1313
     * specialist=cerrah
     * status=1
     */
    public function actionSearch()
    {

        Yii::$app->db->schema->refresh();
        $model = new DoctorsSearch();
        $search = Yii::$app->request->get();
        $status = SiteDoctors::get_Status();

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

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['status_name'] = $status[$result[$key]['status']];
                }
            }

            $data['list'] = $this->ResultList($result);

            $pages = $pagination->getPaginationInfo();

            $result = array_merge($pages,$data);

            return $this->response(200, "Məlumat mövcuddur", $result);
        }


    }

}
