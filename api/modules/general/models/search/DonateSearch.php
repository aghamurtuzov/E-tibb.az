<?php

namespace api\modules\general\models\search;

use Yii;

/**
 * @property string $status
 * @property string $date
 */
class DonateSearch extends \yii\db\ActiveRecord
{
    public static $tableName = 'site_ads';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_ads';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'string'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'status' => 'Status',
            'date' => 'Tarix',
        ];
    }

    public function searchCount($search)
    {
        if ($search['status'] == 'all') {
            $status = 'status <> 3';
        } else {
            $status = "status= " . $search['status'] . " AND status <> 3";
        }
        $date = !empty($search['date']) ? $search['date'] : '----';

        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_ads` WHERE " . $status . " AND `created_at` LIKE '%$date%'")->queryOne();

    }


    public function search($search, $limits)
    {
        if ($search['status'] == 'all') {
            $status = 'status <> 3';
        } else {
            $status = "status= " . $search['status'] . " AND status <> 3";
        }
        $date = !empty($search['date']) ? $search['date'] : '----';

        return Yii::$app->db->createCommand("SELECT * FROM `site_ads` WHERE " . $status . " AND `created_at` LIKE '%$date%' ORDER BY `id` DESC LIMIT {$limits[0]},{$limits[1]}")->queryAll();

    }


}
