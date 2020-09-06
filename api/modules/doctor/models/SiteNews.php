<?php

namespace api\modules\doctor\models;
 
use Yii;

/**
 * This is the model class for table "site_news".
 *
 * @property int $id
 * @property int $category_id
 * @property string $photo
 * @property string $headline
 * @property string $content
 * @property string $datetime
 * @property int $news_read
 */
class SiteNews extends \yii\db\ActiveRecord
{
    public $files;
    public $mainImage;
    public $deletedImages;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_news';
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
            [['headline','datetime','content','category_id','keywords'], 'required'],
            ['datetime', 'datetime', 'format' => 'php:Y-m-d H:i'],
            [['category_id', 'news_read','status','connect_id','type'], 'integer'],
            [['content'], 'string'],
            [['photo','mainImage','deletedImages'],'string'],
            [['datetime'], 'safe'],
            [['headline', 'keywords', 'content'], 'string', 'min' => 5],
            [['headline','keywords', 'slug'], 'string', 'max' => 250],
            [['files'], 'file','skipOnEmpty' => true, 'extensions'=>'png,jpg,jpeg','maxFiles' => 30,'wrongExtension'=>'Yalnız {extensions}']
        ];
    }

    /**
     * Get status
     */
    public static function get_Status()
    {
        return [
            0 => 'DeAktiv',
            1 => 'Aktiv'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Kateqoriya',
            'photo' => 'Şəkil ( png , jpg )',
            'headline' => 'Başlıq',
            'slug' => 'Link',
            'keywords' => 'Açar Sözlər',
            'content' => 'Mətn',
            'datetime' => 'Tarix',
            'news_read' => 'Baxış Sayı',
        ];
    }

    public static function newsFind($id)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_news` WHERE `id`=:id", [':id' => $id])->queryOne();

    }
}
