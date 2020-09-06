<?php

namespace api\modules\site\controllers;

use yii;
use api\components\Pagination;
use api\modules\site\models\NewsApiModel;


/**
 * News API
 */
class NewsController extends MainController
{

    public $modelClass = '';
    public $customPath = 'news';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Xeber
     * https://e-tibb.az/api/front/news/limited
     * categoryID=1
     * limit=10
     */
    public function actionLimited()
    {
        $categoryID = intval(Yii::$app->request->get('categoryID'));
        $link = intval(Yii::$app->request->get('limit'));

        $list = NewsApiModel::LimitedNews($link,$categoryID);

        if(empty($list))
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $data['list'] = $this->ResultList($list,$this->customPath,'LinkNews');

        return $this->response(200,"Məlumat mövcuddur",$data);
    }

    /**
     * Xeber
     * https://e-tibb.az/api/front/news
     * categoryID=1
     * page=1
     * count=5
     * search=soz
     */
    public function actionIndex()
    {
        $id = intval(Yii::$app->request->get('categoryID'));

        $search = null;
        if(isset($_GET['search']) && !empty($_GET['search']))
        {
            if(strlen($_GET['search'])>=3)
                $search = strip_tags(Yii::$app->request->get('search'));
            else
                return $this->response(200,"Axtarış üçün minimum 3 simvol daxil edin.");
        }

        $totalCount = !empty($id) ? NewsApiModel::NewsByIdCount($id,$search) : NewsApiModel::NewsCount($search);

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = !empty($id) ? NewsApiModel::NewsByIdList($id,$limits,$search) : NewsApiModel::NewsList($limits,$search);

        $data['list'] = $this->ResultList($list,$this->customPath,'LinkNews');

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages,$data);

        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Info
     * https://e-tibb.az/api/front/news/info/2
     */
    public function actionInfo()
    {
        $id = intval(Yii::$app->request->get('id'));
        if(empty($id) && $id<=0)
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }
        $data = NewsApiModel::News($id);
        if(!empty($data))
        {
            $data = $this->ResultList($data,$this->customPath);
            return $this->response(200,"Məlumat mövcuddur",$data);
        }
    }

}