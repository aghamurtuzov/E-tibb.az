<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use frontend\models\LoginUserModel;

/**
 * Login form
 */
class LoginTestForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = false;
    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required','message'=>'{attribute} xanasını boş buraxmayın'],
            [['email','password'],'trim'],
            ['email','email'],
            ['rememberMe', 'boolean'],
            ['email', 'string', 'min' => 8],
            ['email', 'string', 'max' => 40],
            ['password', 'string', 'min' => 4],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Şifrə',
            'rememberMe' => 'Yadda saxla',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'İstifadəçi adı və ya şifrə yalnışdır');
            }
        }
    }

    /**
     * Logs in a user using the provided email and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */

    protected function getUser()
    {
        if($this->_user === null){
            $this->_user = LoginUserModel::findByEmail($this->email);
        }
        return $this->_user;
    }

}
