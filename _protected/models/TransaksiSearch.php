<?php

namespace app\models;

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
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urut', 'DEBET', 'KREDIT'], 'integer'],
            [['CUSTID', 'METODE', 'TRXDATE', 'NOREFF', 'FIDBANK', 'KDCHANNEL', 'REFFBANK', 'TRANSNO', 'created_at', 'updated_at','namaCustomer'], 'safe'],
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
        $query->joinWith(['cUST as m']);
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

        $dataProvider->sort->attributes['namaCustomer'] = [
            'asc' => ['m.nama_mahasiswa'=>SORT_ASC],
            'desc' => ['m.nama_mahasiswa'=>SORT_DESC]
        ];

        // grid filtering conditions
        // $query->andFilterWhere([
        //     'urut' => $this->urut,
        //     'TRXDATE' => $this->TRXDATE,
        //     'DEBET' => $this->DEBET,
        //     'KREDIT' => $this->KREDIT,
        //     'created_at' => $this->created_at,
        //     'updated_at' => $this->updated_at,
        // ]);

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
