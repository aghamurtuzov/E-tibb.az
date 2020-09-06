<?php

namespace api\modules\general\controllers;

use api\models\AdminLog;
use api\modules\general\models\search\CommentSearch;
use yii;
use api\components\Pagination;
use yii\filters\AccessControl;
use api\modules\general\models\CommentsApiModel;
use api\modules\general\controllers\MainController;
use api\models\SiteDoctors;
use api\models\Comment;

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

        $type = (Yii::$app->request->get('type') == null) ? 'all' : Yii::$app->request->get('type');


        $totalCount = $model->CommentsCount($type);

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $model->Comments($limits, $type);

        if(!empty($list))
        {
            foreach($list as $key => $val)
            {
                $list[$key]['comment'] = strip_tags($list[$key]['comment']);
                $list[$key]['datetime'] = Yii::$app->formatter->asTime($list[$key]['datetime'],'php:d/m/Y');

                $list[$key]['comment_by'] = '';
                $list[$key]['status_name'] = $status[$list[$key]['status']];
                $list[$key]['type_name'] = $types[$list[$key]['type']];
                if (!empty($val['connect_id'])) {
                    $commentBy = $model->CommentBy($val['connect_id']);
                    if (!empty($commentBy)) {
                        $list[$key]['comment_by'] = $commentBy['name'];

                    }
                    $list[$key]['rating'] = $commentBy['rating'];
                }

            }
        }

        $data['list'] = $list;

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages,$data);
        return $this->response(200,"Məlumat mövcuddur",$result);
    }


    /**
     * Serhler
     * https://e-tibb.az/api/general/comments/search
     * https://e-tibb.az/api/general/comments/search?page=1&count=5
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

        if(!empty($update))
        {

            $comment = CommentsApiModel::Comment($id);
            if(!empty($comment))
            {

                if($comment['type'] == CommentsApiModel::TYPE_DOCTOR)
                {
                    $ratings = CommentsApiModel::getRatingDoctor($comment['connect_id']);

                    $r = !empty($ratings) ? round($ratings['sum_rating']/$ratings['count_rating']) : 0;

                    $updateDoctors = SiteDoctors::findOne($comment['connect_id']);
                    if ($updateDoctors){
                        $updateDoctors->rating = $r;
                        $updateDoctors->save(false);
                    }

                }
            }
            return $this->response(200,"Melumat aktiv edildi");
        }

        return $this->response(400,"Əməliyyatın icra olunması zamanı xəta baş verdi");
    }





    // Delete functions
    /**
     * Serh sil
     * https://e-tibb.az/api/general/comments/delete-one
     * method: POST
     * id: integer
     */
    public function actionDeleteOne() {
        $id = Yii::$app->request->post('id');
        if($id) {
            $promotion = CommentsApiModel::CommentFind($id);
            if(!empty($promotion)) {
                CommentsApiModel::CommentDelete($id);
                AdminLog::write(['id' => $promotion['id'], 'name' => $promotion['name']]);

                return $this->response(200, "Məlumat silindi");
            }
        }
        return $this->response(404, "Bu nömrəli Şərh tapilmadı");
    }


    /**
     * Sherh toplu sil
     * https://e-tibb.az/api/general/comments/all-delete
     * method: POST
     * id: array
     */
    public function actionAllDelete() {
        $ids = Yii::$app->request->post('id');
        if($ids && is_array($ids)) {
            foreach($ids as $id) {
                if(is_numeric($id)) {
                    $promotion = CommentsApiModel::CommentFind($id);
                    if(!empty($promotion)) {
                        CommentsApiModel::CommentDelete($id);
                        AdminLog::write(['id' => $promotion['id'], 'name' => $promotion['name']]);
                    }
                }
            }

            return $this->response(200, "Məlumat silindi");
        } else {
            return $this->response(400, "Id sahəsi vacibdir");
        }

        return $this->response(404, "Bu nömrəli Şərh tapilmadı");
    }



    /**
     * Sherh daimi sil
     * https://e-tibb.az/api/general/comments/base-delete-one
     * method: POST
     * id: integer
     */
    public function actionBaseDeleteOne() {
        if(Yii::$app->userCheck->can("superadmin")) {
            $id = Yii::$app->request->post('id');
            if ($id) {
                $promotion = CommentsApiModel::CommentFind($id);
                if (!empty($promotion)) {
                    CommentsApiModel::CommentDeletePermanently($id);
                    AdminLog::write(['id' => $promotion['id'], 'name' => $promotion['name']]);
                    return $this->response(200, "Məlumat silindi");
                }
            }

            return $this->response(404, "Bu nömrəli Şərh tapilmadı");
        } else {
            return $this->response(403, "Bu əməliyyatı icra etmək üçün icazəniz yoxdur");
        }
    }

    /**
     * Sherh daimi sil
     * https://e-tibb.az/api/general/comments/all-base-delete
     * method: POST
     * id: array
     */
    public function actionAllBaseDelete() {
        if(Yii::$app->userCheck->can("superadmin")) {
            $ids = Yii::$app->request->post('id');
            if ($ids && is_array($ids)) {
                foreach ($ids as $id) {
                    if (is_numeric($id)) {
                        $promotion = CommentsApiModel::CommentFind($id);
                        if (!empty($promotion)) {
                            CommentsApiModel::CommentDeletePermanently($id);
                            AdminLog::write(['id' => $promotion['id'], 'name' => $promotion['name']]);
                        }
                    }
                }

                return $this->response(200, "Məlumat silindi");
            } else {
                return $this->response(400, "Id sahəsi vacibdir");
            }



            return $this->response(404, "Bu nömrəli Şərh tapilmadı");
        } else {
            return $this->response(403, "Bu əməliyyatı icra etmək üçün icazəniz yoxdur");
        }
    }

    /**
     * https://e-tibb.az/api/general/comments/comment-rating
     */

//    public function actionCommentRating(){
//        exit();
//        $comments = CommentsApiModel::CommentAll();
//        if(!empty($comments))
//        {
//            foreach ($comments as $comment){
//                if($comment['type'] == CommentsApiModel::TYPE_DOCTOR)
//                {
//                    $ratings = CommentsApiModel::getRatingDoctor($comment['connect_id']);
//
//                    $r = !empty($ratings) ? round($ratings['sum_rating']/$ratings['count_rating']) : 0;
//
//                    $updateDoctors = SiteDoctors::findOne($comment['connect_id']);
//                    if ($updateDoctors){
//                        $updateDoctors->rating = $r;
//                        $updateDoctors->save(false);
//                    }
//
//                }
//            }
//
//        }
//        return 'Success';
//    }

}
