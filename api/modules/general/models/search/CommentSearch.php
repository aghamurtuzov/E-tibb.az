<?php

namespace api\modules\general\models\search;

use Yii;

/**
 * This is the model class for table "site_comments".
 *
 * @property string $doctor_name
 * @property string $datetime
 * @property string $status
 */
class CommentSearch extends \yii\db\ActiveRecord
{
    public static $tableName = 'site_comments';
    public $doctor_name;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'string'],
            [['datetime'], 'safe'],
            [['doctor_name'], 'string', 'min' => 3, 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'doctor_name' => 'Ad',
            'datetime' => 'Tarix',
            'status' => 'Status',
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
        $datetime = !empty($search['datetime']) ? $search['datetime'] : '----';

        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `view_comments_search` WHERE " . $status . " AND ((`doctor_name` LIKE '%$doctor_name%') OR (`datetime` LIKE '%$datetime%'))", [":status" => $status])->queryOne();

    }


    public function search($search,$limits)
    {

        if ($search['status'] == 'all') {

            $status = ' status <> 2';
        } else {
            $status = "status= " . $search['status'] . " AND status <> 2";
        }
        $doctor_name = !empty($search['doctor_name']) ? $search['doctor_name'] : '----';
        $datetime = !empty($search['datetime']) ? $search['datetime'] : '----';

        return Yii::$app->db->createCommand("SELECT * FROM `view_comments_search` WHERE " . $status . " AND ((`doctor_name` LIKE '%$doctor_name%') OR (`datetime` LIKE '%$datetime%')) ORDER BY `id` DESC LIMIT {$limits[0]},{$limits[1]}", [":status" => $status])->queryAll();

    }


}
