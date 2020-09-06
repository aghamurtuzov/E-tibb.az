<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "site_gallery".
 *
 * @property int $id
 * @property string $photo
 * @property int $connect_id
 * @property int $type 1 - Doctor | 2 - Enterprise | 3 - News
 * @property int $main 	0 - Regular | 1 - Main
 */
class SiteGallery extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_gallery';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['photo', 'connect_id', 'type', 'main'], 'required'],
            [['connect_id', 'type', 'main'], 'integer'],
            [['photo'], 'string', 'max' => 60]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'photo' => 'Photo',
            'connect_id' => 'Connect ID',
            'type' => 'Type',
            'main'=>'Əsas şəkil'
        ];
    }
}
