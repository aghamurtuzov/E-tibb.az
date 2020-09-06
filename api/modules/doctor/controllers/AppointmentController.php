<?php

namespace api\modules\doctor\controllers;

use api\components\Functions;
use api\models\AdminDoctorLog;
use api\models\SitePhoneNumbersModel;
use api\modules\doctor\models\search\AppointmentSearch;
use yii;
use api\models\SiteAppoint;
use api\models\AppointmentWorkdaysSettings;
use api\components\Pagination;
use api\models\AppointmentApiModel;
use api\modules\doctor\controllers\MainController;

/**
 * Appointment API
 */
class AppointmentController extends MainController
{

    public $modelClass = '';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * https://e-tibb.az/api/doctor/appointment/work-time-block
     * date=2019-11-04
     * time=12:00-12:30
     */
    public function actionWorkTimeBlock()
    {
        $request = Yii::$app->request;
        $date = $request->post('date');
        $time = $request->post('time');
        $doctorID = Yii::$app->session->get('userID');
        $add = new SiteAppoint();
        $add->date = $date;
        $add->time = $time;
        $add->doctor_id = $doctorID;
        $add->user_id = Yii::$app->user->identity->id;
        $add->status = 1;
        if ($add->save()) {
            return $this->response(200, "Seçilmiş tarix bloklandı");
        }
        return $this->response(200, "Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * https://e-tibb.az/api/doctor/appointment/work-time-active
     * date=2019-11-04
     * time=12:00-12:30
     */
    public function actionWorkTimeActive()
    {
        $request = Yii::$app->request;
        $date = $request->post('date');
        $time = $request->post('time');
        $doctorID = Yii::$app->session->get('userID');

        $delete = SiteAppoint::find()->where(['doctor_id' => $doctorID, 'date' => $date, 'time' => $time])->one();
        if (!empty($delete)) {
            if ($delete->delete()) {
                return $this->response(200, "Seçilmiş tarix aktiv edildi");
            }
        }
        return $this->response(200, "Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * https://e-tibb.az/api/doctor/appointment/work-day
     */
    public function actionWorkDay()
    {
        $app = [];
        $data = [];
        $request = Yii::$app->request;
        $doctorID = Yii::$app->session->get('userID');
        $day = $request->get('day');
        $weekDay = date('N', strtotime($day));

        if (empty($day)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        $DayOfSetting = AppointmentWorkdaysSettings::find()->where(['doctor_id' => $doctorID, 'day' => $weekDay])->one();

        if (!empty($DayOfSetting)) {
            $setting = json_decode($DayOfSetting['setting'], true);
            $l = $this->WorkTime($setting['StartTime'], $setting['EndTime'], $setting['Interval'], $setting['breakTimeStart'], $setting['breakTimeEnd']);
        }

        $appoints = AppointmentApiModel::Appoint($doctorID, $day);

        if (!empty($appoints)) {
            foreach ($appoints as $key => $val) {
                $app[$val['time']] = $val;
            }
        }

        if (isset($l)) {
            foreach ($l['list'] as $key => $val) {
                if (isset($app[$val])) {
                    if ($app[$val]['user_id'] != Yii::$app->user->identity->id) {
                        $data['list'][$key]['user'] = $app[$val]['user_name'];
                    } else {
                        $data['list'][$key]['deleted'] = 1;
                    }
                } else {
                    $data['list'][$key]['deleted'] = 0;
                }
                $data['list'][$key]['time'] = $val;
            }
        }

        if (empty($data)) {
            return $this->response(200, "Heçbir məlumat tapılmadı");
        }

        return $this->response(200, "Məlumat mövcuddur", $data);
    }

    /**
     * https://e-tibb.az/api/doctor/appointment/time-interval-list
     */
    public function actionTimeIntervalList()
    {
        $result = [
            [
                'interval' => 30,
                'name' => '30 dəqiqə',
            ],
            [
                'interval' => 45,
                'name' => '45 dəqiqə',
            ],
            [
                'interval' => 60,
                'name' => '1 saat',
            ]
        ];
        return $this->response(200, "Məlumat mövcuddur", $result);
    }

    /**
     * https://e-tibb.az/api/doctor/appointment/block
     * id=18
     */
    public function actionBlock()
    {
        $id = intval(Yii::$app->request->post('id'));
        $doctorID = Yii::$app->session->get('userID');

        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }
        $phone = SitePhoneNumbersModel::getPhones($doctorID, 1, 1);
        $status = AppointmentApiModel::Status($id, 1);
        if ($status) {

            if (isset($phone)) {
                /** Send Sms */
                $message = "Hörmətli istifadəçi, təəssüf ki, təyin olunan vaxtda həkimlə görüşünüz mümkün deyil. Yenidən qəbula yazılın.";
                Functions::SendSms($phone[0]['number'], $message);

                $message = "Hörmətli istifadəçi, təəssüf ki, təyin olunan vaxtda həkimlə görüşünüz mümkün deyil. Yenidən qəbula yazılın.";
                Functions::SendSms('994552884261', $message);

            }
        }

        $update = AppointmentApiModel::ChangeStatus($id, AppointmentApiModel::STATUS_DEACTIVE, $doctorID);

        if ($update) {
            $data = AppointmentApiModel::appointFind($id);
            AdminDoctorLog::write(['id' => $id, 'name' => $data['fullname']]);
            return $this->response(200, "Melumat bloklandı");
        }


        return $this->response(400, "Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * https://e-tibb.az/api/doctor/appointment/active
     * id=18
     */
    public function actionActive()
    {
        $id = intval(Yii::$app->request->post('id'));
        $doctorID = Yii::$app->session->get('userID');


        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        $update = AppointmentApiModel::ChangeStatus($id, AppointmentApiModel::STATUS_ACTIVE, $doctorID);

        $phone = SitePhoneNumbersModel::getPhones($doctorID, 1, 1);

        $appointData = AppointmentApiModel::getTimeStatus($id, 1);


        $doctorName = AppointmentApiModel::getDoctorName($doctorID);

        if ($update) {

            $timeStatus = AppointmentApiModel::TimeStatusDeaktiv($doctorID,$appointData[0]["date"],$appointData[0]["time"],0,$id);

            if ($timeStatus) {

                if (isset($phone)) {
                    /** Send Sms */
                    $message = "Hörmətli istifadəçi, təəssüf ki, təyin olunan vaxtda həkimlə görüşünüz mümkün deyil. Yenidən qəbula yazılın.";
                    Functions::SendSms($phone[0]['number'], $message);

                    $message = "Hörmətli istifadəçi, təəssüf ki, təyin olunan vaxtda həkimlə görüşünüz mümkün deyil. Yenidən qəbula yazılın.";
                    Functions::SendSms('994552884261', $message);
                }
            }

            if (isset($phone)) {
                /** Send Sms */
                $message = "Hörmətli istifadəçi, ".$appointData[0]["fullname"]."\nHəkim randevunuzu təsdiqlədi\nHəkim: ".$doctorName[0]["name"]."\nTarix: ".$appointData[0]["date"]."\nVaxt: ".$appointData[0]["time"]."\nQeyd olunan vaxtda qəbulda olmanızı xahiş edirik.";
                Functions::SendSms($phone[0]['number'], $message);

                $message = "Hörmətli istifadəçi, ".$appointData[0]["fullname"]."\nHəkim randevunuzu təsdiqlədi\nHəkim: ".$doctorName[0]["name"]."\nTarix: ".$appointData[0]["date"]."\nVaxt: ".$appointData[0]["time"]."\nQeyd olunan vaxtda qəbulda olmanızı xahiş edirik.";
                Functions::SendSms('994552884261', $message);
            }

            $data = AppointmentApiModel::appointFind($id);
            AdminDoctorLog::write(['id' => $id, 'name' => $data['fullname']]);
            return $this->response(200, "Melumat aktiv edildi");
        }

        return $this->response(400, "Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * https://e-tibb.az/api/doctor/appointment/work-time-info
     * weekday=monday
     */
    public function actionWorkTimeInfo()
    {
        $request = Yii::$app->request;
        $doctorID = Yii::$app->session->get('userID');
        $weekday = $request->get('weekday');

        if (empty($weekday)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        $setting = AppointmentWorkdaysSettings::find()->where(['doctor_id' => $doctorID, 'day' => $weekday])->one();
        if (!empty($setting)) {
            $stg = json_decode($setting['setting'], true);
            $data['id'] = $setting['id'];
            $data['doctor_id'] = $setting['doctor_id'];
            $data['day'] = $setting['day'];
            $data['StartTime'] = $stg['StartTime'];
            $data['EndTime'] = $stg['EndTime'];
            $data['Interval'] = $stg['Interval'];
            $data['breakTimeStart'] = $stg['breakTimeStart'];
            $data['breakTimeEnd'] = $stg['breakTimeEnd'];
            return $this->response(200, "Məlumat mövcuddur", $data);
        } else {
            return $this->response(200, "Heçbir məlumat tapılmadı");
        }

    }

    /**
     * https://e-tibb.az/api/doctor/appointment/work-time-setting
     * weekday=monday
     * start-time=09:00
     * end-time=19:00
     * interval=60
     * break-time-start=16:00
     * break-time-end=18:00
     */
    public function actionWorkTimeSetting()
    {

        Yii::$app->db->schema->refresh();

        $request = Yii::$app->request;
        $doctorID = Yii::$app->session->get('userID');
        $weekday = intval($request->post('weekday'));
        $model = new AppointmentWorkdaysSettings();

        if (empty($weekday)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

//        $data['StartTime'] = $request->post('StartTime');
//        $data['EndTime'] = $request->post('EndTime');
//        $data['breakTimeStart'] = $request->post('breakTimeStart');
//        $data['Interval'] = $request->post('Interval');
//        $data['breakTimeEnd'] = $request->post('breakTimeEnd');


        $model->StartTime = $request->post('StartTime');
        $model->EndTime = $request->post('EndTime');
        $model->Interval = $request->post('Interval');
        $model->breakTimeStart = $request->post('breakTimeStart');
        $model->breakTimeEnd = $request->post('breakTimeEnd');

//
        $data['StartTime'] = $model->StartTime;
        $data['EndTime'] = $model->EndTime;
        $data['Interval'] = $model->Interval;
        $data['breakTimeStart'] = $model->breakTimeStart;
        $data['breakTimeEnd'] = $model->breakTimeEnd;


        if ($model->validate()){
            $data = json_encode($data);
            $setting = AppointmentWorkdaysSettings::find()->where(['doctor_id' => $doctorID, 'day' => $weekday])->one();

            if (!empty($setting)) {
                $setting->setting = $data;
                $setting->save();
                return $this->response(200, "Məlumat yeniləndi");
            } else {
                $s = new AppointmentWorkdaysSettings();
                $s->doctor_id = $doctorID;
                $s->day = $weekday;
                $s->setting = $data;
                if ($s->save()) {
                    return $this->response(200, "Məlumat əlavə olundu");
                }
            }
        }


        if (!empty($model->errors)) {
            return $this->response(400, 'Məlumatın əlavə olunması zamanı xəta baş verdi', $model->errors);
        }


//        $setting = AppointmentWorkdaysSettings::find()->where(['doctor_id' => $doctorID, 'day' => $weekday])->one();

//        if (!empty($setting)) {
//            $setting->setting = $data;
//            $setting->save();
//            return $this->response(200, "Məlumat yeniləndi");
//        } else {
//            $s = new AppointmentWorkdaysSettings();
//            $s->doctor_id = $doctorID;
//            $s->day = $weekday;
//            $s->setting = $data;
//            if ($s->save()) {
//                return $this->response(200, "Məlumat əlavə olundu");
//            }
//        }

    }

    /**
     * start-time=09:00
     * end-time=19:00
     * interval=60
     * break-time-start=16:00
     * break-time-end=18:00
     */
    public function WorkTime($startTime, $endTime, $interval, $breakTimeStart, $breakTimeEnd)
    {
        $data = [];
        $loop = 0;
        $startTime = $startTime;
        $endTime = $endTime;
        $interval = $interval;
        $interval = !empty($interval) ? intval($interval) : 30;
        $breakTimeStart = $breakTimeStart;
        $breakTimeEnd = $breakTimeEnd;

        /** Loop teyin etme */
        if (strpos($startTime, ':')) {
            $exp_start = explode(':', $startTime);
        }
        if (strpos($endTime, ':')) {
            $exp_end = explode(':', $endTime);
        }

        if (isset($exp_start[1]) && isset($exp_end[1])) {
            $minute = (intval($exp_end[0]) - intval($exp_start[0])) * 60;

            $a = intval($exp_start[1]);
            $b = intval($exp_end[1]);

            if ($a > $b) {
                $d = $b == 0 ? $minute - $a : $minute - ($a - $b);
            } else {
                $d = $a == 0 ? $minute + $b : $minute + ($b - $a);
            }

            $loop = intval($d / $interval);

        }

        /** Fasile loop teyin etme */
        if (strpos($breakTimeStart, ':')) {
            $exp_breakStart = explode(':', $breakTimeStart);
        }
        if (strpos($breakTimeEnd, ':')) {
            $exp_breakEnd = explode(':', $breakTimeEnd);
        }

        if (isset($exp_breakStart[1]) && isset($exp_breakEnd[1])) {
            $minute = (intval($exp_breakEnd[0]) - intval($exp_breakStart[0])) * 60;

            $i = intval($exp_breakStart[1]);
            $f = intval($exp_breakEnd[1]);

            if ($i > $f) {
                $g = $f == 0 ? $minute - $i : $minute - ($i - $f);
            } else {
                $g = $i == 0 ? $minute + $f : $minute + ($f - $i);
            }

            //$breakTimeLoop = intval($g/$interval);
            $breakTimeLoop = ceil($g / $interval);

        }

        /** Vaxt siyahisi */
        $startTime_str = strtotime($startTime);

        $bp = 0;
        $limit = 0;
        for ($x = 1; $x < $loop; $x++) {
            if ($x == 1) {
                $t2 = date("H:i", strtotime('+' . $interval . ' minutes', $startTime_str));
                $data['list'][] = $startTime . '-' . $t2;
            }

            $t1 = date("H:i", strtotime('+' . $interval . ' minutes', $startTime_str));
            $t2 = date("H:i", strtotime('+' . $interval . ' minutes', strtotime($t1)));

            if (strpos($t1, ':')) {
                $exp_t1 = explode(':', $t1);
            }

            if (isset($exp_breakStart)) {
                if ($exp_t1[0] == $exp_breakStart[0] && $exp_t1[1] >= $exp_breakStart[1] && $bp == 0) {
                    $bp = 1;
                    $limit = $x + $breakTimeLoop;
                    $startTime_str = strtotime($t1);
                    continue;
                }

                if ($bp == 1 && $x < $limit) {
                    $startTime_str = strtotime($t1);
                    continue;
                }
            }

            $data['list'][] = $t1 . '-' . $t2;

            $startTime_str = strtotime($t1);

        }
        return $data;
    }


    /**
     * Randevular
     * https://e-tibb.az/api/doctor/appointment
     */
    public function actionIndex()
    {
        $doctorID = Yii::$app->session->get('userID');
        $model = new AppointmentApiModel();
        $status = AppointmentApiModel::getStatus();

        $totalCount = $model->AppointmentsCount($doctorID);

        if ($totalCount['count'] <= 0) {
            return $this->response(200, "Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $model->Appointments($limits, $doctorID);

        if (!empty($list)) {
            foreach ($list as $key => $val) {
                $list[$key]['status_name'] = $status[$list[$key]['status']];
                $list[$key]['date'] = Yii::$app->formatter->asTime($list[$key]['date'],'php: d M Y');
            }
        }

        $data['list'] = $list;

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages, $data);

        return $this->response(200, "Məlumat mövcuddur", $result);
    }

    /**
     * Randevular search
     * https://e-tibb.az/api/doctor/appointment/search
     * https://e-tibb.az/api/doctor/appointment/search?page=1&count=5
     * fullname = Eli Eliyev
     * email = ali@mail.ru
     * phone = 05543878880
     * date_start = 2020-01-01
     * date_end = 2020-01-01
     * status=1
     */
    public function actionSearch()
    {
        Yii::$app->db->schema->refresh();
        $model = new AppointmentSearch();
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
}
