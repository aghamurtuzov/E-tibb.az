<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "view_doctor_specialist".
 *
 * @property int $doctor_id
 * @property int $id
 * @property string $name
 * @property string $icon
 * @property int $count
 */
class ViewDoctorSpecialist extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_doctor_specialist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doctor_id'], 'required'],
            [['doctor_id', 'id', 'count'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['icon'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'doctor_id' => 'Doctor ID',
            'id' => 'ID',
            'name' => 'Name',
            'icon' => 'Icon',
            'count' => 'Count',
        ];
    }
}
