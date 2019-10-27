<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SimakTahunakademik;

/**
 * SimakTahunakademikSearch represents the model behind the search form of `app\models\SimakTahunakademik`.
 */
class SimakTahunakademikSearch extends SimakTahunakademik
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'semester', 'proses_buka', 'proses_ipk', 'proses_tutup', 'max_sks'], 'integer'],
            [['tahun_id', 'tahun', 'nama_tahun', 'krs_mulai', 'krs_selesai', 'krs_online_mulai', 'krs_online_selesai', 'krs_ubah_mulai', 'krs_ubah_selesai', 'kss_cetak_mulai', 'kss_cetak_selesai', 'cuti', 'mundur', 'bayar_mulai', 'bayar_selesai', 'kuliah_mulai', 'kuliah_selesai', 'uts_mulai', 'uts_selesai', 'uas_mulai', 'uas_selesai', 'nilai', 'akhir_kss', 'buka', 'syarat_krs', 'syarat_krs_ips', 'cuti_selesai'], 'safe'],
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
        $query = SimakTahunakademik::find();

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
            'semester' => $this->semester,
            'krs_mulai' => $this->krs_mulai,
            'krs_selesai' => $this->krs_selesai,
            'krs_online_mulai' => $this->krs_online_mulai,
            'krs_online_selesai' => $this->krs_online_selesai,
            'krs_ubah_mulai' => $this->krs_ubah_mulai,
            'krs_ubah_selesai' => $this->krs_ubah_selesai,
            'kss_cetak_mulai' => $this->kss_cetak_mulai,
            'kss_cetak_selesai' => $this->kss_cetak_selesai,
            'cuti' => $this->cuti,
            'mundur' => $this->mundur,
            'bayar_mulai' => $this->bayar_mulai,
            'bayar_selesai' => $this->bayar_selesai,
            'kuliah_mulai' => $this->kuliah_mulai,
            'kuliah_selesai' => $this->kuliah_selesai,
            'uts_mulai' => $this->uts_mulai,
            'uts_selesai' => $this->uts_selesai,
            'uas_mulai' => $this->uas_mulai,
            'uas_selesai' => $this->uas_selesai,
            'nilai' => $this->nilai,
            'akhir_kss' => $this->akhir_kss,
            'proses_buka' => $this->proses_buka,
            'proses_ipk' => $this->proses_ipk,
            'proses_tutup' => $this->proses_tutup,
            'cuti_selesai' => $this->cuti_selesai,
            'max_sks' => $this->max_sks,
        ]);

        $query->andFilterWhere(['like', 'tahun_id', $this->tahun_id])
            ->andFilterWhere(['like', 'tahun', $this->tahun])
            ->andFilterWhere(['like', 'nama_tahun', $this->nama_tahun])
            ->andFilterWhere(['like', 'buka', $this->buka])
            ->andFilterWhere(['like', 'syarat_krs', $this->syarat_krs])
            ->andFilterWhere(['like', 'syarat_krs_ips', $this->syarat_krs_ips]);

        return $dataProvider;
    }
}
