<?php

namespace api\components;

use yii;
use yii\base\BaseObject;

/**
 *
 * PAGINATION CLASS
 *
 * public function actionIndex()
 * {
 *     $totalCount = yii::$app->db->createCommand('SELECT COUNT(`id`) as count FROM categories LIMIT 1')->queryOne();
 *
 *     $pagination = new Pagination(['totalCount' => $totalCount]);
 *
 *     $limits = $pagination->getLimits();
 *
 *     $data['list'] = Yii::$app->db->createCommand("SELECT `id`,`name` FROM categories LIMIT {$limits[0]},{$limits[1]}")->queryAll();
 *
 *     $pages = $pagination->getPaginationInfo();
 *
 *     $result = array_merge($pages,$data);
 *
 *     return $result;
 * }
 *
 * URL PARAMS
 *
 * ?page=1&count=5
 * page  | Hansi sehifede olduqumuzu bildirir
 * count | Bir sehifedeki melumat sayini bildirir
 *
*/
class Pagination extends BaseObject
{

    private $totalCount;

    private $currentPage = 1;

    private $pageSize = 20;

    private $pageCount;

    public function __construct($config = [])
    {
        if(isset($config['totalCount']['count']))
        {
            $this->setConfig($config);
            return $this;
        }
    }

    private function setConfig($config)
    {

        $this->totalCount = $config['totalCount']['count'];

        $page = yii::$app->request->get('page');

        if(!empty($page) && $page>=1)
        {
            $this->currentPage = yii::$app->request->get('page');
        }

        $count = yii::$app->request->get('count');

        if(!empty($count) && $count>=1)
        {
            $this->pageSize = $count;
        }

        $this->pageCount = ceil($this->totalCount/$this->pageSize);

        if($this->currentPage>$this->pageCount || $this->currentPage<1)
        {
            $this->currentPage = 1;
        }

    }

    public function getLimits()
    {

        $limits[0] = $this->currentPage * $this->pageSize - $this->pageSize;

        $limits[1] = $this->pageSize;

        return $limits;

    }

    public function getPaginationInfo()
    {

        $data['pagination']['totalCount'] = (int) $this->totalCount;

        $data['pagination']['perPage'] = (int) $this->pageSize;

        $data['pagination']['currentPage'] = (int) $this->currentPage;

        $data['pagination']['pageCount'] = (int) $this->pageCount;

        return $data;

    }

}