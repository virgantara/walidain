<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SimakMasterdosen;

/**
 * SimakMasterdosenSearch represents the model behind the search form of `app\models\SimakMasterdosen`.
 */
class SimakMasterdosenSearch extends SimakMasterdosen
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'provinsi_dosen'], 'integer'],
            [['kode_pt', 'kode_fakultas', 'kode_jurusan', 'kode_prodi', 'kode_jenjang_studi', 'no_ktp_dosen', 'nidn_asli', 'nidn', 'niy', 'nama_dosen', 'gelar_depan', 'gelar_akademik', 'tempat_lahir_dosen', 'tgl_lahir_dosen', 'jenis_kelamin', 'kode_jabatan_akademik', 'kode_pendidikan_tertinggi', 'kode_status_kerja_pts', 'kode_status_aktivitas_dosen', 'tahun_semester', 'nip_pns', 'home_base', 'photo_dosen', 'no_telp_dosen', 'no_hp_dosen', 'email_dosen', 'alamat_dosen', 'alamat_domisili', 'kabupaten_dosen', 'agama_dosen', 'created', 'google_scholar_id', 'scopus_id', 'sinta_id', 'kode_feeder', 'id_reg_ptk', 'status_aktif'], 'safe'],
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
        $query = SimakMasterdosen::find();

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
            'tgl_lahir_dosen' => $this->tgl_lahir_dosen,
            'provinsi_dosen' => $this->provinsi_dosen,
            'created' => $this->created,
        ]);

        $query->andFilterWhere(['like', 'kode_pt', $this->kode_pt])
            ->andFilterWhere(['like', 'kode_fakultas', $this->kode_fakultas])
            ->andFilterWhere(['like', 'kode_jurusan', $this->kode_jurusan])
            ->andFilterWhere(['like', 'kode_prodi', $this->kode_prodi])
            ->andFilterWhere(['like', 'kode_jenjang_studi', $this->kode_jenjang_studi])
            ->andFilterWhere(['like', 'no_ktp_dosen', $this->no_ktp_dosen])
            ->andFilterWhere(['like', 'nidn_asli', $this->nidn_asli])
            ->andFilterWhere(['like', 'nidn', $this->nidn])
            ->andFilterWhere(['like', 'niy', $this->niy])
            ->andFilterWhere(['like', 'nama_dosen', $this->nama_dosen])
            ->andFilterWhere(['like', 'gelar_depan', $this->gelar_depan])
            ->andFilterWhere(['like', 'gelar_akademik', $this->gelar_akademik])
            ->andFilterWhere(['like', 'tempat_lahir_dosen', $this->tempat_lahir_dosen])
            ->andFilterWhere(['like', 'jenis_kelamin', $this->jenis_kelamin])
            ->andFilterWhere(['like', 'kode_jabatan_akademik', $this->kode_jabatan_akademik])
            ->andFilterWhere(['like', 'kode_pendidikan_tertinggi', $this->kode_pendidikan_tertinggi])
            ->andFilterWhere(['like', 'kode_status_kerja_pts', $this->kode_status_kerja_pts])
            ->andFilterWhere(['like', 'kode_status_aktivitas_dosen', $this->kode_status_aktivitas_dosen])
            ->andFilterWhere(['like', 'tahun_semester', $this->tahun_semester])
            ->andFilterWhere(['like', 'nip_pns', $this->nip_pns])
            ->andFilterWhere(['like', 'home_base', $this->home_base])
            ->andFilterWhere(['like', 'photo_dosen', $this->photo_dosen])
            ->andFilterWhere(['like', 'no_telp_dosen', $this->no_telp_dosen])
            ->andFilterWhere(['like', 'no_hp_dosen', $this->no_hp_dosen])
            ->andFilterWhere(['like', 'email_dosen', $this->email_dosen])
            ->andFilterWhere(['like', 'alamat_dosen', $this->alamat_dosen])
            ->andFilterWhere(['like', 'alamat_domisili', $this->alamat_domisili])
            ->andFilterWhere(['like', 'kabupaten_dosen', $this->kabupaten_dosen])
            ->andFilterWhere(['like', 'agama_dosen', $this->agama_dosen])
            ->andFilterWhere(['like', 'google_scholar_id', $this->google_scholar_id])
            ->andFilterWhere(['like', 'scopus_id', $this->scopus_id])
            ->andFilterWhere(['like', 'sinta_id', $this->sinta_id])
            ->andFilterWhere(['like', 'kode_feeder', $this->kode_feeder])
            ->andFilterWhere(['like', 'id_reg_ptk', $this->id_reg_ptk])
            ->andFilterWhere(['like', 'status_aktif', $this->status_aktif]);

        return $dataProvider;
    }
}
