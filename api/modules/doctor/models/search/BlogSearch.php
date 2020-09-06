<?php

namespace api\modules\doctor\models\search;

use Yii;

/**
 * This is the model class for table "site_promotions".
 *
 * @property string $id
 * @property string $headline
 * @property string $date
 * @property int $status
 */
class BlogSearch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public $id;
    public $headline;
    public $date;
    public $status;

    public static function tableName()

    {

        return 'site_news';

    }

    /**
     * {@inheritdoc}
     */

    public function rules()

    {

        return [

            [['id'], 'integer'],

            [['headline'], 'string', 'min' => 3, 'max' => 250],

            [['status'], 'string'],

            [['date'], 'safe'],

        ];

    }


    /**
     * {@inheritdoc}
     */

    public function attributeLabels()

    {

        return [

            'id' => 'İd',

            'status' => 'Status',

            'date' => 'Tarix',

            'headline' => 'Başlıq',

        ];

    }

    public function searchCount($search)
    {

        if ($search['status'] == 'all') {

            $status = ' status <> 2';
        } else {
            $status = "status= " . $search['status'] . " AND status <> 2";
        }

        $date = !empty($search['date']) ? $search['date'] : '----';
        $headline = !empty($search['headline']) ? $search['headline'] : '----';
        $id = !empty($search['id']) ? $search['id'] : '----';
        $connect_id =Yii::$app->session->get('userID');

        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_news` WHERE " . $status . " AND `category_id` = 34 AND `connect_id`=:connect_id AND ((`headline` LIKE '%$headline%') OR (`datetime` LIKE '%$date%') OR (`id`=:id))", [':connect_id' => $connect_id, ':id' => $id])->queryOne();

    }


    public function search($search, $limits)
    {

        if ($search['status'] == 'all') {

            $status = ' status <> 2';
        } else {
            $status = "status= " . $search['status'] . " AND status <> 2";
        }

        $date = !empty($search['date']) ? $search['date'] : '----';
        $headline = !empty($search['headline']) ? $search['headline'] : '----';
        $id = !empty($search['id']) ? $search['id'] : '----';
        $connect_id = Yii::$app->session->get('userID');

        return Yii::$app->db->createCommand("SELECT * FROM `site_news` WHERE " . $status . " AND `category_id` = 34 AND `connect_id`=:connect_id AND ((`headline` LIKE '%$headline%') OR (`datetime` LIKE '%$date%') OR (`id`=:id)) ORDER BY `id` DESC LIMIT {$limits[0]},{$limits[1]}", [':connect_id' => $connect_id, ':id' => $id])->queryAll();

    }


}
