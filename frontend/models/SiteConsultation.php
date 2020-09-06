<?php

namespace frontend\models;

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
 */
class SiteConsultation extends \yii\db\ActiveRecord
{

    public $info;

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
            [['name', 'email', 'question'], 'required','message'=>'{attribute} xanasını boş buraxmayın'],
            [['doctor_id','user_id'], 'integer'],
            [['question', 'answer','info'], 'string'],
            [['q_datetime', 'a_datetime'], 'safe'],
            [['name'], 'string', 'min' => 3],
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
            'doctor_id' => 'Doctor ID',
            'user_id' => 'User ID',
            'name' => 'Ad',
            'email' => 'E-mail',
            'question' => 'Sualınız',
            'q_datetime' => 'Q Datetime',
            'answer' => 'Answer',
            'a_datetime' => 'A Datetime',
        ];
    }
}
