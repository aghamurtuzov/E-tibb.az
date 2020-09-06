<?php
/**
 * Created by PhpStorm.
 * User: Chingiz
 * Date: 12/3/2018
 * Time: 5:57 PM
 */

namespace frontend\models;


use Yii;
use yii\base\Model;

class PromotionModel extends Model
{

    public $db;
    public $datetime;
    public $promotion;
    public $user_promotion;
    public $count;
    public $singlepromotion;
    public $connect_name1;
    public $connect_name2;

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->datetime = Yii::$app->params['current.date'].' '.Yii::$app->params['current.time'];
        $this->db       = Yii::$app->db;

    }

    public static function getNotExpiredPromotions()
    {
        return Yii::$app->db->createCommand("SELECT `id`,`headline`,`photo`,`date_start`,`date_end`,`price`,`discount`,`connect_id`,`type` FROM site_promotions WHERE status=:status and date_end>=:date_end ORDER BY `date_end` DESC LIMIT 10",[":status" => 1, ":date_end" => date("Y-m-d")])->cache(60)->queryAll();
    }

    public function getPromotions($keyowrd=null,$limit,$offset,$clinic_id=null,$doc_id=null)
    {
        $where='';
        if($clinic_id != null )
            $where = ' AND connect_id='.$clinic_id;
        elseif($clinic_id != null && $doc_id != null)
            $where = ' AND (connect_id='.$clinic_id .' OR '. ' connect_id='.$doc_id .')';
        elseif($doc_id != null)
            $where = ' AND connect_id='.$doc_id;
        elseif($keyowrd != null)
            $where = ' AND (headline LIKE "%'.$keyowrd.'%" OR content LIKE "%'.$keyowrd.'%")';

        if(!$this->promotion)
        {
            $this->promotion = $this->db->createCommand("SELECT `id`,`headline`,`photo`,`date_start`,`date_end`,`date`,`price`,`discount`,`connect_id`,`type` 
                                                              FROM site_promotions where status=1 ".$where." ORDER BY `id` DESC LIMIT $limit OFFSET $offset")->cache(60)->queryAll();
        }
        return $this->promotion;
    }

    public function getPromotionCount($keyowrd=null,$clinic_id=null,$doc_id=null)
    {
        $where='';
        if($clinic_id != null )
            $where = ' AND connect_id='.$clinic_id;
        elseif($clinic_id != null && $doc_id != null)
            $where = ' AND (connect_id='.$clinic_id .' OR '. ' connect_id='.$doc_id .')';
        elseif($doc_id != null)
            $where =' AND connect_id='. $doc_id;
        elseif($keyowrd != null)
            $where = ' AND (headline LIKE "%'.$keyowrd.'%" OR content LIKE "%'.$keyowrd.'%")';

        if(!$this->count)
        {
            $this->count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM site_promotions WHERE status=1 ".$where." ORDER BY `id` DESC  ")->cache(60)->queryScalar();
        }
        return $this->count;
    }

    public function getUserPromotions($user_id,$limit,$offset)
    {
        if(!$this->user_promotion)
        {
            $this->user_promotion = $this->db->createCommand("SELECT `id`,`headline`,`photo`,`date_start`,`date_end`,`price`,`discount`,`connect_id`,`type` 
                                                              FROM site_promotions WHERE `connect_id`=$user_id ORDER BY `id` DESC LIMIT $limit OFFSET $offset")->queryAll();
        }
        return $this->user_promotion;
    }

    public function getUserPromotionCount($user_id)
    {
        if(!$this->count)
        {
            $this->count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM site_promotions WHERE `connect_id`=$user_id ORDER BY `id` DESC ")->queryScalar();
        }
        return $this->count;
    }

    public function getSinglePromotion($promotion_id)
    {
        if(!$this->singlepromotion)
        {
            $this->singlepromotion = $this->db->createCommand("SELECT `id`,`rating_value`,`review_count`,`headline`,`photo`,`date_start`,`content`,`date_end`,`price`,`discount`,`connect_id`,`type`
                                                                  FROM site_promotions WHERE  `id`=$promotion_id")->cache(60)->queryOne();
        }
        return $this->singlepromotion;
    }

    public function getConnectName($type,$connect_id)
    {
        if(!empty($connect_id))
        {
            if($type==1)
            {
                $result = $this->db->createCommand("SELECT sd.`id`,sd.`name`,sd.`photo`, sw.`name` as `kat`
                                                        FROM site_doctors sd INNER JOIN `view_doctor_specialist` sw ON sd.`id`=sw.`doctor_id` WHERE sd.`id`=$connect_id")->cache(60)->queryOne();
                return $result;
            }else if( $type==2)
            {
                $result = $this->db->createCommand("SELECT `id`,`name`,`photo`,`category_id` as `kat` FROM site_enterprises WHERE `id`=$connect_id")->cache(60)->queryOne();

                return $result;
            }
        }
    }

    public static function getPromotionWithConnectId($connect_id,$type)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT `id`,`headline`,`photo`,`date_start`,`date_end`,`price`,`discount`,`connect_id`,`type` FROM site_promotions WHERE connect_id=:connect_id and `type`=:type and `status`!=:status  ORDER BY `date_end` DESC ",[":connect_id" => $connect_id,":type" => $type, ":status" => 3])->cache(60)->queryAll();
        return $result;
    }

    public function get_next_post($post_id)
    {
        $data = $this->db->createCommand("SELECT * FROM site_promotions
                              WHERE `id`>:id  LIMIT 1",[':id'=>$post_id])->queryOne();
        if(!empty($data))
            return $data;
        else
            return false;
    }

    public function get_prev_post($post_id)
    {
        $data = $this->db->createCommand("SELECT * FROM site_promotions
                          WHERE `id`<:id LIMIT 1",[':id'=>$post_id])->queryOne();
        if(!empty($data))
            return $data;
        else
            return false;
    }

    public static function getPromotionsProfile($limit=20)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT `id`,`headline`,`photo`,`date_start`,`date_end`,`price`,`discount`,`connect_id`,`type` FROM site_promotions WHERE status=:status and date_end>=:date_end ORDER BY `date_end` DESC LIMIT $limit",[":status" => 1, ":date_end" => date("Y-m-d")])->cache(60)->queryAll();
        return $result;
    }

    public static function getUserPromotionsProfile($user_id)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT `id`,`headline`,`photo`,`date_start`,`date_end`,`price`,`discount`,`connect_id`,`type` FROM site_promotions WHERE status=:status and connect_id=:connect_id ORDER BY `date_end` DESC",[":status" => 1, ":connect_id" => $user_id])->cache(60)->queryAll();

        return $result;
    }

    public static function getPromotionsUsed($user_id)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT `promocode` FROM site_used_promotions WHERE user_id=:user_id",[":user_id" => $user_id])->cache(60)->queryAll();
        return $result;
    }

//    public static function checkUserPromotion($promotion,$user_id,$type)
//    {
//        var_dump($type);var_dump($promotion['connect_id']);var_dump($user_id);
//        $db = Yii::$app->db;
//        if($type === 0){
//            return ($promotion['connect_id'] == $user_id ? true : false);
//        }elseif($type === 1){
//            $data = $db->createCommand('SELECT user_id FROM site_doctors where id=:id',[':id' => $promotion['connect_id']])->queryOne();
//            return ($data['user_id'] == $user_id ? true : false);
//        }else{
//            $data = $db->createCommand('SELECT user_id FROM site_enterprises where id=:id',[':id' => $promotion['connect_id']])->queryOne();
//            return ($data['user_id'] == $user_id  ? true : false);
//        }
//    }

}