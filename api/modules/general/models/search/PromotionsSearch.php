<?php

namespace api\modules\general\models\search;

use backend\models\SiteEnterprises;
use Yii;

/**
 * This is the model class for table "site_promotions".
 *
 * @property string $headline
 * @property string $date_start
 * @property string $date_end
 * @property string $organizer
 * @property string $status
 */
class PromotionsSearch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */


    public static function tableName()

    {

        return 'site_promotions';

    }

    /**
     * {@inheritdoc}
     */

    public function rules()

    {

        return [

            [['status'], 'string'],

            [['date_start','date_end'], 'safe'],

            [['headline', 'organizer'], 'string', 'min' => 3, 'max' => 250],

        ];

    }


    /**
     * {@inheritdoc}
     */

    public function attributeLabels()

    {

        return [

            'headline' => 'Başlıq',

            'date_start' => 'Başlanğıc Tarix',

            'date_end' => 'Son Tarix',

            'organizer' => 'Oqanizator',

            'status' => 'Status'

        ];

    }

    /**
     * Get status
     */
    public static function get_Status()
    {
        return [
            0 => 'DeAktiv',
            1 => 'Aktiv'
        ];
    }

    public function searchCount($search)
    {
        if ($search['status'] == 'all') {

            $status = ' status <> 3';
        } else {
            $status = "status= " . $search['status'] . " AND status <> 2";
        }

        $organizer = !empty($search['organizer']) ? $search['organizer'] : '----';
        $date_start = !empty($search['date_start']) ? $search['date_start'] : '----';
        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `view_promotions_search` WHERE " . $status . " AND ((`organizer` LIKE '%$organizer%') OR (`doctor_name` LIKE '%$organizer%') OR (`enter_name` LIKE '%$organizer%') OR (`date_start` LIKE '%$date_start%'))")->queryOne();

    }


    public function search($search, $limits)
    {
        if ($search['status'] == 'all') {

            $status = ' status <> 3';
        } else {
            $status = "status= " . $search['status'] . " AND status <> 2";
        }

        $organizer = !empty($search['organizer']) ? $search['organizer'] : '----';
        $date_start = !empty($search['date_start']) ? $search['date_start'] : '----';
        return Yii::$app->db->createCommand("SELECT * FROM `view_promotions_search` WHERE " . $status . " AND ((`organizer` LIKE '%$organizer%') OR (`doctor_name` LIKE '%$organizer%') OR (`enter_name` LIKE '%$organizer%') OR (`date_start` LIKE '%$date_start%')) ORDER BY `id` DESC LIMIT {$limits[0]},{$limits[1]}")->queryAll();

    }

    public function usedSearchCount($search)
    {

        if ($search['status'] == 'all') {

            $status = 'status <> 3';
        } else {
            $status = "status= " . $search['status'] . " AND status <> 3";
        }

        $organizer = !empty($search['organizer']) ? $search['organizer'] : '----';
        $date_start = !empty($search['date_start']) ? $search['date_start'] : date('Y-m-d');
        $date_end = !empty($search['date_end']) ? $search['date_end'] : date('Y-m-d');

        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `view_promotions_used_search` WHERE " . $status . " AND ((`organizer` LIKE '%$organizer%') OR (`doctor_name` LIKE '%$organizer%') OR (`enter_name` LIKE '%$organizer%') OR (`created_at` BETWEEN CAST('$date_start' AS DATE) AND CAST('$date_end' AS DATE)))")->queryOne();

    }

    public function usedSearch($search, $limits)
    {

        if ($search['status'] == 'all') {

            $status = 'status <> 3';
        } else {
            $status = "status= " . $search['status'] . " AND status <> 3";
        }

        $organizer = !empty($search['organizer']) ? $search['organizer'] : '----';
        $date_start = !empty($search['date_start']) ? $search['date_start'] : date('Y-m-d');
        $date_end = !empty($search['date_end']) ? $search['date_end'] : date('Y-m-d');

        return Yii::$app->db->createCommand("SELECT * FROM `view_promotions_used_search` WHERE " . $status . " AND ((`organizer` LIKE '%$organizer%') OR (`doctor_name` LIKE '%$organizer%') OR (`enter_name` LIKE '%$organizer%') OR (`created_at` BETWEEN CAST('$date_start' AS DATE) AND CAST('$date_end' AS DATE))) ORDER BY `id` DESC LIMIT {$limits[0]},{$limits[1]}")->queryAll();

    }


}
