<?php

namespace api\modules\doctor\controllers;

use api\models\AdminDoctorLog;
use yii;
use api\components\Pagination;
use yii\filters\AccessControl;
use api\modules\doctor\models\CommentsApiModel;
use api\modules\doctor\controllers\MainController;
use api\modules\doctor\models\search\CommentSearch;


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
     * https://e-tibb.az/api/doctor/comments
     * https://e-tibb.az/api/doctor/comments?page=1&count=5
     */
    public function actionIndex()
    {
        $model  = new CommentsApiModel();
        $userID = Yii::$app->session->get('userID');
        $status = CommentsApiModel::getStatus();
        $types  = CommentsApiModel::getTypes();
        $totalCount = $model->CommentsByConnectCount($userID, CommentsApiModel::STATUS_ACTIVE);

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $model->CommentsByConnect($limits,$userID, CommentsApiModel::STATUS_ACTIVE);

        if(!empty($list))
        {
            foreach($list as $key => $val)
            {
                $list[$key]['comment_by']    = '';
                $list[$key]['comment']       = strip_tags($list[$key]['comment']);
                $list[$key]['status_name']   = $status[$list[$key]['status']];
                $list[$key]['type_name']     = $types[$list[$key]['type']];
                $list[$key]['datetime'] = Yii::$app->formatter->asTime($list[$key]['datetime'],'php: d M Y');
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
     * https://e-tibb.az/api/doctor/comments/block
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
            $data = CommentsApiModel::CommentFind($id);
            AdminDoctorLog::write(['id' => $id, 'name' => $data['name']]);
            return $this->response(200,"Melumat bloklandı");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }

    /**
     * Serh aktiv
     * https://e-tibb.az/api/doctor/comments/active
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
            $data = CommentsApiModel::CommentFind($id);
            AdminDoctorLog::write(['id' => $id, 'name' => $data['name']]);
            return $this->response(200,"Melumat aktiv edildi");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }


    /**
     * Serhler
     * https://e-tibb.az/api/doctor/comments/search
     * https://e-tibb.az/api/doctor/comments/search?page=1&count=5
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
        $userID = Yii::$app->session->get('userID');

        if ($model->load($search, '')){

            $model->validate();
            if (!empty($model->errors)) {
                return $this->response(400, 'Məlumatın axtarılması zamanı xəta baş verdi', $model->errors);
            }

            $totalCount = $model->searchCount($search, $userID, CommentsApiModel::STATUS_ACTIVE);

            if($totalCount['count'] <= 0)
            {
                return $this->response(400,"Heçbir məlumat tapılmadı");
            }

            $pagination = new Pagination(['totalCount' => $totalCount]);

            $limits = $pagination->getLimits();

            $result = $model->search($search,$limits, $userID, CommentsApiModel::STATUS_ACTIVE);

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