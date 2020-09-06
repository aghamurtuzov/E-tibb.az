<?php

namespace api\modules\site\controllers;

use yii;
use api\models\SiteSpecialists;

/**
 * Specialists API
 */
class SpecialistsController extends MainController
{

    public $modelClass = '';
    public $customPath = 'user';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Mutexessis kateqoriyalari | Siyahi
     * https://e-tibb.az/api/front/specialists/list
     */
    public function actionList()
    {
        $specialists = SiteSpecialists::find()->select('id,name,slug,icon')->all();
        if(!empty($specialists))
        {
            $data['list'] = $this->ResultList($specialists,'specialists');
            return $this->response(200,"Məlumat mövcuddur",$data);
        }
        return $this->response(400,"Heçbir məlumat tapılmadı");
    }
}