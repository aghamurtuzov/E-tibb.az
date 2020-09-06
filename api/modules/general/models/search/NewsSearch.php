<?php

namespace api\modules\general\models\search;

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
            [['id','category_id'], 'integer'],
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
            'id' => 'ID',
            'category_id' => 'Kateqoriya',
            'headline' => 'Başlıq',
            'datetime' => 'Tarix',
            'status' => 'Status'
        ];
    }

    public function searchCount($search)
    {

        $headline = !empty($search['headline']) ? $search['headline'] : '----';
        $datetime = !empty($search['datetime']) ? $search['datetime'] : '----';
        $id = !empty($search['id']) ? $search['id'] : 0;
        $category_id = !empty($search['category_id']) ? $search['category_id'] : 37;
        if ($search['status'] == 'all') {

            $status = ' status <> 2';
        } else {
            $status = "status= " . $search['status'] . " AND status <> 2";
        }

        return Yii::$app->db->createCommand("SELECT DISTINCT count(`id`) AS `count` FROM `site_news` WHERE " . $status . " AND `category_id` = :category_id AND ((`id`=:id) OR (`headline` LIKE '%$headline%') OR (`datetime` LIKE '%$datetime%'))", ['id' => $id, ':category_id' => $category_id])->queryOne();
    }


    public function search($search, $limits)
    {

        $headline = !empty($search['headline']) ? $search['headline'] : '----';
        $datetime = !empty($search['datetime']) ? $search['datetime'] : '----';
        $id = !empty($search['id']) ? $search['id'] : 0;
        $category_id = !empty($search['category_id']) ? $search['category_id'] : 37;
        if ($search['status'] == 'all') {

            $status = ' status <> 2';
        } else {
            $status = "status= " . $search['status'] . " AND status <> 2";
        }

        return Yii::$app->db->createCommand("SELECT DISTINCT id,photo,headline,datetime,news_read,status FROM `site_news` WHERE " . $status . " AND `category_id` = :category_id AND ((`id`=:id) OR (`headline` LIKE '%$headline%') OR (`datetime` LIKE '%$datetime%')) ORDER BY `id` DESC LIMIT {$limits[0]},{$limits[1]}", ['id' => $id, ':category_id' => $category_id])->queryAll();
    }

}
