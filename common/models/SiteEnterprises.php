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

class SiteEnterprises extends \yii\db\ActiveRecord

{

    public $sosial_links;

    public $phone_numbers;

    public $addresses;

    public $added_sosial_links;

    public $added_phone_numbers;

    public $added_addresses;

    public $files;

    public $mainImage;

    public $deletedImages;

    public $catdirilma;

    public $saat24;

    public $eve_caqiris;

    public $email;
    public $contact_name;
    public $contact_phone;
    public $contact_birthday;
    public $company_email;
    public $password;
    public $repassword;
    public $agree_rules;

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

    public static function getWorkers($company_id)
    {
        $connection = Yii::$app->getDb();
        $result     = $connection->createCommand("SELECT * FROM `site_enterprise_employers` where `connect_id`='$company_id' order by id DESC")->queryAll();
        if(!empty($result))
            return $result;
        else
            return False;
    }

    public static function get_Study()
    {
        return [
            0 => 'Natamam ali',
            1 => 'Ali',
            2 => 'Tibb üzrə fəlsəfə doktoru',
            3 => 'Elmlər doktoru'
        ];
    }



    /**

     * {@inheritdoc}

     */

    public function rules()

    {

        $return = [

            [['category_id','email', 'name','contact_phone','password','repassword'], 'required','message'=>'{attribute} xanasını boş buraxmayın.'],
            ['agree_rules','required','requiredValue' => 1,'message'=>'Qeydiyyatdan keçmək üçün qaydaları qəbul etdiyinizi təsdiqləyin'],
            ['email', 'unique', 'targetClass' => '\frontend\models\User', 'message' => 'Bu email ilə istifadəçi artıq qeydiyyatdan keçmişdir.'],
            ['email','trim'],
            ['email','email'],
            [['password','repassword'], 'trim'],
            [['password','repassword'], 'string', 'min' => 6],
            ['repassword', 'compare', 'compareAttribute'=>'password', 'message'=>"Şifrə ilə təkrar şifrə eyni deyil" ],

            [['category_id', 'promotion', 'catdirilma','saat24','eve_caqiris','balance'], 'integer'],

            [['expires'], 'safe'],

            [['rating'], 'number'],

            ['contact_phone', 'string', 'min' => 10, 'max' => 10],

            [['sosial_links','phone_numbers','addresses','company_email'],'checkIsArray'],

            [['about', 'services_prices','added_sosial_links','added_phone_numbers','added_addresses','contact_birthday'], 'string'],

            ['contact_birthday', 'date', 'format' => 'php:Y-m-d'],

            [['name'], 'string','min' => 3, 'max' => 200],

            [['weekdays','saturday','sunday'],'string'],

            [['photo','mainImage','deletedImages'],'string'],

            [['files'], 'file','skipOnEmpty' => true, 'extensions'=>'png,jpg','maxFiles' => 20,'wrongExtension'=>'Yalnız {extensions}']

        ];

        return $return;

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
            'balane' => 'Balans',

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

            'sunday'=>'Bazar',

//            'email'=>'Email düzgün e-mail deyil.',
//
//            'contact_name'=>'Əlaqələndirici şəxsin adı',
//
            'contact_phone'=>'Mobil nömrə',
//
//            'contact_birthday'=>'Əlaqələndirici şəxsin doğum tarixi',

            'company_email'=>'Şirkətin e-poçt ünvanı',

            'password'=>'Şifrə',

            'repassword' => 'Təkrar şifrə',

            'agree_rules'=>'Qaydalar'

        ];

    }

    public static function getStatus()
    {
        return [
            0 => 'DeAktiv',
            1 => 'Aktiv',
            2 => 'Gözləmə Rejimi',
        ];
    }

}

