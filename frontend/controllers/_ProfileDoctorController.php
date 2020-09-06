<?php

namespace frontend\controllers;

use backend\models\SiteDoctorSpecialist;
use backend\components\Functions;
use backend\models\SiteSpecialists;
use frontend\models\SiteCalling;
use common\models\SiteEnterprises;
use frontend\components\ProfileLinks;
use frontend\models\Doctor;
use frontend\models\Enterprise;
use frontend\models\EnterpriseModel;
use frontend\models\PricesModel;
use frontend\models\ServicesModel;
use frontend\models\SiteConsultation;
use frontend\models\SiteConsultationModel;
use common\models\SiteDoctors;
use frontend\models\SiteDoctorsModel;
use frontend\models\Transactions;
use frontend\models\WorkDaysModel;
use frontend\models\SiteDoctorWorkplacesList;
use frontend\models\User;
use backend\models\SiteUsers;
use backend\models\SiteSosialLinks;
use backend\models\SitePhoneNumbers;
use backend\components\ImageUpload;
use backend\models\SiteGallery;
use common\models\AuthEnterpriseForm;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\web\Session;
use yii\web\UploadedFile;
use backend\models\SiteAddresses;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use frontend\components\Menu;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;


/**
 * Auth controller
 */
class ProfileDoctorController extends MainController
{

    public $menus;
    public $pages;
    public $typeModel;
    public $layout = 'static';
    const TYPE = 1;

