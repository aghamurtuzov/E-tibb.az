<?php

namespace api\modules\general\models\search;

use Yii;

/**
 * This is the model class for table "site_consultation".
 *
 * @property string $doctor_name
 * @property string $status
 * @property string $q_datetime
 */
class ConsultationSearch extends \yii\db\ActiveRecord
{

    public $doctor_name;

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
            [['q_datetime'], 'safe'],
            [['doctor_name'], 'string', 'min' => 3, 'max' => 100],
            [['status'], 'string']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'doctor_name' => 'Ad',
            'status' => 'Status',
            'q_datetime' => 'Tarix',
        ];
    }

    public function searchCount($search)
    {

        if ($search['status'] == 'all') {

            $status = ' status <> 2';
        } else {
            $status = "status= " . $search['status'] . " AND status <> 2";
        }
        $doctor_name = !empty($search['doctor_name']) ? $search['doctor_name'] : '----';
        $q_datetime = !empty($search['q_datetime']) ? $search['q_datetime'] : '----';

        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count`  FROM `view_consultation_search` WHERE " . $status . " AND 
                ((`doctor_name` LIKE '%$doctor_name%') OR (`q_datetime` LIKE '%$q_datetime%'))", [":status" => $status])->queryOne();

    }

    public function search($search, $limits)
    {

        if ($search['status'] == 'all') {

            $status = ' status <> 2';
        } else {
            $status = "status= " . $search['status'] . " AND status <> 2";
        }
        $doctor_name = !empty($search['doctor_name']) ? $search['doctor_name'] : '----';
        $q_datetime = !empty($search['q_datetime']) ? $search['q_datetime'] : '----';

        return Yii::$app->db->createCommand("SELECT * FROM `view_consultation_search` WHERE " . $status . " AND 
                ((`doctor_name` LIKE '%$doctor_name%') OR (`q_datetime` LIKE '%$q_datetime%')) ORDER BY `id` DESC LIMIT {$limits[0]},{$limits[1]}", [":status" => $status])->queryAll();

    }

}
