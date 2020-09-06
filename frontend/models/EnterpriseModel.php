<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class EnterpriseModel extends Model
{
    public $db;
    public $datetime;
    public $menus;
    public static $tableName = 'site_enterprises';

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->datetime = Yii::$app->params['current.date'].' '.Yii::$app->params['current.time'];
        $this->db       = Yii::$app->db;

    }

    public static function getEnterpriseWithUser($user_id)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT id,name,photo,expires,slug,promotion,feature,rating,about,services_prices,weekdays,saturday,sunday,category_id FROM `".self::$tableName."` WHERE `user_id`=:user_id ",[":user_id" => $user_id])->cache(60)->queryOne();
        return $result;
    }

    public static function getcategoryEnterprisesCount($keyword=null,$id)
    {
        $db = Yii::$app->db;

        if($keyword!=null)
        {
            $result = $db->createCommand("SELECT count(id) FROM `".self::$tableName."` WHERE `category_id`=:category_id and `status`=1 and `name` LIKE '%$keyword%'",[":category_id" => $id])->cache(60)->queryScalar();
        }
        else
        {
            $result = $db->createCommand("SELECT count(id) FROM `".self::$tableName."` WHERE `category_id`=:category_id and `status`=1",[":category_id" => $id])->cache(60)->queryScalar();
        }


        return $result;
    }

    public static function getCategoryEnterprises($keyword=null,$id,$offset=0,$limit=0)
    {
        $db = Yii::$app->db;
        $limit_query = '';
        if($offset>0 and $limit>0){
            $limit_query = ' LIMIT '.$offset.",".$limit;
        }elseif ($offset==0 and $limit>0){
            $limit_query = ' LIMIT '.$limit;
        }

        if($keyword!=null)
        {
            $result = $db->createCommand("SELECT id,name,photo,slug,expires,promotion,feature,rating,address FROM `view_enterprise_address`  WHERE `category_id`=:category_id and `status`=1 and `name` LIKE '%$keyword%' ORDER BY id DESC".$limit_query,[":category_id" => $id])->cache(60)->queryAll();
        }
        else
        {
            $result = $db->createCommand("SELECT id,name,photo,slug,expires,promotion,feature,rating,address FROM `view_enterprise_address`  WHERE `category_id`=:category_id and `status`=1 ORDER BY id DESC".$limit_query,[":category_id" => $id])->cache(60)->queryAll();
        }


        return $result;
    }
    public static function getPremiumEnterprisesCount($id)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT count(id) FROM `".self::$tableName."` WHERE `category_id`=:category_id and `status`=1 and expires>=:expires ",[":category_id" => $id,':expires' => date("Y-m-d")])->cache(60)->queryScalar();
        return $result;
    }

    public static function getPremiumEnterprises($id,$offset=0,$limit=0)
    {
        $db = Yii::$app->db;
        $limit_query = '';
        if($offset>0 and $limit>0){
            $limit_query = ' LIMIT '.$offset.",".$limit;
        }elseif ($offset==0 and $limit>0){
            $limit_query = ' LIMIT '.$limit;
        }
        $result = $db->createCommand("SELECT id,name,photo,slug,expires,promotion,feature,rating,address FROM `view_enterprise_address`  WHERE `category_id`=:category_id and `status`=1 and expires>=:expires ORDER BY id DESC".$limit_query,[":category_id" => $id,':expires' => date("Y-m-d")])->cache(60)->queryAll();

        return $result;
    }

    public static function getEnterprise($id)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT * FROM `".self::$tableName."` WHERE `id`=:id ",[":id" => $id])->cache(60)->queryOne();
        return $result;
    }

    public function find_SearchEnterprises($searchdata,$limit, $offset)
    {
        if(!empty($searchdata['like']) and !empty($searchdata['id']) and !empty($searchdata['check'])){
            $result = $this->db->createCommand("SELECT id,category_id,name,photo,expires,promotion,feature,rating,address FROM `view_enterprise_address` 
            WHERE `name` LIKE '%".$searchdata['like']."%' and feature LIKE '%\"saat24\":1%' and `category_id`='".$searchdata['id']."' ORDER BY id DESC LIMIT $limit OFFSET $offset")->cache(60)->queryAll();
            return $result;
        }else if (!empty($searchdata['like']) and !empty($searchdata['id']) and empty($searchdata['check']) ){
            $result = $this->db->createCommand("SELECT id,category_id,name,photo,expires,promotion,feature,rating,address FROM `view_enterprise_address` 
            WHERE `name` LIKE '%".$searchdata['like']."%' and `category_id`='".$searchdata['id']."' ORDER BY id DESC LIMIT $limit OFFSET $offset")->cache(60)->queryAll();
            return $result;
        }else if(empty($searchdata['like']) and !empty($searchdata['id']) and !empty($searchdata['check'])){
            $result = $this->db->createCommand("SELECT id,category_id,name,photo,expires,promotion,feature,rating,address FROM `view_enterprise_address` 
            WHERE feature LIKE '%\"saat24\":1%' and `category_id`='".$searchdata['id']."' ORDER BY id DESC LIMIT $limit OFFSET $offset")->cache(60)->queryAll();
            return $result;
        }
    }

    public function findSearchEnterpriseCount($searchdata)
    {
        if(!empty($searchdata['like']) and !empty($searchdata['id']) and !empty($searchdata['check'])){
            $count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM `view_enterprise_address` WHERE `name` LIKE '%".$searchdata['like']."%' and feature LIKE '%\"saat24\":1%' and `category_id`='".$searchdata['id']."'")->cache(60)->queryScalar();
            return $count;
        }else if(!empty($searchdata['like']) and !empty($searchdata['id']) and empty($searchdata['check'])){
            $count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM `view_enterprise_address` WHERE `name` LIKE '%".$searchdata['like']."%' and `category_id`='".$searchdata['id']."'")->cache(60)->queryScalar();
            return $count;
        }else if(empty($searchdata['like']) and !empty($searchdata['id']) and !empty($searchdata['check'])){
            $count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM `view_enterprise_address` WHERE feature LIKE '%\"saat24\":1%' and `category_id`='".$searchdata['id']."'")->cache(60)->queryScalar();
            return $count;
        }

    }

    public function get_next_post($post_id)
    {
        $data = $this->db->createCommand("SELECT * FROM `site_enterprises`
                              WHERE `id`>:id  LIMIT 1",[':id'=>$post_id])->queryOne();
        if(!empty($data))
            return $data;
        else
            return false;
    }

    public function get_prev_post($post_id)
    {
        $data = $this->db->createCommand("SELECT * FROM `site_enterprises`
                          WHERE `id`<:id LIMIT 1",[':id'=>$post_id])->queryOne();
        if(!empty($data))
            return $data;
        else
            return false;
    }

    public static function getClinicCount()
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT count(id) FROM `".self::$tableName."` WHERE `status`=1")->cache(120)->queryScalar();
        return $result;
    }

    public static function getClinics($category_id)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT id,name,photo,expires,slug,promotion,feature,rating,about,services_prices,weekdays,saturday,sunday,category_id FROM `".self::$tableName."` WHERE `category_id`=:category_id Order by name",[":category_id" => $category_id])->cache(60)->queryAll();
        return $result;
    }

    public static function getCategory($id)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT `id`,`link`,`name`,`parent` FROM `site_menus` WHERE `id`=:id ",[":id" => $id])->cache(60)->queryOne();
        return $result;
    }

    public function Doctors($cat_id)
    {
        return Yii::$app->db->createCommand("SELECT d.`id`,d.`name`,d.`slug`,d.`photo`,d.`email`,`spc`.`specialist_id` AS `specialist_id`,d.`experience1` FROM `site_doctors` AS d 
INNER JOIN `site_enterprise_doctor` as sde ON d.`id`=sde.`doctor_id` 
LEFT JOIN `site_doctor_specialist` `spc` ON `d`.`id` = `spc`.`doctor_id`
WHERE sde.`enterprise_id`=:cat_id AND d.`status`=:status ORDER BY d.id DESC",[':cat_id'=>$cat_id,':status'=>1])->queryAll();
    }
}
