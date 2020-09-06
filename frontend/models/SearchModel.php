<?php
/**
 * Created by PhpStorm.
 * User: Chingiz
 * Date: 12/12/2018
 * Time: 3:29 PM
 */

namespace frontend\models;
use Yii;
use yii\base\Model;



class SearchModel extends Model
{
    public $db;
    public $datetime;
    public $today;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->datetime = Yii::$app->params['current.date'].' '.Yii::$app->params['current.time'];
        $this->db       = Yii::$app->db;
    }

    public function get_SearchNews($searchdata,$limit, $offset)
    {
        $this->today = date('Y-m-d');
        if(!empty($searchdata['like']) and !empty($searchdata['id'])){
            $result = $this->db->createCommand("SELECT * FROM site_news WHERE (`headline` LIKE '%".$searchdata['like']."%' or `keywords` LIKE '%".$searchdata['like']."%') and `category_id`='".$searchdata['id']."' and `datetime`<='$this->today' and status=1 ORDER BY `id` DESC LIMIT $limit OFFSET $offset")->cache(120)->queryAll();
        }else if(empty($searchdata['like']) and !empty($searchdata['id'])){
            $result = $this->db->createCommand("SELECT * FROM site_news WHERE `category_id`='".$searchdata['id']."' and `datetime`<='$this->today' and status=1 ORDER BY `id` DESC LIMIT $limit OFFSET $offset")->cache(120)->queryAll();
        }else if(!empty($searchdata['like']) and !isset($searchdata['id'])){
            $result = $this->db->createCommand("SELECT * FROM site_news WHERE (`headline` LIKE '%".$searchdata['like']."%' or `keywords` LIKE '%".$searchdata['like']."%' ) and `datetime`<='$this->today' and `status`=1 ORDER BY `id` DESC LIMIT $limit OFFSET $offset")->cache(120)->queryAll();
        }else if(empty($searchdata['like']) and !isset($searchdata['id'])){
            $result = $this->db->createCommand("SELECT * FROM site_news WHERE `datetime`<='$this->today' and `status`=1  ORDER BY `id` DESC LIMIT $limit OFFSET $offset")->cache(120)->queryAll();
        }
        return $result;
    }

    public function getSearchNewsCount($searchdata)
    {
        $count = 0;
        $this->today = date('Y-m-d');
        if(!empty($searchdata['like']) and !empty($searchdata['id']))
        {

            $count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM site_news WHERE (`headline` LIKE '%".$searchdata['like']."%' or `keywords` LIKE '%".$searchdata['like']."%') and `category_id`='".$searchdata['id']."' and `datetime`<='$this->today' and status=1")->cache(120)->queryScalar();

        }else if(empty($searchdata['like']) and !empty($searchdata['id']))
        {

            $count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM site_news WHERE `category_id`='".$searchdata['id']."' and `datetime`<='$this->today' and status=1")->cache(120)->queryScalar();

        }else if(!empty($searchdata['like']) and !isset($searchdata['id']))
        {

            $count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM site_news WHERE (`headline` LIKE '%".$searchdata['like']."%' or `keywords` LIKE '%".$searchdata['like']."%') and `datetime`<='$this->today' and `status`=1")->cache(120)->queryScalar();

        }else if(empty($searchdata['like']) and !isset($searchdata['id']))
        {

            $count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM site_news WHERE `datetime`<='$this->today' and  `status`=1")->cache(120)->queryScalar();
        }
        return $count;
    }

    public function get_SearchEnterprises($searchdata,$limit, $offset)
    {
        if(!empty($searchdata['like']) and !empty($searchdata['id'])){
            $result = $this->db->createCommand("SELECT id,category_id,name,photo,expires,promotion,feature,rating,address FROM `view_enterprise_address` WHERE `name` LIKE '%".$searchdata['like']."%' or `address` LIKE '%".$searchdata['like']."%'  and `category_id`='".$searchdata['id']."' ORDER BY id DESC LIMIT $limit OFFSET $offset")->cache(120)->queryAll();
        }else if((empty($searchdata['like']) or isset($searchdata['like']) ) and !empty($searchdata['id'])){
            $result = $this->db->createCommand("SELECT id,category_id,name,photo,expires,promotion,feature,rating,address FROM `view_enterprise_address` WHERE `category_id`='".$searchdata['id']."' ORDER BY id DESC LIMIT $limit OFFSET $offset")->cache(120)->queryAll();
        }else if( !empty($searchdata['like']) and ( !isset($searchdata['id']) or empty($searchdata['id'])) ){
            $result = $this->db->createCommand("SELECT id,category_id,name,photo,expires,promotion,feature,rating,address FROM `view_enterprise_address` WHERE `name` LIKE '%".$searchdata['like']."%' or `address` LIKE '%".$searchdata['like']."%' ORDER BY id DESC LIMIT $limit OFFSET $offset")->cache(120)->queryAll();
        }else if( empty($searchdata['like']) and !isset($searchdata['id']) ){
            $result = $this->db->createCommand("SELECT id,category_id,name,photo,expires,promotion,feature,rating,address FROM `view_enterprise_address` ORDER BY id  DESC LIMIT $limit OFFSET $offset")->cache(120)->queryAll();
        }
        return $result;
    }

    public function getSearchEnterpriseCount($searchdata)
    {

        if(!empty($searchdata['like']) and !empty($searchdata['id'])){
            $count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM `view_enterprise_address` WHERE `name` LIKE '%".$searchdata['like']."%' or `address` LIKE '%".$searchdata['like']."%' and `category_id`='".$searchdata['id']."'")->cache(120)->queryScalar();
            return $count;
        }else if( (empty($searchdata['like']) or isset($searchdata['like']) ) and !empty($searchdata['id'])){
            $count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM `view_enterprise_address` WHERE `category_id`='".$searchdata['id']."'")->cache(120)->queryScalar();
            return $count;
        }else if(!empty($searchdata['like']) and !isset($searchdata['id'])){
            $count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM `view_enterprise_address` WHERE `name` LIKE '%".$searchdata['like']."%' or `address` LIKE '%".$searchdata['like']."%' ")->cache(120)->queryScalar();
            return $count;
        }else if(empty($searchdata['like']) and !isset($searchdata['id'])){
            $count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM `view_enterprise_address` ")->cache(120)->queryScalar();
            return $count;
        }

    }

    public function get_SearchDoctors($searchdata,$limit, $offset)
    {
        if(!empty($searchdata['like']) and !empty($searchdata['id']))
        {
            $result = $this->db->createCommand("SELECT * FROM view_doctor_name_specialist WHERE `name` LIKE '%".$searchdata['like']."%' and `specialist_id`='".$searchdata['id']."' and status=1 ORDER BY `id` DESC LIMIT $limit OFFSET $offset")->queryAll();
            return $result;

        }
        else if((empty($searchdata['like']) or !isset($searchdata['like']) ) and !empty($searchdata['id']))
        {
            $result = $this->db->createCommand("SELECT * FROM view_doctor_name_specialist WHERE `specialist_id`='".$searchdata['id']."' and status=1 ORDER BY `id` DESC LIMIT $limit OFFSET $offset")->queryAll();
            return $result;

        }
        else if(!empty($searchdata['like']) and (empty($searchdata['id']) or !isset($searchdata['id']) ) )
        {
            $result = $this->db->createCommand("SELECT * FROM view_doctor_name_specialist WHERE `name` LIKE '%".$searchdata['like']."%' and status=1 ORDER BY `id` DESC LIMIT $limit OFFSET $offset")->queryAll();
            return $result;

        }
        else if(empty($searchdata['like']) and (empty($searchdata['id']) or !isset($searchdata['id']) ) )
        {
            $result = $this->db->createCommand("SELECT * FROM view_doctor_name_specialist WHERE status=1 ORDER BY `id` DESC LIMIT $limit OFFSET $offset")->queryAll();
            return $result;

        }

    }

    public function getSearchDoctorCount($searchdata)
    {
        if(!empty($searchdata['like']) and !empty($searchdata['id']))
        {
            $count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM view_doctor_name_specialist WHERE `name` LIKE '%".$searchdata['like']."%' and `specialist_id`='".$searchdata['id']."' and status=1")->queryScalar();
        }
        else if( (empty($searchdata['like']) or !isset($searchdata['like']) ) and !empty($searchdata['id']))
        {
            $count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM view_doctor_name_specialist WHERE `specialist_id`='".$searchdata['id']."' and status=1")->queryScalar();
        }
        else if(!empty($searchdata['like']) and !isset($searchdata['id']))
        {
            $count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM view_doctor_name_specialist WHERE `name` LIKE '%".$searchdata['like']."%' and status=1")->queryScalar();
        }
        else if((empty($searchdata['like']) or isset($searchdata['like']) ) and empty($searchdata['id']))
        {
            $count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM view_doctor_name_specialist WHERE status=1")->queryScalar();
        }
        return $count;
    }

    public function getSwitcher($type)
    {
        $results = $this->db->createCommand("SELECT `id`,`name` FROM `site_menus` WHERE `type`=$type and `status`=1 and `parent`>0 ORDER by `name` ASC ")->cache(120)->queryAll();
        return $results;
    }


}