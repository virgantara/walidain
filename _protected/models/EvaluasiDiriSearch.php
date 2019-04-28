<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EvaluasiDiri;

/**
 * EvaluasiDiriSearch represents the model behind the search form of `app\models\EvaluasiDiri`.
 */
class EvaluasiDiriSearch extends EvaluasiDiri
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'departemen_id', 'is_verified'], 'integer'],
            [['tanggal', 'strength', 'weakness', 'opportunity', 'threat', 'attachment', 'created_at', 'updated_at'], 'safe'],
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
        $query = EvaluasiDiri::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'departemen_id' => $this->departemen_id,
            'tanggal' => $this->tanggal,
            'is_verified' => $this->is_verified,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'strength', $this->strength])
            ->andFilterWhere(['like', 'weakness', $this->weakness])
            ->andFilterWhere(['like', 'opportunity', $this->opportunity])
            ->andFilterWhere(['like', 'threat', $this->threat])
            ->andFilterWhere(['like', 'attachment', $this->attachment]);

        return $dataProvider;
    }
}
