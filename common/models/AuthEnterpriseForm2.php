<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "site_enterprises".
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $photo
 * @property string $expires
 * @property int $promotion
 * @property int $feature 1 - Catdirilma | 2 - 24 saat | 3 - Caqiris
 * @property double $rating
 * @property string $about
 * @property string $services_prices
 */
class AuthEnterpriseForm extends \yii\db\ActiveRecord
{
    public $name;
    public $category_id;
    public $photo;
    public $weekdays;
    public $saturday;
    public $sunday;
    public $enterprise_email;
    public $contact_name;
    public $contact_email;
    public $contact_phone;
    public $contact_phone2;
    public $social_list;
    public $phone_list;
    public $phone_list2;
    public $address_list;
    public $about;
    public $pass;
    public $re_pass;
    public $catdirilma;
    public $saat24;
    public $eve_caqiris;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_enterprises';
    }

    public function getClassName()
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
            [['category_id', 'name'], 'required','message'=>'{attribute} xanasını boş buraxmayın'],
            [['category_id', 'promotion', 'catdirilma','saat24','eve_caqiris'], 'integer'],
            [['weekdays','saturday','sunday'],'string'],
            [['photo','contact_name','contact_email','contact_phone','contact_phone2',''],'string'],

            /*
            [['expires'], 'safe'],
            [['rating'], 'number'],
            [['sosial_links','phone_numbers','addresses'],'checkIsArray'],
            [['about', 'services_prices','added_sosial_links','added_phone_numbers','added_addresses'], 'string'],
            [['name'], 'string', 'max' => 200],
            [['files'], 'file','skipOnEmpty' => true, 'extensions'=>'png,jpg','maxFiles' => 20,'wrongExtension'=>'Yalnız {extensions}']*/
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
            'category_id' => 'Kateqoriya',
            'name' => 'Ad',
            'photo' => 'Şəkil ( png , jpg )',
            'expires' => 'Etibarlıdır',
            'promotion' => 'Promotion',
            'feature' => 'Özəlliklər',
            'rating' => 'Reytinq',
            'about' => 'Haqqında',
            'services_prices' => 'Qiymətlər',
            'catdirilma' => 'Evə çatdılıma',
            'saat24' => '24 Saat açıq',
            'eve_caqiris' => 'Evə çağırış',
            'addresses' => 'Ünvanlar',
            'sosial_links' => 'Sosial linklər',
            'phone_numbers' => 'Telefon nömrələri',
            'weekdays'=>'Həftə içi',
            'saturday'=>'Şənbə',
            'sunday'=>'Bazar'
        ];
    }
}
