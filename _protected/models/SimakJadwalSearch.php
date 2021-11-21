<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SimakJadwal;

/**
 * SimakJadwalSearch represents the model behind the search form of `app\models\SimakJadwal`.
 */
class SimakJadwalSearch extends SimakJadwal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'matkul_id', 'kuota_kelas', 'jadwal_temp_id', 'jumlah_tatap_muka', 'kapasitas'], 'integer'],
            [['hari', 'jam', 'kode_mk', 'kode_dosen', 'kode_pengampu_nidn', 'semester', 'kelas', 'fakultas', 'prodi', 'kd_ruangan', 'tahun_akademik', 'kampus', 'presensi', 'materi', 'bobot_formatif', 'bobot_uts', 'bobot_uas', 'bobot_harian1', 'bobot_harian', 'kode_feeder', 'a_selenggara_pditt', 'bahasan_case', 'lingkup_kelas', 'mode_kuliah', 'tgl_mulai_koas', 'tgl_selesai_koas', 'classroom_id', 'alternateLink', 'enrollment_code', 'tgl_tutup_daftar', 'created_at', 'updated_at'], 'safe'],
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
        $query = SimakJadwal::find();

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
            'jam' => $this->jam,
            'matkul_id' => $this->matkul_id,
            'kuota_kelas' => $this->kuota_kelas,
            'jadwal_temp_id' => $this->jadwal_temp_id,
            'jumlah_tatap_muka' => $this->jumlah_tatap_muka,
            'tgl_mulai_koas' => $this->tgl_mulai_koas,
            'tgl_selesai_koas' => $this->tgl_selesai_koas,
            'tgl_tutup_daftar' => $this->tgl_tutup_daftar,
            'kapasitas' => $this->kapasitas,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'hari', $this->hari])
            ->andFilterWhere(['like', 'kode_mk', $this->kode_mk])
            ->andFilterWhere(['like', 'kode_dosen', $this->kode_dosen])
            ->andFilterWhere(['like', 'kode_pengampu_nidn', $this->kode_pengampu_nidn])
            ->andFilterWhere(['like', 'semester', $this->semester])
            ->andFilterWhere(['like', 'kelas', $this->kelas])
            ->andFilterWhere(['like', 'fakultas', $this->fakultas])
            ->andFilterWhere(['like', 'prodi', $this->prodi])
            ->andFilterWhere(['like', 'kd_ruangan', $this->kd_ruangan])
            ->andFilterWhere(['like', 'tahun_akademik', $this->tahun_akademik])
            ->andFilterWhere(['like', 'kampus', $this->kampus])
            ->andFilterWhere(['like', 'presensi', $this->presensi])
            ->andFilterWhere(['like', 'materi', $this->materi])
            ->andFilterWhere(['like', 'bobot_formatif', $this->bobot_formatif])
            ->andFilterWhere(['like', 'bobot_uts', $this->bobot_uts])
            ->andFilterWhere(['like', 'bobot_uas', $this->bobot_uas])
            ->andFilterWhere(['like', 'bobot_harian1', $this->bobot_harian1])
            ->andFilterWhere(['like', 'bobot_harian', $this->bobot_harian])
            ->andFilterWhere(['like', 'kode_feeder', $this->kode_feeder])
            ->andFilterWhere(['like', 'a_selenggara_pditt', $this->a_selenggara_pditt])
            ->andFilterWhere(['like', 'bahasan_case', $this->bahasan_case])
            ->andFilterWhere(['like', 'lingkup_kelas', $this->lingkup_kelas])
            ->andFilterWhere(['like', 'mode_kuliah', $this->mode_kuliah])
            ->andFilterWhere(['like', 'classroom_id', $this->classroom_id])
            ->andFilterWhere(['like', 'alternateLink', $this->alternateLink])
            ->andFilterWhere(['like', 'enrollment_code', $this->enrollment_code]);

        return $dataProvider;
    }
}
