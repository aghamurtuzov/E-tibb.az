<?php

namespace api\modules\enterprise\controllers;

use api\modules\enterprise\models\search\CommentSearch;
use yii;
use api\components\Pagination;
use yii\filters\AccessControl;
use api\modules\enterprise\models\CommentsApiModel;
use api\modules\enterprise\controllers\MainController;

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
     * https://e-tibb.az/api/enterprise/comments
     * https://e-tibb.az/api/enterprise/comments?page=1&count=5
     */
    public function actionIndex()
    {
        $model  = new CommentsApiModel();
        $userID = Yii::$app->session->get('userID');
        $status = CommentsApiModel::getStatus();
        $types  = CommentsApiModel::getTypes();

        $totalCount = $model->CommentsByConnectCount($userID);

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $model->CommentsByConnect($limits,$userID);

        if(!empty($list))
        {
            foreach($list as $key => $val)
            {
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
     * https://e-tibb.az/api/enterprise/comments/block
     */
    public function actionBlock()
    {
        $id = intval(Yii::$app->request->post('id'));
        $userID = Yii::$app->session->get('userID');
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $update = CommentsApiModel::ChangeConnectStatus($id,CommentsApiModel::STATUS_DEACTIVE,$userID);
        if($update)
        {
            return $this->response(200,"Melumat bloklandı");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Serh aktiv
     * https://e-tibb.az/api/enterprise/comments/active
     */
    public function actionActive()
    {
        $id = intval(Yii::$app->request->post('id'));
        $userID = Yii::$app->session->get('userID');
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $update = CommentsApiModel::ChangeConnectStatus($id,CommentsApiModel::STATUS_ACTIVE,$userID);
        if($update)
        {
            return $this->response(200,"Melumat aktiv edildi");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Serhler
     * https://e-tibb.az/api/enterprise/comments/search
     * https://e-tibb.az/api/enterprise/comments/search?page=1&count=5
     * doctor_name = agha
     * datetime = 01.01.2020
     * status = 1
     */
    public function actionSearch()
    {

        Yii::$app->db->schema->refresh();
        $model = new CommentSearch();
        $status = CommentsApiModel::getStatus();
        $types  = CommentsApiModel::getTypes();
        $search = Yii::$app->request->get();

        if ($model->load($search, '')){

            $model->validate();
            if (!empty($model->errors)) {
                return $this->response(400, 'Məlumatın axtarılması zamanı xəta baş verdi', $model->errors);
            }

            $totalCount = $model->searchCount($search);

            if($totalCount['count'] <= 0)
            {
                return $this->response(400,"Heçbir məlumat tapılmadı");
            }

            $pagination = new Pagination(['totalCount' => $totalCount]);

            $limits = $pagination->getLimits();

            $result = $model->search($search,$limits);

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['comment_by'] = $result[$key]['doctor_name'];
                    $result[$key]['status_name'] = $status[$result[$key]['status']];
                    $result[$key]['datetime'] = Yii::$app->formatter->asTime($result[$key]['datetime'], 'php: d/m/Y H:i');
                }
            }
            $data['list'] = $result;

            $pages = $pagination->getPaginationInfo();

            $result = array_merge($pages,$data);

            return $this->response(200, "Məlumat mövcuddur", $result);
        }

    }

}
