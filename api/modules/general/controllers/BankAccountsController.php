<?php

namespace api\modules\general\controllers;

use yii;
//use api\models\SiteTransactionDetails;
use api\components\Pagination;
use api\components\Functions;
use yii\filters\AccessControl;
//use api\models\PackagesServicesModel;
//use api\modules\general\models\GeneralApiModel;
use api\models\BankAccounts;
use api\models\BankAccountsApiModel;
use api\modules\general\controllers\MainController;

/**
 * Bank accounts API
 */

class BankAccountsController extends MainController
{

    public $modelClass = '';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Hesablar
     * https://e-tibb.az/api/general/bank-accounts
     */
    public function actionIndex()
    {
        $model  = new BankAccountsApiModel();
        $status = BankAccounts::get_Status();
        $type   = BankAccounts::get_types();

        $list = $model->Accounts();

        if(!empty($list))
        {
            foreach($list as $key => $val)
            {
                $list[$key]['type_name'] = $type[$list[$key]['type']];
                $list[$key]['status_name'] = $status[$list[$key]['status']];
            }
        }

        $data['list'] = $list;

        return $this->response(200,"Məlumat mövcuddur",$data);
    }

    /**
     * Hesab yaratmaq
     * https://e-tibb.az/api/general/bank-accounts/list-type
     *
     */
    public function actionListType()
    {
        $result = [
            ['type'=>1,'name'=>'Visa'],
            ['type'=>2,'name'=>'Master']
        ];
        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Hesab yaratmaq
     * https://e-tibb.az/api/general/bank-accounts/create
        bank:Unibank
        card_number:1234-5678-9101
        type:1
        status:1
        balance: 100
     */
    public function actionCreate()
    {
        $model = new BankAccounts();
        $post = Yii::$app->request->post();

        if(!empty($post) && $model->load($post,''))
        {

            $model->validate();

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
     * Hesab info
     * https://e-tibb.az/api/general/bank-accounts/info/1
     */
    public function actionInfo()
    {
        $id = intval(Yii::$app->request->get('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        /** Slider info */
        $info = BankAccounts::find(['id' => $id])->asArray()->one();
        if(empty($info))
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $info = $this->ResultList($info,'slider');

        unset($info['thumb']);

        return $this->response(200,"Məlumat mövcuddur",$info);

    }

    /**
     * Hesab yaratmaq
     * https://e-tibb.az/api/general/bank-accounts/edit
        bank:Unibank
        card_number:1234-5678-9101
        type:1
        status:1
    */
    public function actionEdit()
    {
        $id = intval(Yii::$app->request->post('id'));
        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $model    = $this->findModel($id);
        $post     = Yii::$app->request->post();

        if(!empty($post) && $model->load($post,''))
        {
            $model->validate();

            if(!empty($model->errors))
            {
                return $this->response(400,'Məlumatın yenilənməsi zamanı xəta baş verdi',$model->errors);
            }

            if($model->save(false))
            {
                return $this->response(200,'Məlumat uğurla yeniləndi');
            }
        }

        return $this->response(400,'Məlumatın yenilənməsi zamanı xəta baş verdi');
    }

    /**
     * https://e-tibb.az/api/general/bank-accounts/del
     * ids array
    */
    public function actionDel()
    {
        $ids = Yii::$app->request->post('ids');
        if(empty($ids))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        foreach($ids as $key => $id)
        {
            $del = BankAccounts::findOne($id);
            if(empty($del))
            {
                return $this->response(400,"Məlumatın silinməsi zamanı xəta baş verdi");
            }else{
                $del->delete();
            }
        }
        return $this->response(200,"Melumat(lar) silindi");
    }

    protected function findModel($id)
    {
        if(($model = BankAccounts::findOne($id)) !== null){
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

}