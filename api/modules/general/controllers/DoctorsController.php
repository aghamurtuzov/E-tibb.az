<?php

namespace api\modules\general\controllers;

use api\models\AdminLog;
use api\modules\general\models\search\DoctorsSearch;
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
use api\modules\general\models\DoctorsApiModel;
use api\modules\general\models\AccountingApiModel;
use api\modules\general\controllers\MainController;

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

        exit();
        /** Hekime tipli userler */
        $DefaultNumnber = '994775643888';
        $mass = [];
        $users = SiteUsers::find()->where(['type' => self::TYPE])->all();
        if (!empty($users)) {
            foreach ($users as $key => $user) {
                $phone_number = $user->phone_number;
                $phone_number = trim($phone_number, '+');
                $phone_number = trim($phone_number, '_');
                $phone_number = str_replace('-', '', $phone_number);
                $phone_number = str_replace(' ', '', $phone_number);

                if (empty($phone_number) || $phone_number == 'NaN' || strlen($phone_number) < 9) {
                    $phone_number = $DefaultNumnber;
                }

                $first3simvol = substr($phone_number, 0, 9);
                if ($first3simvol == '(+994 12)') {
                    $phone_number = $DefaultNumnber;
                }

                $first3simvol = substr($phone_number, 0, 8);
                if ($first3simvol == '(+99412)') {
                    $phone_number = $DefaultNumnber;
                }

                $first3simvol = substr($phone_number, 0, 3);
                if ($first3simvol == '012' || $first3simvol == '(01') {
                    $phone_number = $DefaultNumnber;
                }

                $first3simvol = substr($phone_number, 0, 1);
                if ($first3simvol == '0') {
                    $phone_number = substr($phone_number, 1);
                }

                $first3simvol = substr($phone_number, 0, 3);
                if ($first3simvol != '994') {
                    $phone_number = '994' . $phone_number;
                }

                if (strlen($phone_number) < 12 || strlen($phone_number) > 12) {
                    $phone_number = $DefaultNumnber;
                }

                $user->phone_number = $phone_number;
                if ($user->save(false)) {
                    $mass[$user->id] = 'yes';
                } else {
                    $mass[$user->id] = 'no';
                }
//                echo '<br>';
//                echo $phone_number." Kohne: {$user->phone_number}";
//                echo '<br>';
            }
        }

        echo '<pre>';
        print_r($mass);
        echo '</pre>';
        return 'ok';
    }

    /**
     * doctor ve obyekt panel real rejime kecdikde site_user cedvelindeki reset=1 olanalri sil
     * site_doctors site_enterprise cedvellerinde reset=1 olnalarin user_id sini 0 et
     * asaqidaki kodu birde islet
     */
    /**
     * https://e-tibb.az/api/general/doctors/make-user
     */
    public function actionMakeUser()
    {
        exit('a');
        Yii::$app->db->schema->refresh();

        $array = [];
        $doctors = SiteDoctors::find()->where(['update' => 0, 'user_id' => 0])->limit(15)->all();

        if (!empty($doctors)) {
            foreach ($doctors as $key => $doctor) {
                $number = SitePhoneNumbers::find()->where(['number_type' => 1, 'type' => self::TYPE, 'connect_id' => $doctor['id']])->one();

                $lastRow = SiteUsers::find()->select('id')->orderBy(['id' => SORT_DESC])->one();

                $unique_id = str_pad($lastRow['id'] + 1, 7, 0, STR_PAD_LEFT);

                $makeEmail = str_replace('-', '.', Functions::slugify($doctor['name'])) . '.' . $doctor['id'] . '0@e-tibb.az';

                $email = empty($doctor['email']) ? $makeEmail : $doctor['email'];

                $check = SiteUsers::find()->where(['email' => $email])->one();
                if (empty($check)) {
                    $user = new SiteUsers();
                    $user->name = $doctor['name'];
                    $user->unique_id = $unique_id;
                    $user->email = $email;
                    $user->phone_number = !empty($number) ? $number['number'] : '994775643888';
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

                        $update = SiteDoctors::findOne($doctor['id']);
                        $update->user_id = $user->id;
                        $update->update = 1;
                        $update->reset = 1;
                        if ($update->save(false)) {
                            if (!empty($user->phone_number)) {

                                $array['ok'][$key]['doctor_id'] = $doctor['id'];
                                $array['ok'][$key]['user_id'] = $user['id'];

                                /** Send Sms */
                                $message = "Hörmətli {$doctor['name']}, E-tibb.az saytı yeniləndi.\n\nKabinetinizə daxil olmaq üçün aşağıdakı məlumatları istifadə edə bilərsiniz\n\n{$user->phone_number}\n\nE-mail:{$email}\nŞifrə:{$password}";
                                Functions::SendSms('994552884261', $message);

                                $message = "Hörmətli {$doctor['name']}, E-tibb.az saytı yeniləndi.\n\nKabinetinizə daxil olmaq üçün aşağıdakı məlumatları istifadə edə bilərsiniz\n\nE-mail:{$email}\nŞifrə:{$password}";
                                Functions::SendSms($user->phone_number, $message);

                            }
                        }

                    } else {
                        $array['no'][$key]['enterprise_id'] = $doctor['id'];
                    }
                } else {
                    $array['indb'][$key]['enterprise_id'] = $doctor['id'] . '-' . $doctor['email'];
                }
            }
        }
        return $array;
    }

    /**
     * Hekimler
     * https://e-tibb.az/api/general/doctors
     * ?page=1
     * &count=5
     * &search=test
     * &type=0 | 1
     */
    public function actionIndex()
    {
        $model = new DoctorsApiModel();

        $type = Yii::$app->request->get('type');


        $totalCount = $model->DoctorsCount($type);

        if ($totalCount['count'] <= 0) {
            return $this->response(200, "Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $status = SiteDoctors::get_Status();

        $list = $model->Doctors($limits, $type);

        if (!empty($list)) {
            foreach ($list as $key => $val) {
                $list[$key]['status_name'] = $status[$list[$key]['status']];
            }
        }

        $data['list'] = $this->ResultList($list);

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages, $data);

        return $this->response(200, "Məlumat mövcuddur", $result);

    }

    /**
     * Hekimi qalici silmek
     * postla [id] gondermek lazimdi
     * https://e-tibb.az/api/general/doctors/delete-one
     */
    public function actionDeleteOne()
    {

        $id = Yii::$app->request->post('id');

        try {
            $data = DoctorsApiModel::doctorFind($id);


            DoctorsApiModel::deleteDoctor($id, $data['user_id']);

            AdminLog::write(['id' => $data['id'], 'name' => $data['name']]);

            return $this->response(200, "Məlumat silindi");

        } catch (\Exception $e) {
            return $this->response(400, "Bu nömrəli həkim tapilmadı");
        }


    }

    /**
     * Hekimler
     * https://e-tibb.az/api/general/doctors/search
     * https://e-tibb.az/api/general/doctors/search?page=1&count=5
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

    /**
     * Bir nece hekimi qalici silmek
     * postla [id] - ler gondermek lazimdi
     * https://e-tibb.az/api/general/doctors/all-delete
     */
    public function actionAllDelete()
    {
        $ids = Yii::$app->request->post('id');

        try {
            foreach ($ids as $id) {
                $data = DoctorsApiModel::doctorFind($id);
                DoctorsApiModel::deleteDoctor($id, $data['user_id']);

                AdminLog::write(['id' => $data['id'], 'name' => $data['name']]);
            }

            return $this->response(200, "Məlumat silindi");

        } catch (\Exception $e) {
            return $this->response(400, "Bu nömrəli həkim tapilmadı");
        }


    }


    /**
     * Hekimi Databasaden silmek
     * postla [id] gondermek lazimdi
     * https://e-tibb.az/api/general/doctors/base-delete-one
     */

    public function actionBaseDeleteOne()
    {

        $id = Yii::$app->request->post('id');

        try {
            if (Yii::$app->userCheck->can("superadmin")) {
                $data = DoctorsApiModel::doctorFind($id);

                DoctorsApiModel::deleteBaseDoctor($id);

                AdminLog::write(['id' => $data['id'], 'name' => $data['name']]);

                return $this->response(200, "Məlumat silindi");
            } else {
                return $this->response(403, "Bu əməliyyatı icra etmək üçün icazəniz yoxdur");
            }

        } catch (\Exception $e) {
            return $this->response(400, "Bu nömrəli həkim tapilmadı");
        }


    }

    /**
     * Bir nece hekimi databaseden silmek
     * postla [id] - ler gondermek lazimdi
     * https://e-tibb.az/api/general/doctors/all-base-delete
     */

    public function actionAllBaseDelete()
    {
        $ids = Yii::$app->request->post('id');

        try {
            if (Yii::$app->userCheck->can("superadmin")) {
                foreach ($ids as $id) {
                    $data = DoctorsApiModel::doctorFind($id);
                    DoctorsApiModel::deleteBaseDoctor($id);
                    AdminLog::write(['id' => $data['id'], 'name' => $data['name']]);
                }
                return $this->response(200, "Məlumat silindi");
            } else {
                return $this->response(403, "Bu əməliyyatı icra etmək üçün icazəniz yoxdur");
            }
        } catch (\Exception $e) {
            return $this->response(400, "Bu nömrəli həkim tapilmadı");
        }
    }


    /**
     * Hekim yaratmaq
     * https://e-tibb.az/api/general/doctors/create
     *  name:Java
     * //vip:0
     * experience1:2017
     * specialists[0]:29
     * specialists[1]:47
     * email:java@ss.aa
     * home_doctor:0
     * child_doctor:1
     * sosial_links[0][link]:https://www.facebook.com/Torakal-Cerrah-Nurlan-Alizade-677507572695956/
     * phone_numbers[0][type]:1
     * phone_numbers[0][number]:2884261
     * workplaces_list[0][name]:Merkezi gomruk hospitali
     * workplaces_list[0][address]:Azerbaijan,Baku
     * gender:1
     * degree:1
     * about:test
     * birthday:2017-09-10
     * skype:dfgdfgdfgdfg
     */
    public function actionCreate()
    {
        Yii::$app->db->schema->refresh();
        $model = new SiteDoctors();
        $user = new SiteUsers();
        $post = Yii::$app->request->post();

        if (!empty($post) && $model->load($post, '')) {

            /** Create User */
            $lastRow = SiteUsers::find()->select('id')->orderBy(['id' => SORT_DESC])->one();
            $pass = rand(111111, 999999);
            $user->name = $model->name;
            $user->unique_id = str_pad($lastRow['id'] + 1, 7, 0, STR_PAD_LEFT);
            $user->email = $model->email;
            $user->phone_number = $model->phone_numbers[1]['number'];
            $user->phone_prefix = "994";
            $user->status = 1;
            $user->created_at = time();
            $user->last_login = date("Y-m-d H:i:s");
            $user->type = self::TYPE;
            $user->birthday = $model->birthday;
            $user->password = $pass;
            $user->setPassword($user->password);
            $user->generateAuthKey();


            $datetime = date('Y-m-d', strtotime("+1 month", strtotime(date('Y-m-d'))));

            $model->expires = $datetime;
            $model->slug = Functions::slugify($model->name);
//            $model->vip             = 0;
            $model->status = 0;
            $model->published_time = date('Y-m-d H:i:s');
            $model->modified_time = date('Y-m-d H:i:s');

            // Default value
            if ($model->degree == 0) {
                $model->degree = 1;
            }

            if ($model->home_doctor) {
                $model->feature = 1;
            }

            if ($model->child_doctor) {
                $model->feature = 2;
            }

            if ($model->home_doctor && $model->child_doctor) {
                $model->feature = 3;
            }

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

            /** Check Diplomas*/
            $diplomas = UploadedFile::getInstancesByName('dp_files');
            if (empty($diplomas)) {
                $model->addError('dp_files', 'Diplom elave edin');
            } else {
                $imageUpload = new ImageUpload();
                foreach ($diplomas as $key => $diplom) {
                    if (!$imageUpload->validate($diplom)) {
                        $model->addError('dp_files', 'Diplom yüklənməsi zamanı xəta baş verdi');
                    }
                }
            }

            if (!empty($model->errors)) {
                return $this->response(400, 'Məlumatın əlavə olunması zamanı xəta baş verdi', $model->errors);
            }

            if ($model->save()) {
                $errors = [];
                /** User_id */
                try {
                    if ($user->save()) {
                        $model2 = SiteDoctors::findOne($model->id);
                        $model2->user_id = $user->id;
                        $model2->save(false);
                    } else {
                        $errors['date'] = date("Y-m-d H:i:s");
                        $errors['errors'] = $user->errors;
                    }
                } catch (\Exception $e) {
                    $errors['date'] = date("Y-m-d H:i:s");
                    $errors['errors'] = $e->getMessage();
                }

                if(!empty($errors)) {
                    $errors = json_encode($errors)."\n";
                    $jsonfile= Yii::getAlias("@webroot/errorsss.php");
                    $fp = fopen($jsonfile, 'a');
                    fwrite($fp, $errors);
                    fclose($fp);
                }

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

                if(!empty($model->phone_numbers)) {
                    foreach ($model->phone_numbers as $key => $val) {
                        if (!empty($val['number'])) {
                            $phone_numbers = new SitePhoneNumbers();
                            $phone_numbers->connect_id = $model->id;
                            $phone_numbers->number = $val['number'];
                            $phone_numbers->number_type = $val['type'];
                            $phone_numbers->type = self::TYPE;
                            $phone_numbers->prefix = "994";
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
                if (!empty($photo)) {
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
                if (!empty($diplomas)) {
                    Functions::uploadFiles($diplomas, $model->id, 1, $this->customPath, self::TYPE);
                }

                /** Certificates */
                $certificates = UploadedFile::getInstancesByName('ct_files');
                if (!empty($certificates)) {
                    Functions::uploadFiles($certificates, $model->id, 2, $this->customPath, self::TYPE);
                }

                /** Doctor Add Enterprise
                 * if(isset($model->hospital_id) && !empty($model->hospital_id))
                 * {
                 *      DoctorsApiModel::EnterpriseAddDoctor($model->hospital_id,$model->id);
                 * }
                 */

                if (!empty($model->phone_numbers[1]['number']) && $model->phone_numbers[1]['type'] == 1) {
                    /** Send Sms */
                    $message = "Təbriklər! Siz, e-tibb.az saytından uğurla qeydiyyatdan keçdiniz.\n\nKabinetinizə daxil olmaq üçün aşağıdakı məlumatları istifadə edə bilərsiniz\n\nE-mail:{$user->email}\nŞifrə:{$pass}";
                    Functions::SendSms($model->phone_numbers[1]['number'], $message);

                    $message = "Təbriklər! Siz, e-tibb.az saytından uğurla qeydiyyatdan keçdiniz.\n\nKabinetinizə daxil olmaq üçün aşağıdakı məlumatları istifadə edə bilərsiniz\n\nE-mail:{$user->email}\nŞifrə:{$pass}";
                    Functions::SendSms('994552884261', $message);
                }

                $data = DoctorsApiModel::doctorFind($model->id);

                AdminLog::write(['id' => $data['id'], 'name' => $data['name']]);

                return $this->response(200, 'Həkim uğurla əlavə olundu');

            } else {
                return $this->response(400, 'Məlumatın əlavə olunması zamanı xəta baş verdi');
            }

        } else {
            return $this->response(400, 'Məlumatın əlavə olunması zamanı xəta baş verdi');
        }

    }

    /**
     * Hekim duzelis et
     * https://e-tibb.az/api/general/doctors/edit
     * id
     * deletedImages = 0 1
     * deletedDiplomas [107,108]
     * deletedCertificates [107,108]
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
        $user = new SiteUsers();

        if (empty($model)) {
            return $this->response(200, "Heçbir məlumat tapılmadı");
        }

        if (!empty($post) && $model->load($post, '')) {

            $model->email = $oldModel->email;

            $model->modified_time = date('Y-m-d H:i:s');

            if ($model->home_doctor) {
                $model->feature = 1;
            }

            if ($model->child_doctor) {
                $model->feature = 2;
            }

            if ($model->home_doctor && $model->child_doctor) {
                $model->feature = 3;
            }

            // Default value
            if ($model->degree == 0) {
                $model->degree = 1;
            }

            $model->validate();

            /** Work places  */
            if (isset($model->workplaces_list) && !empty($model->workplaces_list) && empty($model->errors)) {
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

            /** Specialists */
            $spc_selected_options = $this->getSpecialist($model->id);
            $spc_selected_options = !empty($spc_selected_options) ? $spc_selected_options : [];
            $specialists = !empty($model->specialists) ? $model->specialists : [];

            $spcDeleteOptions = array_diff($spc_selected_options, $specialists);
            $spcAddOptions = array_diff($specialists, $spc_selected_options);

            if (!empty($spcAddOptions) && empty($model->errors)) {
                foreach ($spcAddOptions as $key => $val) {
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

            if (!empty($spcDeleteOptions)) {
                foreach ($spcDeleteOptions as $key => $val) {
                    $spcDelete = SiteDoctorSpecialist::find()->where(['doctor_id' => $model->id, 'specialist_id' => $val])->one();
                    if (!empty($spcDelete)) {
                        $siteSpc = SiteSpecialists::find()->where(['id' => $val])->one();
                        $siteSpc->count = $siteSpc->count - 1;
                        if ($siteSpc->count < 0) {
                            $siteSpc->count = 0;
                        }
                        $siteSpc->save();
                        $spcDelete->delete();
                    }
                }
            }

            /** Sosial links */
            if (isset($model->sosial_links) && !empty($model->sosial_links) && empty($model->errors)) {
                $added_sosial_links = SiteSocialLinksModel::getSocialLinks($model->id, self::TYPE);
                $added_sosial_links = !empty($added_sosial_links) ? $added_sosial_links : [];
                $sosial_links = !empty($model->sosial_links) && is_array($model->sosial_links) ? $model->sosial_links : [];
                $max = max(count($added_sosial_links), count($sosial_links));

                for ($x = 0; $x < $max; $x++) {
                    if (isset($added_sosial_links[$x]['type'])) {
                        if (!empty($sosial_links[$x]['link'])) {
                            if (($added_sosial_links[$x]['type'] != $sosial_links[$x]['type']) || ($added_sosial_links[$x]['link'] != $sosial_links[$x]['link'])) {
                                $upd_sosial_links = SiteSosialLinks::findOne($added_sosial_links[$x]['id']);
                                $upd_sosial_links->link = $sosial_links[$x]['link'];
                                $upd_sosial_links->link_type = $sosial_links[$x]['type'];
                                $upd_sosial_links->save(false);
                            }
                        } else {
                            $SosialLinksDelete = SiteSosialLinks::findOne($added_sosial_links[$x]['id']);
                            if (!empty($SosialLinksDelete)) {
                                $SosialLinksDelete->delete();
                            }
                        }
                    } elseif (isset($sosial_links[$x]['type']) && !empty($sosial_links[$x]['link'])) {

                        $ins_sosial_links = new SiteSosialLinks();
                        $ins_sosial_links->connect_id = $model->id;
                        $ins_sosial_links->link = $sosial_links[$x]['link'];
                        $ins_sosial_links->link_type = $sosial_links[$x]['type'];
                        $ins_sosial_links->type = self::TYPE;
                        $ins_sosial_links->save();

                    }

                };

            }

            /** Phone numbers */
            if (isset($model->phone_numbers) && !empty($model->phone_numbers) && empty($model->errors)) {
                $added_phone_numbers = SitePhoneNumbersModel::getPhones($model->id, self::TYPE);
                $added_phone_numbers = !empty($added_phone_numbers) ? $added_phone_numbers : [];
                $phone_numbers = !empty($model->phone_numbers) && is_array($model->phone_numbers) ? $model->phone_numbers : [];

                $max = max(count($added_phone_numbers), count($phone_numbers));
                for ($x = 0; $x < $max; $x++) {
                    if (isset($added_phone_numbers[$x]['type'])) {
                        if (!empty($phone_numbers[$x]['number'])) {
                            if (($added_phone_numbers[$x]['type'] != $phone_numbers[$x]['type']) || ($added_phone_numbers[$x]['number'] != $phone_numbers[$x]['number']) || ($added_phone_numbers[$x]['number'] != !empty($phone_numbers[$x]['number_type']))) {
                                $upd_phone_numbers = SitePhoneNumbers::findOne($added_phone_numbers[$x]['id']);
                                $upd_phone_numbers->number = $phone_numbers[$x]['number'];
                                $upd_phone_numbers->number_type = $phone_numbers[$x]['type'];
                                $upd_phone_numbers->prefix = "994";
                                $upd_phone_numbers->save(false);

                            }
                        } elseif (!empty($phone_numbers[$x]['number_type'])) {
                            $upd_phone_numbers = SitePhoneNumbers::findOne($added_phone_numbers[$x]['id']);
                            $upd_phone_numbers->number = $phone_numbers[$x]['number'];
                            $upd_phone_numbers->number_type = $phone_numbers[$x]['type'];
                            $upd_phone_numbers->prefix = "994";
                            $upd_phone_numbers->save(false);
                        } else {
                            $del_phone_numbers = SitePhoneNumbers::findOne($added_phone_numbers[$x]['id']);
                            if (!empty($del_phone_numbers)) {
                                $del_phone_numbers->delete();

                            }

                        }
                    } elseif (isset($phone_numbers[$x]['type']) && !empty($phone_numbers[$x]['number'])) {
                        $ins_phone_numbers = new SitePhoneNumbers();
                        $ins_phone_numbers->connect_id = $model->id;
                        $ins_phone_numbers->number = $phone_numbers[$x]['number'];
                        $ins_phone_numbers->number_type = $phone_numbers[$x]['type'];
                        $ins_phone_numbers->type = self::TYPE;
                        $ins_phone_numbers->prefix = "994";
                        $ins_phone_numbers->save();
                    }
                }
            }



            /** Check main image */
            $deletedImages = $model->deletedImages;
            $photo = UploadedFile::getInstanceByName('files');
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

            /** Check Diplomas */
            $diplomas = UploadedFile::getInstancesByName('dp_files');
            if (empty($diplomas)) {
//                $addedDiplomas = SiteDoctorFilesModel::find()->where(['connect_id'=>$model->id,'user_type'=>self::TYPE,'type'=>1])->all();
//                if(count($addedDiplomas)<1 || (!empty($model->deletedDiplomas) && (count($addedDiplomas) == count($model->deletedDiplomas))))
//                {
//                    $model->addError('dp_files','Diplom elave edin');
//                }
            } else {
                $imageUpload = new ImageUpload();
                foreach ($diplomas as $key => $diplom) {
                    if (!$imageUpload->validate($diplom)) {
                        $model->addError('dp_files', 'Diplom yüklənməsi zamanı xəta baş verdi');
                    }
                }
            }

            if (!empty($model->errors)) {
                return $this->response(400, 'Məlumatın yenilənməsi zamanı xəta baş verdi', $model->errors);
            }

            if ($model->save()) {

                /** Update update */
                if (!empty($oldModel->user_id)) {
                    $userUpdate = SiteUsers::findOne($model->user_id);
                    if (!empty($userUpdate)) {
                        $userUpdate->birthday = $model->birthday;
                        $userUpdate->name = $model->name;
                        $userUpdate->phone_number = $model->phone_numbers[1]['number'];
                        $userUpdate->phone_prefix = "994";
                        $userUpdate->save(false);
                    }
                }

                /** Main image */
                if (!empty($photo)) {
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

                /** Diplomas*/
                if (!empty($model->deletedDiplomas)) {
                    Functions::deleteFiles(1, $model->deletedDiplomas, $this->customPath, $model->id);
                }

                if (!empty($diplomas)) {
                    Functions::uploadFiles($diplomas, $model->id, 1, $this->customPath, self::TYPE);
                }

                /** Certificates*/
                if (!empty($model->deletedCertificates)) {
                    Functions::deleteFiles(2, $model->deletedCertificates, $this->customPath, $model->id);
                }

                $ctFiles = UploadedFile::getInstancesByName('ct_files');
                if (!empty($ctFiles)) {
                    Functions::uploadFiles($ctFiles, $model->id, 2, $this->customPath, self::TYPE);
                }

                $data = DoctorsApiModel::doctorFind($id);

                AdminLog::write(['id' => $data['id'], 'name' => $data['name']]);

                return $this->response(200, 'Məlumatın uğurla yeniləndi');
            }
        }

        return $this->response(400, 'Məlumatın yenilənməsi zamanı xəta baş verdi');
    }

    public function getSpecialist($id)
    {
        $return = [];
        $data = SiteDoctorSpecialist::find()->where(['doctor_id' => $id])->all();
        if (!empty($data)) {
            foreach ($data as $key => $val) {
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
     * https://e-tibb.az/api/general/doctors/list-status
     */
    public function actionListStatus()
    {
        $result = SiteDoctors::getStatus();
        return $this->response(200, "Məlumat mövcuddur", $result);
    }

    /**
     * Hekim cins
     * https://e-tibb.az/api/general/doctors/list-gender
     */
    public function actionListGender()
    {
        $result = SiteDoctors::getSex();
        return $this->response(200, "Məlumat mövcuddur", $result);
    }

    /**
     * Akademik derece
     * https://e-tibb.az/api/general/doctors/list-academic-degree
     */
    public function actionListAcademicDegree()
    {
        $result = SiteDoctors::getDegree();
        return $this->response(200, "Məlumat mövcuddur", $result);
    }

    /**
     * Mutexessisler
     * https://e-tibb.az/api/general/doctors/specialists
     */
    public function actionSpecialists()
    {
        $result = SiteSpecialists::find()->orderBy('name')->all();
        return $this->response(200, "Məlumat mövcuddur", $result);
    }

    /**
     * Sosial link tipleri
     * https://e-tibb.az/api/general/doctors/list-sosial-link
     */
    public function actionListSosialLink()
    {
        $result = Yii::$app->params['allow.sosial_icons'];
        return $this->response(200, "Məlumat mövcuddur", $result);
    }

    /**
     * Nomre tipleri
     * https://e-tibb.az/api/general/doctors/list-phone-number
     */
    public function actionListPhoneNumber()
    {
        $result = Yii::$app->params['allow.number_type'];
        return $this->response(200, "Məlumat mövcuddur", $result);
    }

    /**
     * Hekim info
     * https://e-tibb.az/api/general/doctors/info/245
     */
    public function actionInfo()
    {

        $id = intval(Yii::$app->request->get('id'));
        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        /** Hekim info */
        $doctor = SiteDoctorsModel::getDoctorInfo($id);
        if (!empty($doctor)) {
            $data['doctor'] = $this->ResultList($doctor);
        } else {
            return $this->response(200, "Heçbir məlumat tapılmadı");
        }

        $user = SiteUsers::find()->where(['id' => $data['doctor']['user_id']])->one();

        $data['doctor']['home_doctor'] = 0;
        $data['doctor']['child_doctor'] = 0;
        $data['doctor']['experience'] = $data['doctor']['experience1'];
        $data['doctor']['real_experience'] = intval(date('Y')) - intval($data['doctor']['experience1']) > 0 && !empty($data['doctor']['experience1']) ? intval(date('Y')) - intval($data['doctor']['experience1']) : '';

        unset($data['doctor']['experience1']);

        $feature = $data['doctor']['feature'];

        if ($feature == 1) {
            $data['doctor']['home_doctor'] = 1;
        }

        if ($feature == 2) {
            $data['doctor']['child_doctor'] = 1;
        }

        if ($feature == 3) {
            $data['doctor']['home_doctor'] = 1;
            $data['doctor']['child_doctor'] = 1;
        }

        /** Hekim | User */
        $data['doctor']['birthday'] = $user['birthday'];

        /** Hekim | Mutexessis */
        $data['specialist'] = SiteDoctorsModel::getDoctorSpecialist($id);

        /** Hekim | İs yerleri */
        $data['workplaces'] = SiteDoctorsModel::getWorkplaces($id);

        /** Hekim | Telefon nomreleri */
        $data['phones'] = SitePhoneNumbersModel::getPhones($doctor["id"], self::TYPE);


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

                    } else {
                        $data['phones'][$val] = [];
                    }
                }
            }
        }


        /** Hekim | Sosial linkler */
        $data['social_links'] = SiteSocialLinksModel::getSocialLinks($doctor["id"], self::TYPE);

        /** Hekim | Diplom */
        $diploms = SiteDoctorFilesModel::getFiles($id, 1);
        $data['diploms'] = !empty($diploms) ? $this->ResultList($diploms) : [];

        /** Hekim | Sertifikat */
        $certificate = SiteDoctorFilesModel::getFiles($id, 2);
        $data['certificate'] = !empty($certificate) ? $this->ResultList($certificate) : [];

        return $this->response(200, "Məlumat mövcuddur", $data);
    }

    /**
     * Hekim odenisler
     * https://e-tibb.az/api/general/doctors/payments/130
     */
    public function actionPayments()
    {
        $id = intval(Yii::$app->request->get('id'));
        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        $model = new AccountingApiModel();

        $totalCount = $model->PaymentsCount($id);

        if ($totalCount['count'] <= 0) {
            return $this->response(200, "Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $model->Payments($id, $limits);

        if (!empty($list)) {

            $packages = Functions::customIndexArray(PackagesServicesModel::getPackages(), 'id');
            $services = Functions::customIndexArray(PackagesServicesModel::getServices(), 'id');

            if (!empty($packages) && !empty($services)) {
                foreach ($list as $key => $val) {
                    if (isset(Yii::$app->params['payment.type'][$val['payment_type']])) {
                        $list[$key]['service_name'] = isset($packages[$val['payment_info']]['name']) ? $packages[$val['payment_info']]['name'] : null;
                    } else {
                        $list[$key]['service_name'] = isset($services[$val['payment_info']]['name']) ? $services[$val['payment_info']]['name'] : null;
                    }

                    $month = intval($val['quantity']);
                    $time = strtotime($list[$key]['payment_date']);
                    $endData = date("Y-m-d", strtotime("+{$month} month", $time));
                    $endData = Functions::getDatetime($endData, ['type' => 'date', 'month' => 'no', 'combine' => '.']);

                    $list[$key]['payment_method'] = $val['payment_method'] == 0 ? 'Nağd' : 'Kart';
                    $list[$key]['package_name'] = $val['quantity'] . ' Aylıq';
                    $list[$key]['services_duration'] = Functions::getDatetime($list[$key]['payment_date'], ['type' => 'date', 'month' => 'no', 'combine' => '.']) . ' - ' . $endData;

                    unset($list[$key]['type']);
                    unset($list[$key]['payment_type']);
                    unset($list[$key]['payment_info']);
                    unset($list[$key]['created_date']);
                }
            }

        }

        $data['list'] = $list;

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages, $data);

        return $this->response(200, "Məlumat mövcuddur", $result);
    }

    /**
     * Hekim hesab bloklamaq
     * https://e-tibb.az/api/general/doctors/block
     *
     */
    public function actionBlock()
    {
        $id = intval(Yii::$app->request->post('id'));
        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        $update = DoctorsApiModel::Block($id);
        if ($update) {
            $data = DoctorsApiModel::doctorFind($id);

            AdminLog::write(['id' => $data['id'], 'name' => $data['name']]);
            return $this->response(200, "Həkimin hesabı donduruldu");
        }

        return $this->response(400, "Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Hekim hesab aktiv etmek
     * https://e-tibb.az/api/general/doctors/active
     */
    public function actionActive()
    {
        $id = intval(Yii::$app->request->post('id'));
        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        $update = DoctorsApiModel::Active($id);
        if ($update) {
            $data = DoctorsApiModel::doctorFind($id);

            AdminLog::write(['id' => $data['id'], 'name' => $data['name']]);
            return $this->response(200, "Həkimin hesabı aktiv edildi");

        }

        return $this->response(400, "Əməliyyatın icra olunması zamanı xəta baş verdi");
    }


    protected function findModel($id)
    {
        if (($model = SiteDoctors::findOne($id)) !== null) {
            return $model;
        }
    }

    /**
     * Bildiris sms gondermek
     * https://e-tibb.az/api/general/doctors/sms
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
        $doctor = SiteDoctorsModel::getDoctorInfo($id);
        $data['phones'] = SitePhoneNumbersModel::getPhones($doctor["id"], self::TYPE);


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
                            $data['phones']['1']['number'] = '0' . substr($data['phones']['1']['number'], 3);
                        }


                    } else {
                        $data['phones'][$val] = [];
                    }
                }
            }
        }

        $phone = '994'.substr($data['phones'][1]['number'],1);

        /** Send Sms */
        Functions::SendSms($phone, $message);

        Functions::SendSms('994554387880', $message);



        return $this->response(200, "Məlumat uğurla göndərildi");

    }

    /**
     * Bildiris mail gondermek
     * https://e-tibb.az/api/general/doctors/mail
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
        $doctor = SiteDoctorsModel::getDoctorInfo($id);

        if(empty($doctor))
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $message = $message . '<br><br>'. nl2br('Hörmətlə e-tibb.az komandası.');

        Yii::$app->mailer->compose()
            ->setFrom('no-reply@e-tibb.az')
            ->setTo($doctor['email'])
            ->setSubject('E-tibb.az | Sorğunuzun cavabı')
            ->setHtmlBody($message)
            ->send();


        return $this->response(200, "Məlumat uğurla göndərildi");
    }

}
