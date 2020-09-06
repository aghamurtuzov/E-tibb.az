<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class MainModel extends Model
{

    const MENU_TYPE_STATIC = 1;
    const MENU_TYPE_NEWS = 2;
    public $db;
    public $datetime;
    public $menus;
    public $specialists;
    public $news;
    public $today;


    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->datetime = Yii::$app->params['current.date'].' '.Yii::$app->params['current.time'];
        $this->db       = Yii::$app->db;

    }

    public function  getSlugId($slug)
    {
        if(!empty($slug))
        {
            $result = $this->db->createCommand("SELECT `id` FROM site_menus WHERE `link`='$slug' and `status`=:status ORDER BY `menu_order`",[':status'=>1])->cache(120)->queryScalar();
            return $result;
        }

    }

    public function getMenus()
    {
        if(!$this->menus)
        {
            $this->menus = $this->db->createCommand("SELECT * FROM site_menus WHERE `status`=:status ORDER BY `menu_order`",[':status'=>1])->cache(120)->queryAll();
        }
        return $this->menus;
    }

    public function get_Category($type)
    {
        $results = $this->db->createCommand("SELECT * FROM `site_menus` WHERE `type`=$type and `status`=1 and `parent`>0 ORDER by `name` ASC ")->cache(120)->queryAll();
        return $results;
    }

    public function getSpecialists()
    {
        if(!$this->specialists)
        {
            $this->specialists = $this->db->createCommand('select * from `site_specialists` ORDER BY `name`')->cache(120)->queryAll();
        }
        return $this->specialists;
    }

    public function getNews()
    {
        $this->today = date('Y-m-d H:i:s');
        if(!$this->news)
        {
            $this->news = $this->db->createCommand("select * from `site_news` where `datetime`<='$this->today' and status=1 ORDER BY `datetime` DESC LIMIT 50")->cache(60)->queryAll();
        }
        return $this->news;
    }

    public function getNewsBlog($limit)
    {
        $this->today = date('Y-m-d H:i:s');
        if(!$this->news)
        {
            $this->news = $this->db->createCommand("select * from `site_news` where `datetime`<='$this->today' and status=1 and category_id=34 ORDER BY `datetime` DESC LIMIT $limit")->cache(60)->queryAll();
        }
        return $this->news;
    }

    public function getEnterprises($id,$limit)
    {
        $result = $this->db->createCommand("SELECT * FROM `view_enterprise_address` WHERE `category_id`=:category_id LIMIT {$limit}",[":category_id" => $id])->cache(120)->queryAll();
        return $result;
    }

    public function getPromotions($limit= 3)
    {
        $result = $this->db->createCommand("SELECT * FROM `site_promotions` ORDER BY `id` DESC LIMIT :limit",[":limit" => $limit])->cache(120)->queryAll();
        return $result;
    }

    public function getPremiumDoctors($limit=12)
    {
        $result = $this->db->createCommand("SELECT * FROM `view_doctors_list` WHERE `status`=1 and `is_premium`=1 LIMIT {$limit}")->cache(60)->queryAll();
        return $result;
    }
    public function getSiteComments($limit=4)
    {
        $result = $this->db->createCommand("SELECT *,sc.name as name,sd.name as doctor_name, sc.rating as rating, sd.photo as doctor_image FROM `site_comments` as sc left join site_doctors as sd on sd.id = sc.connect_id WHERE sc.type=1 and sc.status=1 ORDER by sc.id desc LIMIT {$limit} ")->cache(60)->queryAll();
        return $result;
    }
    public function getVideos($limit=4)
    {
        $result = $this->db->createCommand("SELECT * FROM `site_news` WHERE is_video=1 ORDER by news_read desc LIMIT {$limit} ")->cache(60)->queryAll();
        return $result;
    }

}
