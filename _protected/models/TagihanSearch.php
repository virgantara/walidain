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
    public $excludeWisuda;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'urutan', 'semester', 'tahun', 'komponen_id', 'edit', 'status_bayar'], 'integer'],
            [['nim', 'created_at', 'updated_at','namaKomponen','namaCustomer','namaProdi','namaKampus','namaTahun','nilai_minimal'], 'safe'],
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

    public function searchManual()
    {
        $query = Tagihan::find();
        $query->joinWith([
            'komponen as k',
            'nim0 as c',
            'tahun0 as t',
            'nim0.kodeProdi as p',
            'nim0.kampus0 as kps'
        ]);

        // grid filtering conditions
        $query->andWhere([
            'k.tahun' => $this->tahun,
            'p.kode_prodi' => $this->namaProdi,
            'c.kampus' => $this->namaKampus,
            'c.status_aktivitas' => 'A'
        ]);

        $query->andWhere('terbayar < nilai');

        if($this->excludeWisuda == 1)
        {
            $query->andWhere(['NOT LIKE','k.kode','06%',false]);
        }

        $query->orderBy(['c.nama_mahasiswa'=>SORT_ASC]);
            

        return $query->all();
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
        $query->joinWith([
            'komponen as k',
            'nim0 as c',
            'tahun0 as t',
            'nim0.kodeProdi as p',
            'nim0.kampus0 as kps'
        ]);

        $query->andWhere(['t.buka' => 'Y']);
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
            'asc' => ['c.nama_mahasiswa'=>SORT_ASC],
            'desc' => ['c.nama_mahasiswa'=>SORT_DESC]
        ];

        $dataProvider->sort->attributes['namaProdi'] = [
            'asc' => ['p.nama_prodi'=>SORT_ASC],
            'desc' => ['p.nama_prodi'=>SORT_DESC]
        ];

        $dataProvider->sort->attributes['namaKampus'] = [
            'asc' => ['kps.nama_kampus'=>SORT_ASC],
            'desc' => ['kps.nama_kampus'=>SORT_DESC]
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

        if(!empty($this->namaKampus))
            $query->where(['kps.nama_kampus'=> $this->namaKampus]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'urutan' => $this->urutan,
            self::tableName().'.semester' => $this->semester,
            'tahun' => $this->tahun,
            'komponen_id' => $this->komponen_id,
            'nilai' => $this->nilai,
            'terbayar' => $this->terbayar,
            'edit' => $this->edit,
            self::tableName().'.status_bayar' => $this->status_bayar,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);



        $query->andFilterWhere(['like', 'nim', $this->nim])
            ->andFilterWhere(['like', 'k.nama', $this->namaKomponen])
            ->andFilterWhere(['like', 'c.nama_mahasiswa', $this->namaCustomer])
            ->andFilterWhere(['like', 'p.nama_prodi', $this->namaProdi])
            
            ->andFilterWhere(['like', 't.nama', $this->namaTahun]);

        return $dataProvider;
    }
}
