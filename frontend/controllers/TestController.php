<?php

namespace frontend\controllers;

use Yii;
use frontend\models\MainModel;
use frontend\models\User;
use frontend\models\User2;
use backend\components\Functions;
use frontend\controllers\MilliCardController;

class TestController extends MainController
{

    public function actionTest()
    {
        return 'OK';
        //return $this->render('index');
    }

    public function actionPayment()
    {
        $id = 91;
        $payment = "1";
        $reference = $id;
        $description = 'acixlama';

        $getPayment = new MilliCardController($payment,$reference,$description);
        $getPayment->pay();
    }

    public function actionDoc()
    {

        exit();

        /*
        $db = Yii::$app->db;
        $datas = $db->createCommand("SELECT * FROM `site_users` where `type`=1")->queryAll();
        $a = [];
        $b = [];

        if($datas)
        {
            foreach($datas as $key => $val)
            {
                //unset($val['about']);
                $a[$val['name']][] = $val;
            }
        }

        if($a)
        {
            foreach($a as $key => $val)
            {
                $count = count($val);
                if($count>1)
                {
                    foreach($val as $k => $v)
                    {
                        $delete = $db->createCommand()->delete('site_users', 'id = '.$v['id'])->execute();
                        $update = $db->createCommand('UPDATE site_doctors SET user_id=0 WHERE `name`="'.$v['name'].'"')->execute();
                        $b[$v['name']][] = 1;

                    }
                }
            }
        }
        */

        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT * FROM `site_doctors` where `user_id`=0 limit 20")->queryAll();

        if(!empty($result))
        {
            foreach($result as $key => $val)
            {

                $numberData = $db->createCommand("SELECT * FROM `site_phone_numbers` where `connect_id`={$val['id']} and `type`=1 and number_type=1")->queryOne();

                $number = isset($numberData['number']) ? '+994'.ltrim(trim($numberData['number']),'0') : null;
                $email  = isset($val['email']) ? trim($val['email']) : null;
                $pass   = 'doctor'.$val['id'].rand(100,300);

                if(!empty($number))
                {
                    if(!empty($email))
                    {
                        /** Save user */
                        $model               = new User();
                        $model->name         = $val['name'];
                        $model->email        = $val['email'];
                        $model->setPassword($pass);
                        $model->phone_number = $number;
                        $model->generateAuthKey();
                        $model->last_login   = Yii::$app->params['current.date'].' '.Yii::$app->params['current.time'];
                        $model->type         = 1;
                        $model->status       = 1;

                        if($model->save(false))
                        {

                            /** Update doctor */
                            $update = $db->createCommand("UPDATE site_doctors SET user_id={$model->id} WHERE id=".$val['id'])->execute();

                            if($update)
                            {

                                /** Make message */
                                $msg = "E-tibb.az saytına daxil olub istifadəçilərin suallarını cavablandıra bilərsiniz.\n";
                                $msg .= "E-mail: {$email}\n";
                                $msg .= "Şifrə: {$pass}\n";

                                echo $msg.'<br>';

                                $emailBody = str_replace("\n","<br>",$msg);

                                /** Send sms */
                                $smsBody = urlencode($msg);
                                $sendSms = "gw.maradit.net/api/xml/reply/submit?Credential={Username:appa1,Password:347h3i4}&Header={From:E-tibb.az}&Message={$smsBody}&To=[{$number}]&DataCoding=Default";
                                $sendSms = Functions::SendRequestWithCurl($sendSms);

                                /*
                                if($sendSms)
                                {
                                    echo '<br>Sms göndərildi<br>';
                                }else{
                                    echo '<br>Sms GÖNDƏRİLMƏDİ<br>';
                                }
                                */

                                /** Send e-mail */
                                Yii::$app->mailer->compose()
                                    ->setTo($email)
                                    ->setFrom('no-reply@e-tibb.az')
                                    ->setSubject('www.e-tibb.az')
                                    ->setHtmlBody($emailBody)
                                    ->send();

                                Yii::$app->mailer->compose()
                                    ->setTo(Yii::$app->params['admin.email'])
                                    ->setFrom('no-reply@e-tibb.az')
                                    ->setSubject('www.e-tibb.az')
                                    ->setHtmlBody($emailBody)
                                    ->send();

                                /*
                                echo '<br>İstifadəçi əlavə olundu<br>';
                                */
                            }

                        }else{
                            /*
                            echo '<br>İstifadəçi əlavə OLUNMADI<br>';
                            */
                        }
                    }

                }

            }
        }
    }

    public function actionUpdateExperience()
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT id,experience1 FROM `site_doctors`")->queryAll();
        foreach ($result as $item) {
            print_r('<pre>');
            if (strlen($item['experience1']) == 10) {
                var_dump($item['experience1']);
                $date1 = date('Y', strtotime($item['experience1']));

//            $time = strtotime("-".$item['experience']." year", time());
//            $date = date("Y-m-d", $time);
//            print_r($date);
//
            $db->createCommand("UPDATE site_doctors SET experience1=:exp1 WHERE id=:id")
                ->bindValue(':id', $item['id'])
                ->bindValue(':exp1', $date1)
                ->execute();

            }
        }
        return true;
    }

    public function actionUpdateUserid()
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT id,unique_id FROM `site_users`")->queryAll();
        foreach ($result as $item) {
            print_r('<pre>');
            print_r($item);
            $user_id = str_pad($item['id'], 7, 0, STR_PAD_LEFT);
            $db->createCommand("UPDATE site_users SET unique_id=:u1 WHERE id=:id")
                ->bindValue(':id', $item['id'])
                ->bindValue(':u1', $user_id)
                ->execute();

        }
        return true;
    }

}
