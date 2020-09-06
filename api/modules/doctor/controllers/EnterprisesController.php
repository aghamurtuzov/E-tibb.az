<?php

namespace api\modules\doctor\controllers;

use yii;

use yii\web\UploadedFile;
use api\components\Pagination;
use api\components\Functions;
use api\models\SiteSosialLinks;
use api\models\SitePhoneNumbers;
use api\models\SiteAddresses;
use api\models\SiteMenus;
use api\models\SiteUsers;
use api\models\SiteEnterprises;
use api\models\SiteDoctorFilesModel;
use api\components\ImageUpload;
use api\models\SitePhoneNumbersModel;
use api\models\SiteSocialLinksModel;
use api\models\EnterpriseModel;
use api\models\PackagesServicesModel;
use api\modules\doctor\models\GeneralApiModel;
use api\modules\doctor\models\EnterprisesApiModel;
use api\modules\doctor\models\SiteAdressesModel;
use api\modules\doctor\models\SiteUserContact;
use api\modules\doctor\models\SiteUserContactModel;
use api\modules\doctor\controllers\MainController;

/**
 * Enterprises API
 */

class EnterprisesController extends MainController
{

    const TYPE = 2;
    public $modelClass = '';
    public $customPath = 'enterprises';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Obyektler
     * https://e-tibb.az/api/doctor/enterprises?id=1
     * https://e-tibb.az/api/doctor/enterprises?id=1&page=1&count=5
     */
    public function actionIndex()
    {
        $model = new EnterprisesApiModel();

        $cat_id = intval(Yii::$app->request->get('id'));
        if(empty($cat_id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $totalCount = $model->EnterpriseCount($cat_id);

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $model->Enterprises($cat_id,$limits);

        $data['list'] = $this->ResultList($list);

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages,$data);

        return $this->response(200,"Məlumat mövcuddur",$result);

    }

    /**
     * Obyektler kateqoriyalari
     * https://e-tibb.az/api/doctor/enterprises/categories
     */
    public function actionCategories()
    {
        $result = SiteMenus::find()->where(['type'=>2])->orderBy('name')->all();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Obyektler yaratmaq
     * https://e-tibb.az/api/doctor/enterprises/create
     */
    public function actionCreate()
    {
//      Yii::$app->db->schema->refresh();
        $model  = new SiteEnterprises();
        $user   = new SiteUsers();
        $post = Yii::$app->request->post();

        if(!empty(Yii::$app->request->post()) && !empty($post) && $model->load($post,''))
        {

            /** Create User */
            $lastRow            = SiteUsers::find()->select('id')->orderBy(['id' => SORT_DESC])->one();
            $user->name         = $model->contact_name;
            $user->unique_id    = str_pad($lastRow['id'] + 1, 7, 0, STR_PAD_LEFT);
            $user->email        = $model->email;
            $user->phone_number = $model->contact_phone;
            $user->status       = 1;
            $user->created_at   = time();
            $user->last_login   = date("Y-m-d H:i:s");
            $user->type         = self::TYPE;
            $user->birthday     = $model->contact_birthday;
            $user->password     = ($lastRow['id'] + 1)+rand(11111,99999);
            $user->setPassword($user->password);
            $user->generateAuthKey();

            $datetime = date('Y-m-d', strtotime("+1 month", strtotime(date('Y-m-d'))));

            $model->expires = $datetime;

            $model->slug            = Functions::slugify($model->name);
            $model->status          = 1;
            $model->published_time  = date('Y-m-d H:i:s');
            $model->modified_time   = date('Y-m-d H:i:s');

            if($model->catdirilma){ $jsonData['catdirilma'] = 1; }

            if($model->saat24){ $jsonData['saat24'] = 1; }

            if($model->eve_caqiris){ $jsonData['eve_caqiris'] = 1; }

            if(isset($jsonData))
            {
                $model->feature = json_encode($jsonData);
            }

            $model->validate();

            /** Check Main image */
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

            /** Check Certificates */
            $certificates = UploadedFile::getInstancesByName('ct_files');
            if(!empty($certificates))
            {
                $imageUpload = new ImageUpload();
                foreach($certificates as $key => $diplom)
                {
                    if(!$imageUpload->validate($diplom))
                    {
                        $model->addError('ct_files','Sertifikat yüklənməsi zamanı xəta baş verdi');
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
                    $model2 = SiteEnterprises::findOne($model->id);
                    $model2->user_id = $user->id;
                    $model2->save(false);
                }

                /** Sosial links */
                if(isset($model->sosial_links[0]['link']) && !empty($model->sosial_links[0]['link']))
                {
                    foreach($model->sosial_links as $key => $val)
                    {
                        if(!empty($val['link']))
                        {
                            $sosial_links             = new SiteSosialLinks();
                            $sosial_links->connect_id = $model->id;
                            $sosial_links->link       = $val['link'];
                            $sosial_links->link_type  = $val['type'];//$key;
                            $sosial_links->type       = self::TYPE;
                            $sosial_links->save();
                        }
                    }
                }

                /** Phone numbers */
                if(isset($model->phone_numbers[0]['number']) && !empty($model->phone_numbers[0]['number']))
                {
                    foreach($model->phone_numbers as $key => $val)
                    {
                        if(!empty($val['number']))
                        {
                            $phone_numbers              = new SitePhoneNumbers();
                            $phone_numbers->connect_id  = $model->id;
                            $phone_numbers->number      = $val['number'];
                            $phone_numbers->number_type = isset($val['type'])?$val['type']:0;//$key;
                            $phone_numbers->type        = self::TYPE;
                            $phone_numbers->save();
                        }
                    }
                }

                /** Addresses */
                if(isset($model->addresses) && !empty($model->addresses))
                {
                    foreach($model->addresses as $key => $val)
                    {
                        if(!empty($val['name']))
                        {
                            $address             = new SiteAddresses();
                            $address->connect_id = $model->id;
                            $address->address    = $val['name'];
                            $address->type       = self::TYPE;
                            $address->save();
                        }
                    }
                }

                /** Company mail */
                if(isset($model->company_email) && !empty($model->company_email))
                {
                    foreach($model->company_email as $key => $val)
                    {
                        $mdl             = new SiteUserContact();
                        $mdl->connect_id = $model->id;
                        $mdl->user_type  = self::TYPE; // 1 - Doctor | 2 - Enterprise | 3 - User
                        $mdl->data       = $val;
                        $mdl->type       = 3; // 1 - Contact numbers | 2 - Social links | 3 - Email |  4 - Address
                        $mdl->save();
                    }
                }

                /** Main image */
                if(!empty($photo))
                {
                    $imageUpload = new ImageUpload();
                    $uploadedFile = $imageUpload->saveFile($photo, [
                        'path.save' => $this->customPath,
                        'resize.img' => [185, 185],
                        'resize.thumb'=>[121,121]
                    ]);
                    $updatePhoto = $model;
                    $updatePhoto->photo = $uploadedFile;
                    $updatePhoto->save(false);
                }

                /** Certificates */
                $certificates = UploadedFile::getInstancesByName('ct_files');
                if(!empty($certificates))
                {
                    Functions::uploadFiles($certificates, $model->id, 2, $this->customPath,self::TYPE);
                }

                return $this->response(200,'Məlumat uğurla əlavə olundu');

            }else{
                return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi');
            }

        }else{
            return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi');
        }

    }

    /**
     * Obyektler duzelis et
     * https://e-tibb.az/api/doctor/enterprises/edit
     * id
     * deletedImages = 0 1
     * deletedCertificates = [1,3,4]
     */
    public function actionEdit()
    {
        Yii::$app->db->schema->refresh();
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

            $model->modified_time = date('Y-m-d H:i:s');

            if($model->catdirilma){ $jsonData['catdirilma'] = 1; }

            if($model->saat24){ $jsonData['saat24'] = 1; }

            if($model->eve_caqiris){ $jsonData['eve_caqiris'] = 1; }

            if(isset($jsonData))
            {
                $model->feature = json_encode($jsonData);
            }

            $model->validate();

            /** Sosial links */
            if(isset($model->sosial_links) && !empty($model->sosial_links))
            {
                $added_sosial_links = SiteSocialLinksModel::getSocialLinks($model->id,self::TYPE);
                $added_sosial_links = !empty($added_sosial_links) ? $added_sosial_links : [];
                $sosial_links       = !empty($model->sosial_links) && is_array($model->sosial_links)? $model->sosial_links : [];
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
            if(isset($model->phone_numbers) && !empty($model->phone_numbers))
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

            /** Addresses */
            if(isset($model->addresses) && !empty($model->addresses))
            {
                $added_addresses = SiteAdressesModel::getAddresses($model->id,self::TYPE);
                $added_addresses = !empty($added_addresses) ? $added_addresses : [];
                $addresses       = !empty($model->addresses) && is_array($model->addresses) ? $model->addresses : [];
                $max             = max(count($added_addresses),count($addresses));

                for($x=0;$x<$max;$x++)
                {
                    if(isset($added_addresses[$x]['name']))
                    {
                        if(!empty($addresses[$x]['name']))
                        {
                            if(($added_addresses[$x]['name'] != $addresses[$x]['name']))
                            {
                                $upd_address = SiteAddresses::findOne($added_addresses[$x]['id']);
                                $upd_address->address = $addresses[$x]['name'];
                                $upd_address->save(false);
                            }
                        }else{
                            $del_addresses = SiteAddresses::findOne($added_addresses[$x]['id']);
                            if(!empty($del_addresses)){ $del_addresses->delete(); }
                        }
                    }elseif(isset($addresses[$x]['name']) && !empty($addresses[$x]['name'])){
                        $ins_address             = new SiteAddresses();
                        $ins_address->connect_id = $model->id;
                        $ins_address->address    = $addresses[$x]['name'];
                        $ins_address->type       = self::TYPE;
                        $ins_address->save();
                    }
                };
            }

            /** Company email */
            if(isset($model->company_email) && !empty($model->company_email))
            {
                $added_emails = SiteUserContactModel::getEmails($model->id,self::TYPE);
                $added_emails = !empty($added_emails) ? $added_emails : [];
                $emails       = !empty($model->company_email) && is_array($model->company_email) ? $model->company_email : [];
                $max          = max(count($added_emails),count($emails));

                for($x=0;$x<$max;$x++)
                {
                    if(isset($added_emails[$x]))
                    {
                        if(!empty($emails[$x]))
                        {
                            if(($added_emails[$x] != $emails[$x]))
                            {
                                $upd_address = SiteUserContact::findOne($added_emails[$x]['id']);
                                $upd_address->data = $emails[$x];
                                $upd_address->save(false);
                            }
                        }else{
                            $del_emails = SiteUserContact::findOne($added_emails[$x]['id']);
                            if(!empty($del_emails)){ $del_emails->delete(); }
                        }
                    }elseif(isset($emails[$x]) && !empty($emails[$x])){
                        $mdl             = new SiteUserContact();
                        $mdl->connect_id = $model->id;
                        $mdl->user_type  = self::TYPE; // 1 - Doctor | 2 - Enterprise | 3 - User
                        $mdl->data       = $emails[$x];
                        $mdl->type       = 3; // 1 - Contact numbers | 2 - Social links | 3 - Email |  4 - Address
                        $mdl->save();
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

            /** Check Certificates */
            $certificates = UploadedFile::getInstancesByName('ct_files');
            if(!empty($certificates))
            {
                $imageUpload = new ImageUpload();
                foreach($certificates as $key => $diplom)
                {
                    if(!$imageUpload->validate($diplom))
                    {
                        $model->addError('ct_files','Sertifikat yüklənməsi zamanı xəta baş verdi');
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
                    $userUpdate = SiteUsers::findOne($oldModel->user_id);
                    $userUpdate->birthday = $model->contact_birthday;
                    $userUpdate->name = $model->contact_name;
                    $userUpdate->phone_number = $model->contact_phone;
                    $userUpdate->save(false);
                }

                /** Main image */
                if(!empty($photo))
                {
                    $imageUpload  = new ImageUpload();
                    $uploadedFile = $imageUpload->saveFile($photo,[
                        'path.save' => $this->customPath,
                        'resize.img' => [185,185],
                        'resize.thumb' => [121,121]
                    ]);
                    $updatePhoto = $model;
                    $updatePhoto->photo = $uploadedFile;
                    $updatePhoto->save(false);
                }

                /** Certificates*/
                if(!empty($model->deletedCertificates))
                {
                    Functions::deleteFiles(2,$model->deletedCertificates,$this->customPath,$model->id,self::TYPE);
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

//    public function getSosialLinks($id)
//    {
//        return SiteSosialLinks::find()->where(['connect_id'=>$id,'type'=>self::TYPE])->all();
//    }
//
//    public function getNumbers($id)
//    {
//        return SitePhoneNumbers::fint()->where(['connect_id'=>$id,'type'=>self::TYPE])->all();
//    }
//
//    public function getAddresses($id)
//    {
//        return SiteAdressesModel::fint()->where(['connect_id'=>$id,'type'=>self::TYPE])->all();
//    }
//
//    public function getCompanyEmails($id)
//    {
//        return SiteUserContact::fint()->where(['connect_id'=>$id,'type'=>self::TYPE,'type'=>3])->all();
//    }

    /**
     * Obyektler form status
     * https://e-tibb.az/api/doctor/enterprises/list-status
    */
    public function actionListStatus()
    {
        $result = SiteEnterprises::getStatus();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Obyekt info
     * https://e-tibb.az/api/doctor/enterprises/info/725
     */
    public function actionInfo()
    {

        $id = intval(Yii::$app->request->get('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        /** Obyekt info */
        $enterprise = EnterpriseModel::getEnterprise($id);
        if(!empty($enterprise))
        {
            $enterprise = $this->ResultList($enterprise);
        }else{
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $user = SiteUsers::find()->where(['id'=>$enterprise['user_id']])->one();

        $feature = json_decode($enterprise['feature'],true);

        $enterprise['catdirilma'] = isset($feature['catdirilma']) ? 1 : 0;
        $enterprise['saat24'] = isset($feature['saat24']) ? 1 : 0;
        $enterprise['eve_caqiris'] = isset($feature['eve_caqiris']) ? 1 : 0;

        /** Hekim | User */
        $enterprise['email'] = $user['email'];
        $enterprise['contact_phone'] = $user['phone_number'];
        $enterprise['contact_name'] = $user['name'];
        $enterprise['contact_birthday'] = $user['birthday'];

        $data['enterprise'] = $enterprise;

        /** Obyekt | Ünvanlar */
        $data['addresses'] = SiteAdressesModel::getAddresses($id,self::TYPE);

        /** Obyekt | Sirket emaili */
        $data['company_emails'] = SiteUserContactModel::getEmails($enterprise["id"],self::TYPE);

        /** Obyekt | Telefon nomreleri */
        $data['phones'] = SitePhoneNumbersModel::getPhones($enterprise["id"],self::TYPE);

        /** Obyekt | Sosial linkler */
        $data['social_links'] = SiteSocialLinksModel::getSocialLinks($enterprise["id"],self::TYPE);

        /** Hekim | Sertifikat */
        $certificate = SiteDoctorFilesModel::getFiles($id,2);
        $data['certificate'] = !empty($certificate) ? $this->ResultList($certificate) : [];

        return $this->response(200,"Məlumat mövcuddur",$data);
    }

    /**
     * Obyekt hekimler
     * https://e-tibb.az/api/doctor/enterprises/doctors/715
     */
    public function actionDoctors()
    {
        $id = intval(Yii::$app->request->get('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $model = new EnterprisesApiModel();

        $totalCount = $model->DoctorsCount($id);

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $model->Doctors($id,$limits);

        $data['list'] = $this->ResultList($list,'doctors');

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages,$data);

        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Obyekt odenisler
     * https://e-tibb.az/api/doctor/enterprises/payments/715
     */
    public function actionPayments()
    {
        $id = intval(Yii::$app->request->get('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $model = new GeneralApiModel();

        $totalCount = $model->PaymentsCount($id,self::TYPE);

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $model->Payments($id,$limits,self::TYPE);

        if(!empty($list))
        {

            $packages = Functions::customIndexArray(PackagesServicesModel::getPackages(self::TYPE),'id');
            $services = Functions::customIndexArray(PackagesServicesModel::getServices(self::TYPE),'id');

            if(!empty($packages) && !empty($services))
            {
                foreach($list as $key => $val)
                {

                    $month   = intval($val['quantity']);
                    $time    = strtotime($list[$key]['payment_date']);
                    $endData = date("Y-m-d", strtotime("+{$month} month", $time));
                    $endData = Functions::getDatetime($endData,['type'=>'date','month'=>'no','combine'=>'.']);

                    if(isset(Yii::$app->params['payment.type'][$val['payment_type']]))
                    {
                        $list[$key]['service_name'] = $packages[$val['payment_info']]['name'];
                    }else{
                        $list[$key]['service_name'] = $services[$val['payment_info']]['name'];
                    }
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
     * Obyekt hesab bloklamaq
     * https://e-tibb.az/api/doctor/enterprises/block
     */
    public function actionBlock()
    {
        $id = intval(Yii::$app->request->post('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $update = EnterprisesApiModel::ChangeStatus($id,EnterprisesApiModel::STATUS_ACTIVE);
        if($update)
        {
            return $this->response(200,"Obyekt hesabı donduruldu");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Obyekt hesab aktiv etmek
     * https://e-tibb.az/api/doctor/enterprises/active
     */
    public function actionActive()
    {
        $id = intval(Yii::$app->request->post('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $update = EnterprisesApiModel::ChangeStatus($id,EnterprisesApiModel::STATUS_DEACTIVE);
        if($update)
        {
            return $this->response(200,"Obyekt hesabı aktiv edildi");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Obyekt hesab sil
     * https://e-tibb.az/api/doctor/enterprises/del
     * ids []
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
            EnterprisesApiModel::ChangeStatus($id,EnterprisesApiModel::STATUS_DELETED);
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    protected function findModel($id)
    {
        if(($model = SiteEnterprises::findOne($id)) !== null) {
            return $model;
        }
    }

}
