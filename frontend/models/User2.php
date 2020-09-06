<?php
namespace frontend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $name
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $birthday
 * @property string $phone_number
 * @property string $auth_key
 * @property string $last_login
 * @property string $live_consultation
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $type
 * @property string $password write-only password
 */
class User2 extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_PENDING= 2;

    const  TYPE_USER = 0;
    const  TYPE_DOCTOR = 1;
    const  TYPE_ENTERPRISE = 2;

    public $user_types = ["0" => "İstifadəçi","1" => "Həkim","2" => "Obyekt"];


    public $pass;
    public $repassword;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_users2';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'unique', 'targetClass' => '\frontend\models\User', 'message' => 'Bu email ilə istifadəçi artıq qeydiyyatdan keçmişdir.'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED, self::STATUS_PENDING]],
            ['type', 'in', 'range' => [0,1,2]],
            [['name','email','pass','phone_number'] , 'required','message'=>'{attribute} xanasını boş buraxmayın'],
            [['name','email'], 'trim'],
            ['email' , 'email'],
            [['name'], 'string', 'min' => 3, 'max' => 40],
            ['phone_number', 'safe','min' => 10, 'max' => 10],
            ['birthday', 'safe'],
            ['pass', 'string', 'min' => 6]
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_type' => 'Növü',
            'name' => 'Ad, Soyad:',
            'phone_number' => "Mobil Telefon",
            'email' => "Email",
            'pass' => "Şifrə",
            'password' => "Şifrə",
            'birthday' => "Doğum tarixi",
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by name
     *
     * @param string $name
     * @return static|null
     */
    public static function findByName($name)
    {
        return static::findOne(['name' => $name, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByEmail($email)
    {
        return static::find()->where(['email' => $email])->andWhere(["in","status",[self::STATUS_ACTIVE,self::STATUS_PENDING]])->one();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
