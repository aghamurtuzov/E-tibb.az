<?php
/**
 * Created by PhpStorm.
 * User: Chingiz
 * Date: 1/9/2019
 * Time: 3:00 PM
 */

namespace frontend\models;

use frontend\components\Seo;
use Yii;
use yii\base\Model;
use yii\helpers\Url;

class SitemapModel extends Model
{
    public $db;
    public $datetime;
    public $today;
    public $menu;
    public $news;
    public $xeberler;
    public $count_x;
    public $promotion;
    public $enterprises;

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->datetime = Yii::$app->params['current.date'] . ' ' . Yii::$app->params['current.time'];
        $this->db = Yii::$app->db;

    }

    public function getSiteMapMenus()
    {
        if (!$this->menu) {
            $this->menu = $this->db->createCommand("SELECT * FROM site_menus WHERE `status`=:status ORDER BY `menu_order`", [':status' => 1])->queryAll();
        }
        return $this->menu;
    }

    public function getXeberlerCount()
    {
        $this->today = date('Y-m-d H:i:s');
        if (!$this->count_x) {
            $this->count_x = $this->db->createCommand("SELECT count(`id`) as `counter` FROM site_news  WHERE `status`= 1 and `datetime`<='$this->today' ")->queryScalar();
        }
        return $this->count_x;
    }

    public function getPromotions()
    {
        if (!$this->promotion) {
            $this->promotion = $this->db->createCommand("SELECT `id`,`headline`,`photo`,`content`,`date_start`,`date_end`,`price`,`discount`,`connect_id`,`type` FROM `site_promotions` ORDER BY `id` DESC")->queryAll();
        }
        return $this->promotion;
    }

    public function getCategoryEnterprises($id)
    {
        if (!$this->enterprises) {
            $this->enterprises = $this->db->createCommand("SELECT id,name,photo,expires,promotion,feature,rating,address FROM `view_enterprise_address`  WHERE `category_id`=:category_id ORDER BY id DESC ", [":category_id" => $id])->queryAll();
        }
        return $this->enterprises;
    }

    public function getRssEnterprises($offset, $limit)
    {
        if (!$this->enterprises) {
            $this->enterprises = $this->db->createCommand("SELECT id,name,photo,about,promotion,published_time FROM `site_enterprises` ORDER BY id DESC LIMIT :offset,:limit", [":offset" => $offset, ":limit" => $limit])->queryAll();
        }
        return $this->enterprises;
    }

    public static function getCountEnterprises()
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT count(`id`) as `count` FROM `site_enterprises`")->queryScalar();
        return $result;
    }


    public static function getDoctors($specialist_id)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT * FROM `site_doctors` WHERE `specialist_id`=:specialist_id and status=1 ORDER BY `id` DESC", [":specialist_id" => $specialist_id])->queryAll();
        return $result;
    }

    public static function getSpecialist()
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT * FROM `site_specialists` ORDER BY `id` DESC")->queryAll();
        return $result;
    }


    public static function getCountDoctors()
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT count(`id`) as `count` FROM `site_doctors`")->queryScalar();
        return $result;
    }

    public static function getRssDoctors($offset, $limit)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT * FROM `view_doctors_list` ORDER BY `id` DESC LIMIT :offset,:limit", [":offset" => $offset, ":limit" => $limit])->queryAll();
        return $result;
    }

    public static function getEnterpriseMenu($slug)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT * FROM `site_menus` WHERE link=:link AND `status` = :status", [":link" => $slug, ":status" => 1])->queryOne();
        return $result;
    }

    public static function getEnterprise($id)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT * FROM `site_enterprises` WHERE category_id=:id AND `status` = :status ORDER BY id DESC", [":id" => $id, ":status" => 1])->queryAll();
        return $result;
    }

    public function getNews($id)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT * FROM `site_news` WHERE category_id=:id AND `status` = :status ORDER BY id DESC", [":id" => $id, ":status" => 1])->queryAll();
        return $result;
    }

    public function getNewsAll()
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT * FROM `site_news` WHERE `status` = :status  ORDER BY id DESC LIMIT 1000", [":status" => 1])->queryAll();
        return $result;
    }
}