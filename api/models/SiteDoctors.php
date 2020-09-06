<?phpnamespace api\models;use Yii;use yii\helpers\ArrayHelper;use api\modules\enterprise\models\EnterpriseDoctor;/** * This is the model class for table "site_doctors". * * @property int $id * @property string $name * @property file $photo * @property string $expires * @property int $vip 0 - not vip | 1 - vip * @property int $promotion * @property int $feature 1 - Home doctor | 2 - Child doctor | 3 - All * @property string $about * @property string $rating_count * @property string $services_prices * @property string $birthday */class SiteDoctors extends \yii\db\ActiveRecord{    public $sosial_links;    public $phone_numbers;    public $workplaces;    public $new_clinic;    public $specialists;    public $home_doctor;    public $child_doctor;    //public $gender;    //public $degree;    public $addresses;    public $spc_selected_options;    public $wkp_selected_options;    public $added_sosial_links;    public $added_phone_numbers;    public $files;    public $mainImage;    public $deletedImages;    public $mainDiploma;    public $deletedDiplomas;    public $mainCertificate;    public $deletedCertificates;    public $birthday;    public $contact_name;    public $contact_phone;    public $workplaces_list;    public $workplaces_list_names;    public $workplaces_list_addresses;    public $certificate;    public $diplomas;    public $dp_files;    public $ct_files;    public $hospital_id;    /**     * {@inheritdoc}     */    public static function tableName()    {        return 'site_doctors';    }    public static function getClassName()    {        $exp = explode('\\',__CLASS__);        return $exp[count($exp)-1];    }    /**     * {@inheritdoc}     */    public function rules()    {        $return = [            [['name','experience1','specialists','about','birthday','email'], 'required','message'=>'{attribute} xanasını boş buraxmayın'],            [['expires','birthday'], 'safe'],            ['birthday', 'date', 'format' => 'php:Y-m-d'],            ['email','trim'],            ['email','email'],            ['email', 'unique', 'message' => 'Bu email ilə istifadəçi artıq qeydiyyatdan keçmişdir.'],            [['vip', 'promotion','rating','degree','gender','status','balance','hospital_id'], 'integer'],            [['status'],'integer'],            ['degree','default', 'value' => 1],            [['home_doctor','child_doctor'] ,'integer'],            [['about', 'services_prices'], 'string'],//            [['name'], 'string','min' => 3],//            [['name'], 'string','max' => 100],            [['name'], 'string','min' => 3, 'max' => 100],            [['slug'], 'string', 'max' => 250],            [['rating_count','published_time','modified_time'],'safe'],            [['sosial_links','phone_numbers','workplaces','new_clinic','workplaces_list','deletedDiplomas','deletedCertificates'],'checkIsArray'],            ['phone_numbers','CheckPhoneNumber'],            ['sosial_links', 'CheckSocialLink'],            ['birthday', 'CheckBirthday'],            //['experience1','integer','min'=>4,'max'=>4],            ['experience1','trim'],            [['experience1'], 'match', 'pattern' => '/^\d{4}$/'],            [['experience1'], 'integer', 'message'=>'Sadəcə ədəd daxil edə bilərsiniz'],            [['photo','mainImage','deletedImages'],'string'],            [['spc_selected_options','added_sosial_links','added_phone_numbers','wkp_selected_options'],'string'],            [['files'], 'file','skipOnEmpty' => true, 'extensions'=>'jpeg,png,jpg','maxFiles' => 20,'wrongExtension'=>'Yalnız {extensions}'],            [['dp_files'], 'file','skipOnEmpty' => true, 'extensions'=>'png,jpg,jpeg','maxFiles' => 20,'wrongExtension'=>'Yalnız {extensions}'],            [['ct_files'], 'file','skipOnEmpty' => true, 'extensions'=>'png,jpg,jpeg','maxFiles' => 20,'wrongExtension'=>'Yalnız {extensions}']        ];//        if($this->isNewRecord)//        {//            array_push($return,['email', 'required']);//            array_push($return,['email', 'unique', 'targetClass' => '\api\models\SiteUsers', 'message' => 'Bu email ilə istifadəçi artıq qeydiyyatdan keçmişdir.']);//        }        return $return;    }//    public function isNotEmpty($attribute)//    {//        $this->$attribute = null;//        return false;//        if(!isset($this->$attribute[0]['name']))//        {//            echo 'yoxdu';//        }else{//            echo 'var';//        }//        exit();//        if(!isset($this->$attribute[0]['name']))//        {//            $this->addError($attribute,'Şəkil elave edin');//        }//    }    public function CheckPhoneNumber($attribute)    {        $field = $this->$attribute;        $errors = [];        for($i = 0; $i < count($field); $i++) {            $type = $field[$i]['type'];            $number = $field[$i]['number'];            if(!empty($number) && strlen($field[$type]['number']) != 10) {                $errors[$type] = "Nömrə 10 simvoldan ibarət olmalıdır";            } else {                if($type == 1 && empty($number)) {                    $errors[$type] = "Mobil telefon xanasını boş buraxmayın";                }            }        }        if($errors) {            $this->addError($attribute,$errors);        }    }    public function CheckSocialLink($attribute)    {        $expressions = [            '/(?:https?:\/\/)?(?:www\.)?facebook\.com\/.(?:(?:\w)*#!\/)?(?:pages\/)?(?:[\w\-]*\/)*([\w\-\.]*)/',            '/(?:https?:)?\/\/(?:www\.)?(?:instagram\.com|instagr\.am)\/(?P<username>[A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)/',            '/(?:https?:)?\/\/(?:[A-z]+\.)?youtube.com\/channel\/(?P<id>[A-z0-9-\_]+)\/?/',            '/(?:https?:)?\/\/(?:[A-z]+\.)?twitter\.com\/@?(?P<username>[A-z0-9_]+)\/?/',            '/(?:https?:)?\/\/(?:[\w]+\.)?linkedin\.com\/in\/(?P<permalink>[\w\-\_À-ÿ%]+)\/?/'        ];        $field = $this->$attribute;        $errors = [];        for($i = 0; $i < count($field); $i++) {            $type = $field[$i]['type'];            $link = trim($field[$i]['link']);            if(!empty($link) && !preg_match($expressions[$type], $link)) {                $errors[$type] = "Link düzgün deyil";            }        }        if($errors) {            $this->addError($attribute,$errors);        }    }    public function checkIsArray($attribute)    {        if(!is_array($this->$attribute)){            $this->addError($attribute,'Xəta! Massiv deyil!');        }    }    public static function get_Status()    {        return [            0 => 'DeAktiv',            1 => 'Aktiv'        ];    }    public static function getSex()    {        return [            0 => 'Qadın',            1 => 'Kişi'        ];    }    /**     * {@inheritdoc}     */    public function attributeLabels()    {        return [            'id' => 'ID',            'name' => 'Ad',            'photo' => 'Şəkil ( png , jpg )',            'expires' => 'Etibarlıdır',            'vip' => 'Vip',            'promotion' => 'Promotion',            'degree' => 'Elmi Dərəcə',            'feature' => 'Özəlliklər',            'about' => 'Haqqinda',            'balance' => 'Balans',            'gender' => 'Cins',            'services_prices' => 'Qiymətlər',            'experience1' => 'Təcrübə',            'rating' => 'Reytinq',            'sosial_links' => 'Sosial linklər',            'phone_numbers' => 'Telefon nömrələri',            'workplaces' => 'İş yer(lər)i',            'new_clinic' => 'Klinika əlavə et',            'specialists' => 'İxtisas(lar)',            'home_doctor' => 'Evə çağırış',            'child_doctor' => 'Uşaq həkimi',            'files' => 'Şəkil',            'dp_files' => 'Diplomlar',            'ct_files' => 'Sertifikatlar',            'status' => 'Status',        ];    }    public static function getDegree()    {        return [            1 => 'Ali',            2 => 'Tibb üzrə fəlsəfə doktoru',            3 => 'Elmlər doktoru'        ];    }    public static function getStatus()    {        return [            0 => 'DeAktiv',            1 => 'Aktiv',            2 => 'Gözləmə Rejimi',        ];    }    public static function getDoctors($id = null)    {        if($id === null){            return ArrayHelper::map(self::find()->all(),'id','name');        } else {            $doctor = self::findOne($id);            if($doctor) {                return $doctor->name;            }else{                return '-';            }        }    }    public function getInfo()    {        return $this->hasOne(EnterpriseDoctor::className(), ['doctor_id' => 'id']);    }    public function CheckBirthday($attribute) {        $valid_date = date('Y-m-d', strtotime('-20 year'));        if($this->birthday > $valid_date) {            $this->addError($attribute,"Doğum tarixinizi düzgün daxil edin");        }    }    public function search($search)    {        if (!empty($search['number'])) {            $phone = '994' . substr($search['number'], 1);        } else {            $phone = '----';        }        if ($search['status']=='all'){            $status = 'status <> 2';        }        else{            $status = "status= ".$search['status']." AND status <> 2";        }        $email = !empty($search['email']) ? $search['email'] : '----';        $name = !empty($search['name']) ? $search['name'] : '----';        $qeydiyyat = !empty($search['code']) ? $search['code'] : '----';        $specialist = !empty($search['specialist']) ? $search['specialist'] : '----';        return Yii::$app->db->createCommand("SELECT DISTINCT `qeydiyyat_id` AS code ,`id`,`name`,`email`,`status`,`photo` FROM `view_doctors_search` WHERE ".$status."                             AND  ((`name` LIKE '%$name%') OR (`email` LIKE '%$email%') OR (`qeydiyyat_id` LIKE '%$qeydiyyat%')                             OR (`ixtisas` LIKE '%$specialist%') OR (`number` LIKE  '%$phone%' AND  `number_type` = 1))",            [':status' => $status])->queryAll();    }    public static function doctorFind($id)    {        return Yii::$app->db->createCommand("SELECT * FROM `site_doctors` WHERE `id`=:id", [':id' => $id])->queryOne();    }}