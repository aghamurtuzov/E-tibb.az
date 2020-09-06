<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "site_ads".
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $text
 * @property string $image
 * @property int $type 0 => user; 1 => Doctor; 2 => Enterprise
 * @property string $premium_expiry
 * @property double $rating_value
 * @property int $review_count
 * @property int $is_blood 0=>not blood; 1=> blood type ad.
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $phone_prefix
 */
class SiteAds extends \yii\db\ActiveRecord
{
    public static $tableName = 'site_ads';

    public $deletedImages;
//    public $user_info;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_ads';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'text', 'user_info', 'email', 'phone', 'blood_type'], 'required', 'message' => '{attribute} boş olmamalıdır'],
            [['email', 'user_info'], 'trim'],
            ['email', 'email'],
            [['user_info'], 'string', 'min' => 6, 'max' => 100],
            [['user_info'], 'string', 'min' => 6, 'max' => 100],
            [['title'], 'string', 'min' => 3, 'max' => 100],
            ['phone', 'string', 'min' => 10, 'max' => 10],
            ['phone', 'number'],
//            [['image'], 'file','skipOnEmpty' => true, 'extensions'=>'png,jpg,jpeg','maxFiles' => 20,'wrongExtension'=>'Yalnız {extensions}'],
            [['title', 'slug', 'user_info'], 'string'],
            [['type', 'review_count', 'is_blood','blood_type', 'discount'], 'integer'],
            [['status'], 'integer'],
            [['premium_expiry', 'created_at', 'updated_at', 'image'], 'safe'],
            [['rating_value'], 'number'],
            [['text'], 'string', 'max' => 500],
            [['deletedImages'], 'string'],
        ];
    }

    public function CheckPhoneNumber($attribute)
    {
        $type = 1;
        $field = $this->$attribute;
        if (!empty($field[$type]['number']) && $field[$type]['type'] == 1) {
            $strlen = strlen($field[$type]['number']);
            if ($strlen != 12) {
                $this->addError($attribute, 'Nömrə 10 simvoldan ibarət olmalıdır');
            }
        } else {
            $this->addError($attribute, 'Mobil telefon xanasını boş buraxmayın');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_info' => 'Ad Soyad',
            'email' => 'Email',
            'phone' => 'Mobil Nömrə',
            'blood_type' => 'Qan qrupu',
            'id' => 'ID',
            'title' => 'Başlıq',
            'slug' => 'Slug',
            'text' => 'Mətn',
            'image' => 'Şəkil',
            'type' => 'Type',
            'premium_expiry' => 'Premium bitmə vaxtı',
            'rating_value' => 'Reyting dəyəri',
            'review_count' => 'Baxış sayı',
            'is_blood' => 'Qan axtarışı',
            'status' => 'Status',
            'premium_type' => 'Premium tipi',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getPremiumTypes()
    {
        return [
            '0' => 'Yoxdur',
            '1' => '3 gün',
            '2' => "1 həftə",
            '3' => "1 ay"
        ];
    }

    /**
     * Get type
     */
    public static function get_Type()
    {

        return [


            1 => 'Həkim',

            2 => 'Korporativ',

        ];

    }

    public function getBloodTypes()
    {
        return [
            '1',
            '2',
            '3',
            '4',
            '-1',
            '-2',
            '-3',
            '-4'
        ];
    }

    /**
     * Get status
     */
    public static function get_Status()
    {
        return [
            0 => 'DeAktiv',
            1 => 'Aktiv',
            2 => 'Gözləmədə'
        ];
    }

    public static function getAdsCount($blood)
    {
        $db = Yii::$app->db;
//        $result = $db->createCommand("SELECT count(id) FROM `".self::$tableName."` WHERE `is_blood`=:blood and `status`=1",[":blood" => $blood])->cache(60)->queryScalar();
        $result = $db->createCommand("SELECT count(`id`) as `counter` FROM `" . self::$tableName . "`  WHERE `is_blood`=:blood and `status`=1", [":blood" => $blood])->cache(60)->queryScalar();
        return $result;

    }

    public static function getAds($blood, $limit, $offset)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT * FROM `" . self::$tableName . "`  WHERE `is_blood`=:blood and `status`=1 ORDER BY `id` DESC LIMIT $limit OFFSET $offset", [":blood" => $blood])->cache(60)->queryAll();

        return $result;
    }


}
