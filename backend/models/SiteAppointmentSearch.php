<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SiteAppointment;

/**
 * SiteAppointmentSearch represents the model behind the search form of `backend\models\SiteAppointment`.
 */
class SiteAppointmentSearch extends SiteAppointment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'connect_id', 'patient_id', 'type', 'site', 'status'], 'integer'],
            [['content', 'date', 'time'], 'safe'],
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
        $query = SiteAppointment::find()->where(['site' => 1]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['date'=>SORT_DESC]]
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
            'date' => $this->date,
            'connect_id' => $this->connect_id,
            'patient_id' => $this->patient_id,
            'patient' => $this->patient,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'type' => $this->type,
            'site' => $this->site,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'patient', $this->patient])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'time', $this->time]);

        return $dataProvider;
    }
}
