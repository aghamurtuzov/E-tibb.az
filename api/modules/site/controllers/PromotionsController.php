<?php

namespace api\modules\site\controllers;

use yii;
use api\components\Pagination;
use api\models\SitePromotions;
use api\modules\site\models\PromotionsApiModel;

/**
 * Promotions API
 */
class PromotionsController extends MainController
{

    public $modelClass = '';
    public $customPath = 'promotions';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Aksiyalar
     * https://e-tibb.az/api/front/promotions
     * connectID=1
     * type=1 ( 1 - Doctor | 2 - Enterprise )
     * page=1
     * count=5
     * search=soz
     */
    public function actionIndex()
    {
        $id     = intval(Yii::$app->request->get('connectID'));
        $type   = intval(Yii::$app->request->get('type'));
        $type   = empty($type) ? 1 : $type;

        $search = null;
        if(isset($_GET['search']) && !empty($_GET['search']))
        {
            if(strlen($_GET['search'])>=3)
                $search = strip_tags(Yii::$app->request->get('search'));
            else
                return $this->response(200,"Axtarış üçün minimum 3 simvol daxil edin.");
        }

        $model = new PromotionsApiModel();

        $totalCount = !empty($id) ? $model->PromotionsListCount($id,$type,$search) : $model->PromotionsCount($search);

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = !empty($id) ? $model->PromotionsList($id,$limits,$type,$search) : $model->Promotions($limits,$search);

        if(!empty($list))
        {
            foreach($list as $key => $val)
            {
                $list[$key]['byPromotion'] = $list[$key]['organizer'];
                if(!empty($val['connect_id']))
                {
                    $organizer = $model->getOrganizer($val['connect_id'],$val['type']);
                    if(!empty($organizer))
                    {
                        $list[$key]['byPromotion'] = $organizer;
                    }
                }
            }
        }

        $data['list'] = $this->ResultList($list,'promotions','LinkPromotion');

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages,$data);

        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Info
     * https://e-tibb.az/api/front/promotions/info/2
     */
    public function actionInfo()
    {
        $id = intval(Yii::$app->request->get('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $model  = new PromotionsApiModel();

        /** Aksiya info */
        $promotion = $model->Promotion($id);
        if(empty($promotion))
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $promotion['byPromotion'] = $promotion['organizer'];
        $promotion['discount']    = empty($promotion['discount']) ? '' : $promotion['discount'];
        if(!empty($promotion['connect_id']))
        {
            $organizer = $model->getOrganizer($promotion['connect_id'],$promotion['type']);
            if(!empty($organizer))
            {
                $promotion['byPromotion'] = $organizer;
            }
        }

        $promotion = $this->ResultList($promotion,$this->customPath,'LinkPromotion');

        return $this->response(200,"Məlumat mövcuddur",$promotion);

    }

}