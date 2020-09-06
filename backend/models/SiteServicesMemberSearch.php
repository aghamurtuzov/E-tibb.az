<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SiteServicesMember;

/**
 * SiteServicesMemberSearch represents the model behind the search form of `backend\models\SiteServicesMember`.
 */
class SiteServicesMemberSearch extends SiteServicesMember
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'connect_id', 'type', 'service_id'], 'integer'],
            [['payment_date', 'expires_date'], 'safe'],
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
        $query = SiteServicesMember::find();

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
            'order_id' => $this->order_id,
            'connect_id' => $this->connect_id,
            'type' => $this->type,
            'service_id' => $this->service_id,
            'payment_date' => $this->payment_date,
            'expires_date' => $this->expires_date,
        ]);

        return $dataProvider;
    }
}
