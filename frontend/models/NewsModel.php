<?php
/**
 * Created by PhpStorm.
 * User: Taleh
 * Date: 11/29/2018
 * Time: 11:54 AM
 */

namespace frontend\models;

use Yii;
use yii\base\Model;

class NewsModel extends Model
{
    public $db;
    public $datetime;
    public $news;
    public $xeberler;
    public $count;
    public $count_x;
    public $categories;
    public $relatednews;
    public $singlenews;
    public $gallery;
    public $type = 3;
    public $view;
    public $today;

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->datetime = Yii::$app->params['current.date'] . ' ' . Yii::$app->params['current.time'];
        $this->db = Yii::$app->db;

    }

    public function getNewsCount($category_id = null, $keyword = null, $cache = 60) {
        $today = date('Y-m-d H:i:s');
        $sql = "SELECT count(id) as counter FROM site_news WHERE status= 1 AND datetime<='$today'";

        if(is_array($category_id)) {
            $category_id = implode(",", $category_id);
            $sql .= " AND category_id NOT IN($category_id)";
        } else {
            $sql .= " AND category_id = ".$category_id;
        }

        if(!empty($keyword)) {
            $sql .= " AND (headline LIKE '%$keyword%' or content LIKE '%$keyword%' or keywords LIKE '%$keyword%')";
        }

        return $this->db->createCommand($sql)->cache($cache)->queryScalar();
    }

    public function getNews($category_id = null, $keyword = null, $limit = null, $offset = null) {
        $today = date('Y-m-d H:i:s');
        $sql = "SELECT `id`,`category_id`,`photo`,`headline`,`slug`,`content`,`datetime`,`news_read`,`keywords` FROM site_news WHERE status= 1 AND datetime<='$today' ";

        if(is_array($category_id)) {
            $category_id = implode(",", $category_id);
            $sql .= " AND category_id NOT IN($category_id)";
        } else {
            $sql .= " AND category_id = ".$category_id;
        }

        if(!empty($keyword)) {
            $sql .= " AND (headline LIKE '%$keyword%' or content LIKE '%$keyword%' or keywords LIKE '%$keyword%')";
        }

        $sql .= " ORDER BY `id` DESC LIMIT $limit OFFSET $offset";

        return $this->db->createCommand($sql)->cache(60)->queryAll();
    }

    public function getVideos($not_in, $limit, $offset)
    {
        $this->today = date('Y-m-d H:i:s');
        if (!$this->xeberler) {
            $this->xeberler = $this->db->createCommand("SELECT `id`,`category_id`,`photo`,`headline`,`slug`,`content`,`datetime`,`news_read`,`keywords` FROM site_news WHERE `category_id` NOT IN($not_in) and `status`= 1 and `datetime`<='$this->today' and is_video=1 ORDER BY `id` DESC LIMIT $limit OFFSET $offset")->cache(60)->queryAll();
        }
        return $this->xeberler;
    }

    public function getVideosCount($not_in)
    {
        $this->today = date('Y-m-d H:i:s');
        if (!$this->count_x) {
            $this->count_x = $this->db->createCommand("SELECT count(`id`) as `counter` FROM site_news  WHERE `category_id` NOT IN($not_in) and `status`= 1 and `datetime`<='$this->today' and is_video=1 ")->cache(120)->queryScalar();
        }
        return $this->count_x;
    }

    public function getRelatedNews($category_id, $post_id)
    {
        $this->today = date('Y-m-d H:i:s');
        if (!$this->relatednews) {
            $this->relatednews = $this->db->createCommand("SELECT `id`,`category_id`,`photo`,`headline`,`slug`,`content`,`datetime`,`news_read`,`keywords` FROM site_news WHERE  `category_id`=$category_id and `status`= 1 and `datetime`<='$this->today' and `id`!=$post_id ORDER BY `id` DESC LIMIT 3")->cache(60)->queryAll();
        }
        return $this->relatednews;
    }

    public function getSingleNews($news_id)
    {
        if (!$this->singlenews) {
            $this->singlenews = $this->db->createCommand("SELECT `id`, `published_time`,`slug`,`rating_value`,`review_count`,`modified_time`, `category_id`,`photo`,`headline`,`content`,`datetime`,`news_read`,`keywords`,`published_time`,`modified_time` FROM site_news WHERE  `id`=$news_id and `status`=1 ")->cache(60)->queryOne();
        }
        return $this->singlenews;
    }

    public function getNewsGallery($news_id)
    {
        if (!$this->gallery) {
            $this->gallery = $this->db->createCommand("SELECT `id`,`photo` FROM site_gallery WHERE `type`=$this->type and `connect_id`=$news_id ORDER BY `position` ASC")->cache(60)->queryAll();
        }
        return $this->gallery;
    }

    public function setNewsView($view, $news_id)
    {
        $this->db->createCommand("UPDATE `site_news` SET  `news_read`=$view WHERE `id`=$news_id")->query();

    }

    public function getNewsView($news_id)
    {
        if (!$this->view) {
            $this->view = $this->db->createCommand("SELECT `news_read` FROM site_news WHERE `id`=$news_id")->cache(60)->queryOne();
        }
        return $this->view;
    }

    public function getKeywords()
    {
        $data = $this->db->createCommand("SELECT DISTINCT SUBSTRING_INDEX(SUBSTRING_INDEX(site_news.keywords, ',', numbers.n), ' ', -1) as keywords1, count(id) as say FROM (SELECT 1 n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4) numbers INNER JOIN site_news ON CHAR_LENGTH(site_news.keywords) -CHAR_LENGTH(REPLACE(site_news.keywords, ',', ''))>=numbers.n-1 GROUP by keywords1 order by say desc limit 15")->cache(120)->queryAll();

        return $data;
    }

    public function getMostRead()
    {
        $data = $this->db->createCommand("SELECT * FROM `site_news` WHERE `datetime`>=DATE_ADD(CURDATE(),INTERVAL -10 DAY) order by news_read desc limit 10")->cache(120)->queryAll();
        return $data;
    }

    public function getNextNews($id)
    {
        $data = $this->db->createCommand("SELECT * FROM `site_news` where id=(select min(id) from site_news as s2 WHERE s2.id>:id and s2.status= 1)", [':id' => $id])->queryOne();
        return $data;
    }

    public function getPrevNews($id)
    {
        $data = $this->db->createCommand("SELECT * FROM `site_news` where id=(select max(id) from site_news as s2 WHERE s2.id<:id and s2.status= 1) ", [':id' => $id])->queryOne();
        return $data;
    }

    public function getNewsByDoctor($doctor_id, $limit, $offset)
    {
        $this->today = date('Y-m-d H:i:s');
        if (!$this->news) {
            $this->news = $this->db->createCommand("SELECT `id`,`category_id`,`photo`,`headline`,`slug`,`content`,`datetime`,`news_read`,`keywords` FROM site_news WHERE `connect_id`=$doctor_id and `status`= 1 and `datetime`<='$this->today' ORDER BY `id` DESC LIMIT $limit OFFSET $offset")->cache(60)->queryAll();
        }
        return $this->news;
    }

    public function getNewsCountByDoctor($doctor_id)
    {
        $this->today = date('Y-m-d H:i:s');
        if (!$this->count) {
            $this->count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM site_news  WHERE `connect_id`=$doctor_id and `status`= 1 and `datetime`<='$this->today' ")->cache(60)->queryScalar();
        }
        return $this->count;
    }

    //    public function getNewsCount($category_id)
