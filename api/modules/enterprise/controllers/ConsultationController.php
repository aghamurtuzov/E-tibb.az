<?php

namespace api\modules\enterprise\controllers;

use yii;
use api\components\Pagination;
use api\modules\enterprise\models\ConsultationApiModel;
use api\modules\enterprise\controllers\MainController;


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
     * https://e-tibb.az/api/enterprise/consultation
     * https://e-tibb.az/api/enterprise/consultation?page=1&count=5
     */
    public function actionIndex()
    {
        $userId = Yii::$app->session->get('userID');
        $model = new ConsultationApiModel();

        $totalCount = $model->QuestionsDoctorCount($userId);

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $model->QuestionsDoctor($limits,$userId);

        $data['list'] = $list;

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages,$data);

        return $this->response(200,"Məlumat mövcuddur",$result);

    }

    /**
     * Sual
     * https://e-tibb.az/api/enterprise/consultation/info/123
     */
    public function actionInfo()
    {
        $id = intval(Yii::$app->request->get('id'));
        $doctor_id = Yii::$app->session->get('userID');
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $question = ConsultationApiModel::QuestionDoctor($id,$doctor_id);
        if(empty($question))
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        return $this->response(200,"Məlumat mövcuddur",$question);

    }

    /**
     * Sual block
     * https://e-tibb.az/api/enterprise/consultation/block
     */
    public function actionBlock()
    {
        $id = intval(Yii::$app->request->post('id'));
        $doctor_id = Yii::$app->session->get('userID');
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $update = ConsultationApiModel::ChangeStatusDoctor($id,ConsultationApiModel::STATUS_DEACTIVE,'question',$doctor_id);
        if($update)
        {
            return $this->response(200,"Melumat bloklandı");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Cavab ver
     * https://e-tibb.az/api/enterprise/consultation/answer
     */
    public function actionAnswer()
    {
        $id        = intval(Yii::$app->request->post('id'));
        $answer    = Yii::$app->request->post('answer');
        $doctor_id = Yii::$app->session->get('userID');
        $answer    = !empty($answer) ? trim($answer) : null;
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        if(!empty($answer))
        {
            $update = ConsultationApiModel::AnswerDoctor($id,$answer,$doctor_id);
            if($update)
            {
                return $this->response(200,"Əməliyyatın uğurla icra olundu");
            }
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Cavab block
     * https://e-tibb.az/api/enterprise/consultation/block-answer
     */
    public function actionBlockAnswer()
    {
        $id = intval(Yii::$app->request->post('id'));
        $doctor_id = Yii::$app->session->get('userID');
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $update = ConsultationApiModel::ChangeStatusDoctor($id,ConsultationApiModel::STATUS_DEACTIVE,'answer',$doctor_id);
        if($update)
        {
            return $this->response(200,"Melumat bloklandı");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Sual aktiv
     * https://e-tibb.az/api/enterprise/consultation/active
     */
    public function actionActive()
    {
        $id = intval(Yii::$app->request->post('id'));
        $doctor_id = Yii::$app->session->get('userID');
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $update = ConsultationApiModel::ChangeStatusDoctor($id,ConsultationApiModel::STATUS_ACTIVE,'question',$doctor_id);
        if($update)
        {
            return $this->response(200,"Melumat aktiv edildi");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Cavab aktiv
     * https://e-tibb.az/api/enterprise/consultation/active-answer
     */
    public function actionActiveAnswer()
    {
        $id = intval(Yii::$app->request->post('id'));
        $doctor_id = Yii::$app->session->get('userID');
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $update = ConsultationApiModel::ChangeStatusDoctor($id,ConsultationApiModel::STATUS_ACTIVE,'answer',$doctor_id);
        if($update)
        {
            return $this->response(200,"Melumat aktiv edildi");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Sual sil
     * https://e-tibb.az/api/enterprise/consultation/del
     */
    public function actionDel()
    {
        $id = intval(Yii::$app->request->post('id'));
        $doctor_id = Yii::$app->session->get('userID');
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $update = ConsultationApiModel::ChangeStatusDoctor($id,ConsultationApiModel::STATUS_DELETED,'question',$doctor_id);
        if($update)
        {
            return $this->response(200,"Melumat silindi");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Cavab sil
     * https://e-tibb.az/api/enterprise/consultation/del-answer
     */
    public function actionDelAnswer()
    {
        $id = intval(Yii::$app->request->post('id'));
        $doctor_id = Yii::$app->session->get('userID');
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $update = ConsultationApiModel::ChangeStatusDoctor($id,ConsultationApiModel::STATUS_DELETED,'answer',$doctor_id);
        if($update)
        {
            return $this->response(200,"Melumat silindi");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

}