<?php

namespace frontend\models;

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
 */
class SiteAds extends \yii\db\ActiveRecord
{
    public static $tableName = 'site_ads';

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
            [['title', 'text','user_info', 'email', 'phone', 'blood_type'], 'required', 'message' => '{attribute} boş olmamalıdır'],
            [['email', 'user_info'],'trim'],
            ['email','email'],
            [['user_info'], 'string','min' => 5, 'max' => 100],
            [['title'], 'string','min' => 3, 'max' => 100],
            ['phone', 'string', 'min' => 10, 'max' => 10],
            ['phone','number'],
            [['image'], 'file','skipOnEmpty' => true, 'extensions'=>'png,jpg,jpeg','maxFiles' => 20,'wrongExtension'=>'Yalnız {extensions}'],
            [['title', 'slug', 'user_info', 'image'], 'string'],
            [['type', 'review_count', 'is_blood', 'status', 'blood_type','discount'], 'integer'],
            [['premium_expiry', 'created_at', 'updated_at', 'image'], 'safe'],
            [['rating_value'], 'number'],
            [['text'], 'string', 'max' => 500],
        ];
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

    public function getPremiumTypes(){
        return [
            '0' => 'Yoxdur',
            '1' => '3 gün',
            '2' => "1 həftə",
            '3' => "1 ay"
        ];
    }

    public function getBloodTypes(){
        return [
            '' => 'Qan qrupu seçin *',
            '1' => '1',
            '2' => '2',
            '3' => "3",
            '4' => "4",
            '5' => '-1',
            '6' => '-2',
            '7' => "-3",
            '8' => "-4"
        ];
    }

    public static function getAdsCount($blood,$keyword=null,$blood_type=null)
    {
        $db = Yii::$app->db;

        if($keyword!=null && $blood_type!=null)
        {
            $result = $db->createCommand("SELECT count(`id`) as `counter` FROM `".self::$tableName."`  WHERE `is_blood`=:blood and `status`=1 and `title` LIKE '%$keyword%' and `blood_type`=:blood_type",[":blood" => $blood, ":blood_type" => $blood_type])->cache(60)->queryScalar();
        }
        elseif($keyword!=null)
        {
            $result = $db->createCommand("SELECT count(`id`) as `counter` FROM `".self::$tableName."`  WHERE `is_blood`=:blood and `status`=1 and `title` LIKE '%$keyword%'",[":blood" => $blood])->cache(60)->queryScalar();
        }
        elseif($blood_type!=null)
        {
            $result = $db->createCommand("SELECT count(`id`) as `counter` FROM `".self::$tableName."`  WHERE `is_blood`=:blood and `status`=1 and `blood_type`=:blood_type",[":blood" => $blood, ":blood_type" => $blood_type])->cache(60)->queryScalar();
        }
        else
        {
            $result = $db->createCommand("SELECT count(`id`) as `counter` FROM `".self::$tableName."`  WHERE `is_blood`=:blood and `status`=1",[":blood" => $blood])->cache(60)->queryScalar();
        }

        return $result;

    }

    public static function getAds($blood,$limit,$offset,$keyword=null,$blood_type=null)
    {
        $db = Yii::$app->db;

        if($keyword!=null && $blood_type!=null)
        {
            $result = $db->createCommand("SELECT * FROM `".self::$tableName."`  WHERE `is_blood`=:blood and `status`=1 and `title` LIKE '%$keyword%' and `blood_type`=:blood_type ORDER BY `id` DESC LIMIT $limit OFFSET $offset",[":blood" => $blood, ":blood_type" => $blood_type])->cache(60)->queryAll();
        }
        elseif($keyword!=null)
        {
            $result = $db->createCommand("SELECT * FROM `".self::$tableName."`  WHERE `is_blood`=:blood and `status`=1 and `title` LIKE '%$keyword%' ORDER BY `id` DESC LIMIT $limit OFFSET $offset",[":blood" => $blood])->cache(60)->queryAll();
        }
        elseif($blood_type!=null)
        {
            $result = $db->createCommand("SELECT * FROM `".self::$tableName."`  WHERE `is_blood`=:blood and `status`=1 and `blood_type`=:blood_type ORDER BY `id` DESC LIMIT $limit OFFSET $offset",[":blood" => $blood, ":blood_type" => $blood_type])->cache(60)->queryAll();
        }
        else
        {
            $result = $db->createCommand("SELECT * FROM `".self::$tableName."`  WHERE `is_blood`=:blood and `status`=1 ORDER BY `id` DESC LIMIT $limit OFFSET $offset",[":blood" => $blood])->cache(60)->queryAll();
        }

        return $result;
    }

    public function getSingleAds($blood,$blood_id)
    {
        $db = Yii::$app->db;

        $result = $db->createCommand("SELECT `id`, `user_info`,`title`,`slug`,`text`,`image`,`created_at` FROM site_ads WHERE `id`=:blood_id and `is_blood`=:is_blood and `status`=:status ",[":is_blood" => $blood,":blood_id" => $blood_id, ":status" => 1])->cache(60)->queryOne();

        return $result;
    }

}
