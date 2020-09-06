<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "site_photo_gallery".
 *
 * @property int $id
 * @property string $name
 * @property int $connect_id
 * @property int $type
 * @property string $created_at
 */
class SitePhotoGallery extends \yii\db\ActiveRecord
{
    /** TYPES */
    const TYPE_NEWS = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_photo_gallery';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'connect_id', 'type'], 'required'],
            [['connect_id', 'type'], 'integer'],
            [['created_at'], 'default', 'value' => date('Y-m-d H:i:s')],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'connect_id' => 'Connect ID',
            'type' => 'Type',
            'created_at' => 'Created At',
        ];
    }


}
