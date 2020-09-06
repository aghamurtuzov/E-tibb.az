<?php

namespace api\modules\general\controllers;

use yii;
//use api\models\SiteTransactionDetails;
use api\components\Pagination;
use api\components\Functions;
use yii\filters\AccessControl;
//use api\models\PackagesServicesModel;
//use api\modules\general\models\GeneralApiModel;
use api\models\BankOperations;
use api\models\BankOperationsApiModel;
use api\models\BankAccounts;
use api\modules\general\controllers\MainController;

/**
 * Bank accounts API
 */

class OperationsController extends MainController
{

    public $modelClass = '';
    public $customPath = '';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Emeliyyatlar
     * https://e-tibb.az/api/general/operations
     * https://e-tibb.az/api/general/operations/cart-payments?page=1&count=5
     */
    public function actionIndex()
    {

        $model = new BankOperationsApiModel();
        $action = BankOperations::get_Actions();

        $totalCount = $model->OperationsCount();

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $model->Operations($limits);

        if(!empty($list))
        {
            foreach($list as $key => $val)
            {
                $list[$key]['action_name'] = $action[$list[$key]['action']];
            }
        }

        $data['list'] = $this->ResultList($list);

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages,$data);

        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Emeliyyatlarin novu
     * https://e-tibb.az/api/general/operations/list-actions
     *
     */
    public function actionListActions()
    {
        $result = [
            ['value'=>1,'name'=>'Mədaxil'],
            ['value'=>2,'name'=>'Məxaric']
        ];
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Emeliyyat yaratmaq
     * https://e-tibb.az/api/general/operations/create
        account_id: 1 // Bank hesabinin id-si
        reason: texniki xercler
        price: 200
        action: 1 | mədaxil ve ya məxaric
     */
    public function actionCreate()
    {
        Yii::$app->db->schema->refresh();

        $model = new BankOperations();
        $post = Yii::$app->request->post();

        if(!empty($post) && $model->load($post,''))
        {

            $model->datetime = date('Y-m-d H:i:s');

            $model->validate();

            if(!empty($model->account_id))
            {
                $BankAccount = BankAccounts::findOne($model->account_id);
                if(!empty($BankAccount))
                {
                    if($model->action == 1)
                    {
                        $BankAccount->balance = $BankAccount->balance+$model->price;
                        $BankAccount->save();
                    }else{
                        if($BankAccount->balance>0 && ($BankAccount->balance-$model->price)>0)
                        {
                            $BankAccount->balance = $BankAccount->balance-$model->price;
                            $BankAccount->save();
                        }else{
                            $model->addError('price','Bank kartınızda kifayət qədər məbləğ yoxdur');
                        }
                    }
                }
            }

            if(!empty($model->errors))
            {
                return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi',$model->errors);
            }

            if($model->save())
            {
                return $this->response(200,'Məlumat uğurla əlavə olundu');
            }

        }

        return $this->response(400,'Məlumatın əlavə olunması zamanı xəta baş verdi');
    }

    /**
     * Emeliyyat info
     * https://e-tibb.az/api/general/operations/info/1
     */
    public function actionInfo()
    {
        $id = intval(Yii::$app->request->get('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $info = BankOperations::find()->where(['id' => $id])->asArray()->one();
        if(empty($info))
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        return $this->response(200,"Məlumat mövcuddur",$info);

    }

    /**
     * Emeliyyat duzelis et
     * https://e-tibb.az/api/general/operations/edit
        account_id: 1 // Bank hesabinin id-si
        reason: texniki xercler
        price: 200
        action: 1 | mədaxil ve ya məxaric
    */
//    public function actionEdit()
//    {
//        $id = intval(Yii::$app->request->post('id'));
//        if(empty($id))
//        {
//            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
//        }
//
//        $model    = $this->findModel($id);
//        $post     = Yii::$app->request->post();
//
//        if(!empty($post) && !empty($post) && $model->load($post,''))
//        {
//            $model->validate();
//
//            if(!empty($model->errors))
//            {
//                return $this->response(400,'Məlumatın yenilənməsi zamanı xəta baş verdi',$model->errors);
//            }
//
//            if($model->save(false))
//            {
//                return $this->response(200,'Məlumat uğurla yeniləndi');
//            }
//        }
//
//        return $this->response(400,'Məlumatın yenilənməsi zamanı xəta baş verdi');
//    }

    /**
     * https://e-tibb.az/api/general/operations/del
     * ids array
    */
//    public function actionDel()
//    {
//        $ids = Yii::$app->request->post('ids');
//        if(empty($ids))
//        {
//            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
//        }
//
//        foreach($ids as $key => $id)
//        {
//            $del = BankOperations::findOne($id);
//            if(empty($del))
//            {
//                return $this->response(400,"Məlumatın silinməsi zamanı xəta baş verdi");
//            }else{
//                $del->delete();
//            }
//        }
//        return $this->response(200,"Melumat(lar) silindi");
//    }

//    protected function findModel($id)
//    {
//        if(($model = BankOperations::findOne($id)) !== null){
//            return $model;
//        }
//        throw new NotFoundHttpException('The requested page does not exist.');
//    }

}