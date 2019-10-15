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
            [['CUSTID', 'METODE', 'TRXDATE', 'NOREFF', 'FIDBANK', 'KDCHANNEL', 'REFFBANK', 'TRANSNO', 'created_at', 'updated_at'], 'safe'],
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
            'urut' => $this->urut,
            'TRXDATE' => $this->TRXDATE,
            'DEBET' => $this->DEBET,
            'KREDIT' => $this->KREDIT,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'CUSTID', $this->CUSTID])
            ->andFilterWhere(['like', 'METODE', $this->METODE])
            ->andFilterWhere(['like', 'NOREFF', $this->NOREFF])
            ->andFilterWhere(['like', 'FIDBANK', $this->FIDBANK])
            ->andFilterWhere(['like', 'KDCHANNEL', $this->KDCHANNEL])
            ->andFilterWhere(['like', 'REFFBANK', $this->REFFBANK])
            ->andFilterWhere(['like', 'TRANSNO', $this->TRANSNO]);

        return $dataProvider;
    }
}
