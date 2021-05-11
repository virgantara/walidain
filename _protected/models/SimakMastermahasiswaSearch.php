<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SimakMastermahasiswa;

/**
 * SimakMastermahasiswaSearch represents the model behind the search form of `app\models\SimakMastermahasiswa`.
 */
class SimakMastermahasiswaSearch extends SimakMastermahasiswa
{
    public $namaProdi;
    public $namaKampus;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status_bayar', 'status_mahasiswa', 'is_synced', 'kamar_id', 'is_eligible'], 'integer'],
            [['kode_pt', 'kode_fakultas', 'kode_prodi', 'kode_jenjang_studi', 'nim_mhs', 'nama_mahasiswa', 'tempat_lahir', 'tgl_lahir', 'jenis_kelamin', 'tahun_masuk', 'semester_awal', 'batas_studi', 'asal_propinsi', 'tgl_masuk', 'tgl_lulus', 'status_aktivitas', 'status_awal', 'jml_sks_diakui', 'nim_asal', 'asal_pt', 'nama_asal_pt', 'asal_jenjang_studi', 'asal_prodi', 'kode_biaya_studi', 'kode_pekerjaan', 'tempat_kerja', 'kode_pt_kerja', 'kode_ps_kerja', 'nip_promotor', 'nip_co_promotor1', 'nip_co_promotor2', 'nip_co_promotor3', 'nip_co_promotor4', 'photo_mahasiswa', 'semester', 'keterangan', 'telepon', 'hp', 'email', 'alamat', 'berat', 'tinggi', 'ktp', 'rt', 'rw', 'dusun', 'kode_pos', 'desa', 'kecamatan', 'kecamatan_feeder', 'jenis_tinggal', 'penerima_kps', 'no_kps', 'provinsi', 'kabupaten', 'status_warga', 'warga_negara', 'warga_negara_feeder', 'status_sipil', 'agama', 'gol_darah', 'masuk_kelas', 'tgl_sk_yudisium', 'no_ijazah', 'kampus', 'jur_thn_smta', 'kode_pd', 'va_code', 'created_at', 'updated_at','namaProdi','namaKampus'], 'safe'],
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
        $query = SimakMastermahasiswa::find();
        $query->joinWith(['kampus0 as k']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // $query->where(['status_aktivitas'=>'A']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dataProvider->sort->attributes['namaKampus'] = [
            'asc' => ['k.nama_kampus'=>SORT_ASC],
            'desc' => ['k.nama_kampus'=>SORT_DESC]
        ];

        $dataProvider->sort->attributes['namaProdi'] = [
            'asc' => ['p.nama_prodi'=>SORT_ASC],
            'desc' => ['p.nama_prodi'=>SORT_DESC]
        ];


        // grid filtering conditions
        $query->andFilterWhere([
            'semester' => $this->semester,
            'tgl_lahir' => $this->tgl_lahir,
            'tgl_masuk' => $this->tgl_masuk,
            'tgl_lulus' => $this->tgl_lulus,
            'status_bayar' => $this->status_bayar,
            'tgl_sk_yudisium' => $this->tgl_sk_yudisium,
            'status_mahasiswa' => $this->status_mahasiswa,
            'is_synced' => $this->is_synced,
            'kamar_id' => $this->kamar_id,
            'is_eligible' => $this->is_eligible,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        if(Yii::$app->user->identity->access_role == 'admin')
        {
            $list_kampus = explode(',', Yii::$app->user->identity->kampus);
            $query->andWhere(['IN','kampus',$list_kampus]);
            // $query->andFilterWhere(['or',
            //     ['kampus'=>Yii::$app->user->identity->kampus],
            //     ['kampus'=>Yii::$app->user->identity->kampus2]
                
            // ]);
        }

        if(!empty($this->kode_prodi))
        {
            $query->andWhere(['kode_prodi'=>$this->kode_prodi]);
        }

        if(!empty($this->kampus))
        {
            $query->andWhere(['kampus'=>$this->kampus]);
        }

        if(!empty($this->nim_mhs))
        {
            $query->andWhere(['nim_mhs'=>$this->nim_mhs]);
        }


        $query->andFilterWhere(['like', 'kode_fakultas', $this->kode_fakultas])
            
            ->andFilterWhere(['like', 'nama_mahasiswa', $this->nama_mahasiswa])
            ->andFilterWhere(['like', 'tempat_lahir', $this->tempat_lahir])
            ->andFilterWhere(['like', 'jenis_kelamin', $this->jenis_kelamin])
            ->andFilterWhere(['like', 'status_aktivitas', $this->status_aktivitas])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan])
            ->andFilterWhere(['like', 'telepon', $this->telepon])
            ->andFilterWhere(['like', 'hp', $this->hp])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'ktp', $this->ktp])
            ->andFilterWhere(['like', 'kecamatan', $this->kecamatan])
            ->andFilterWhere(['like', 'kecamatan_feeder', $this->kecamatan_feeder])
            ->andFilterWhere(['like', 'jenis_tinggal', $this->jenis_tinggal])
            ->andFilterWhere(['like', 'provinsi', $this->provinsi])
            ->andFilterWhere(['like', 'kabupaten', $this->kabupaten])
            ->andFilterWhere(['like', 'status_warga', $this->status_warga])
            ->andFilterWhere(['like', 'warga_negara', $this->warga_negara])
            ->andFilterWhere(['like', 'warga_negara_feeder', $this->warga_negara_feeder])
            ->andFilterWhere(['like', 'status_sipil', $this->status_sipil])
            ->andFilterWhere(['like', 'va_code', $this->va_code]);

        return $dataProvider;
    }
}
