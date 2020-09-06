<?php

namespace api\modules\enterprise\models\search;

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


    public $doctor_name;


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

            [['date_start', 'date_end'], 'safe'],

        ];

    }


    /**
     * {@inheritdoc}
     */

    public function attributeLabels()

    {

        return [

            'date_start' => 'Başlanğıc Tarix',

            'date_end' => 'Son Tarix',

            'status' => 'Status',

        ];

    }

    public function searchCount($search)
    {

        if ($search['status'] == 'all') {

            $status = ' status <> 3';
        } else {
            $status = "status= " . $search['status'] . " AND status <> 2";
        }

        $date_start = !empty($search['date_start']) ? $search['date_start'] : '----';
        $date_end = !empty($search['date_end']) ? $search['date_end'] : '----';
        $connect_id = Yii::$app->session->get('userID');

        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_promotions` WHERE " . $status . " AND `connect_id`=:connect_id AND `type` = 2 AND ((`date_start` LIKE '%$date_start%') OR (`date_end` LIKE '%$date_end%'))", ['connect_id' => $connect_id])->queryOne();

    }


    public function search($search, $limits)
    {

        if ($search['status'] == 'all') {

            $status = ' status <> 3';
        } else {
            $status = "status= " . $search['status'] . " AND status <> 2";
        }

        $date_start = !empty($search['date_start']) ? $search['date_start'] : '----';
        $date_end = !empty($search['date_end']) ? $search['date_end'] : '----';
        $connect_id = Yii::$app->session->get('userID');

        return Yii::$app->db->createCommand("SELECT `id`,`photo`,`headline`,`organizer`,`price`,`discount`,`date_start`,`connect_id`,`date_end`,`status` FROM `site_promotions` WHERE " . $status . " AND `connect_id`=:connect_id AND `type` = 2 AND ((`date_start` LIKE '%$date_start%') OR (`date_end` LIKE '%$date_end%')) ORDER BY `id` DESC LIMIT {$limits[0]},{$limits[1]}", ['connect_id' => $connect_id])->queryAll();

    }

    public function usedSearchCount($search)
    {

        $date_start = !empty($search['date_start']) ? $search['date_start'] : date('Y-m-d');
        $date_end = !empty($search['date_end']) ? $search['date_end'] : date('Y-m-d');
        $connect_id = Yii::$app->session->get('userID');

        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `view_doctors_used_promotions` WHERE `connect_id`=:connect_id AND ((`created_at` BETWEEN CAST('$date_start' AS DATE) AND CAST('$date_end' AS DATE)) )", ['connect_id' => $connect_id])->queryOne();

    }

    public function usedSearch($search, $limits)
    {

        $date_start = !empty($search['date_start']) ? $search['date_start'] : date('Y-m-d');
        $date_end = !empty($search['date_end']) ? $search['date_end'] : date('Y-m-d');
        $connect_id = Yii::$app->session->get('userID');

        return Yii::$app->db->createCommand("SELECT * FROM `view_doctors_used_promotions` WHERE  `connect_id`=:connect_id AND ((`created_at` BETWEEN CAST('$date_start' AS DATE) AND CAST('$date_end' AS DATE))) ORDER BY `id` DESC LIMIT {$limits[0]},{$limits[1]}", ['connect_id' => $connect_id])->queryAll();

    }


}
