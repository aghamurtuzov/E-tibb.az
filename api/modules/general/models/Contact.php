<?php

namespace api\modules\general\models;

use Yii;

/**
 * This is the model class for table "site_ads".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $title
 * @property string $text
 * @property string $datetime
 */
class Contact extends \yii\db\ActiveRecord
{
    public static $tableName = 'site_contact';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_contact';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'title', 'text'], 'required', 'message' => '{attribute} boş olmamalıdır'],
            [['email'], 'trim'],
            ['email', 'email'],
            ['datetime', 'safe'],
            [['title'], 'string', 'min' => 3, 'max' => 100],
            [['status'], 'integer'],
            [['text'], 'string'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Ad Soyad',
            'email' => 'Email',
            'id' => 'ID',
            'title' => 'Başlıq',
            'text' => 'Mətn',
            'datetime' => 'Tarix'

        ];
    }



}
