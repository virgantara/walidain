<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SimakKampus;

/**
 * SimakKampusSearch represents the model behind the search form of `app\models\SimakKampus`.
 */
class SimakKampusSearch extends SimakKampus
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'kmi'], 'integer'],
            [['kode_kampus', 'nama_kampus', 'prefix'], 'safe'],
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
        $query = SimakKampus::find();

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
            'kmi' => $this->kmi,
        ]);

        $query->andFilterWhere(['like', 'kode_kampus', $this->kode_kampus])
            ->andFilterWhere(['like', 'nama_kampus', $this->nama_kampus])
            ->andFilterWhere(['like', 'prefix', $this->prefix]);

        return $dataProvider;
    }
}
