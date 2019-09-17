<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Customer;

/**
 * CustomerSearch represents the model behind the search form of `app\models\Customer`.
 */
class CustomerSearch extends Customer
{
    public $saldo;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['custid', 'nama', 'va_code', 'kampus', 'nama_kampus', 'kode_prodi', 'nama_prodi', 'created_at', 'updated_at'], 'safe'],
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
        $query = Customer::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'custid', $this->custid])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'va_code', $this->va_code])
            ->andFilterWhere(['like', 'kampus', $this->kampus])
            ->andFilterWhere(['like', 'nama_kampus', $this->nama_kampus])
            ->andFilterWhere(['like', 'kode_prodi', $this->kode_prodi])
            ->andFilterWhere(['like', 'nama_prodi', $this->nama_prodi]);

        return $dataProvider;
    }
}
