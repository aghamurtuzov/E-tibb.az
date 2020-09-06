<?php

namespace api\modules\general\controllers;

use api\models\AdminLog;
use yii;
use api\components\Functions;
use api\models\Consultation;
use api\models\SitePhoneNumbersModel;
use api\modules\general\models\search\ConsultationSearch;
use frontend\models\PasswordResetRequestForm;
use api\components\Pagination;
use api\modules\general\models\ConsultationApiModel;
use api\modules\general\controllers\MainController;
use yii\helpers\Url;


/**
 * Consultation API
 */
class ConsultationController extends MainController
{
    const TYPE = 1;
    public $modelClass = '';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Suallar
     * https://e-tibb.az/api/v1/consultation
     * https://e-tibb.az/api/general/consultation?page=1&count=5
     */
    public function actionIndex()
    {
        $model = new ConsultationApiModel();

        $type = (Yii::$app->request->get('type') == null) ? 'all' : Yii::$app->request->get('type');


        $totalCount = $model->QuestionsCount($type);

        if ($totalCount['count'] <= 0) {
            return $this->response(200, "Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $model->Questions($limits, $type);

        if (!empty($list)) {
            foreach ($list as $key => $val) {
                $list[$key]['q_datetime'] = Yii::$app->formatter->asTime($list[$key]['q_datetime'], 'php:d/m/Y H:i');
                $list[$key]['a_datetime'] = Yii::$app->formatter->asTime($list[$key]['a_datetime'], 'php:d/m/Y H:i');
            }
        }

        $data['list'] = $list;

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages, $data);

        return $this->response(200, "Məlumat mövcuddur", $result);

    }

    /**
     * Suallar search
     * https://e-tibb.az/api/general/consultation/search
     * https://e-tibb.az/api/general/consultation/search?page=1&count=5
     * status=1
     * doctor_name=agha
     * q_datetime=01.01.2020
     */

    public function actionSearch()
    {
        Yii::$app->db->schema->refresh();
        $model = new ConsultationSearch();
        $search = Yii::$app->request->get();

        if ($model->load($search, '')) {

            $model->validate();
            if (!empty($model->errors)) {
                return $this->response(400, 'Məlumatın axtarılması zamanı xəta baş verdi', $model->errors);
            }

            $totalCount = $model->searchCount($search);

            if ($totalCount['count'] <= 0) {
                return $this->response(400, "Heçbir məlumat tapılmadı");
            }

            $pagination = new Pagination(['totalCount' => $totalCount]);

            $limits = $pagination->getLimits();

            $result = $model->search($search, $limits);

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['q_datetime'] = Yii::$app->formatter->asTime($result[$key]['q_datetime'], 'php:d/m/Y H:i');
                    $result[$key]['a_datetime'] = Yii::$app->formatter->asTime($result[$key]['a_datetime'], 'php:d/m/Y H:i');
                }
            }

            $data['list'] = $result;

            $pages = $pagination->getPaginationInfo();

            $result = array_merge($pages, $data);

            return $this->response(200, "Məlumat mövcuddur", $result);
        }
    }

    /**
     * Sual
     * https://e-tibb.az/api/general/consultation/info/123
     */
    public function actionInfo()
    {
        $id = intval(Yii::$app->request->get('id'));
        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        $question = ConsultationApiModel::Question($id);
        if (empty($question)) {
            return $this->response(200, "Heçbir məlumat tapılmadı");
        }

        return $this->response(200, "Məlumat mövcuddur", $question);

    }

    /**
     * Sual block
     * https://e-tibb.az/api/general/consultation/block
     */
    public function actionBlock()
    {
        $id = intval(Yii::$app->request->post('id'));
        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        $update = ConsultationApiModel::ChangeStatus($id, ConsultationApiModel::STATUS_DEACTIVE);
        if ($update) {
            return $this->response(200, "Melumat bloklandı");
        }

        return $this->response(400, "Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Cavab ver
     * https://e-tibb.az/api/general/consultation/answer
     */
    public function actionAnswer()
    {
        $id = intval(Yii::$app->request->post('id'));
        $answer = Yii::$app->request->post('answer');
        $answer = !empty($answer) ? trim($answer) : null;
        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        if (!empty($answer)) {
            $update = ConsultationApiModel::Answer($id, $answer);
            if ($update) {
                return $this->response(200, "Əməliyyatın uğurla icra olundu");
            }
        }

        return $this->response(400, "Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

//    public function actionSend()
//    {
//        exit();
//        phpinfo();
//        $email = 'javanshir.abishov@gmail.com';
//        Yii::$app->mailer->compose()
//            ->setFrom('no-reply@e-tibb.az')
//            ->setTo($email)
//            ->setSubject('Email sent from Yii2-Swiftmailer')
//            ->send();
//
//        exit();
//        echo '<pre>';
//        print_r(Yii::$app
//            ->mailer);
//        echo '</pre>';
//        exit();
//        return Yii::$app
//            ->mailer
//            ->compose()
//            ->setFrom(['no-reply@e-tibb.az' => 'E-tibb.az robot'])
//            ->setTo($email)
//            ->setSubject('Test')
//            ->send();
//    }


    /**
     * Cavab block
     * https://e-tibb.az/api/general/consultation/block-answer
     */
    public function actionBlockAnswer()
    {
        $id = intval(Yii::$app->request->post('id'));
        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        $update = ConsultationApiModel::ChangeStatus($id, ConsultationApiModel::STATUS_DEACTIVE, 'answer');
        if ($update) {
            return $this->response(200, "Melumat bloklandı");
        }

        return $this->response(400, "Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Sual aktiv
     * https://e-tibb.az/api/general/consultation/active
     */
    public function actionActive()
    {


        $id = (int)Yii::$app->request->post('id');
        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        $question = ConsultationApiModel::Question($id);

        if ($question) {
            $phone = SitePhoneNumbersModel::getPhones($question['doctor_id'], 1, 1);

            $update = ConsultationApiModel::ChangeStatus($id, ConsultationApiModel::STATUS_ACTIVE);
            if ($update) {
                if (!empty($phone[0]['number']) && $phone[0]['type'] == 1) {
                    /** Send Sms */
                    $message = "Hörmətli Dr. " . $question['doctor_name'] . " E-tibb.az saytinda size sual var.Zehmet olmasa, cavablandirin.Hormetle, e-tibb.az komandası";
                    Functions::SendSms($phone[0]['number'], $message);

                    $message = "Hörmətli Dr. " . $question['doctor_name'] . " E-tibb.az saytinda size sual var.Zehmet olmasa, cavablandirin.Hormetle, e-tibb.az komandası " . $phone[0]['number'];
                    Functions::SendSms('994552884261', $message);

                    $baseUrl = Url::to('https://e-tibb.az/parol-yenile');
                    $message = "Hormetli Dr " . $question['doctor_name'] . " Şifrənizi unutmusunuzsa aşağıdakı linkə daxil olub, mobil nömrənizi qeyd edərək şifrənizi yeniləyə bilərsiniz\n\n" . $baseUrl . "\n\nHörmətlə: E-tibb komandası\nƏlavə məlumat üçün: 994775643888";
                    Functions::SendSms($phone[0]['number'], $message);
                    Functions::SendSms('994552884261', $message);


                    return $this->response(200, "Melumat Aktiv edildi");


                }

            }

        }


        return $this->response(400, "Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Cavab aktiv
     * https://e-tibb.az/api/general/consultation/active-answer
     */
    public function actionActiveAnswer()
    {
        $id = intval(Yii::$app->request->post('id'));
        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        $update = ConsultationApiModel::ChangeStatus($id, ConsultationApiModel::STATUS_ACTIVE, 'answer');
        if ($update) {
            return $this->response(200, "Melumat aktiv edildi");
        }

        return $this->response(400, "Əməliyyatın icra olunması zamanı xəta baş verdi");
    }


    /**
     * Cavab delete
     * https://e-tibb.az/api/general/consultation/delete-answer
     */
    public function actionDeleteAnswer()
    {
        $id = intval(Yii::$app->request->post('id'));
        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        $update = ConsultationApiModel::DeleteAnswer($id, ConsultationApiModel::STATUS_DEACTIVE);
        if ($update) {
            return $this->response(200, "Melumat silindi");
        }

        return $this->response(400, "Əməliyyatın icra olunması zamanı xəta baş verdi");
    }


    /**
     * Sual sil
     * https://e-tibb.az/api/general/consultation/del
     */
    public function actionDel()
    {
        $id = intval(Yii::$app->request->post('id'));
        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        $update = ConsultationApiModel::ChangeStatus($id, ConsultationApiModel::STATUS_DELETED);
        if ($update) {
            return $this->response(200, "Melumat silindi");
        }

        return $this->response(400, "Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Cavab sil
     * https://e-tibb.az/api/general/consultation/del-answer
     */
    public function actionDelAnswer()
    {
        $id = intval(Yii::$app->request->post('id'));
        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }

        $update = ConsultationApiModel::ChangeStatus($id, ConsultationApiModel::STATUS_DELETED, 'answer');
        if ($update) {
            return $this->response(200, "Melumat silindi");
        }

        return $this->response(400, "Əməliyyatın icra olunması zamanı xəta baş verdi");
    }


    // Delete functions

    /**
     * Xeber sil
     * https://e-tibb.az/api/general/consultation/delete-one
     * method: POST
     */
    public function actionDeleteOne()
    {
        $id = Yii::$app->request->post('id');
        if ($id) {
            $consulation = ConsultationApiModel::ConsultationFind($id);
            if (!empty($consulation)) {
                ConsultationApiModel::ConsultationDelete($id);
                AdminLog::write(['id' => $consulation['id'], 'name' => $consulation['user_name']]);

                return $this->response(200, "Məlumat silindi");
            }
        }

        return $this->response(404, "Bu nömrəli sual tapilmadı");
    }


    /**
     * Xeberler toplu sil
     * https://e-tibb.az/api/general/consultation/all-delete
     * method: POST
     * id: array
     */
    public function actionAllDelete()
    {
        $ids = Yii::$app->request->post('id');

        if ($ids && is_array($ids)) {
            foreach ($ids as $id) {
                if (is_numeric($id)) {
                    $consulation = ConsultationApiModel::ConsultationFind($id);
                    if (!empty($consulation)) {
                        ConsultationApiModel::ConsultationDelete($id);
                        AdminLog::write(['id' => $consulation['id'], 'name' => $consulation['user_name']]);
                    }
                }
            }
            return $this->response(200, "Məlumat silindi");
        } else {
            return $this->response(400, "Id sahəsi vacibdir");
        }

        return $this->response(404, "Bu nömrəli sual tapilmadı");
    }


    /**
     * Xeber daimi sil
     * https://e-tibb.az/api/general/consultation/base-delete-one
     * method: POST
     */
    public function actionBaseDeleteOne()
    {
        if (Yii::$app->userCheck->can("superadmin")) {
            $id = Yii::$app->request->post('id');

            if ($id) {
                $consulation = ConsultationApiModel::ConsultationFind($id);
                if (!empty($consulation)) {
                    ConsultationApiModel::ConsulationDeletePermanently($id);
                    AdminLog::write(['id' => $consulation['id'], 'name' => $consulation['user_name']]);
                    return $this->response(200, "Məlumat silindi");
                }
            }

            return $this->response(404, "Bu nömrəli sual tapilmadı");
        } else {
            return $this->response(403, "Bu əməliyyatı icra etmək üçün icazəniz yoxdur");
        }
    }

    /**
     * Xeberleri daimi sil
     * https://e-tibb.az/api/general/consultation/all-base-delete
     * method: POST
     * id: array
     */
    public function actionAllBaseDelete()
    {
        if (Yii::$app->userCheck->can("superadmin")) {
            $ids = Yii::$app->request->post('id');
            if ($ids && is_array($ids)) {
                foreach ($ids as $id) {
                    if (is_numeric($id)) {
                        $consulation = ConsultationApiModel::ConsultationFind($id);
                        if (!empty($consulation)) {
                            ConsultationApiModel::ConsulationDeletePermanently($id);
                            AdminLog::write(['id' => $consulation['id'], 'name' => $consulation['user_name']]);
                        }
                    }
                }
                return $this->response(200, "Məlumat silindi");
            } else {
                return $this->response(400, "Ids sahəsi vacibdir");
            }

            return $this->response(404, "Bu nömrəli sual tapilmadı");
        } else {
            return $this->response(403, "Bu əməliyyatı icra etmək üçün icazəniz yoxdur");
        }
    }


}