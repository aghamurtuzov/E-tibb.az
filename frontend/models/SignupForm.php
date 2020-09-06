<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $phone;
    public $password;
    public $repassword;
    public $birthday;
    public $user_type;
    public $user_types = ["user" => "İstifadəçi","doctor" => "Həkim","enterprise" => "Obyekt"];
    public $user_types_name = ["user","doctor","enterprise"];


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
/*            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],*/
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],

            ['user_type', 'in', 'range' => $this->user_types_name],
            ['phone', 'required'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['repassword', 'required'],
            ['repassword', 'compare', 'compareAttribute'=>'password', 'message'=>"Şifrə təkrarı düzgün deyil" ],


        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_type' => 'Növü',
            'username' => 'Ad, Soyad:',
            'phone' => "Mobil Telefon",
            'email' => "Email",
            'password' => "Şifrə",
            'repassword' => "Şifrə təkrarı",
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