//    {
//        $this->today = date('Y-m-d H:i:s');
//        if (!$this->count) {
//            $this->count = $this->db->createCommand("SELECT count(`id`) as `counter` FROM site_news  WHERE `category_id`=$category_id and `status`= 1 and `datetime`<='$this->today' ")->cache(60)->queryScalar();
//        }
//        return $this->count;
//    }

    //    public function getXeberlerCount($not_in,$id=null, $keyword = null)
//    {
//        $this->today = date('Y-m-d H:i:s');
//        $add = "";
//        if(!empty($keyword)) {
//            $add = " AND (headline LIKE '%$keyword%' or content LIKE '%$keyword%' or keywords LIKE '%$keyword%')";
//        }
//        if (!$this->count_x) {
//            if($id==null)
//            {
//                $this->count_x = $this->db->createCommand("SELECT count(`id`) as `counter` FROM site_news  WHERE `category_id` NOT IN($not_in) and `status`= 1 and `datetime`<='$this->today' ".$add)->cache(120)->queryScalar();
//            }
//            else
//            {
//                $this->count_x = $this->db->createCommand("SELECT count(`id`) as `counter` FROM site_news WHERE `status`= 1 and `datetime`<='$this->today' and `category_id`='$id' ".$add)->cache(120)->queryScalar();
//            }
//        }
//        return $this->count_x;
//    }

    //    public function getNews($category_id, $limit, $offset)
