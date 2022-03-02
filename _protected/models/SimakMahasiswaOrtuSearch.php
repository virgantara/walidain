<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SimakMahasiswaOrtu;

/**
 * SimakMahasiswaOrtuSearch represents the model behind the search form of `app\models\SimakMahasiswaOrtu`.
 */
class SimakMahasiswaOrtuSearch extends SimakMahasiswaOrtu
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_synced', 'ortu_user_id'], 'integer'],
            [['nik', 'nim', 'hubungan', 'nama', 'tanggal_lahir', 'agama', 'pendidikan', 'pekerjaan', 'penghasilan', 'hidup', 'alamat', 'kota', 'propinsi', 'negara', 'pos', 'telepon', 'hp', 'email', 'created_at', 'updated_at'], 'safe'],
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
        $query = SimakMahasiswaOrtu::find();

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
            'tanggal_lahir' => $this->tanggal_lahir,
            'is_synced' => $this->is_synced,
            'ortu_user_id' => $this->ortu_user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'nik', $this->nik])
            ->andFilterWhere(['like', 'nim', $this->nim])
            ->andFilterWhere(['like', 'hubungan', $this->hubungan])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'agama', $this->agama])
            ->andFilterWhere(['like', 'pendidikan', $this->pendidikan])
            ->andFilterWhere(['like', 'pekerjaan', $this->pekerjaan])
            ->andFilterWhere(['like', 'penghasilan', $this->penghasilan])
            ->andFilterWhere(['like', 'hidup', $this->hidup])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'kota', $this->kota])
            ->andFilterWhere(['like', 'propinsi', $this->propinsi])
            ->andFilterWhere(['like', 'negara', $this->negara])
            ->andFilterWhere(['like', 'pos', $this->pos])
            ->andFilterWhere(['like', 'telepon', $this->telepon])
            ->andFilterWhere(['like', 'hp', $this->hp])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
