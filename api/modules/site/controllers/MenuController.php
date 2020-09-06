<?php

namespace api\modules\site\controllers;

use yii;
use api\models\SiteMenus;

/**
 * Menu API
 */
class MenuController extends MainController
{

    public $modelClass = '';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Siyahi
     * https://e-tibb.az/api/front/menu/list
     * parentID=5
     * filter=1
     */
    public function actionList()
    {
        $parentID = intval(Yii::$app->request->get('parentID'));
        $filter = intval(Yii::$app->request->get('filter'));
        $where    = !empty($parentID) ? ['parent'=>$parentID,'status'=>1]: ['status'=>1];
        if($filter < 1)
        {
            $AllMenu  = SiteMenus::find()->select('id,parent,menu_order,name,link,keywords,description')->where($where)->all();
        }else{
            $AllMenu  = SiteMenus::find()->select('id,parent,menu_order,name,link,keywords,description')->where($where)->andWhere(['in','id',[4,1,6,5,18,28,34]])->all();
        }
        if(!empty($AllMenu))
        {
            return $this->response(200,"Məlumat mövcuddur",$AllMenu);
        }
        return $this->response(400,"Heçbir məlumat tapılmadı");
    }

    /**
     * Info
     * https://e-tibb.az/api/front/menu/info
     * https://e-tibb.az/api/front/menu/info/2
     */
    public function actionInfo()
    {
        $id = intval(Yii::$app->request->get('id'));
        if(empty($id) && $id<=0)
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }
        $MenuInfo = SiteMenus::find()->select('id,parent,menu_order,name,link,keywords,description,content')->where(['id'=>$id,'status'=>1])->one();
        if(!empty($MenuInfo))
        {
            return $this->response(200,"Məlumat mövcuddur",$MenuInfo);
        }
    }

}