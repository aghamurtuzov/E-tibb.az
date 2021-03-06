<?PHP

namespace api\modules\doctor\models;

use yii;
use yii\base\Model;

class ConsultationApiModel extends Model
{

    const STATUS_DEACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    const STATUS_DELETED  = 2;

    /** Suallar */
    public static function QuestionsCount()
    {
        return Yii::$app->db->createCommand("SELECT count(c.`id`) AS `count` FROM `site_consultation` AS c INNER JOIN `site_doctors` AS d ON d.`id`=c.`doctor_id` WHERE c.`status`<>:status",[':status'=>self::STATUS_DELETED])->queryOne();
    }

    public static function Questions($limits)
    {
        return Yii::$app->db->createCommand("SELECT c.*,d.`name` as `doctor_name` FROM `site_consultation` AS c INNER JOIN `site_doctors` AS d ON d.`id`=c.`doctor_id` WHERE c.`status`<>:status ORDER BY c.id DESC LIMIT {$limits[0]},{$limits[1]}",[':status'=>self::STATUS_DELETED])->queryAll();
    }

    /** Sual */
    public static function Question($id)
    {
        return Yii::$app->db->createCommand("SELECT c.`id`,c.`name` as user_name,c.`doctor_id`,c.`question`,`q_datetime`,`answer`,`a_datetime`,d.`name` as `doctor_name` FROM `site_consultation` AS c INNER JOIN `site_doctors` AS d ON d.`id`=c.`doctor_id` WHERE c.`id`=:id AND c.`status`=:status DESC LIMIT 1",[':id'=>$id,':status'=>self::STATUS_DELETED])->queryOne();
    }

    /** Sual status deyismek */
    public static function ChangeStatus($id,$status,$type = 'question')
    {
        if($type == 'question')
        {
            return Yii::$app->db->createCommand("UPDATE `site_consultation` SET `status`=:status WHERE id=:id",[':id'=>$id,'status'=>$status])->execute();
        }else{
            return Yii::$app->db->createCommand("UPDATE `site_consultation` SET `a_status`=:a_status WHERE id=:id AND answer<>'' ",[':id'=>$id,'a_status'=>$status])->execute();
        }
    }

    /** Cavab ver */
    public static function Answer($id,$answer)
    {
        $a_datetime = date('Y-m-d H:i:s');
        return Yii::$app->db->createCommand("UPDATE `site_consultation` SET `answer`=:answer,`a_datetime`=:a_datetime WHERE id=:id",[':id'=>$id,':answer'=>$answer,':a_datetime'=>$a_datetime])->execute();
    }
    
    /** Hekime aid suallar */
    public static function QuestionsDoctorCount($doctor_id)
    {
        return Yii::$app->db->createCommand("SELECT count(c.`id`) AS `count` FROM `site_consultation` AS c INNER JOIN `site_doctors` AS d ON d.`id`=c.`doctor_id` WHERE c.`doctor_id`=:doctor_id AND c.`status`<>:status",[':doctor_id'=>$doctor_id,':status'=>self::STATUS_DELETED])->queryOne();
    }

    public static function QuestionsDoctor($limits,$doctor_id)
    {
        return Yii::$app->db->createCommand("SELECT c.*,d.`name` as `doctor_name` FROM `site_consultation` AS c INNER JOIN `site_doctors` AS d ON d.`id`=c.`doctor_id` WHERE c.`doctor_id`=:doctor_id AND c.`status`=:status ORDER BY c.id DESC LIMIT {$limits[0]},{$limits[1]}",[':doctor_id'=>$doctor_id,':status'=>self::STATUS_ACTIVE])->queryAll();
    }

    /** Hekim sual */
    public static function QuestionDoctor($id,$doctor_id)
    {
        return Yii::$app->db->createCommand("SELECT c.`id`,c.`name` as user_name,c.`doctor_id`,c.`question`,`q_datetime`,`answer`,`a_datetime`,d.`name` as `doctor_name` FROM `site_consultation` AS c INNER JOIN `site_doctors` AS d ON d.`id`=c.`doctor_id` WHERE c.`id`=:id AND c.`doctor_id`=:doctor_id AND c.`status`=:status DESC LIMIT 1",[':doctor_id'=>$doctor_id,':id'=>$id,':status'=>self::STATUS_DELETED])->queryOne();
    }

    /** Hekim aid sualin statusunu deyismek */
    public static function ChangeStatusDoctor($id,$status,$type = 'question',$doctor_id)
    {
        if($type == 'question')
        {
            return Yii::$app->db->createCommand("UPDATE `site_consultation` SET `status`=:status WHERE id=:id and doctor_id=:doctor_id",[':doctor_id'=>$doctor_id,':id'=>$id,'status'=>$status])->execute();
        }else{
            return Yii::$app->db->createCommand("UPDATE `site_consultation` SET `a_status`=:a_status WHERE id=:id AND answer<>'' ",[':id'=>$id,'a_status'=>$status])->execute();
        }
    }

    /** Hekim cavab ver */
    public static function AnswerDoctor($id,$answer,$doctor_id)
    {
        $a_datetime = date('Y-m-d H:i:s');
        return Yii::$app->db->createCommand("UPDATE `site_consultation` SET `answer`=:answer,`a_datetime`=:a_datetime WHERE id=:id and doctor_id=:doctor_id",[':doctor_id'=>$doctor_id,':id'=>$id,':answer'=>$answer,':a_datetime'=>$a_datetime])->execute();
    }

    /** Cavab delete */
    public static function DeleteAnswer($id, $status,$doctor_id)
    {
        return Yii::$app->db->createCommand("UPDATE `site_consultation` SET `a_status`=:a_status, answer = '' WHERE id=:id  and doctor_id=:doctor_id", [':doctor_id'=>$doctor_id,':id' => $id, 'a_status' => $status])->execute();
    }
    /** Cavab */
    public static function consultationFind($id)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_consultation` WHERE `id`=:id",[':id'=>$id])->queryOne();
    }
}