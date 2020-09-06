<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "emails".
 *
 * @property int $id
 * @property int $user_id
 * @property string $email
 * @property string $text
 * @property int $status 0=>waiting,1=>send,2=>success,3=>error	
 * @property string $created_at
 * @property string $updated_at
 */
class Email extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'emails';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'email', 'text'], 'required' , 'message'=>'{attribute} boş ola bilməz'],
            [['user_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['email'], 'string', 'max' => 50],
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
            'email' => 'E-mail',
            'text' => 'Mətn',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
