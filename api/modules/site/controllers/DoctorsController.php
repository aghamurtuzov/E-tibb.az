<?php

namespace api\modules\site\controllers;

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
use api\modules\site\models\DoctorsApiModel;
use frontend\components\Specialist;
use yii\helpers\ArrayHelper;


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
     * Hekimler siyahi limit
     * https://e-tibb.az/api/front/doctors/limited
     * vip=1
     * categoryID=1
     * limit=10
     */
    public function actionLimited()
    {
        $categoryID = intval(Yii::$app->request->get('categoryID'));
        $link = intval(Yii::$app->request->get('limit'));
        $vip = intval(Yii::$app->request->get('vip'));

        $list = DoctorsApiModel::LimitedDoctor($link,$categoryID,$vip);
        if(!empty($list))
        {
            $link = null;
            $spc = ArrayHelper::toArray(new Specialist());
            foreach($list as $key => $val)
            {

                $list[$key]['rating'] = intval($list[$key]['rating']);

                /** Hekim | Mutexessis */
                $spc_list = [];
                if(isset($spc['specialists']['id'][$val['specialist_id']]))
                {
                    $spc_list[] = $spc['specialists']['id'][$val['specialist_id']];
                    $link = Functions::getDoctorLink($spc_list,$val['id'],$val['slug']);
                }

                $list[$key]['specialist'] = $spc_list;

                /** Hekim | Share link */
                $list[$key]['link'] = Functions::getSiteUrl().'/'.$link;

                /** Hekim | Tecrube */
                $exp = date('Y')-intval($list[$key]['experience1']);
                $list[$key]['experience'] = $exp > 0 ? $exp : 0;

                unset($list[$key]['experience1']);

                /** Hekim | Eve caqiris , Usaq hekimi */
                $list[$key]['home_doctor'] = 0;
                $list[$key]['child_doctor'] = 0;
                $feature = $list[$key]['feature'];
                if($feature == 1){ $data['home_doctor'] = 1; }
                if($feature == 2){ $data['child_doctor'] = 1; }
                if($feature == 3)
                {
                    $data['home_doctor'] = 1;
                    $data['child_doctor'] = 1;
                }
            }
        }

        $data['list'] = $this->ResultList($list,$this->customPath);

        return $this->response(200,"Məlumat mövcuddur",$data);
    }

    /**
     * Hekimler siyahi
     * https://e-tibb.az/api/front/doctors
     * page=1
     * count=5
     * search=test
     * categoryID=10
     */
    public function actionIndex()
    {

        $categoryID = !empty(Yii::$app->request->get('categoryID')) ? intval(Yii::$app->request->get('categoryID')) : null;

        $search = null;
        if(isset($_GET['search']) && !empty($_GET['search']))
        {
            if(strlen($_GET['search'])>=3)
                $search = strip_tags(Yii::$app->request->get('search'));
            else
                return $this->response(200,"Axtarış üçün minimum 3 simvol daxil edin.");
        }

        $totalCount = DoctorsApiModel::DoctorsCount($search,$categoryID);

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

//        $status = SiteDoctors::get_Status();

        $list = $totalCount = DoctorsApiModel::Doctors($limits,$search,$categoryID);

        if(!empty($list))
        {
            $link = null;
            $spc = ArrayHelper::toArray(new Specialist());
            foreach($list as $key => $val)
            {

                $list[$key]['rating'] = intval($list[$key]['rating']);

                /** Hekim | Mutexessis */
                $spc_list = [];
                if(isset($spc['specialists']['id'][$val['specialist_id']]))
                {
                    $spc_list[] = $spc['specialists']['id'][$val['specialist_id']];
                    $link = Functions::getDoctorLink($spc_list,$val['id'],$val['slug']);
                }

                $list[$key]['specialist'] = $spc_list;

                /** Hekim | Share link */
                $list[$key]['link'] = Functions::getSiteUrl().'/'.$link;

                /** Hekim | Tecrube */
                $exp = date('Y')-intval($list[$key]['experience1']);
                $list[$key]['experience'] = $exp > 0 ? $exp : 0;

                unset($list[$key]['experience1']);

                /** Hekim | Eve caqiris , Usaq hekimi */
                $list[$key]['home_doctor'] = 0;
                $list[$key]['child_doctor'] = 0;
                $feature = $list[$key]['feature'];
                if($feature == 1){ $data['home_doctor'] = 1; }
                if($feature == 2){ $data['child_doctor'] = 1; }
                if($feature == 3)
                {
                    $data['home_doctor'] = 1;
                    $data['child_doctor'] = 1;
                }
            }
        }

        $data['list'] = $this->ResultList($list,$this->customPath);

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages,$data);

        return $this->response(200,"Məlumat mövcuddur",$result);

    }

    /**
     * Hekim info
     * https://e-tibb.az/api/front/doctors/info/245
     */
    public function actionInfo()
    {

        $id = intval(Yii::$app->request->get('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        /** Hekim info */
        $doctor = SiteDoctorsModel::getDoctorInfo($id,'id,user_id,name,slug,email,photo,vip,promotion,experience1,about,feature,rating');
        if(!empty($doctor))
        {
            $data['doctor'] = $this->ResultList($doctor);
        }else{
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $user = SiteUsers::find()->where(['id'=>$data['doctor']['user_id']])->one();

        $data['doctor']['rating'] = intval($data['doctor']['rating']);
        $data['doctor']['home_doctor'] = 0;
        $data['doctor']['child_doctor'] = 0;
        $data['doctor']['experience'] = intval(date('Y'))-intval($data['doctor']['experience1']) > 0 && !empty($data['doctor']['experience1']) ? intval(date('Y'))-intval($data['doctor']['experience1']) : '';

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
        $data['specialist'] = SiteDoctorsModel::getDoctorSpecialist($id);

        /** Hekim | İs yerleri */
        $data['workplaces'] = SiteDoctorsModel::getWorkplaces($id);

        /** Hekim | Telefon nomreleri */
        $numberTypes = Yii::$app->params['allow.number_type'];
        $phones = SitePhoneNumbersModel::getPhones($doctor["id"],self::TYPE);
        if(!empty($phones))
        {
            foreach($phones as $key => $val)
            {
                $val['typeName'] = isset($numberTypes[$val['type']]) ? $numberTypes[$val['type']] : '';
                $data['phones'] = $val;
            }
        }

        /** Hekim | Sosial linkler */
        $sosialLinkType = Yii::$app->params['allow.sosial_icons'];
        $sosialLinks = SiteSocialLinksModel::getSocialLinks($doctor["id"],self::TYPE);
        if(!empty($sosialLinks))
        {
            foreach($sosialLinks as $key => $val)
            {
                $val['typeName'] = isset($sosialLinkType[$val['type']]) ? $sosialLinkType[$val['type']] : '';
                $data['social_links'] = $val;
            }
        }

        /** Hekim | Diplom */
        $diploms = SiteDoctorFilesModel::getFiles($id,1);
        $data['diploms'] = !empty($diploms) ? $this->ResultList($diploms) : [];

        /** Hekim | Sertifikat */
        $certificate = SiteDoctorFilesModel::getFiles($id,2);
        $data['certificate'] = !empty($certificate) ? $this->ResultList($certificate) : [];

        return $this->response(200,"Məlumat mövcuddur",$data);
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
     * https://e-tibb.az/api/front/doctors/list-status
    */
    public function actionListStatus()
    {
        $result = SiteDoctors::getStatus();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Hekim cins
     * https://e-tibb.az/api/front/doctors/list-gender
     */
    public function actionListGender()
    {
        $result = SiteDoctors::getSex();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Akademik derece
     * https://e-tibb.az/api/front/doctors/list-academic-degree
     */
    public function actionListAcademicDegree()
    {
        $result = SiteDoctors::getDegree();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Mutexessisler
     * https://e-tibb.az/api/front/doctors/specialists
     */
    public function actionSpecialists()
    {
        $result = SiteSpecialists::find()->orderBy('name')->all();
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Sosial link tipleri
     * https://e-tibb.az/api/front/doctors/list-sosial-link
     */
    public function actionListSosialLink()
    {
        $result = Yii::$app->params['allow.sosial_icons'];
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Nomre tipleri
     * https://e-tibb.az/api/front/doctors/list-phone-number
     */
    public function actionListPhoneNumber()
    {
        $result = Yii::$app->params['allow.number_type'];
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Hekim odenisler
     * https://e-tibb.az/api/front/doctors/payments/130
     */
    public function actionPayments()
    {
        $id = intval(Yii::$app->request->get('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $model = new AccountingApiModel();

        $totalCount = $model->PaymentsCount($id);

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $model->Payments($id,$limits);

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
                        $list[$key]['service_name'] = isset($packages[$val['payment_info']]['name']) ? $packages[$val['payment_info']]['name'] : null;
                    }else{
                        $list[$key]['service_name'] = isset($services[$val['payment_info']]['name']) ? $services[$val['payment_info']]['name'] : null;
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
     * https://e-tibb.az/api/front/doctors/block
     *
     */
    public function actionBlock()
    {
        $id = intval(Yii::$app->request->post('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $update = DoctorsApiModel::Block($id);
        if($update)
        {
            return $this->response(200,"Həkimin hesabı donduruldu");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Hekim hesab aktiv etmek
     * https://e-tibb.az/api/front/doctors/active
     */
    public function actionActive()
    {
        $id = intval(Yii::$app->request->post('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $update = DoctorsApiModel::Active($id);
        if($update)
        {
            return $this->response(200,"Həkimin hesabı aktiv edildi");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    protected function findModel($id)
    {
        if(($model = SiteDoctors::findOne($id)) !== null) {
            return $model;
        }
    }

}
