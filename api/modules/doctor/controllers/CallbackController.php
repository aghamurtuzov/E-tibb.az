<?php

namespace api\modules\doctor\controllers;

use frontend\models\User;
use Yii;
use api\controllers\MilliCardController;
use api\models\SiteTransaction;
use api\models\SiteTransactionDetails;
use yii\web\NotFoundHttpException;
use api\models\SiteDoctorsModel;
use api\models\EnterpriseModel;
use api\models\SiteServicesMember;
use api\modules\doctor\controllers\MainController;

class CallbackController extends MainController
{

    private $test_url = "http://test.millikart.az:8513/gateway/payment/status?";
    private $pro_url  = "https://pay.millikart.az/gateway/payment/status?";
    public $modelClass = '';

    public function actionIndex()
    {
        $order_id = Yii::$app->request->get('reference');
        $current_datetime = date("Y-m-d H:i:s");
        if(!empty($order_id))
        {

            $type_user   = SiteTransactionDetails::TYPE_USER;
            $type_doctor = SiteTransactionDetails::TYPE_DOCTOR;
            $type_enterprise = SiteTransactionDetails::TYPE_ENTERPRISE;

            $mid          = Yii::$app->params['payment.mid'];
            $status       = Yii::$app->params['payment.production'];
            $paymentTypes = Yii::$app->params['payment.type'];

            $api  = "mid={$mid}&reference={$order_id}";
            $url  = $status ? $this->pro_url.$api : $this->test_url.$api;
            $xml  = file_get_contents(str_replace(' ', '_', $url));
            $xml  = simplexml_load_string($xml);
            $data = (array)$xml;
            //print_r($data); exit();
            /** Status:Ok */
            if($data['code'] == 0)
            {
                $vip = false;
                $registerRow = [];

                /** get Transaction Details */
                $transactionDetails = SiteTransactionDetails::find()->where(['order_id'=>$order_id])->all();
                if(!empty($transactionDetails))
                {
                    foreach($transactionDetails as $key => $val)
                    {
                        $val->status = 1;
                        $val->payment_date = $current_datetime;
                        $val->save();

                        if(!isset($paymentTypes[$val->payment_type]))
                        {

                            $type = $type_user;

                            /** Doctor type */
                            if($val->type == $type_doctor)
                            {
                                if($val->payment_type == 1)
                                {
                                    $vip = $val['quantity'];
                                }
                                $type = $type_doctor;
                            }

                            /** Enterprise type */
                            if($val->type == $type_enterprise)
                            {
                                if($val->payment_type == 2)
                                {
                                    $vip = $val['quantity'];
                                }
                                $type = $type_enterprise;
                            }

                            /** Services Member */
                            $getData = SiteServicesMember::find()->where(['connect_id'=>$val->connect_id,'service_id'=>$val->payment_info,'type' => $type])->one();
                            if(!empty($getData))
                            {
                                $getData->order_id = $order_id;
                                $getData->update_payment = $getData->update_payment+1;
                                $getData->payment_date = $current_datetime;
                                $getData->expires_date = date("Y-m-d H:i:s", strtotime("+{$val['quantity']} month",strtotime($getData->expires_date)));
                                $getData->save();
                            }
                            else
                            {
                                $expires = date("Y-m-d H:i:s", strtotime("+{$val['quantity']} month",strtotime($current_datetime)));
                                $servicesMember = new SiteServicesMember();
                                $servicesMember->order_id = $order_id;
                                $servicesMember->service_id = $val->payment_info;
                                $servicesMember->connect_id = $val->connect_id;
                                $servicesMember->type = $type;
                                $servicesMember->payment_date = $current_datetime;
                                $servicesMember->expires_date = $expires;
                                $servicesMember->save();
                            }

                        }
                        else
                        {
                            $registerRow = $val;
                        }
                    }

                    /** get Transaction */
                    $transaction = SiteTransaction::find()->where(['order_id'=>$order_id])->one();
                    if(!empty($transaction))
                    {
                        if($transaction->order_type == 0) $transaction->order_type = 0;
                        else $transaction->order_type = 1;
                        $transaction->status = 1;
                        $transaction->payment_date = $current_datetime;
                        $transaction->return_data = json_encode($data);
                        $transaction->save();

                        /** Doctor || Enterprise */
                        if($transaction->type == $type_doctor || $transaction->type == $type_enterprise)
                        {

                            if($transaction->type == $type_doctor)
                            {
                                /** Doctor */
                                $getData = SiteDoctorsModel::getDoctorInfo($transaction->connect_id);
                            }else{
                                /** Enterprise */
                                $getData = EnterpriseModel::getEnterprise($transaction->connect_id);
                            }

                            if($getData)
                            {
                                /** User update */
                                if($getData['user_id'])
                                {
                                    $updateUser = User::find()->where(['id'=>$getData['user_id']])->one();
                                    $updateUser->status = 1;
                                    $updateUser->save(false);
                                }

                                /** Doctor update */
                                if(isset($registerRow) && !empty($registerRow))
                                {
                                    $addSql  = null;

                                    $month   = intval($registerRow->quantity);
                                    $expires_date = !empty($getData['expires']) ? $getData['expires'] : $current_datetime;
                                    $expires = date("Y-m-d", strtotime("+{$month} month +1 day",strtotime($expires_date)));

                                    if($vip)
                                    {
                                        $vip_date = !empty($getData['vip_expires']) ? $getData['vip_expires'] : $current_datetime;
                                        $vip_expires = date("Y-m-d", strtotime("+{$vip} month +1 day",strtotime($vip_date)));
                                        $addSql  = "`vip_expires`=\"{$vip_expires}\",";
                                    }

                                    if($transaction->type == $type_doctor)
                                    {
                                        /** Doctor */
                                        $updateData = Yii::$app->db->createCommand("UPDATE `site_doctors` SET {$addSql}`expires`=\"{$expires}\",`update_expires`=update_expires+1,`status`=1 WHERE `id`={$transaction->connect_id}")->execute();
                                    }else{
                                        /** Enterprise */
                                        $updateData = Yii::$app->db->createCommand("UPDATE `site_enterprises` SET {$addSql}`expires`=\"{$expires}\",`update_expires`=update_expires+1,`status`=1 WHERE `id`={$transaction->connect_id}")->execute();
                                    }

                                    if(!empty($updateData))
                                    {
                                        return $this->response(200,"Siz uğurla qeydiyyatdan keçdiniz!");
                                        Yii::$app->session->setFlash('success','Siz uğurla qeydiyyatdan keçdiniz!');
                                        return $this->redirect(['/profil']);
                                    }
                                }
                            }
                        }
                    }
                }
                else
                {
                    return $this->response(200,"Heçbir məlumat tapılmadı");
                    throw new NotFoundHttpException(Yii::t('app',"Paketin alınması zamanı xəta baş verdi."));
                }

            }
            else
            {
                return $this->response(200,"Heçbir məlumat tapılmadı");
                return $this->showError();
            }
        }
        else
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
            return $this->redirect(Yii::$app->params['site.url']);
        }
    }
}
