<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SimakPencekalan;

/**
 * SimakPencekalanSearch represents the model behind the search form of `app\models\SimakPencekalan`.
 */
class SimakPencekalanSearch extends SimakPencekalan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'tahfidz', 'adm', 'akpam', 'akademik'], 'integer'],
            [['tahun_id', 'nim', 'created_at', 'updated_at'], 'safe'],
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
        $query = SimakPencekalan::find();

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
            'tahfidz' => $this->tahfidz,
            'adm' => $this->adm,
            'akpam' => $this->akpam,
            'akademik' => $this->akademik,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'tahun_id', $this->tahun_id])
            ->andFilterWhere(['like', 'nim', $this->nim]);

        return $dataProvider;
    }
}
