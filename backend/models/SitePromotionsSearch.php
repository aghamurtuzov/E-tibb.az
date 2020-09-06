<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SitePromotions;

/**
 * SitePromotionsSearch represents the model behind the search form of `backend\models\SitePromotions`.
 */
class SitePromotionsSearch extends SitePromotions
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'price', 'discount', 'connect_id', 'type'], 'integer'],
            [['headline', 'date_start', 'date_end', 'photo', 'organizer'], 'safe'],
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
        $query = SitePromotions::find();

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
            'price' => $this->price,
            'discount' => $this->discount,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'connect_id' => $this->connect_id,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'headline', $this->headline])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'organizer', $this->organizer]);

        return $dataProvider;
    }
}
