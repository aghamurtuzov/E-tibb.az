<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SiteNews;

/**
 * SiteNewsSearch represents the model behind the search form of `app\models\SiteNews`.
 */
class SiteNewsSearch extends SiteNews
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'news_read'], 'integer'],
            [['photo', 'headline', 'content', 'datetime'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SiteNews::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'datetime' => $this->datetime,
            'news_read' => $this->news_read,
        ]);

        $query->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'headline', $this->headline])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }

    public static function getAllNews()
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT count(`id`) as `count` FROM site_news WHERE category_id in (20,28,34) and `status`=1")->cache(120)->queryScalar();
        return $result;
    }
}
