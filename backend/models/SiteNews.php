<?php

namespace backend\models;
 
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
            [['headline',], 'required'],
            [['category_id', 'news_read', 'is_video'], 'integer'],
            [['content'], 'string'],
            [['photo','mainImage','deletedImages'],'string'],
            [['datetime'], 'safe'],
            [['headline','keywords', 'slug'], 'string', 'max' => 250],
            [['files'], 'file','skipOnEmpty' => true, 'extensions'=>'png,jpg','maxFiles' => 30,'wrongExtension'=>'Yalnız {extensions}']
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
            'content' => 'Kontent',
            'datetime' => 'Tarix',
            'news_read' => 'Baxış Sayı',
        ];
    }
}
