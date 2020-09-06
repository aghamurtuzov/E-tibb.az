<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class SiteConsultationModel extends Model
{
    public $db;
    public $datetime;

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->datetime = Yii::$app->params['current.date'].' '.Yii::$app->params['current.time'];
        $this->db       = Yii::$app->db;

    }

    public static function getQuestionsCount($doctor_id,$checkStatus)
    {
        $db = Yii::$app->db;
        if($checkStatus) {
            $result = $db->createCommand("SELECT count(id) FROM `site_consultation` WHERE `doctor_id`=:doctor_id",[":doctor_id" => $doctor_id])->cache(120)->queryScalar();
        } else {
            $result = $db->createCommand("SELECT count(id) FROM `site_consultation` WHERE `doctor_id`=:doctor_id AND `status`=:status AND a_status=:status",[":doctor_id" => $doctor_id,":status" => 1])->cache(120)->queryScalar();
        }
        return $result;
    }

    public static function getQuestions($doctor_id,$checkStatus,$page = 0)
    {
        $db     = Yii::$app->db;
        $limit  = Yii::$app->params["site.consultation_count"];
        $offset = $page*$limit;
        if($checkStatus) {
            $result = $db->createCommand("SELECT * FROM `site_consultation` WHERE `doctor_id`=:doctor_id ORDER BY id DESC LIMIT :offset,:limit",[":doctor_id" => $doctor_id,":offset"=>$offset,":limit"=>$limit])->cache(120)->queryAll();
        } else {
            $result = $db->createCommand("SELECT * FROM `site_consultation` WHERE `doctor_id`=:doctor_id AND `status`=:status AND `a_status`=:status  ORDER BY id DESC LIMIT :offset,:limit",[":doctor_id" => $doctor_id,":status" => 1,":offset"=>$offset,":limit"=>$limit])->cache(120)->queryAll();
        }
        return $result;
    }

    public function getQuestionAnswerCount($doctor_id,$checkStatus)
    {
        $db = Yii::$app->db;
        if($checkStatus)
        {
            $result = $db->createCommand("SELECT count(id) FROM `site_consultation` WHERE `doctor_id`=:doctor_id AND `status`=:status AND `a_status`=:status",[":doctor_id" => $doctor_id,":status" => 1])->cache(120)->queryScalar();
        }else{
            $result = $db->createCommand("SELECT count(id) FROM `site_consultation` WHERE `doctor_id`=:doctor_id",[":doctor_id" => $doctor_id])->cache(120)->queryScalar();
        }
        return $result;
    }

    public function getQuestionAnswer($doctor_id,$checkStatus,$limit, $offset)
    {
        if($checkStatus)
        {
            $result = $this->db->createCommand("SELECT * FROM `site_consultation` WHERE `doctor_id`=:doctor_id AND `status`=:status AND `a_status`=:status  ORDER BY id DESC LIMIT :offset,:limit",[":doctor_id" => $doctor_id,":status" => 1,":offset"=>$offset,":limit"=>$limit])->queryAll();
        }else{
            $result = $this->db->createCommand("SELECT * FROM `site_consultation` WHERE `doctor_id`=:doctor_id ORDER BY id DESC LIMIT :offset,:limit",[":doctor_id" => $doctor_id,":offset"=>$offset,":limit"=>$limit])->queryAll();
        }
        return $result;
    }



    public static function updateConsultation($consultation_id,$answer,$status,$datetime)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand()->update('site_consultation', ['answer' => $answer,'a_datetime'=>$datetime,'status'=>$status], 'id = '.$consultation_id)->execute();
        return $result;
    }

    public static function getQuestionsByUser($user_id)
    {
        $db     = Yii::$app->db;
        $result = $db->createCommand("SELECT `site_consultation`.*, `site_doctors`.name as doctor_name FROM `site_consultation` LEFT JOIN `site_doctors` ON `site_consultation`.`doctor_id` = `site_doctors`.`id` WHERE `site_consultation`.`user_id`=:user_id AND `site_consultation`.`status`=:status  ORDER BY `site_consultation`.id DESC",[":user_id" => $user_id,":status" => 1])->cache(120)->queryAll();
        return $result;
    }

}
