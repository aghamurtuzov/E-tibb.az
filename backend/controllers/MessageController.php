<?php

namespace backend\controllers;

use backend\components\Functions;
use backend\models\Email;
use backend\models\SitePromotions;
use backend\models\SiteUsers;
use backend\models\Sms;
use backend\models\UserPromoCodes;
use Yii;
use yii\web\Controller;
use backend\controllers\MainController;

class MessageController extends MainController
{
    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionSendsms()
    {
        if (Yii::$app->request->isAjax) {
            $model = new Sms();
            if ($model->load(Yii::$app->request->post())) {
                $model->phone = isset($model['phone']) ? '+994' . ltrim(trim($model['phone']), '0') : null;
                if ($model->save(false)) {
                    Yii::$app->session->setFlash("successS", "Sms göndəriləcəkdir!");
                    return $this->asJson(['data' => 'success']);
                } else {
                    return $this->asJson(['data' => 'error']);
                }
            }
        }
    }

    public function actionSendemail()
    {
        if (Yii::$app->request->isAjax) {
            $model = new Email();
            if ($model->load(Yii::$app->request->post())) {
                if ($model->save(false)) {
                    Yii::$app->session->setFlash("successE", "Email göndəriləcəkdir!");
                    return $this->asJson(['data' => 'success']);
                } else {
                    return $this->asJson(['data' => 'error']);
                }
            }
        }
    }


    public function actionSendAllSms()
    {
        $data = Sms::Find()->where(['status' => 0])->limit(100)->all();
        $error = 0;
        if (!empty($data)) {
            foreach ($data as $item) {

                $smsBody = urlencode($item['text']);
                $sendSms = "gw.maradit.net/api/xml/reply/submit?Credential={Username:appa1,Password:347h3i4}&Header={From:E-tibb.az}&Message={$smsBody}&To=[{$item['phone']}]&DataCoding=Default";
                $sendSms = Functions::SendRequestWithCurl($sendSms);

                $model = $this->findModel(new Sms, $item['id']);
                if (!empty($model)) {
                    $model->status = 1;
                    $model->save(false);
                } else {
                    $error++;
                }
            }
        } else {
            $error++;
        }
        if ($error == 0)
            return True;
        else
            return False;
    }

    public function actionSendAllEmail()
    {
        $data = Email::Find()->where(['status' => 0])->limit(100)->all();
        $error = 0;
        if (!empty($data)) {
            foreach ($data as $item) {
                $model = $this->findModel(new Email(), $item['id']);
                if (!empty($model)) {
                    $model->status = 1;
                    $model->save(false);
                } else {
                    $error++;
                }
            }
        } else {
            $error++;
        }
        if ($error == 0)
            return True;
        else
            return False;
    }


    public function actionPromotionNotification()
    {
        $promotion = SitePromotions::Find()->where(['notification_status' => 0])->one();
        if (!empty($promotion)) {
            $users = SiteUsers::Find()->all();
            $this->PromotionSmsEmailToAll($promotion, $users);
//            $this->PromotionEmail($promotion, $users);

            $promotion->notification_status = 1;
            $promotion->save();
        }
    }

//    private function generatePromoCode($id){
//        $promo_code = Functions::userPromoCodeGenerator($id);
//        UserPromoCodes::insertPromoCode();
//    }

    private function PromotionSmsEmailToAll($promotion, $users)
    {
        foreach ($users as $user) {
            $code = UserPromoCodes::insertPromoCode($promotion,$user->id);
            $msg=($code != '' ? ' sizin promo kod '.$code : '');
            $sms = new Sms();
            $sms->user_id = $user->id;
            $sms->phone = $user->phone_number;
            $sms->text = $promotion['headline'] . ' aksiya elave olunmushdur.'.$msg;
            $sms->save();

            $sms = new Email();
            $sms->user_id = $user->id;
            $sms->email = $user->email;
            $sms->text = $promotion['headline'] . ' aksiya elave olunmushdur.'.$msg;
            $sms->save();
        }
    }
//    private function PromotionEmail($data, $users)
//    {
//        foreach ($users as $user) {
//            $sms = new Email();
//            $sms->user_id = $user->id;
//            $sms->email = $user->email;
//            $sms->text = $data['headline'] . ' aksiya elave olunmushdur.';
//            $sms->save();
//        }
//    }
    protected function findModel($modelName, $id)
    {
        if (($model = $modelName::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
