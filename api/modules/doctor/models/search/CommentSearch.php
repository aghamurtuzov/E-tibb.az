<?php

namespace api\modules\doctor\models\search;

use Yii;

/**
 * This is the model class for table "site_comments".
 *
 * @property string $name
 * @property string $datetime
 * @property string $status
 */
class CommentSearch extends \yii\db\ActiveRecord
{
    public static $tableName = 'site_comments';
    //public $doctor_name;
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
            [['datetime'], 'safe'],
            [['name'], 'string', 'min' => 3, 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Ad',
            'datetime' => 'Tarix'
        ];
    }

    public function searchCount($search, $doctor_id, $status)
    {
        $name = !empty($search['name']) ? $search['name'] : '----';
        $datetime = !empty($search['datetime']) ? $search['datetime'] : '----';

        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `view_comments_search` WHERE status=:status AND ((`name` LIKE '%$name%') OR (`datetime` LIKE '%$datetime%')) AND connect_id=:doctor_id", [":status" => $status, ':doctor_id' => $doctor_id])->queryOne();
    }

    public function search($search, $limits, $doctor_id, $status)
    {
        $name = !empty($search['name']) ? $search['name'] : '----';
        $datetime = !empty($search['datetime']) ? $search['datetime'] : '----';

        return Yii::$app->db->createCommand("SELECT * FROM `view_comments_search` WHERE status=:status AND ((`name` LIKE '%$name%') OR (`datetime` LIKE '%$datetime%')) AND connect_id=:doctor_id ORDER BY `id` DESC LIMIT {$limits[0]},{$limits[1]}", [":status" => $status, ':doctor_id' => $doctor_id])->queryAll();
    }

}
