<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sms".
 *
 * @property int $id
 * @property int $user_id
 * @property string $phone
 * @property string $text
 * @property string $status
 * @property string $created_at
 * @property int $updated_at
 */
class Sms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone', 'text'], 'required','message' => '{attribute} boş ola bilməz.'],
            [['user_id', 'updated_at','created_at'], 'integer'],
            [['status' ], 'safe'],
            [['phone'], 'string', 'max' => 15 ],
            [['text'], 'string', 'max' => 500],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'phone' => 'Nömrə',
            'text' => 'Mətn',
            'status' => 'Status',
            'created_at' => 'Yaradıldı',
            'updated_at' => 'Yeniləndi',
        ];
    }
}
