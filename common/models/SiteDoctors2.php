<?php

namespace backend\models;

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
    public $new_clinic;
    public $specialists;
    public $home_doctor;
    public $child_doctor;
    public $spc_selected_options;
    public $wkp_selected_options;
    public $added_sosial_links;
    public $added_phone_numbers;
    public $files;
    public $mainImage;
    public $deletedImages;

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
            [['name'], 'string', 'max' => 100],
            [['rating_count'],'safe'],
            [['sosial_links','phone_numbers','workplaces','new_clinic'],'checkIsArray'],
            [['experience'], 'integer', 'message'=>'Sadəcə ədəd daxil edə bilərsiniz'],
            [['photo','mainImage','deletedImages'],'string'],
            [['spc_selected_options','added_sosial_links','added_phone_numbers','wkp_selected_options'],'string'],
            [['files'], 'file','skipOnEmpty' => true, 'extensions'=>'png,jpg','maxFiles' => 20,'wrongExtension'=>'Yalnız {extensions}']
        ];
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



}
