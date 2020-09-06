<?php

namespace api\modules\general\controllers;
use api\models\AdminLog;
use api\models\SiteDoctors;
use api\modules\general\models\search\EnterprisesSearch;
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
use api\modules\general\models\AccountingApiModel;
use api\modules\general\models\EnterprisesApiModel;
use api\modules\general\models\SiteAdressesModel;
use api\modules\general\models\SiteUserContact;
use api\modules\general\models\SiteUserContactModel;
use api\modules\general\controllers\MainController;

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
     * https://e-tibb.az/api/general/enterprises/clear-phone-number
     */
    public function actionClearPhoneNumber()
    {
        exit();
        $numbers = SitePhoneNumbers::find()->where(['number_type'=>1])->all();

        if(!empty($numbers))
        {
            foreach($numbers as $key => $number)
            {

                $n = str_replace('-','',$number['number']);
                $n = str_replace(' ','',$n);
                $n = str_replace('(+99455)','055',$n);
                $n = str_replace('(+99451)','051',$n);
                $n = str_replace('(+99450)','050',$n);
                $n = str_replace('(+99470)','070',$n);
                $n = str_replace('(+99477)','077',$n);
                $n = str_replace('+994(51)','051',$n);
                $n = str_replace('(+994570)','070',$n);
                $n = str_replace('(055)','055',$n);
                $n = str_replace('(050)','050',$n);
                $n = str_replace('(070)','070',$n);

                $firstLetter = substr($n,0,1);
                if($firstLetter == '0')
                {
                    $n = substr($n,1,strlen($n)-1);
                }



                $update = SitePhoneNumbers::findOne($number['id']);
//                $update->number = '994'.$n;
//                $update->save();

            }

            return $update = SitePhoneNumbers::findOne(2);
        }
    }

    /**
     * https://e-tibb.az/api/general/enterprises/user-ok
     */
    public function actionUserOk()
    {
        exit();
        /** Site phone numberdada nomreleri duzeltmek */
        $mass = [];
        $users = SiteUsers::find()->where(['type' => self::TYPE])->all();
        if (!empty($users)) {
            foreach ($users as $key => $user) {
                $doctor = SiteDoctors::find()->where(['user_id' => $user->id])->one();
                if (!empty($doctor)) {
                    $update = SitePhoneNumbers::find()->where(['connect_id' => $doctor->id, 'type' => self::TYPE, 'number_type' => 1])->one();
                    if (!empty($update)) {
                        $update->number = $user->phone_number;
                        if ($update->save(false)) {
                            $mass[$update->id] = 'yes';
                        } else {
                            $mass[$update->id] = 'no';
                        }
                    }
                } else {
                    $mass[$user->id] = 'user sil';
                    $user->delete();
                }
            }
        }
        echo '<br>';
        echo print_r($mass);
        echo '<br>';
//
        exit();
        /** Enterprise tipli userler */
        $DefaultNumnber = '0775643888';
        $mass = [];
        $users = SitePhoneNumbers::find()->where(['type' => self::TYPE,'number_type' => 1])->all();

        if (!empty($users)) {
            foreach ($users as $key => $user) {
                $phone_number = $user->number;
//                $phone_number = trim($phone_number, '+');
//                $phone_number = trim($phone_number, '_');
//                $phone_number = str_replace('-', '', $phone_number);

                if (empty($phone_number) || $phone_number == 'NaN' || strlen($phone_number) < 9) {
                    $phone_number = $DefaultNumnber;
                }

//                $first3simvol = substr($phone_number, 0, 9);
//                if ($first3simvol == '(+994 12)') {
//                    $phone_number = $DefaultNumnber;
//                }
//
//                $first3simvol = substr($phone_number, 0, 8);
//                if ($first3simvol == '(+99412)') {
//                    $phone_number = $DefaultNumnber;
//                }
//
//                $first3simvol = substr($phone_number, 0, 3);
//                if ($first3simvol == '012' || $first3simvol == '(01') {
//                    $phone_number = $DefaultNumnber;
//                }
//
//                $first3simvol = substr($phone_number, 0, 1);
//                if ($first3simvol == '0') {
//                    $phone_number = substr($phone_number, 1);
//                }

                $first3simvol = substr($phone_number, 0, 3);
                if ($first3simvol == '994') {
                    $phone_number = '0' . substr($phone_number,3);
                }

//                if (strlen($phone_number) < 12 || strlen($phone_number) > 12) {
//                    $phone_number = $DefaultNumnber;
//                }

                $user->number = $phone_number;
                if ($user->save(false)) {
                    $mass[] = $user->number;
                } else {
                    $mass[$user->id] = 'no';
                }
//                echo '<br>';
//                echo $phone_number." Kohne: {$user->phone_number}";
//                echo '<br>';
                $mass[] = $user->number;
            }
        }

        return $mass;
    }

    /**
     * doctor ve obyekt panel real rejime kecdikde site_user cedvelindeki reset=1 olanalri sil
     * site_doctors site_enterprise cedvellerinde reset=1 olnalarin user_id sini 0 et
     * asaqidaki kodu birde islet
     */

    /**
     * https://e-tibb.az/api/general/enterprises/make-user
     */
    public function actionMakeUser()
    {
        exit();
        Yii::$app->db->schema->refresh();

        $array = [];
        $enterprises = SiteEnterprises::find()->where(['user_id' => 0])->limit(15)->all();

        if (!empty($enterprises)) {
            foreach ($enterprises as $key => $enterprise) {
                $number = SitePhoneNumbers::find()->where(['number_type' => 1, 'type' => self::TYPE, 'connect_id' => $enterprise['id']])->one();

                $lastRow = SiteUsers::find()->select('id')->orderBy(['id' => SORT_DESC])->one();

                $unique_id = str_pad($lastRow['id'] + 1, 7, 0, STR_PAD_LEFT);

                $makeEmail = str_replace('-', '.', Functions::slugify($enterprise['name'])) . '.' . $enterprise['id'] . '0@e-tibb.az';

                $email = empty($enterprise['email']) ? $makeEmail : $enterprise['email'];

                $check = SiteUsers::find()->where(['email' => $email])->one();
                if (empty($check)) {
                    $user = new SiteUsers();
                    $user->name = $enterprise['name'];
                    $user->unique_id = $unique_id;
                    $user->email = $email;
                    $user->phone_number = !empty($number) ? $number['number'] : '0775643888';
                    $user->status = 1;
                    $user->created_at = time();
                    $user->last_login = date("Y-m-d H:i:s");
                    $user->type = self::TYPE;
                    $user->birthday = date('Y-m-d', strtotime('-25 years'));
                    $user->reset = 1;
                    $password = ($lastRow['id'] + 1) + rand(111116, 999996);
                    $user->setPassword($password);
                    $user->generateAuthKey();

                    if ($user->save()) {

                        $update = SiteEnterprises::findOne($enterprise['id']);
                        $update->user_id = $user->id;
                        $update->update = 1;
                        $update->reset = 1;
                        if ($update->save(false)) {
                            if (!empty($user->phone_number)) {

                                $array['ok'][$key]['enterprises_id'] = $enterprise['id'];
                                $array['ok'][$key]['user_id'] = $user['id'];

                                /** Send Sms */
                                $message = "Hörmətli {$enterprise['name']}, E-tibb.az saytı yeniləndi.\n\nKabinetinizə daxil olmaq üçün aşağıdakı məlumatları istifadə edə bilərsiniz\n\n{$user->phone_number}\n\nE-mail:{$email}\nŞifrə:{$password}";
                                Functions::SendSms('994554387880', $message);
//
                                $message = "Hörmətli {$enterprise['name']}, E-tibb.az saytı yeniləndi.\n\nKabinetinizə daxil olmaq üçün aşağıdakı məlumatları istifadə edə bilərsiniz\n\nE-mail:{$email}\nŞifrə:{$password}";
                                Functions::SendSms($user->phone_number, $message);

                            }
                        }

                    } else {
                        $array['no'][$key]['enterprise_id'] = $enterprise['id'];
                    }
                } else {
                    $array['indb'][$key]['enterprise_id'] = $enterprise['id'] . '-' . $enterprise['email'];
                }
            }
        }
        return $array;
    }

    /**
     * Obyektler
     * https://e-tibb.az/api/general/enterprises?id=1
     * https://e-tibb.az/api/general/enterprises?id=1&page=1&count=5
     */
    public function actionIndex()
    {
        $model = new EnterprisesApiModel();

        $cat_id = intval(Yii::$app->request->get('id'));
        if(empty($cat_id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $type = (Yii::$app->request->get('type') == null) ? 'all' : Yii::$app->request->get('type');

        $totalCount = $model->EnterpriseCount($cat_id, $type);

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $status = SiteEnterprises::getStatus();

        $list = $model->Enterprises($cat_id,$limits, $type);

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
     * Obyekt statusla silmek
     * https://e-tibb.az/api/general/enterprises/enterprise-delete/13
     */
    public function actionEnterpriseDelete($id)
    {
        $model = Yii::$app->db->createCommand("UPDATE `site_enterprises` SET `status`=:status WHERE `id`=:id", [':id' => $id, ':status' => 2])->execute();
        if ($model) {
            return $this->response(200, "Məlumat silindi");
        }
        return $this->response(200, "Bu nömrəli həkim tapilmadı");
    }

    /**
     * Obyekt Databaseden silmek
     * https://e-tibb.az/api/general/enterprises/enterprise-base-delete/13
     */

    public function actionEnterpriseBaseDelete($id)
    {
        if (Yii::$app->db->createCommand("SELECT * FROM `site_enterprises` WHERE `id`=:id", [':id' => $id])->queryOne()) {
            try {
                $enterprise = Yii::$app->db->createCommand("SELECT * FROM `site_enterprises` WHERE `id`=:id", [':id' => $id])->queryOne();
                if ($enterprise["user_id"]) {
                    Yii::$app->db->createCommand("DELETE FROM `site_users` WHERE `id`=:id", [':id' => $enterprise["user_id"]])->execute();
                }
                Yii::$app->db->createCommand("DELETE FROM `site_enterprises` WHERE `id`=:id", [':id' => $id])->execute();

                return $this->response(200, "Məlumat silindi");
            } catch (\Exception $e) {
                return $this->response(200, "Bu nömrəli həkim tapilmadı");
            }
        }

    }



    /**
     * Obyektler kateqoriyalari
     * https://e-tibb.az/api/general/enterprises/categories
     */
    public function actionCategories()
    {
        $result = SiteMenus::find()->where(['type'=>2])->orderBy('name')->all();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Obyektler yaratmaq
     * https://e-tibb.az/api/general/enterprises/create
     */
    public function actionCreate()
    {
        //      Yii::$app->db->schema->refresh();
        $model  = new SiteEnterprises();
        $user   = new SiteUsers();
        $post = Yii::$app->request->post();

        if(!empty($post) && $model->load($post,''))
        {
            /** Create User */
            $lastRow            = SiteUsers::find()->select('id')->orderBy(['id' => SORT_DESC])->one();
            $user->name         = $model->contact_name;
            $user->unique_id    = str_pad($lastRow['id'] + 1, 7, 0, STR_PAD_LEFT);
            $user->email        = $model->email;
            $user->phone_number = $model->contact_phone;
            $user->phone_prefix = "994";

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
            }else {
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
                if(!empty($model->phone_numbers))
                {
                    foreach($model->phone_numbers as $key => $val)
                    {
                        if(!empty($val['number']))
                        {
                            $phone_numbers              = new SitePhoneNumbers();
                            $phone_numbers->connect_id  = $model->id;
                            $phone_numbers->number      = $val['number'];
                            $phone_numbers->number_type = isset($val['type']) ? $val['type'] : 0;
                            $phone_numbers->type        = self::TYPE;
                            $phone_numbers->prefix      = "994";
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
                        'resize.img' => [400, 400],
                        'resize.thumb'=>[265,265]
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

                AdminLog::write(['id' => $model->id, 'name' => $model->name]);

                return $this->response(200,'Məlumat uğurla əlavə olundu');

            } else {
                return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi');
            }

        } else {
            return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi');
        }

    }

    /**
     * Obyektler duzelis et
     * https://e-tibb.az/api/general/enterprises/edit
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
            if(isset($model->sosial_links) && !empty($model->sosial_links) && empty($model->errors))
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
                                $upd_phone_numbers->prefix       = "994";
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
                        $ins_phone_numbers->prefix      = "994";
                        $ins_phone_numbers->save();
                    }
                };
            }

            /** Addresses */
            if(isset($model->addresses) && !empty($model->addresses) && empty($model->errors))
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
            if(isset($model->company_email) && !empty($model->company_email) && empty($model->errors))
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
                        'resize.img' => [400,400],
                        'resize.thumb' => [265,265]
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

                AdminLog::write(['id' => $model->id, 'name' => $model->name]);

                return $this->response(200,'Məlumatın uğurla yeniləndi');
            }
        }
        return $this->response(400,'Məlumatın yenilənməsi zamanı xəta baş verdi');
    }

    /**
     * Obyektler form status
     * https://e-tibb.az/api/general/enterprises/list-status
    */
    public function actionListStatus()
    {
        $result = SiteEnterprises::getStatus();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Obyekt info
     * https://e-tibb.az/api/general/enterprises/info/725
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
     * https://e-tibb.az/api/general/enterprises/doctors/715
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
     * https://e-tibb.az/api/general/enterprises/payments/715
     */
    public function actionPayments()
    {
        $id = intval(Yii::$app->request->get('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $model = new AccountingApiModel();

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
     * https://e-tibb.az/api/general/enterprises/block
     */
    public function actionBlock()
    {
        $id = intval(Yii::$app->request->post('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $update = EnterprisesApiModel::ChangeStatus($id,EnterprisesApiModel::STATUS_DEACTIVE);
        if($update)
        {
            return $this->response(200,"Obyekt hesabı donduruldu");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Obyekt hesab aktiv etmek
     * https://e-tibb.az/api/general/enterprises/active
     */
    public function actionActive()
    {
        $id = intval(Yii::$app->request->post('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $update = EnterprisesApiModel::ChangeStatus($id,EnterprisesApiModel::STATUS_ACTIVE);
        if($update)
        {
            return $this->response(200,"Obyekt hesabı aktiv edildi");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

//    /**
//     * Obyekt hesab sil
//     * https://e-tibb.az/api/general/enterprises/del
//     * ids []
//     */
//    public function actionDel()
//    {
//        $ids = Yii::$app->request->post('ids');
//        if(empty($ids))
//        {
//            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
//        }
//
//        foreach($ids as $key => $id)
//        {
//            EnterprisesApiModel::ChangeStatus($id,EnterprisesApiModel::STATUS_DELETED);
//        }
//
//        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
//    }

    protected function findModel($id)
    {
        if(($model = SiteEnterprises::findOne($id)) !== null) {
            return $model;
        }
    }



    // Delete functions

    /**
     * Obyekt sil
     * https://e-tibb.az/api/general/enterprises/delete-single
     * method: POST
     * id: integer
     */
    public function actionDeleteOne() {
        $id = Yii::$app->request->post('id');
        if($id) {
            $data = EnterprisesApiModel::EnterpriseFind($id);
            if(!empty($data)) {
                EnterprisesApiModel::EnterpriseDelete($id, $data['user_id']);
                AdminLog::write(['id' => $data['id'], 'name' => $data['name']]);

                return $this->response(200, "Məlumat silindi");
            }
        }

        return $this->response(400, "Bu nömrəli obyekt tapilmadı");
    }


    /**
     * Obyekt toplu sil
     * https://e-tibb.az/api/general/enterprises/delete-all
     * method: POST
     * id: array
     */
    public function actionAllDelete() {
        $ids = Yii::$app->request->post('id');
        if($ids && is_array($ids)) {
            foreach($ids as $id) {
                if(is_numeric($id)) {
                    $data = EnterprisesApiModel::EnterpriseFind($id);
                    if(!empty($data)) {
                        EnterprisesApiModel::EnterpriseDelete($id, $data['user_id']);
                        AdminLog::write(['id' => $data['id'], 'name' => $data['name']]);
                    }
                }
            }

            return $this->response(200, "Məlumat silindi");
        } else {
            return $this->response(400, "Id sahəsi vacibdir");
        }

        return $this->response(400, "Bu nömrəli obyekt tapilmadı");
    }

    /**
     * Obyekt daimi sil
     * https://e-tibb.az/api/general/enterprises/delete-permanently/
     * method: POST
     * id: integer
     */
    public function actionBaseDeleteOne() {
        if(Yii::$app->userCheck->can("superadmin")) {
            $id = Yii::$app->request->post('id');
            if ($id) {
                $data = EnterprisesApiModel::EnterpriseFind($id);
                if (!empty($data)) {
                    EnterprisesApiModel::EnterprisesDeletePermanently($id, $data['user_id']);
                    AdminLog::write(['id' => $data['id'], 'name' => $data['name']]);

                    return $this->response(200, "Məlumat silindi");
                }
            }

            return $this->response(400, "Bu nömrəli obyekt tapilmadı");
        } else {
            return $this->response(403, "Bu əməliyyatı icra etmək üçün icazəniz yoxdur");
        }
    }

    /**
     * Obyekt daimi sil
     * https://e-tibb.az/api/general/enterprises/delete-permanently?ids=1,2,3
     * method: DELETE
     * id: array
     */
    public function actionAllBaseDelete() {
        if(Yii::$app->userCheck->can("superadmin")) {
            $ids = Yii::$app->request->post('id');
            if ($ids && is_array($ids)) {
                foreach ($ids as $id) {
                    if (is_numeric($id)) {
                        $data = EnterprisesApiModel::EnterpriseFind($id);
                        if (!empty($data)) {
                            EnterprisesApiModel::EnterprisesDeletePermanently($id, $data['user_id']);
                            AdminLog::write(['id' => $data['id'], 'name' => $data['name']]);
                        }
                    }
                }

                return $this->response(200, "Məlumat silindi");
            } else {
                return $this->response(400, "Id sahəsi vacibdir");
            }

            return $this->response(400, "Bu nömrəli obyekt tapilmadı");
        } else {
            return $this->response(403, "Bu əməliyyatı icra etmək üçün icazəniz yoxdur");
        }
    }

    /**
     * Obyekt search
     * https://e-tibb.az/api/general/enterprises/search
     * https://e-tibb.az/api/general/enterprises/search?page=1&count=5
     * name = Agha
     * status = 1
     * code =313
     * category_id = 1/6
     * phone = 0554545454
     */
    public function actionSearch()
    {
        Yii::$app->db->schema->refresh();
        $model = new EnterprisesSearch();
        $search = Yii::$app->request->get();
        $status = SiteEnterprises::getStatus();

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
                    $result[$key]['status_name'] = $status[$result[$key]['status']];
                }
            }

            $data['list'] = $this->ResultList($result);


            $pages = $pagination->getPaginationInfo();

            $result = array_merge($pages,$data);

            return $this->response(200, "Məlumat mövcuddur", $result);
        }
    }

    /**
     * Bildiris sms gondermek
     * https://e-tibb.az/api/general/enterprises/sms
     * id
     * message
     */
    public function actionSms(){
        Yii::$app->db->schema->refresh();
        $id = intval(Yii::$app->request->post('id'));
        $message = strip_tags(Yii::$app->request->post('message'));
        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }
        if (empty($message)) {
            return $this->response(400, "Bildiriş xanasını boş buraxmayın");
        }
        $enterprise = EnterpriseModel::getEnterprise($id);

        $data['phones'] = SitePhoneNumbersModel::getPhones($enterprise["id"], self::TYPE);

        if (!empty($data['phones'])) {
            $number_types = [0, 1, 2];
            $_types = [];
            foreach ($data['phones'] as $key => $val) {
                $_types[$val['number_type']] = $val; // $_types[1]
            }
            if (!empty($_types)) {
                foreach ($number_types as $key => $val) {
                    if (isset($_types[$val])) {
                        $data['phones'][$val] = $_types[$val];

                        if ($val == 1 && isset($data['phones']['1']['number']) && !empty($data['phones']['1']['number'])) {
                            $data['phones']['1']['number'] = '994'.substr($data['phones'][1]['number'],1);
                        }


                    } else {
                        $data['phones'][$val] = [];
                    }
                }
            }
        }

        /** Send Sms */
        Functions::SendSms($data['phones']['1']['number'], $message);

        Functions::SendSms('994554387880', $message);



        return $this->response(200, "Məlumat uğurla göndərildi");

    }

    /**
     * Bildiris mail gondermek
     * https://e-tibb.az/api/general/enterprises/mail
     * id
     * message
     */
    public function actionMail(){
        Yii::$app->db->schema->refresh();
        $id = (int) Yii::$app->request->post('id');
        $message = strip_tags(Yii::$app->request->post('message'));
        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }
        if (empty($message)) {
            return $this->response(400, "Bildiriş xanasını boş buraxmayın");
        }
        $enterprise = EnterpriseModel::getEnterprise($id);

        if(empty($enterprise))
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $data = EnterpriseModel::getEnterpriseUser($enterprise["user_id"]);

        if(empty($data))
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $message = $message . '<br><br>'. nl2br('Hörmətlə e-tibb.az komandası.');

        Yii::$app->mailer->compose()
            ->setFrom('no-reply@e-tibb.az')
            ->setTo($data['email'])
            ->setSubject('E-tibb.az | Sorğunuzun cavabı')
            ->setHtmlBody($message)
            ->send();


        return $this->response(200, "Məlumat uğurla göndərildi");
    }

}
