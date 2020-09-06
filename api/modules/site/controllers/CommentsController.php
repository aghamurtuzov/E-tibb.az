<?php

namespace api\modules\general\controllers;

use yii;
use api\components\Pagination;
use yii\filters\AccessControl;
use api\modules\general\models\CommentsApiModel;
use api\modules\general\controllers\MainController;

/**
 * Comments API
 */

class CommentsController extends MainController
{

    public $modelClass = '';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Serhler
     * https://e-tibb.az/api/general/comments
     * https://e-tibb.az/api/general/comments?page=1&count=5
     */
    public function actionIndex()
    {
        $model  = new CommentsApiModel();
        $status = CommentsApiModel::getStatus();
        $types  = CommentsApiModel::getTypes();

        $totalCount = $model->CommentsCount();

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $model->Comments($limits);

        if(!empty($list))
        {
            foreach($list as $key => $val)
            {
                $list[$key]['comment']       = strip_tags($list[$key]['comment']);
                $list[$key]['comment_by']    = '';
                $list[$key]['rating']        = $list[$key]['rating'];
                $list[$key]['comment']       = strip_tags($list[$key]['comment']);
                $list[$key]['status_name']   = $status[$list[$key]['status']];
                $list[$key]['type_name']     = $types[$list[$key]['type']];
                if(!empty($val['connect_id']))
                {
                    $commentBy = $model->CommentBy($val['connect_id'],$val['type']);
                    if(!empty($commentBy))
                    {
                        $list[$key]['comment_by'] = $commentBy;
                    }
                }
            }
        }

        $data['list'] = $list;

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages,$data);

        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Serh block
     * https://e-tibb.az/api/general/comments/block
     */
    public function actionBlock()
    {
        $id = intval(Yii::$app->request->post('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $update = CommentsApiModel::ChangeStatus($id,CommentsApiModel::STATUS_DEACTIVE);
        if($update)
        {
            return $this->response(200,"Melumat bloklandı");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Serh aktiv
     * https://e-tibb.az/api/general/comments/active
     */
    public function actionActive()
    {
        $id = intval(Yii::$app->request->post('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $update = CommentsApiModel::ChangeStatus($id,CommentsApiModel::STATUS_ACTIVE);
        if($update)
        {
            return $this->response(200,"Melumat aktiv edildi");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

}