    public $customPath = 'doctors';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['settings', 'doctor-questions', 'questions', 'save-questions', 'update-questions', 'delete-answer', 'delete-questions', 'workdays', 'update', 'time-expand', 'delete-work-days', 'appoint', 'delete-appoint', 'accept-appoint'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            if (!Yii::$app->user->isGuest) {

                                if (Yii::$app->user->identity->type != self::TYPE) {
                                    $this->redirect("");
                                    return false;
                                } elseif (Yii::$app->user->identity->status == 2) {
                                    $this->redirect(Url::to(['auth-doctor/step2']));
                                    return false;
                                } else {
                                    return true;
                                }
                            }
                        }
                    ]
                ],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'layout' => false
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($action)
    {
        $menus = ArrayHelper::toArray(new Menu());
        $this->menus = $menus['list'];

        $pages = new ProfileLinks();
        $this->pages = $pages->list;
        $this->typeModel = $pages->model;

//        if ($action->id == 'time-expand' or $action->id == 'accept-appoint' or $action->id == 'delete-appoint' or $action->id == 'delete-questions' or $action->id == 'delete-answer' or $action->id == 'update-questions' or $action->id == 'save-questions' or $action->id == 'delete-work-days' or $action->id == 'update')
//        {
//            $this->enableCsrfValidation = true;
//        }

        return parent::beforeAction($action);
    }

    public function actionSettings()
    {
        $user_id = Yii::$app->user->id;
        $doctor = SiteDoctors::getDoctorWithId($user_id);
//        $old_image = $doctor->photo;

        if (empty($doctor)) {
            return $this->showError('Axtardığınız səhifə tapılmadı');
        }
        $model = $doctor;
        $user = User::findIdentity($user_id);
        $model->email = $user->email;

        $model->contact_name = $user->name;
        $model->contact_phone = $user->phone_number;


        //$model->email = $user->email;

        $model->about = html_entity_decode(strip_tags($model->about));
        $specialist = ArrayHelper::map(SiteSpecialists::find()->orderBy('name')->all(), 'id', 'name');
        $selected_specialist = ArrayHelper::map(SiteDoctorsModel::getDoctorSpecialist($model->id), 'id', 'id');
        $model->specialists = $selected_specialist;

        $model->birthday = date("d-m-Y", strtotime($user->birthday));
        //$sosialLinks        = \backend\models\SiteSosialLinks::find()->where(['connect_id'=>$model->id,'type'=>1])->all();

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->post($model->getClassName())) {
            //echo '<pre>'; print_r($model); die();

            if ($model->home_doctor) {
                $jsonData['eve_caqiris'] = 1;
            }
            if ($model->child_doctor) {
                $jsonData['child_doctor'] = 1;
            }

            $model->feature = isset($jsonData) ? json_encode($jsonData) : null;

            /** Work places  */
            if (isset($model->workplaces_list) && !empty($model->workplaces_list)) {
                SiteDoctorWorkplacesList::deleteAll('doctor_id = :doctor', [':doctor' => $model->id]);
                foreach ($model->workplaces_list as $val) {
                    $workplaces = new SiteDoctorWorkplacesList();
                    $workplaces->name = !empty($val['name']) ? $val['name'] : null;
                    $workplaces->address = !empty($val['address']) ? $val['address'] : null;
                    $workplaces->doctor_id = $model->id;
                    $workplaces->save();
                }
            }

            /** Specialists */
            if (is_array($model->specialists) and !empty($model->specialists)) {
                $old_selected = SiteDoctorSpecialist::deleteAll('doctor_id = :doctor', ['doctor' => $model->id]);
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

            /** Sosial links */
            if (isset($model->sosial_links) && !empty($model->sosial_links)) {
                $added_sosial_links = !empty($model->added_sosial_links) ? json_decode(base64_decode($model->added_sosial_links), true) : [];
                $sosial_links = !empty($model->sosial_links) ? $model->sosial_links : [];
                $max = max(count($added_sosial_links), count($sosial_links));
                $added_sosial_links2 = [];
                $sosial_links2 = [];
                $max = 6;
                foreach ($added_sosial_links as $key => $value) {
                    $added_sosial_links2[$value["type"]] = $value;
                }
                $added_sosial_links = $added_sosial_links2;

                foreach ($sosial_links as $key => $value) {
                    $sosial_links2[$value["type"]] = $value;
                }
                $sosial_links = $sosial_links2;

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
            if (isset($model->phone_numbers) && !empty($model->phone_numbers)) {
                //SitePhoneNumbers::deleteAll('connect_id = :doctor',[':doctor' => $model->id]);
                $added_phone_numbers = !empty($model->added_phone_numbers) ? json_decode(base64_decode($model->added_phone_numbers), true) : [];
                $phone_numbers = !empty($model->phone_numbers) ? $model->phone_numbers : [];
                $max = max(count($added_phone_numbers), count($phone_numbers));

                for ($x = 0; $x < $max; $x++) {
                    if (isset($added_phone_numbers[$x]['type'])) {
                        if (!empty($phone_numbers[$x])) {
                            if (($added_phone_numbers[$x]['number'] != $phone_numbers[$x])) {

                                $upd_phone_numbers = SitePhoneNumbers::findOne($added_phone_numbers[$x]['id']);
                                $upd_phone_numbers->number = $phone_numbers[$x];
                                $upd_phone_numbers->number_type = 0;
                                $upd_phone_numbers->save(false);
                            }
                        } else {
                            $del_phone_numbers = SitePhoneNumbers::findOne(["id" => $added_phone_numbers[$x]['id'], "number_type" => 0]);
                            if (!empty($del_phone_numbers)) {
                                $del_phone_numbers->delete();
                            }
                        }
                    } elseif (!empty($phone_numbers[$x])) {
                        $ins_phone_numbers = new SitePhoneNumbers();
                        $ins_phone_numbers->connect_id = $model->id;
                        $ins_phone_numbers->number = $phone_numbers[$x];
                        $ins_phone_numbers->number_type = 0;
                        $ins_phone_numbers->type = self::TYPE;
                        $ins_phone_numbers->save();

                    }

                };

            }

            /** Mobile numbers */
            if (isset($model->mobile_numbers) && !empty($model->mobile_numbers)) {
                $added_mobile_numbers = !empty($model->added_mobile_numbers) ? json_decode(base64_decode($model->added_mobile_numbers), true) : [];
                $mobile_numbers = !empty($model->mobile_numbers) ? $model->mobile_numbers : [];
                //print_r($added_mobile_numbers);
                //echo "<pre>"; print_r($mobile_numbers); exit();
                $max = max(count($added_mobile_numbers), count($mobile_numbers));
                for ($x = 0; $x < $max; $x++) {
                    if (isset($added_mobile_numbers[$x]['type'])) {
                        if (!empty($mobile_numbers[$x])) {
                            if (($added_mobile_numbers[$x]['number'] != $mobile_numbers[$x])) {

                                $upd_phone_numbers = SitePhoneNumbers::findOne($added_mobile_numbers[$x]['id']);
                                $upd_phone_numbers->number = $mobile_numbers[$x];
                                $upd_phone_numbers->number_type = 1;
                                $upd_phone_numbers->save(false);
                            }
                        } else {
                            $del_phone_numbers = SitePhoneNumbers::findOne(["id" => $added_mobile_numbers[$x]['id'], "number_type" => 1]);
                            if (!empty($del_phone_numbers)) {
                                $del_phone_numbers->delete();
                            }
                        }
                    } elseif (!empty($mobile_numbers[$x])) {
                        $ins_phone_numbers = new SitePhoneNumbers();
                        $ins_phone_numbers->connect_id = $model->id;
                        $ins_phone_numbers->number = $mobile_numbers[$x];
                        $ins_phone_numbers->number_type = 1;
                        $ins_phone_numbers->type = self::TYPE;
                        $ins_phone_numbers->save();

                    }

                };

            }

//            if($model->files){
//                var_dump($old_image);
//                var_dump($model);
//                die;
//            }
            /** Main image & Photosession */
            $deletedImages = $model->deletedImages;
            $mainImage = $model->mainImage;


            if (!empty($deletedImages)) {
                if (strpos($deletedImages, ',')) {
                    $deletedImages = explode(',', $deletedImages);
                }

                $delete = SiteGallery::find()->where(['in', 'id', $deletedImages])->andWhere(['connect_id' => $model->id, 'type' => self::TYPE])->all();
                if (!empty($delete)) {
                    foreach ($delete as $key => $val) {
                        $imageUpload = new ImageUpload();
                        $imageUpload->deleteFile([$this->customPath . '/' . $val['photo']]);
                        $imageUpload->deleteFile([$this->customPath . '/small/' . $val['photo']]);
                        if ($val['main'] == 1) {
                            $mainImage = null;
                            $updatePhoto = $this->findSettingModel($model->id);
                            $updatePhoto->photo = '';
                            $updatePhoto->save(false);
                        }
                        $val->delete();
                    }
                }
            }

            $photo = UploadedFile::getInstances($model, 'files');

            if (!empty($photo)) {
//                foreach($photos as $key => $photo)
//                {
                $imageUpload = new ImageUpload();
//                    if(empty($mainImage) && $key == 0)
//                    {


                $imageUpload->deleteFile([$this->customPath . '/' . $model->photo]);
                $imageUpload->deleteFile([$this->customPath . '/small/' . $model->photo]);
                

                $uploadedFile = $imageUpload->saveFile($photo[0], [
                    'path.save' => $this->customPath,
                    'resize.img' => [185, 185],
                    'resize.thumb' => [121, 121]
                ]);

                $updatePhoto = $this->findSettingModel($model->id);
                $updatePhoto->photo = $uploadedFile;
                $updatePhoto->save(false);


                $model->photo = $uploadedFile;

//                    }else{
//                        $uploadedFile = $imageUpload->saveFile($photo,[
//                            'path.save'=>$this->customPath,
//                            'resize.img'=>[708,420],
//                            'resize.thumb'=>[185,110]
//                        ]);
//                    }

//                    if(!empty($uploadedFile))
//                    {
//                        $gallery             = new SiteGallery();
//                        $gallery->photo      = $uploadedFile;
//                        $gallery->connect_id = $model->id;
//                        $gallery->type       = self::TYPE;
//                        $gallery->main       = empty($mainImage) && $key == 0 ? 1 : 0;
//                        $gallery->save();
//                    }

//                }

            }

            if ($model->save()) {
                $user->name = $model->contact_name;
                $user->phone_number = $model->contact_phone;
                $user->email = $model->email;

                if (!empty($model->password) and !empty($model->repassword) and trim($model->password) == trim($model->repassword)) {
                    $user->setPassword($model->password);
                    $user->generateAuthKey();
                }
                $user->save();

                Yii::$app->session->setFlash('success', 'Məlumatlar yeniləndi');
            } else {
                Yii::$app->session->setFlash('error', 'Xəta baş verdi');
            }
        }

        return $this->render('settings', [
            'model' => $model,
            'user' => $user,
            'specialist_list' => $specialist,
            'customPath' => $this->customPath
        ]);
    }

    public function actionSettings2()
    {

        exit();

        $user_id = Yii::$app->user->id;
        $enterprise = SiteEnterprises::getEnterpriseWithId($user_id);


        $enterprise_categories = $this->menus["type"][2];

        if (isset($this->menus["id"][$enterprise->category_id])) {
            $category = $this->menus["id"][$enterprise->category_id];
            if ($category["type"] != self::TYPE) {
                return $this->redirect(Yii::$app->params["site.url"]);
            }
        }


        $model = $enterprise;
        $user = User::findIdentity($user_id);
        $model->contact_name = $user->name;
        $model->contact_phone = $user->phone_number;
        $model->email = $user->email;


        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->post($model->getClassName())) {
            if ($model->catdirilma) {
                $jsonData['catdirilma'] = 1;
            }

            if ($model->saat24) {
                $jsonData['saat24'] = 1;
            }

            if ($model->eve_caqiris) {
                $jsonData['eve_caqiris'] = 1;
            }

            $model->feature = isset($jsonData) ? json_encode($jsonData) : null;


            /** Addresses */
            if (isset($model->addresses) && !empty($model->addresses)) {
                $added_addresses = !empty($model->added_addresses) ? json_decode(base64_decode($model->added_addresses), true) : [];
                $addresses = !empty($model->addresses) ? $model->addresses : [];
                $max = max(count($added_addresses), count($addresses));

                for ($x = 0; $x < $max; $x++) {
                    if (isset($added_addresses[$x]['name'])) {
                        if (!empty($addresses[$x])) {
                            if (($added_addresses[$x]['name'] != $addresses[$x])) {
                                echo $x . "<br />";
                                $upd_address = SiteAddresses::findOne($added_addresses[$x]['id']);
                                $upd_address->address = $addresses[$x];
                                $upd_address->save(false);

                            }
                        } else {
                            $del_addresses = SiteAddresses::findOne($added_addresses[$x]['id']);
                            if (!empty($del_addresses)) {
                                $del_addresses->delete();
                            }
                        }
                    } elseif (isset($addresses[$x]) && !empty($addresses[$x])) {

                        $ins_address = new SiteAddresses();
                        $ins_address->connect_id = $model->id;
                        $ins_address->address = $addresses[$x];
                        $ins_address->type = self::TYPE;
                        $ins_address->save();

                    }
                };
            }


            /** Sosial links */
            if (isset($model->sosial_links) && !empty($model->sosial_links)) {

                $added_sosial_links = !empty($model->added_sosial_links) ? json_decode(base64_decode($model->added_sosial_links), true) : [];
                $sosial_links = !empty($model->sosial_links) ? $model->sosial_links : [];
                $max = max(count($added_sosial_links), count($sosial_links));

                $max = 6;
                foreach ($added_sosial_links as $key => $value) {
                    $added_sosial_links2[$value["type"]] = $value;
                }
                $added_sosial_links = $added_sosial_links2;

                foreach ($sosial_links as $key => $value) {
                    $sosial_links2[$value["type"]] = $value;
                }
                $sosial_links = $sosial_links2;

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
            if (isset($model->phone_numbers) && !empty($model->phone_numbers)) {
                $added_phone_numbers = !empty($model->added_phone_numbers) ? json_decode(base64_decode($model->added_phone_numbers), true) : [];
                $phone_numbers = !empty($model->phone_numbers) ? $model->phone_numbers : [];
                $max = max(count($added_phone_numbers), count($phone_numbers));

                for ($x = 0; $x < $max; $x++) {
                    if (isset($added_phone_numbers[$x]['type'])) {
                        if (!empty($phone_numbers[$x])) {
                            if (($added_phone_numbers[$x]['number'] != $phone_numbers[$x])) {

                                $upd_phone_numbers = SitePhoneNumbers::findOne($added_phone_numbers[$x]['id']);
                                $upd_phone_numbers->number = $phone_numbers[$x];
                                $upd_phone_numbers->number_type = 0;
                                $upd_phone_numbers->save(false);
                            }
                        } else {
                            $del_phone_numbers = SitePhoneNumbers::findOne(["id" => $added_phone_numbers[$x]['id'], "number_type" => 0]);
                            if (!empty($del_phone_numbers)) {
                                $del_phone_numbers->delete();
                            }
                        }
                    } elseif (!empty($phone_numbers[$x])) {
                        $ins_phone_numbers = new SitePhoneNumbers();
                        $ins_phone_numbers->connect_id = $model->id;
                        $ins_phone_numbers->number = $phone_numbers[$x];
                        $ins_phone_numbers->number_type = 0;
                        $ins_phone_numbers->type = self::TYPE;
                        $ins_phone_numbers->save();

                    }

                };

            }


            /** Mobile numbers */
            if (isset($model->mobile_numbers) && !empty($model->mobile_numbers)) {
                $added_mobile_numbers = !empty($model->added_mobile_numbers) ? json_decode(base64_decode($model->added_mobile_numbers), true) : [];
                $mobile_numbers = !empty($model->mobile_numbers) ? $model->mobile_numbers : [];
                $max = max(count($added_mobile_numbers), count($mobile_numbers));
                for ($x = 0; $x < $max; $x++) {
                    if (isset($added_mobile_numbers[$x]['type'])) {
                        if (!empty($mobile_numbers[$x])) {
                            if (($added_mobile_numbers[$x]['number'] != $mobile_numbers[$x])) {

                                $upd_phone_numbers = SitePhoneNumbers::findOne($added_mobile_numbers[$x]['id']);
                                $upd_phone_numbers->number = $mobile_numbers[$x];
                                $upd_phone_numbers->number_type = 1;
                                $upd_phone_numbers->save(false);
                            }
                        } else {
                            $del_phone_numbers = SitePhoneNumbers::findOne(["id" => $added_mobile_numbers[$x]['id'], "number_type" => 1]);
                            if (!empty($del_phone_numbers)) {
                                $del_phone_numbers->delete();
                            }
                        }
                    } elseif (!empty($mobile_numbers[$x])) {
                        $ins_phone_numbers = new SitePhoneNumbers();
                        $ins_phone_numbers->connect_id = $model->id;
                        $ins_phone_numbers->number = $mobile_numbers[$x];
                        $ins_phone_numbers->number_type = 1;
                        $ins_phone_numbers->type = self::TYPE;
                        $ins_phone_numbers->save();

                    }

                };

            }


            /** Main image & Photosession */
            $deletedImages = $model->deletedImages;
            $mainImage = $model->mainImage;

            if (!empty($deletedImages)) {
                if (strpos($deletedImages, ',')) {
                    $deletedImages = explode(',', $deletedImages);
                }

                $delete = SiteGallery::find()->where(['in', 'id', $deletedImages])->andWhere(['connect_id' => $model->id, 'type' => self::TYPE])->all();
                if (!empty($delete)) {
                    foreach ($delete as $key => $val) {
                        $imageUpload = new ImageUpload();
                        $imageUpload->deleteFile([$this->customPath . '/' . $val['photo']]);
                        $imageUpload->deleteFile([$this->customPath . '/small/' . $val['photo']]);
                        if ($val['main'] == 1) {
                            $mainImage = null;
                            $updatePhoto = $this->findModel($model->id);
                            $updatePhoto->photo = '';
                            $updatePhoto->save(false);
                        }
                        $val->delete();
                    }
                }
            }

            $photos = UploadedFile::getInstances($model, 'files');

            if (!empty($photos)) {
                foreach ($photos as $key => $photo) {
                    $imageUpload = new ImageUpload();
                    if (empty($mainImage) && $key == 0) {
                        $uploadedFile = $imageUpload->saveFile($photo, [
                            'path.save' => $this->customPath,
                            'resize.img' => [185, 185],
                            'resize.thumb' => [121, 121]
                        ]);

                        $updatePhoto = $this->findModel($model->id);
                        $updatePhoto->photo = $uploadedFile;
                        $updatePhoto->save(false);

                    } else {
                        $uploadedFile = $imageUpload->saveFile($photo, [
                            'path.save' => $this->customPath,
                            'resize.img' => [708, 420],
                            'resize.thumb' => [185, 110]
                        ]);
                    }

                    if (!empty($uploadedFile)) {
                        $gallery = new SiteGallery();
                        $gallery->photo = $uploadedFile;
                        $gallery->connect_id = $model->id;
                        $gallery->type = self::TYPE;
                        $gallery->main = empty($mainImage) && $key == 0 ? 1 : 0;
                        $gallery->save();
                    }

                }
            }

            if ($model->save()) {
                $user->name = $model->contact_name;
                $user->phone_number = $model->contact_phone;
                $user->email = $model->email;

                if (!empty($model->password) and !empty($model->repassword) and trim($model->password) == trim($model->repassword)) {
                    $user->setPassword($model->password);
                    $user->generateAuthKey();
                }

                $user->save();
                Yii::$app->session->setFlash('success', 'Məlumatlar yeniləndi');
            } else {
                Yii::$app->session->setFlash('error', 'Xəta baş verdi');
            }

            //return $this->redirect(['index']);

        }


        return $this->render('settings', [
            'model' => $model,
            'user' => $user,
            'enterprise_categories' => $enterprise_categories,
            'customPath' => $this->customPath
        ]);
    }


    public function getPages($model = null)
    {

        return $this->pages;
        /*$pages = [];
        $menus = $this->menus;

        // Default settings
        $settings = $menus["id"][4]["settings"];

        $settings = json_decode($settings,true);
        if(isset($settings["profile"])){
            $pages = $settings["profile"];
        }
        return $pages;
        */


    }


    /*Questions*/
    public function actionQuestions()
    {
        $user_id = Yii::$app->user->id;
        $consultation_model = new SiteConsultationModel();
        $doctor = $this->findDoctor($user_id);
        $checkStatus = false;
        $pages = $this->getPages($doctor);
        $page_type = 'questions';
        $count = $consultation_model->getQuestionAnswerCount($doctor->id, $checkStatus);
        $qa = [];
        $pagination = new Pagination(['totalCount' => $count]);
        if ($count > 0) {
            $pagination->defaultPageSize = 6;
            $list = $consultation_model->getQuestionAnswer($doctor->id, $checkStatus, $pagination->limit, $pagination->offset);
            if (!empty($list)) {
                foreach ($list as $value) {
                    $qa[$value['id']]['question_id'] = $value['id'];
                    $qa[$value['id']]['doctor_id'] = $value['doctor_id'];
                    $qa[$value['id']]['doctor'] = $doctor->name;
                    $qa[$value['id']]['user'] = $value['name'];
                    $qa[$value['id']]['user_email'] = $value['email'];
                    $qa[$value['id']]['time'] = $value['q_datetime'];
                    $qa[$value['id']]['time2'] = $value['a_datetime'];
                    $qa[$value['id']]['question'] = $value['question'];
                    $qa[$value['id']]['answer'] = $value['answer'];
                    $qa[$value['id']]['status'] = $value['status'];
                    $qa[$value['id']]['a_status'] = $value['a_status'];
                }
            }
        } else {
            Yii::$app->session->setFlash("error_message", "Sizə ünvanlanmış sual yoxdur");
        }

        return $this->render('answer', [
            'qa' => $qa,
            'doctor_id' => $doctor->id,
            'model' => $doctor,
            'pagination' => $pagination,
            'pages' => $pages,
            'page_type' => $page_type,
        ]);

    }

    public function actionSaveQuestions()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->isPost) {
                $id = intval(Yii::$app->request->post('post_id'));
                if (!empty($id)) {
                    $model = $this->findData($id);
                    $model->answer = Yii::$app->request->post('text');
                    $model->a_datetime = Yii::$app->params['current.date'] . ' ' . Yii::$app->params['current.time'];
                    $model->a_status = 0;
                    if ($model->save()) {
                        $doctor = $this->findDoctor(Yii::$app->user->id);
                        $data = [
                            'issucces' => 'Yes',
                            'doctor_name' => $doctor->name,
                            'question_id' => $id,
                            'date2' => date('m.d.Y - H:i', strtotime(Yii::$app->params['current.date'] . ' ' . Yii::$app->params['current.time'])),
                        ];
                        return $data;
                    } else {
                        $data = ['issucces' => 'No'];
                        return json_encode($data);
                    }
                }
                $data = ['issucces' => 'No'];
                return json_encode($data);
            }
            $data = ['issucces' => 'No'];
            return json_encode($data);
        }
        $data = ['issucces' => 'No'];
        return json_encode($data);
    }

    public function actionUpdateQuestions()
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->isPost) {
                $id = intval(Yii::$app->request->post('post_id'));
                if (!empty($id)) {
                    $model = $this->findData($id);
                    $model->answer = Yii::$app->request->post('text');
                    $model->a_datetime = Yii::$app->params['current.date'] . ' ' . Yii::$app->params['current.time'];
                    if ($model->save()) {
                        return 'Yes';
                    } else {
                        return 'No';
                    }
                }
                return 'No';
            }
            return 'No';
        }
    }

    public function actionDeleteAnswer()
    {
        if (Yii::$app->request->isAjax) {
            $id = intval(Yii::$app->request->post('post_id'));
            if (!empty($id)) {
                $model = $this->findData($id);
                if ($model) {
                    $model->answer = '';
                    $model->a_status = 0;
                    if ($model->save())
                        return 'Yes';
                    else
                        return 'No';
                }
            }
        }
    }

    public function actionDeleteQuestions()
    {
        if (Yii::$app->request->isAjax) {
            $id = intval(Yii::$app->request->post('post_id'));
            if (!empty($id)) {
                $model = $this->findData($id);
                $model->delete();
                return 'Yes';
            } else {
                return 'No';
            }
        }
    }

    protected function findModelConsultation($id)
    {
        if (($model = SiteConsultation::findOne(['doctor_id' => $id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');

    }

    protected function findData($id)
    {
        if (($model = SiteConsultation::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    /*questions*/

    /*Workdays*/
    public function userWorkDays($user_id)
    {
        if ($user_id) {
            $model = new WorkDaysModel();
            $total_count = $model->getUserWorkDaysCount($user_id);
            $pagination = new Pagination(['totalCount' => $total_count]);
            $pagination->defaultPageSize = 6;
            $workdays_list = $model->getUserWorkDays($user_id, $pagination->limit, $pagination->offset);
            $workdays = array();
            if (!empty($workdays_list)) {
                foreach ($workdays_list as $workday) {
                    $workdays[$workday['id']]['id'] = $workday['id'];
                    $workdays[$workday['id']]['connect_id'] = $workday['connect_id'];
                    $workdays[$workday['id']]['time_interval'] = $workday['time_interval'];
                    $workdays[$workday['id']]['date'] = date('d/m/Y', strtotime($workday['date']));
                    $workdays[$workday['id']]['workdays'] = explode(',', $workday['workdays']);
                }
                $data = ['workdays' => $workdays, 'pagination' => $pagination];
                return $data;
            }
            $data = false;
            return $data;
        }
    }

    public function actionWorkdays()
    {
        $error = 0;
        $msg = '';
        $model = new WorkDaysModel();
        $pages = $this->getPages();
        $page_type = 'workdays';
        $doctor = $this->findDoctor(Yii::$app->user->id);
        $user_id = $doctor->id;
        if (Yii::$app->request->isPost) {
            $model->connect_id = $user_id;
            $model->time_interval = intval(Yii::$app->request->post('time_interval'));
            $post_times = Yii::$app->request->post('times');
            $post_date = Yii::$app->request->post('date');
            if (empty($post_date)) {
                if (empty($post_times)) {
                    $error = $error + 1;
                    $msg = 'Tarix və İş saatları seçilmədi!';
                } else {
                    $error = $error + 1;
                    $msg = 'Tarix seçilmədi!';
                }
            } else {

                if (empty($post_times)) {
                    $error = $error + 1;
                    $msg = 'İş saatları seçilmədi!';
                } else {
                    $model->workdays = implode(',', $post_times);
                }
                $model->date = date("Y-m-d", strtotime(Yii::$app->request->post('date')));
            }


            if ($model->hasDate($model->connect_id, $model->date) != 0) {
                $msg = 'Bu tarix üçün məlumat artıq qeyd olunub';
                $error = $error + 1;
            }

            if ($error == 0) {
                if ($model->save()) {
                    Yii::$app->session->setFlash("success", "İş saatları əlavə edildi!");
                } else {
                    Yii::$app->session->setFlash("danger", "İş saatları əlavə edilmədi!");
                }
            } else {
                Yii::$app->session->setFlash("danger", $msg);

            }
        }

        if ($this->userWorkDays($user_id) != false) {
            $workdays = $this->userWorkDays($user_id)['workdays'];
            $pagination = $this->userWorkDays($user_id)['pagination'];
        } else {
            $workdays = null;
            $pagination = new Pagination();
        }

        return $this->render('workdays', [
            'model' => $model,
            'workdays_data' => $workdays,
            'pagination' => $pagination,
            'pages' => $pages,
            'page_type' => $page_type,
            'doctor' => $doctor
        ]);
    }

    public function actionUpdate()
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->isPost) {
                $id = intval(Yii::$app->request->post('post_id'));
                if (!empty($id)) {
                    $model = $this->findModelWorkdays($id);
                    $model->workdays = Yii::$app->request->post('workday');
                    if ($model->save()) {
                        return 'Yes';
                    } else {
                        return 'No';
                    }
                }
                return 'No';
            }
            return 'No';
        }
        return 'No';
    }

    public function actionTimeExpand()
    {
        //echo 'Test1'; exit();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $time = strtotime('07:00');
        $data['time'] = array();
        //echo 'Test1'; exit();
        $request = Yii::$app->request;
        $i = 0;
        $loop = 30;
        if ($request->isAjax) {
            //echo 'Test1'; exit();
            if ($request->isPost) {
                //echo 'Test1'; exit();
                $interval = intval($request->post('get_option'));
                if ($interval == 30) {
                    $loop = 29;
                } else if ($interval == 45) {
                    $loop = 19;
                } else if ($interval == 60) {
                    $loop = 14;
                }
                while ($i < $loop) {
                    if ($interval == 30) {
                        $startTime = date("H:i", strtotime('+30 minutes', $time));
                        $time = strtotime($startTime);
                        $endTime = date("H:i", strtotime('+30 minutes', $time));
                    } else if ($interval == 45) {
                        $startTime = date("H:i", strtotime('+45 minutes', $time));
                        $time = strtotime($startTime);
                        $endTime = date("H:i", strtotime('+45 minutes', $time));
                    } else if ($interval == 60) {
                        $startTime = date("H:i", strtotime('+60 minutes', $time));
                        $time = strtotime($startTime);
                        $endTime = date("H:i", strtotime('+60 minutes', $time));
                    }
                    $data['time'][] = $startTime . '-' . $endTime;
                    $time = strtotime($startTime);
                    $i++;
                }
                return $data;
            }
        }
        //echo 'Test';
    }

    public function actionDeleteWorkDays()
    {
        if (Yii::$app->request->isAjax) {
            $id = intval(Yii::$app->request->post('del_id'));
            if (!empty($id)) {
                $model = $this->findModelWorkdays($id);
                $model->delete();
                return 'Yes';
            }
        }
    }

    protected function findModelWorkdays($id)
    {
        if (($model = WorkDaysModel::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    /*Workdays*/


    /* appoints */

    public function userAppoints($user_id)
    {
        if (!empty($user_id)) {
            $model = new SiteCalling();
            $total_count = $model->getCountReservedTimes($user_id);
            $pagination = new Pagination(['totalCount' => $total_count]);
            $pagination->defaultPageSize = 10;
            $reserved_list = $model->getReservedTimes($user_id, $pagination->limit, $pagination->offset);
            $reserv_times = array();
            if (!empty($reserved_list)) {
                foreach ($reserved_list as $reserv) {
                    $reserv_times[$reserv['id']]['id'] = $reserv['id'];
                    $reserv_times[$reserv['id']]['doctor'] = $reserv['doctor_id'];
                    $reserv_times[$reserv['id']]['fname'] = $reserv['fullname'];
                    $reserv_times[$reserv['id']]['email'] = $reserv['email'];
                    $reserv_times[$reserv['id']]['telefon'] = $reserv['telefon'];
                    $reserv_times[$reserv['id']]['date'] = Functions::getDatetime($reserv['date'], ['type' => 'date']);
                    $reserv_times[$reserv['id']]['time'] = $reserv['time'];
                    $reserv_times[$reserv['id']]['status'] = $reserv['status'];
                }
                $data = ['reserv_times' => $reserv_times, 'pagination' => $pagination];
                return $data;
            }
            $data = false;
            return $data;
        }
    }

    public function countAppoint($user_id)
    {
        $count = SiteCalling::find()->where(['doctor_id' => $user_id, 'status' => 0])->count();
        return $count;
    }

    public function actionAppoint()
    {
        $error = 0;
        $msg = '';
        $model = new SiteCalling();
        $pages = $this->getPages();
        $page_type = 'appoints';
        $user_id = Yii::$app->user->id;
        $doctor = $this->findDoctor($user_id);
        $counter = $this->countAppoint($doctor->id);
        if ($this->userAppoints($doctor->id) != false) {
            $reserv_times = $this->userAppoints($doctor->id)['reserv_times'];
            $pagination = $this->userAppoints($doctor->id)['pagination'];
        } else {
            $reserv_times = null;
            $pagination = new Pagination();
        }

        //print_r($reserv_times); die();
        return $this->render('appoints', [
            'model' => $model,
            'counter' => $counter,
            'reserv_times' => $reserv_times,
            'pagination' => $pagination,
            'pages' => $pages,
            'page_type' => $page_type,
            'doctor' => $doctor
        ]);
    }

    public function actionAcceptAppoint()
    {
        if (Yii::$app->request->isAjax) {
            $id = intval(Yii::$app->request->post('post_id'));
            if (!empty($id)) {
                $model = SiteCalling::findOne($id);
                if ($model) {
                    $number = isset($model->telefon) ? '+994' . ltrim(trim($model->telefon), '0') : null;
                    $model->status = 1;
                    $msg = "Sizin " . $model->date . " tarixinde " . $model->time . " radesi ucun randevunuz təsdiq edildi!";
                    //$emailBody = str_replace("\n","<br>",$msg);
                    /** Send sms */
                    $smsBody = urlencode($msg);
                    $sendSms = "gw.maradit.net/api/xml/reply/submit?Credential={Username:appa1,Password:347h3i4}&Header={From:E-tibb.az}&Message={$smsBody}&To=[{$number}]&DataCoding=Default";
                    if ($model->save()) {
                        $sendSms = Functions::SendRequestWithCurl($sendSms);
                        return 'Yes';
                    } else
                        return 'No';
                } else
                    return 'No';
            } else
                return 'No';
        } else
            return 'No';
    }

    public function actionDeleteAppoint()
    {
        if (Yii::$app->request->isAjax) {
            $id = intval(Yii::$app->request->post('del_id'));
            if (!empty($id)) {
                $model = SiteCalling::findOne($id);
                if ($model) {
                    $number = isset($model->telefon) ? '+994' . ltrim(trim($model->telefon), '0') : null;
                    $msg = "Sizin " . $model->date . " tarixinde " . $model->time . " radesi ucun randevunuz təsdiq edilmədi!";
                    //$emailBody = str_replace("\n","<br>",$msg);
                    /** Send sms */
                    $smsBody = urlencode($msg);
                    $sendSms = "gw.maradit.net/api/xml/reply/submit?Credential={Username:appa1,Password:347h3i4}&Header={From:E-tibb.az}&Message={$smsBody}&To=[{$number}]&DataCoding=Default";
                    if ($model->status == 0) {
                        $sendSms = Functions::SendRequestWithCurl($sendSms);
                        $model->delete();
                        return 'Yes';
                    } else {
                        $model->delete();
                        return 'Yes';
                    }
                } else
                    return 'No';
            } else
                return 'No';
        } else
            return 'No';
    }


    protected function findDoctor($id)
    {
        if (!$this->typeModel) {
            if (($model = Doctor::findOne(['user_id' => $id])) !== null) {
                return $model;
            }
            throw new NotFoundHttpException('The requested page does not exist.');
        } else {
            return $this->typeModel;
        }

    }

    protected function findSettingModel($id)
    {
        if (($model = SiteDoctors::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}