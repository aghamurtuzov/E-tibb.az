<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "site_consultation".
 *
 * @property int $id
 * @property int $doctor_id
 * @property string $name
 * @property string $email
 * @property string $question
 * @property string $q_datetime
 * @property string $answer
 * @property string $a_datetime
 * @property int $status
 * @property int $a_status
 */
class SiteConsultation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_consultation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doctor_id', 'name', 'email', 'question'], 'required'],
            [['doctor_id', 'status', 'a_status'], 'integer'],
            [['question', 'answer'], 'string'],
            [['q_datetime', 'a_datetime'], 'safe'],
            [['name'], 'string', 'max' => 250],
            [['email'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'doctor_id' => 'Həkim',
            'name' => 'Ad, Soyad',
            'email' => 'Email',
            'question' => 'Sual',
            'q_datetime' => 'Sual vaxtı',
            'answer' => 'Cavab',
            'a_datetime' => 'Cavab vaxtı',
            'status' => 'Sual',
            'a_status' => 'Cavab',
        ];
    }
}
