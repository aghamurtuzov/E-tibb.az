<?php

namespace api\models;

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
        $result = $db->createCommand("SELECT id,name,photo,expires,slug,promotion,feature,rating,about,services_prices,weekdays,saturday,sunday,category_id FROM `".self::$tableName."` WHERE `user_id`=:user_id ",[":user_id" => $user_id])->queryOne();
        return $result;
    }

    public static function getcategoryEnterprisesCount($id)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT count(id) FROM `".self::$tableName."` WHERE `category_id`=:category_id and `status`=1",[":category_id" => $id])->queryScalar();
        return $result;
    }

    public static function getCategoryEnterprises($id,$offset=0,$limit=0)
    {
        $db = Yii::$app->db;
        $limit_query = '';
        if($offset>0 and $limit>0){
            $limit_query = ' LIMIT '.$offset.",".$limit;
        }elseif ($offset==0 and $limit>0){
            $limit_query = ' LIMIT '.$limit;
        }
        $result = $db->createCommand("SELECT id,name,photo,slug,expires,promotion,feature,rating,address FROM `view_enterprise_address`  WHERE `category_id`=:category_id and `status`=1 ORDER BY id DESC".$limit_query,[":category_id" => $id])->queryAll();

        return $result;
    }

    public static function getEnterprise($id)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT * FROM `".self::$tableName."` WHERE `id`=:id ",[":id" => $id])->queryOne();
        return $result;
    }

    public function find_SearchEnterprises($searchdata,$limit, $offset)
    {
        if(!empty($searchdata['like']) and !empty($searchdata['id']) and !empty($searchdata['check'])){
            $result = $this->db->createCommand("SELECT id,category_id,name,photo,expires,promotion,feature,rating,address FROM `view_enterprise_address` 
            WHERE `name` LIKE '%".$searchdata['like']."%' and feature LIKE '%\"saat24\":1%' and `category_id`='".$searchdata['id']."' ORDER BY id DESC LIMIT $limit OFFSET $offset")->queryAll();
            return $result;
        }else if (!empty($searchdata['like']) and !empty($searchdata['id']) and empty($searchdata['check']) ){
            $result = $this->db->createCommand("SELECT id,category_id,name,photo,expires,promotion,feature,rating,address FROM `view_enterprise_address` 
            WHERE `name` LIKE '%".$searchdata['like']."%' and `category_id`='".$searchdata['id']."' ORDER BY id DESC LIMIT $limit OFFSET $offset")->queryAll();
            return $result;
        }else if(empty($searchdata['like']) and !empty($searchdata['id']) and !empty($searchdata['check'])){
            $result = $this->db->createCommand("SELECT id,category_id,name,photo,expires,promotion,feature,rating,address FROM `view_enterprise_address` 
            WHERE feature LIKE '%\"saat24\":1%' and `category_id`='".$searchdata['id']."' ORDER BY id DESC LIMIT $limit OFFSET $offset")->queryAll();
            return $result;
        }
    }

    public function findSearchEnterpriseCount($searchdata)
    {
        if(!empty($searchdata['like']) and !empty($searchdata['id']) and !empty($searchdata['check'])){
            $count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM `view_enterprise_address` WHERE `name` LIKE '%".$searchdata['like']."%' and feature LIKE '%\"saat24\":1%' and `category_id`='".$searchdata['id']."'")->queryScalar();
            return $count;
        }else if(!empty($searchdata['like']) and !empty($searchdata['id']) and empty($searchdata['check'])){
            $count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM `view_enterprise_address` WHERE `name` LIKE '%".$searchdata['like']."%' and `category_id`='".$searchdata['id']."'")->queryScalar();
            return $count;
        }else if(empty($searchdata['like']) and !empty($searchdata['id']) and !empty($searchdata['check'])){
            $count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM `view_enterprise_address` WHERE feature LIKE '%\"saat24\":1%' and `category_id`='".$searchdata['id']."'")->queryScalar();
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

    public static function getEnterpriseUser($connect_id)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT * FROM `site_users` WHERE `id`=:connect_id ",[":connect_id" => $connect_id])->queryOne();
        return $result;
    }
}
