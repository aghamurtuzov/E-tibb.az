<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "site_sosial_links".
 *
 * @property int $id
 * @property int $connect_id
 * @property string $link
 * @property int $link_type 0 - Facebook | 1 - İnstagram | 2 - Youtube | 3 - Twitter | 4 - Linkedin
 * @property int $type
 */
class SiteSosialLinks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_sosial_links';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['connect_id', 'link', 'link_type', 'type'], 'required'],
            [['connect_id', 'link_type', 'type'], 'integer'],
            [['link'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'connect_id' => 'Connect ID',
            'link' => 'Link',
            'link_type' => 'Link Type',
            'type' => 'Type',
        ];
    }
}
