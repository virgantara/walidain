<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SyaratPencekalan;

/**
 * SyaratPencekalanSearch represents the model behind the search form of `app\models\SyaratPencekalan`.
 */
class SyaratPencekalanSearch extends SyaratPencekalan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'tahun_id'], 'integer'],
            [['namaKomponen','komponen_id'], 'safe'],
            [['nilai_minimal'], 'number'],
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
        $query = SyaratPencekalan::find();
        $query->joinWith(['komponen as k']);
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

        $dataProvider->sort->attributes['namaKomponen'] = [
            'asc' => ['k.nama'=>SORT_ASC],
            'desc' => ['k.nama'=>SORT_DESC]
        ];

        $query->andFilterWhere(['like', 'k.nama', $this->namaKomponen]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tahun_id' => $this->tahun_id,
            'nilai_minimal' => $this->nilai_minimal,
        ]);

        return $dataProvider;
    }
}
