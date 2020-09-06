<?php

namespace frontend\models;

use backend\components\Functions;
use Yii;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "site_doctors".
 *
 * @property int $id
 * @property string $name
 * @property file $photo
 * @property string $expires
 * @property int $vip 0 - not vip | 1 - vip
 * @property int $promotion
 * @property int $feature 1 - Home doctor | 2 - Child doctor | 3 - All
 * @property string $about
 * @property string $rating_count
 * @property string $services_prices
 */
class SiteDoctors extends \yii\db\ActiveRecord
{
    public $sosial_links;
    public $phone_numbers;
    public $workplaces;
    public $workplaces_list_addresses;
    public $workplaces_list;
    public $workplaces_list_name;
    public $new_clinic;
    public $specialists;
    public $home_doctor;
    public $child_doctor;
    public $spc_selected_options;
    public $wkp_selected_options;
    public $added_sosial_links;
    public $added_addresses;
    public $added_phone_numbers;
    public $added_mobile_numbers;
    public $mobile_numbers;
    public $files;
    public $mainImage;
    public $deletedImages;

    public $email;
    public $contact_name;
    public $contact_phone;
    public $password;
    public $repassword;

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
        return [
            [['name', 'vip', 'experience', 'specialists'], 'required','message'=>'{attribute} xanasını boş buraxmayın'],
            [['expires'], 'safe'],
            ['email','email'],
            [['vip', 'promotion','rating'], 'integer'],
            [['home_doctor','child_doctor'] ,'integer'],
            [['about', 'services_prices'], 'string'],
            [['name'], 'min' => 3, 'max' => 100],
            [['rating_count'],'safe'],
            [['sosial_links','phone_numbers','workplaces','new_clinic'],'checkIsArray'],
            [['experience'], 'integer', 'message'=>'Sadəcə ədəd daxil edə bilərsiniz'],
            [['photo','mainImage','deletedImages'],'string'],
            [['spc_selected_options','added_sosial_links','added_phone_numbers','wkp_selected_options'],'string'],
            [['files'], 'file','skipOnEmpty' => true, 'extensions'=>'png,jpg','maxFiles' => 20,'wrongExtension'=>'Yalnız {extensions}'],
            [['password','repassword'], 'trim'],
            [['password','repassword'], 'string', 'min' => 6],
            ['repassword','checkPass'],
        ];
    }

    public function checkIsArray($attribute)
    {
        if(!is_array($this->$attribute)){
            $this->addError($attribute,'Xəta! Massiv deyil!');
        }
    }

    public function checkPass($attribute)
    {
        if($this->isNewRecord){
            if(empty($this->password)){
                $this->addError('repassword', 'Şifrə xanasını boş buraxmayın');
            }
            if(empty($this->repassword)){
                $this->addError('password', 'Şifrə xanasını boş buraxmayın');
            }
        }


        if($this->password != $this->repassword)
        {
            $this->addError($attribute, 'Şifrə ilə təkrar şifrə eyni deyil');
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
            'expires' => 'Etibarlıdır',
            'vip' => 'Vip',
            'promotion' => 'Promotion',
            'feature' => 'Özəlliklər',
            'about' => 'Haqqinda',
            'services_prices' => 'Qiymətlər',
            'experience' => 'Təcrübə',
            'rating' => 'Reytinq',
            'sosial_links' => 'Sosial linklər',
            'phone_numbers' => 'Telefon nömrələri',
            'workplaces' => 'İş yer(lər)i',
            'new_clinic' => 'Klinika əlavə et',
            'specialists' => 'İxtisas(lar)',
            'home_doctor' => 'Evə çağırış',
            'child_doctor' => 'Uşaq həkimi',
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

    public static function getDoctorWithId($user_id)
    {
        return static::findOne(['user_id' => $user_id]);

    }

    public static function getDegree()
    {
        return [
            1 => 'Ali',
            2 => 'Tibb üzrə fəlsəfə doktoru',
            3 => 'Elmlər doktoru'
        ];
    }

    public static function ResultList($data,$customPath = null)
    {
        $customPath = empty($customPath) ? "" : $customPath;
        if(count($data)>0)
        {
            if(isset($data[0]))
            {
                foreach ($data as $index => $val)
                {
                    if(isset($val['photo']))
                    {
                        $photo = $data[$index]['photo'];
                        $data[$index]['photo'] = Functions::getUploadUrl().$customPath.'/'.$photo;
                        $data[$index]['thumb'] = Functions::getUploadUrl().$customPath.'/small/'.$photo;
                    }
                    if(isset($val['file_photo']))
                    {
                        $photo = $data[$index]['file_photo'];
                        $data[$index]['file_photo'] = Functions::getUploadUrl().$customPath.'/'.$photo;
                        $data[$index]['file_photo_thumb'] = Functions::getUploadUrl().$customPath.'/small/'.$photo;
                    }
                }
            }else{
                if(isset($data['photo']))
                {
                    $photo = $data['photo'];
                    $data['photo'] = Functions::getUploadUrl() . $customPath . '/' . $photo;
                    $data['thumb'] = Functions::getUploadUrl() . $customPath . '/small/' . $photo;
                }
                if(isset($data['file_photo']))
                {
                    $photo = $data['file_photo'];
                    $data['file_photo'] = Functions::getUploadUrl() . $customPath . '/' . $photo;
                    $data['file_photo_thumb'] = Functions::getUploadUrl() . $customPath . '/small/' . $photo;
                }
            }
            return $data;
        }
    }

}
