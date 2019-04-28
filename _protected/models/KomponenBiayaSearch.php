<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\KomponenBiaya;

/**
 * KomponenBiayaSearch represents the model behind the search form of `app\models\KomponenBiaya`.
 */
class KomponenBiayaSearch extends KomponenBiaya
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'periode_tagihan_id', 'prioritas'], 'integer'],
            [['kode', 'nama', 'created_at', 'updated_at'], 'safe'],
            [['biaya_awal'], 'number'],
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
        $query = KomponenBiaya::find();

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
            'periode_tagihan_id' => $this->periode_tagihan_id,
            'biaya_awal' => $this->biaya_awal,
            'prioritas' => $this->prioritas,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'kode', $this->kode])
            ->andFilterWhere(['like', 'nama', $this->nama]);

        return $dataProvider;
    }
}
