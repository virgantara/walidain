<?php

namespace app\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Transaksi;

/**
 * TransaksiSearch represents the model behind the search form of `app\models\Transaksi`.
 */
class TransaksiSearch extends Transaksi
{
    public $tanggal_awal;
    public $tanggal_akhir;
    public $namaKomponen;
    public $namaCustomer;
    public $namaProdi;
    public $namaKampus;
    public $namaTahun;
    public $semester;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urut', 'DEBET', 'KREDIT'], 'integer'],
            [['CUSTID', 'METODE', 'TRXDATE', 'NOREFF', 'FIDBANK', 'KDCHANNEL', 'REFFBANK', 'TRANSNO', 'created_at', 'updated_at','namaCustomer','tagihan_id','semester','namaProdi','namaKampus'], 'safe'],
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
        $query = Transaksi::find();
        $query->joinWith(['cUST as m','cUST.kodeProdi as p','cUST.kampus0 as k']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at'=>SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(!empty(Yii::$app->user->identity) && Yii::$app->user->identity->access_role == 'admin')
        {
            $list_kampus = explode(',', Yii::$app->user->identity->kampus);
            $query->andWhere(['IN','m.kampus',$list_kampus]);
        }

        $dataProvider->sort->attributes['namaCustomer'] = [
            'asc' => ['m.nama_mahasiswa'=>SORT_ASC],
            'desc' => ['m.nama_mahasiswa'=>SORT_DESC]
        ];

        $dataProvider->sort->attributes['namaProdi'] = [
            'asc' => ['p.nama_prodi'=>SORT_ASC],
            'desc' => ['p.nama_prodi'=>SORT_DESC]
        ];

        $dataProvider->sort->attributes['namaKampus'] = [
            'asc' => ['k.nama_kampus'=>SORT_ASC],
            'desc' => ['k.nama_kampus'=>SORT_DESC]
        ];

        $dataProvider->sort->attributes['semester'] = [
            'asc' => ['m.semester'=>SORT_ASC],
            'desc' => ['m.semester'=>SORT_DESC]
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'm.semester' => $this->semester,
            'TRXDATE' => $this->TRXDATE,
            'DEBET' => $this->DEBET,
            'KREDIT' => $this->KREDIT,
            'm.kode_prodi' => $this->namaProdi,
            'k.kode_kampus' => $this->namaKampus
        ]);

        $query->andFilterWhere(['like', 'CUSTID', $this->CUSTID])
            ->andFilterWhere(['like', 'METODE', $this->METODE])
            ->andFilterWhere(['like', 'm.nama_mahasiswa', $this->namaCustomer])
            ->andFilterWhere(['like', 'NOREFF', $this->NOREFF])
            ->andFilterWhere(['like', 'FIDBANK', $this->FIDBANK])
            ->andFilterWhere(['like', 'KDCHANNEL', $this->KDCHANNEL])
            ->andFilterWhere(['like', 'REFFBANK', $this->REFFBANK])
            ->andFilterWhere(['like', 'TRANSNO', $this->TRANSNO]);

        return $dataProvider;
    }
}