//    {
//        $this->today = date('Y-m-d H:i:s');
//        if (!$this->news) {
//            $this->news = $this->db->createCommand("SELECT `id`,`category_id`,`photo`,`headline`,`slug`,`content`,`datetime`,`news_read`,`keywords` FROM site_news WHERE `category_id`=$category_id and `status`= 1 and `datetime`<='$this->today' ORDER BY `id` DESC LIMIT $limit OFFSET $offset")->cache(60)->queryAll();
//        }
//        return $this->news;
//    }

    //    public function getXeberler($not_in, $limit, $offset, $id = null, $keyword = null)
//    {
//        $this->today = date('Y-m-d H:i:s');
//        $add = "";
//        if(!empty($keyword)) {
//            $add = " AND (headline LIKE '%$keyword%' or content LIKE '%$keyword%' or keywords LIKE '%$keyword%')";
//        }
//        if(!$this->xeberler) {
//            if($id==null) {
//                $this->xeberler = $this->db->createCommand("SELECT `id`,`category_id`,`photo`,`headline`,`slug`,`content`,`datetime`,`news_read`,`keywords` FROM site_news WHERE `category_id` NOT IN($not_in) and `status`= 1 and `datetime`<='$this->today' ".$add." ORDER BY `id` DESC LIMIT $limit OFFSET $offset")->cache(60)->queryAll();
//            } else {
//                $this->xeberler = $this->db->createCommand("SELECT `id`,`category_id`,`photo`,`headline`,`slug`,`content`,`datetime`,`news_read`,`keywords` FROM site_news WHERE `status`= 1 and `datetime`<='$this->today' and `category_id`='$id' ".$add." ORDER BY `id` DESC LIMIT $limit OFFSET $offset")->cache(60)->queryAll();
//            }
//        }
//        return $this->xeberler;
//    }

    //    public function get_next_post($post_id)
    //    {
    //        $data = $this->db->createCommand("SELECT * FROM site_news
    //                              WHERE `id`>:id AND `datetime`<:datetime AND `status`= 1 LIMIT 1", [':datetime' => $this->datetime, ':id' => $post_id])->queryOne();
    //        if (!empty($data))
    //            return $data;
    //        else
    //            return false;
    //    }
    //
    //    public function get_prev_post($post_id)
    //    {
    //        $data = $this->db->createCommand("SELECT * FROM site_news
    //                          WHERE `id`<:id AND `datetime`<:datetime AND `status`= 1 LIMIT 1", [':datetime' => $this->datetime, ':id' => $post_id])->queryOne();
    //        if (!empty($data))
    //            return $data;
    //        else
    //            return false;
    //    }

    //    public function getCategories($type)
    //    {
    //        if (!$this->categories) {
    //            $this->categories = $this->db->createCommand("SELECT * FROM site_menus WHERE `type_id`=$type and `status`=1 ORDER BY menu_order")->cache(120)->queryAll();
    //        }
    //        return $this->categories;
    //    }
}

