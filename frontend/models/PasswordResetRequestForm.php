<?php

namespace frontend\models;

use api\components\Functions;
use frontend\models\User;
use Yii;
use yii\base\Model;
use yii\bootstrap\Html;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
    public $phone_number;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['phone_number', 'trim'],
            ['phone_number', 'required'],
            ['phone_number', 'string','min' => 10, 'max' => 10],
            [['phone_number'], 'number'],
            ['phone_number', 'exist',
                'targetClass' => '\frontend\models\User',
                'message' => 'Bu telefon nömrəsi bazada yoxdur'
            ],

        ];
    }

    public function attributeLabels()
    {
        return [

            'phone_number' => 'Telefon nömrəsi',

        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save(false)) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom(['no-reply@e-tibb.az' => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();
    }


    public function sendPhone()
    {
        /* @var $user User */

        $user = User::findOne([
            'phone_number' => '994' . substr($this->phone_number, 1),
        ]);

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save(false)) {
                return false;
            }
        }
        $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
        $message = "Salam " . Html::encode($user->name) . ",\n\nAşağıdakı linkə daxil olaraq şifrənizi yeniləyə bilərsiniz:\n\n" . $resetLink;

        Functions::SendSms($user['phone_number'], $message);

        return true;
    }

}
