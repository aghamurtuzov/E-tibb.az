<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "site_doctors_specialist".
 *
 * @property int $id
 * @property int $doctor_id
 * @property int $specialist_id
 */
class SiteDoctorSpecialist extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_doctor_specialist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doctor_id', 'specialist_id'], 'required'],
            [['doctor_id', 'specialist_id'], 'integer'],
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
            'specialist_id' => 'Specialist ID',
        ];
    }
}
