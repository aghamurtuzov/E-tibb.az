<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "site_doctor_workplaces_list".
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property int $doctor_id
 */
class SiteDoctorWorkplacesList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_doctor_workplaces_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doctor_id'], 'required'],
            [['doctor_id'], 'integer'],
            [['name', 'address'], 'string', 'max' => 250],
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
            'address' => 'Address',
            'doctor_id' => 'Doctor ID',
        ];
    }
}
