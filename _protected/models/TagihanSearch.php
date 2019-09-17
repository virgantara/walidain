<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tagihan;

/**
 * TagihanSearch represents the model behind the search form of `app\models\Tagihan`.
 */
class TagihanSearch extends Tagihan
{

    public $tanggal_awal;
    public $tanggal_akhir;
    public $namaKomponen;
    public $namaCustomer;
    public $namaProdi;
    public $namaKampus;
    public $namaTahun;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'urutan', 'semester', 'tahun', 'komponen_id', 'edit', 'status_bayar'], 'integer'],
            [['nim', 'created_at', 'updated_at','namaKomponen','namaCustomer','namaProdi','namaKampus','namaTahun'], 'safe'],
            [['nilai', 'terbayar'], 'number'],
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
        $query = Tagihan::find();
        $query->joinWith(['komponen as k','customer as c','tahun0 as t']);


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['updated_at'=>SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['namaKomponen'] = [
            'asc' => ['k.nama'=>SORT_ASC],
            'desc' => ['k.nama'=>SORT_DESC]
        ];

        $dataProvider->sort->attributes['namaCustomer'] = [
            'asc' => ['c.nama'=>SORT_ASC],
            'desc' => ['c.nama'=>SORT_DESC]
        ];

        $dataProvider->sort->attributes['namaProdi'] = [
            'asc' => ['c.nama_prodi'=>SORT_ASC],
            'desc' => ['c.nama_prodi'=>SORT_DESC]
        ];

        $dataProvider->sort->attributes['namaKampus'] = [
            'asc' => ['c.nama_kampus'=>SORT_ASC],
            'desc' => ['c.nama_kampus'=>SORT_DESC]
        ];

        $dataProvider->sort->attributes['namaTahun'] = [
            'asc' => ['t.nama'=>SORT_ASC],
            'desc' => ['t.nama'=>SORT_DESC]
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'urutan' => $this->urutan,
            'semester' => $this->semester,
            'tahun' => $this->tahun,
            'komponen_id' => $this->komponen_id,
            'nilai' => $this->nilai,
            'terbayar' => $this->terbayar,
            'edit' => $this->edit,
            'status_bayar' => $this->status_bayar,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'nim', $this->nim])
            ->andFilterWhere(['like', 'k.nama', $this->namaKomponen])
            ->andFilterWhere(['like', 'c.nama', $this->namaCustomer])
            ->andFilterWhere(['like', 'c.nama_prodi', $this->namaProdi])
            ->andFilterWhere(['like', 'c.nama_kampus', $this->namaKampus])
            ->andFilterWhere(['like', 't.nama', $this->namaTahun]);

        return $dataProvider;
    }
}
