<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "site_contact".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $title
 * @property string $text
 * @property string $created_at
 */
class SiteContact extends \yii\db\ActiveRecord
{
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
            [['name', 'email', 'title', 'text'], 'required', 'message' => '{attribute} xanasını boş buraxmayın'],
            [['id'], 'integer'],
            [['name', 'title', 'text'], 'string'],
            [['datetime'], 'safe'],
            [['email'], 'email']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Ad',
            'email' => 'E-mail',
            'title' => 'Mövzu',
            'text' => 'Mesaj',
            'datetime' => 'Datetime'
        ];
    }
}
