<?php

namespace backend\models;

use Yii;
use backend\models\SiteDoctors;
use backend\models\SiteEnterprises;
use dp\models\SitePatients;
/**
 * This is the model class for table "site_appointment".
 *
 * @property int $id
 * @property string $name
 * @property string $phone_numbers
 * @property string $email
 * @property string $content
 * @property string $date
 * @property string $time
 * @property int $connect_id
 * @property int $type 1 - Doctor | 2 - Enterprise
 * @property int $site
 */
class SiteAppointment extends \yii\db\ActiveRecord
{
    public $patient;
    public $email;
    public $phone_number;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_appointment';
    }

    public static function get_Type()
    {
        return [
            1 => 'Həkim',
            2 => 'Obyekt'
        ];
    }


    public static function get_Connected($id, $type)
    {
        
        if( $type == 1)
        {
            $connected = SiteDoctors::find()->where(['id' => $id,])->one();
        }
        else if( $type== 2 )
        {
            $connected = SiteEnterprises::find()->where(['id' => $id,])->one();
        }
        if(!empty($connected))
        {
            return $connected->name;
        }
        return 'Yoxdur';
    }

    //get patient data [name, lastname, email, phonenumbers]
    public static function get_Patient($patient_id,$doctor_id)
    {
        $patient = SitePatients::find()->where(['id'=>$patient_id,'doctor_id'=>$doctor_id])->one();
        if(!empty($patient)){
            return $patient;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content', 'date', 'time', 'connect_id', 'type', 'site','status'], 'required'],
            [['content'], 'string'],
            [['date'], 'safe'],
            [['connect_id','patient_id', 'type', 'site'], 'integer'],
            [['time'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Info',
            'date' => 'Tarix',
            'time' => 'Saat',
            'connect_id' => 'Həkim',
            'patient_id' => 'Pasient',
            'type' => 'Tip',
            'site' => 'Mənbə',
            'status' => 'Status',
            'email' => 'Elektron Poçt',
            'phone_number' => 'Telefon nömrəsi',
            'patient' => 'Pasient',
        ];
    }
}
