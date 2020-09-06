<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "site_qebul".
 *
 * @property int $id
 * @property string $fullname
 * @property string $email
 * @property string $telefon
 * @property string $date
 * @property string $time
 * @property int $doctor_id
 * @property int $user_id
 * @property int $status
 */
class SiteCalling extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_appoint';
    }

    public static function getSuitTimes($doctor_id,$date)
    {
        $result = SiteCalling::find()->where(['doctor_id'=>$doctor_id,'date'=>$date, 'status'=>1])->all();
        return $result;
    }

    public function getStoppedTime($doctor_id,$date)
    {
        $userID = $this->db->createCommand("SELECT `user_id` FROM `site_doctors` WHERE `id`=$doctor_id")->queryOne();

        $result = $this->db->createCommand("SELECT `time` FROM site_appoint
                                            WHERE `date`='$date' AND user_id='$userID[user_id]' AND status=1")->queryAll();
        return $result;
    }

    public static function hasSuitTimes($doctor_id,$date,$time)
    {
        $result = SiteCalling::find('')->where(['doctor_id'=>$doctor_id,'date'=>$date,'time'=>$time, 'status'=>1])->all();
        if(empty($result))
           return true;

        return false;
    }

    public function getReservedTimes($user_id,$limit,$offset)
    {
       $result = $this->db->createCommand("SELECT * FROM site_appoint where `doctor_id`=$user_id ORDER BY `id` DESC LIMIT $limit OFFSET $offset")->queryAll();
        return $result;
    }

    public function getCountReservedTimes($user_id)
    {
        $result = $this->db->createCommand("SELECT count(`id`) as `counter` FROM site_appoint where `doctor_id`=$user_id")->queryScalar();
        return $result;
    }

    public function getReserveByUser($user_id)
    {
        return $this->db->createCommand("SELECT site_appoint.*, site_doctors.id, site_doctors.name as doctor_name, site_doctors.photo, site_doctors.slug FROM site_appoint LEFT JOIN site_doctors ON site_appoint.doctor_id = site_doctors.id where site_appoint.`user_id`=:user_id and site_appoint.`status`=:status ", [':user_id' => $user_id, ':status' => 1])->queryAll();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fullname','telefon','email','date'], 'required'],
            [['date'], 'safe'],
            [['doctor_id', 'status', 'user_id'], 'integer'],
            [['fullname', 'email'], 'string', 'max' => 255],
            [['telefon'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fullname' => 'Ad, Soyad',
            'email' => 'Elektron Poçt',
            'telefon' => 'Telefon',
            'date' => 'Tarix',
            'time' => 'Zaman',
            'doctor_id' => 'Həkim',
            'status' => 'Status',
            'user_id' => 'User ID',
        ];
    }
}
