<?php

namespace api\modules\enterprise\models\search;

use Yii;

/**
 * This is the model class for table "site_news".
 *
 * @property int $id
 * @property int $category_id
 * @property string $headline
 * @property string $datetime
 * @property string $status
 */
class NewsSearch extends \yii\db\ActiveRecord
{
    public $id;

    /**
     * {@inheritdoc}
     */
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
            [['category_id'], 'integer'],
            [['status'], 'string'],
            [['datetime'], 'safe'],
            [['headline'], 'string', 'min' => 3, 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Kateqoriya',
            'headline' => 'Başlıq',
            'datetime' => 'Tarix',
            'status' => 'Status'
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
        $category_id = !empty($search['category_id']) ? $search['category_id'] : '----';
        $connect_id = 870;

        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_news` WHERE " . $status . " AND `category_id` = :category_id AND `connect_id`=:connect_id AND ((`headline` LIKE '%$headline%') OR (`datetime` LIKE '%$date%'))", [':connect_id' => $connect_id, ':category_id' => $category_id])->queryOne();

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
        $category_id = !empty($search['category_id']) ? $search['category_id'] : '----';
        $connect_id =870;

        return Yii::$app->db->createCommand("SELECT * FROM `site_news` WHERE " . $status . " AND `category_id` = :category_id AND `connect_id`=:connect_id AND ((`headline` LIKE '%$headline%') OR (`datetime` LIKE '%$date%')) ORDER BY `id` DESC LIMIT {$limits[0]},{$limits[1]}", [':connect_id' => $connect_id,':category_id' => $category_id])->queryAll();

    }

}
