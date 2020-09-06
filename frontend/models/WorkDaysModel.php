<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "site_workdays".
 *
 * @property int $id
 * @property int $connect_id
 * @property int $time_interval
 * @property string $workdays
 * @property string $date
 */
class WorkDaysModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_workdays';
    }

    public function getUserWorkDaysCount($user_id)
    {
        $result = $this->db->createCommand("SELECT count('id') FROM `site_workdays` WHERE `connect_id`=$user_id ")->queryScalar();
        return $result;
    }

    public function getUserWorkDays($user_id,$limit,$offset)
    {
        $result = $this->db->createCommand("SELECT * FROM `site_workdays` WHERE `connect_id`=$user_id ORDER BY `id` DESC LIMIT $limit OFFSET $offset")->queryAll();
        return $result;
    }

    public function getUserWorkday($user_id, $date)
    {
        $result = $this->db->createCommand("SELECT `workdays` FROM `site_workdays` WHERE `connect_id`=$user_id and `date`='$date'")->queryOne();
        return $result;
    }

    public function hasDate($user_id,$date)
    {
        $result = $this->db->createCommand("SELECT count(id) FROM `site_workdays` WHERE `connect_id`=$user_id and `date`='$date'")->queryScalar();
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['connect_id','time_interval'], 'required'],
            [['connect_id', 'time_interval'], 'integer'],
            [['workdays'], 'string'],
            [['date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '',
            'connect_id' => '',
            'time_interval' => '',
            'workdays' => '',
            'date' => '',
        ];
    }
}
