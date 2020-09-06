<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SiteDoctors;

/**
 * SiteDoctorsSearch represents the model behind the search form of `backend\models\SiteDoctors`.
 */
class SiteDoctorsSearch extends SiteDoctors
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'vip', 'promotion', 'feature'], 'integer'],
            [['name', 'photo', 'expires', 'data', 'about', 'services_prices'], 'safe'],
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
        $query = SiteDoctors::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder'=>['id'=>SORT_DESC]
            ]
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
            'expires' => $this->expires,
            'vip' => $this->vip,
            'promotion' => $this->promotion,
            'feature' => $this->feature,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'about', $this->about])
            ->andFilterWhere(['like', 'services_prices', $this->services_prices]);

        return $dataProvider;
    }
}
