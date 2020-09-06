<?php
namespace backend\models;
use Yii;
/**
 * This is the model class for table "site_ads".
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $slug
 * @property string $text
 * @property string $image
 * @property int $type 0 => user; 1 => Doctor; 2 => Enterprise 
 * @property string $premium_expiry
 * @property int $premium_type 0 => yoxdur; 1 => 3gun; 2 => 1hefte; 3 => 1ay
 * @property double $rating_value
 * @property int $review_count
 * @property int $is_blood 0=>not blood; 1=> blood type ad. 
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 */
class SiteAds extends \yii\db\ActiveRecord
{
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
//            [['user_id', 'title', 'slug', 'text', 'type'], 'required'],
//            [['user_id', 'type', 'premium_type', 'review_count', 'is_blood', 'status'], 'integer'],
//            [['title', 'slug', 'image'], 'string'],
//            [['premium_expiry', 'created_at', 'updated_at'], 'safe'],
//            [['rating_value'], 'number'],
//            [['text'], 'string', 'max' => 500],
            [['title', 'text'], 'required', 'message' => '{attribute} boş olmamalıdır'],
            [['title', 'slug', 'image'], 'string'],
            [['type', 'review_count', 'is_blood', 'status'], 'integer'],
            [['premium_expiry', 'created_at', 'updated_at'], 'safe'],
            [['rating_value'], 'number'],
            [['text'], 'string', 'max' => 500],
            [['image'], 'file','skipOnEmpty' => true, 'extensions'=>'png,jpg','maxFiles' => 20,'wrongExtension'=>'Yalnız {extensions}'],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
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
            'created_at' => 'Yaradılma tarixi',
            'updated_at' => 'Dəyişdirilmə tarixi',
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
}
