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
 * @property string $phone_number
 * @property string $phone_prefix
 * @property string $photo
 * @property string $auth_key
 * @property string $last_login
 * @property string $live_consultation
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $type
 * @property string $password write-only password
 */
class UserSettingsForm extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_PENDING = 2;

    const  TYPE_USER = 0;
    const  TYPE_DOCTOR = 1;
    const  TYPE_ENTERPRISE = 2;

    public $user_types = ["0" => "İstifadəçi","1" => "Həkim","2" => "Obyekt"];

    public $pass;
    public $newpass;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_users';
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
            [['email','name','phone_number'], 'required','message'=>'{attribute} xanasını boş buraxmayın'],
            [['photo'], 'file','skipOnEmpty' => true, 'extensions'=>'png,jpg,jpeg','maxFiles' => 20,'wrongExtension'=>'Yalnız {extensions}'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['email', 'email', 'message'=>'{attribute} düzgün deyil'],
            ['type', 'in', 'range' => [0,1,2]],
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 3, 'max' => 255],
            [['phone_number'], 'string', 'min' => 10, 'max' => 10],
            ['phone_number', 'safe'],
            ['pass','trim'],
            ['pass','string','min'=>6],
            ['pass', 'validatePass'],
            ['newpass','trim'],
            ['newpass','string','min'=>6]
        ];
    }

    public function validatePass($attribute, $params)
    {
        if(!empty($attribute))
        {
             if(!$this->hasErrors())
             {
                if (!Yii::$app->security->validatePassword($this->$attribute, Yii::$app->user->identity->password)) {
                    $this->addError($attribute, 'Şifrə yalnışdır');
                }
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_type' => 'Növü',
            'name' => 'Ad, Soyad:',
            'phone_number' => "Mobil Telefon",
            'photo' => "Şəkil",
            'email' => "Email",
            'pass' => "Köhnə Şifrə",
            'password' => "Köhnə Şifrə",
            'newpass' => "Yeni Şifrə",
        ];
    }

}
