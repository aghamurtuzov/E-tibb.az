<?php

namespace api\models;

use Yii;
Yii::$app->db->schema->refresh();
/**
 * This is the model class for table "site_transaction_details".
 *
 * @property int $id
 * @property int $doctor_id
 * @property int $day
 * @property string $setting
 *
 */
class AppointmentWorkdaysSettings extends \yii\db\ActiveRecord
{
    public $Interval;
    public $StartTime;
    public $EndTime;
    public $breakTimeStart;
    public $breakTimeEnd;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_doctor_workdays_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doctor_id'], 'number'],
            ['day','number'],
            ['StartTime','string','min'=>5,'max'=>5],
            ['EndTime','string','min'=>5,'max'=>5],
            ['breakTimeStart','string','min'=>5,'max'=>5],
            ['breakTimeEnd','string','min'=>5,'max'=>5],
            ['setting','string','max'=>250],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'doctor_id' => 'Hekim ID',
            'day' => 'Gün',
            'interval' => 'Interval',
            'StartTime' => 'İşin başlama vaxtı',
            'EndTime' => 'İşin bitmə vaxtı',
            'breakTimeStart' => 'Nahar başlama vaxtı',
            'breakTimeEnd' => 'Nahar bitmə vaxtı',
            'setting' => 'Tənzimləmə'
        ];
    }
}
