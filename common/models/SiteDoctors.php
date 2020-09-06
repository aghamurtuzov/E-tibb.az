<?php

namespace common\models;

use DateTime;
use Yii;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "site_doctors".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property file $photo
 * @property string $expires
 * @property string $birthday
 * @property string $user_id
 * @property string $vip_expires
 * @property int $promotion
 * @property int $feature 1 - Home doctor | 2 - Child doctor | 3 - All
 * @property string $about
 * @property string $rating_count
 * @property string $services_prices
 * @property string $published_time
 * @property string $modified_time
 * @property string $degree
 */
class SiteDoctors extends \yii\db\ActiveRecord
{
    public $sosial_links;
    public $phone_numbers;
    public $workplaces;
    public $new_clinic;
    public $specialists;
    public $home_doctor;
    public $child_doctor;
    public $spc_selected_options;
    public $wkp_selected_options;
    public $added_sosial_links;
    public $added_phone_numbers;
    public $added_mobile_numbers;
    public $mobile_numbers;
    public $files;
    public $mainImage;
    public $deletedImages;
    public $saat24;
    public $eve_caqiris;
    //public $published_time;
    //public $modified_time;

    public $birthday;
    public $contact_name;
    public $contact_phone;
    public $password;
    public $repassword;
    public $workplaces_list;
    public $workplaces_list_names;
    public $workplaces_list_addresses;
    public $certificate;
    public $diplomas;
    public $dp_files;
    public $ct_files;
//    public $photo;
    public $phone;
    public $agree_rules;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_doctors';
    }

    public static function getClassName()
    {
        $exp = explode('\\',__CLASS__);
        return $exp[count($exp)-1];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {

        $rules = [

//            [['name', 'experience1', 'specialists','birthday','email','gender'], 'required','message'=>'{attribute} xanasını boş buraxmayın'],
            [['name', 'specialists','email','gender','phone','birthday','password','repassword'], 'required','message'=>'{attribute} xanasını boş buraxmayın.'],
            ['agree_rules','required','requiredValue' => 1,'message'=>'Qeydiyyatdan keçmək üçün qaydaları qəbul etdiyinizi təsdiqləyin'],
            [['expires','birthday'], 'safe'],
            ['email','trim'],
            ['email','email'],
            ['email', 'unique', 'message' => 'Bu email ilə istifadəçi artıq qeydiyyatdan keçmişdir.'],
            [['promotion','rating','degree'], 'integer'],
            [['home_doctor','child_doctor'] ,'integer'],
            [['about', 'services_prices','skype'], 'string'],
            [['name'], 'string','min' => 3, 'max' => 100],
            [['slug'], 'string', 'max' => 250],
            ['phone', 'string', 'min' => 10, 'max' => 10], // user modeli ile eyni
            ['phone','number'],
            [['password','repassword'], 'trim'],
            [['password','repassword'], 'string', 'min' => 6],
            ['repassword', 'compare', 'compareAttribute'=>'password', 'message'=>"Şifrə ilə təkrar şifrə eyni deyil" ],
            [['rating_count','published_time','modified_time'],'safe'],
            [['sosial_links','phone_numbers','workplaces','new_clinic','workplaces_list','mobile_numbers'],'checkIsArray'],
            [['photo','diplomas','certificate','mainImage','deletedImages'],'string'],
            [['spc_selected_options','added_sosial_links','added_phone_numbers','added_mobile_numbers','wkp_selected_options'],'string'],
            [['files'], 'file','skipOnEmpty' => true, 'extensions'=>'png,jpg,jpeg','maxFiles' => 20,'wrongExtension'=>'Yalnız {extensions} formatlarında şəkil yükləyə bilərsiniz'],
            [['dp_files'], 'file','skipOnEmpty' => true, 'extensions'=>'png,jpg,jpeg','maxFiles' => 20,'wrongExtension'=>'Yalnız {extensions}'],
            [['ct_files'], 'file','skipOnEmpty' => true, 'extensions'=>'png,jpg,jpeg','maxFiles' => 20,'wrongExtension'=>'Yalnız {extensions}']
        ];

        if($this->isNewRecord){
            $addRules   = [['password'], 'required'];
            array_push($rules,$addRules);
        }

        return $rules;

    }

    public function checkPass($attribute)
    {
        if($this->password != $this->repassword)
        {
            $this->addError($attribute, 'Şifrə ilə təkrar şifrə eyni deyil');
        }
    }

    public function checkIsArray($attribute)
    {
        if(!is_array($this->$attribute)){
            $this->addError($attribute,'Xəta! Massiv deyil!');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Ad',
            'photo' => 'Şəkil ( png , jpg )',
            'diplomas' => 'Diplom ( png , jpg )',
            'certificate' => 'Sertifikat ( png , jpg )',
            'expires' => 'Etibarlıdır',
            'promotion' => 'Promotion',
            'feature' => 'Özəlliklər',
            'about' => 'Haqqında',
            'services_prices' => 'Qiymətlər',
            'experience1' => 'Təcrübə',
            'rating' => 'Reytinq',
            'sosial_links' => 'Sosial linklər',
            'phone_numbers' => 'Telefon nömrələri',
            'workplaces'   => 'İş yer(lər)i',
            'new_clinic'   => 'Klinika əlavə et',
            'specialists'  => 'İxtisas(lar)',
            'home_doctor'  => 'Evə çağırış',
            'child_doctor' => 'Uşaq həkimi',
            'degree'       => 'Elmi dərəcə',
            'birthday'     => 'Doğum tarixi',
            'skype'        => 'Skype',
            'gender'        => 'Cins',
            'password'=>'Şifrə',
            'repassword'=>'Təkrar şifrə',
            'mobile_numbers'=>'Mobil telefon*',
            'phone' => 'Mobil nömrə',
            'agree_rules'=>'Qaydalar'
        ];
    }

    public static function getDoctors($id = null)
    {
        if($id === null){
            return ArrayHelper::map(self::find()->all(),'id','name');
        } else {
            $doctor = self::findOne($id);
            if($doctor) {
                return $doctor->name;
            }else{
                return '-';
            }
        }
    }

    public static function getDegree()
    {
        return [
            0 => 'Natamam ali',
            1 => 'Ali',
            2 => 'Elmlər namizədi',
            3 => 'Elmlər doktoru'
        ];
    }

    public function getDoctorSpecialist()
    {
        return true;
    }

    public static function getDoctorWithId($user_id)
    {
        return static::findOne(['user_id' => $user_id]);

    }


}
