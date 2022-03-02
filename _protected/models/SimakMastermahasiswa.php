<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "simak_mastermahasiswa".
 *
 * @property int $id
 * @property string $kode_pt
 * @property string $kode_fakultas
 * @property string $kode_prodi
 * @property string $kode_jenjang_studi
 * @property string $nim_mhs
 * @property string $nama_mahasiswa
 * @property string $tempat_lahir
 * @property string $tgl_lahir
 * @property string $jenis_kelamin
 * @property string $tahun_masuk
 * @property string $semester_awal
 * @property string $batas_studi
 * @property string $asal_propinsi
 * @property string $tgl_masuk
 * @property string $tgl_lulus
 * @property string $status_aktivitas
 * @property string $status_awal
 * @property string $jml_sks_diakui
 * @property string $nim_asal
 * @property string $asal_pt
 * @property string $nama_asal_pt
 * @property string $asal_jenjang_studi
 * @property string $asal_prodi
 * @property string $kode_biaya_studi
 * @property string $kode_pekerjaan
 * @property string $tempat_kerja
 * @property string $kode_pt_kerja
 * @property string $kode_ps_kerja
 * @property string $nip_promotor
 * @property string $nip_co_promotor1
 * @property string $nip_co_promotor2
 * @property string $nip_co_promotor3
 * @property string $nip_co_promotor4
 * @property string $photo_mahasiswa
 * @property string $semester
 * @property string $keterangan
 * @property int $status_bayar
 * @property string $telepon
 * @property string $hp
 * @property string $email
 * @property string $alamat
 * @property string $berat
 * @property string $tinggi
 * @property string $ktp
 * @property string $rt
 * @property string $rw
 * @property string $dusun
 * @property string $kode_pos
 * @property string $desa
 * @property string $kecamatan
 * @property string $kecamatan_feeder
 * @property string $jenis_tinggal
 * @property string $penerima_kps
 * @property string $no_kps
 * @property string $provinsi
 * @property string $kabupaten
 * @property string $status_warga
 * @property string $warga_negara
 * @property string $warga_negara_feeder
 * @property string $status_sipil
 * @property string $agama
 * @property string $gol_darah
 * @property string $masuk_kelas
 * @property string $tgl_sk_yudisium
 * @property string $no_ijazah
 * @property int $status_mahasiswa 1 Reguler, 2 Intensif
 * @property string $kampus
 * @property string $jur_thn_smta
 * @property int $is_synced
 * @property string $kode_pd
 * @property string $va_code
 * @property int $kamar_id
 * @property int $is_eligible
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Tagihan[] $tagihans
 * @property Transaksi[] $transaksis
 * @property ErpRiwayatPelanggaran[] $erpRiwayatPelanggarans
 * @property SimakMahasiswaOrtu[] $simakMahasiswaOrtus
 * @property SimakMahasiswaProgramTambahan[] $simakMahasiswaProgramTambahans
 * @property SimakMasterprogramstudi $kodeProdi
 * @property ErpKamar $kamar
 * @property SimakKampus $kampus0
 * @property SimakPencekalan[] $simakPencekalans
 * @property SimakTahfidzKelompokAnggotum[] $simakTahfidzKelompokAnggota
 * @property SimakTahfidzNilai[] $simakTahfidzNilais
 */
class SimakMastermahasiswa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'simak_mastermahasiswa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nim_mhs', 'nama_mahasiswa'], 'required'],
            [['va_code','va_oppal'], 'unique'],
            [['tgl_lahir', 'tgl_masuk', 'tgl_lulus', 'tgl_sk_yudisium', 'created_at', 'updated_at'], 'safe'],
            [['keterangan'], 'string'],
            [['status_bayar', 'status_mahasiswa', 'is_synced', 'kamar_id', 'is_eligible'], 'integer'],
            [['kode_pt', 'asal_prodi', 'kode_pos'], 'string', 'max' => 6],
            [['kode_fakultas', 'kode_prodi', 'kode_jenjang_studi', 'jenis_kelamin', 'semester_awal', 'batas_studi', 'status_awal', 'asal_jenjang_studi', 'semester', 'rt', 'rw'], 'string', 'max' => 5],
            [['nim_mhs', 'nama_asal_pt', 'telepon', 'hp'], 'string', 'max' => 25],
            [['nama_mahasiswa', 'dusun', 'desa', 'kecamatan', 'warga_negara', 'status_sipil', 'jur_thn_smta', 'kode_pd'], 'string', 'max' => 100],
            [['tempat_lahir', 'asal_propinsi', 'status_aktivitas', 'email', 'status_warga'], 'string', 'max' => 50],
            [['tahun_masuk'], 'string', 'max' => 4],
            [['jml_sks_diakui'], 'string', 'max' => 45],
            [['nim_asal', 'kode_biaya_studi', 'kode_pekerjaan', 'tempat_kerja', 'kode_pt_kerja'], 'string', 'max' => 55],
            [['asal_pt', 'ktp'], 'string', 'max' => 30],
            [['kode_ps_kerja', 'nip_promotor', 'nip_co_promotor4'], 'string', 'max' => 44],
            [['nip_co_promotor1'], 'string', 'max' => 11],
            [['nip_co_promotor2'], 'string', 'max' => 12],
            [['nip_co_promotor3'], 'string', 'max' => 33],
            [['photo_mahasiswa', 'alamat', 'kecamatan_feeder', 'provinsi', 'kabupaten', 'warga_negara_feeder', 'no_ijazah'], 'string', 'max' => 255],
            [['berat', 'tinggi'], 'string', 'max' => 3],
            [['jenis_tinggal', 'no_kps', 'agama', 'va_code'], 'string', 'max' => 20],
            [['penerima_kps', 'masuk_kelas'], 'string', 'max' => 1],
            [['gol_darah', 'kampus'], 'string', 'max' => 2],
            [['nim_mhs'], 'unique'],
            [['kode_prodi'], 'exist', 'skipOnError' => true, 'targetClass' => SimakMasterprogramstudi::className(), 'targetAttribute' => ['kode_prodi' => 'kode_prodi']],
            [['kamar_id'], 'exist', 'skipOnError' => true, 'targetClass' => ErpKamar::className(), 'targetAttribute' => ['kamar_id' => 'id']],
            [['kampus'], 'exist', 'skipOnError' => true, 'targetClass' => SimakKampus::className(), 'targetAttribute' => ['kampus' => 'kode_kampus']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_pt' => 'Kode Pt',
            'kode_fakultas' => 'Kode Fakultas',
            'kode_prodi' => 'Kode Prodi',
            'kode_jenjang_studi' => 'Kode Jenjang Studi',
            'nim_mhs' => 'Nim Mhs',
            'nama_mahasiswa' => 'Nama Mahasiswa',
            'tempat_lahir' => 'Tempat Lahir',
            'tgl_lahir' => 'Tgl Lahir',
            'jenis_kelamin' => 'Jenis Kelamin',
            'tahun_masuk' => 'Tahun Masuk',
            'semester_awal' => 'Semester Awal',
            'batas_studi' => 'Batas Studi',
            'asal_propinsi' => 'Asal Propinsi',
            'tgl_masuk' => 'Tgl Masuk',
            'tgl_lulus' => 'Tgl Lulus',
            'status_aktivitas' => 'Status Aktivitas',
            'status_awal' => 'Status Awal',
            'jml_sks_diakui' => 'Jml Sks Diakui',
            'nim_asal' => 'Nim Asal',
            'asal_pt' => 'Asal Pt',
            'nama_asal_pt' => 'Nama Asal Pt',
            'asal_jenjang_studi' => 'Asal Jenjang Studi',
            'asal_prodi' => 'Asal Prodi',
            'kode_biaya_studi' => 'Kode Biaya Studi',
            'kode_pekerjaan' => 'Kode Pekerjaan',
            'tempat_kerja' => 'Tempat Kerja',
            'kode_pt_kerja' => 'Kode Pt Kerja',
            'kode_ps_kerja' => 'Kode Ps Kerja',
            'nip_promotor' => 'Nip Promotor',
            'nip_co_promotor1' => 'Nip Co Promotor1',
            'nip_co_promotor2' => 'Nip Co Promotor2',
            'nip_co_promotor3' => 'Nip Co Promotor3',
            'nip_co_promotor4' => 'Nip Co Promotor4',
            'photo_mahasiswa' => 'Photo Mahasiswa',
            'semester' => 'Semester',
            'keterangan' => 'Keterangan',
            'status_bayar' => 'Status Bayar',
            'telepon' => 'Telepon',
            'hp' => 'Hp',
            'email' => 'Email',
            'alamat' => 'Alamat',
            'berat' => 'Berat',
            'tinggi' => 'Tinggi',
            'ktp' => 'Ktp',
            'rt' => 'Rt',
            'rw' => 'Rw',
            'dusun' => 'Dusun',
            'kode_pos' => 'Kode Pos',
            'desa' => 'Desa',
            'kecamatan' => 'Kecamatan',
            'kecamatan_feeder' => 'Kecamatan Feeder',
            'jenis_tinggal' => 'Jenis Tinggal',
            'penerima_kps' => 'Penerima Kps',
            'no_kps' => 'No Kps',
            'provinsi' => 'Provinsi',
            'kabupaten' => 'Kabupaten',
            'status_warga' => 'Status Warga',
            'warga_negara' => 'Warga Negara',
            'warga_negara_feeder' => 'Warga Negara Feeder',
            'status_sipil' => 'Status Sipil',
            'agama' => 'Agama',
            'gol_darah' => 'Gol Darah',
            'masuk_kelas' => 'Masuk Kelas',
            'tgl_sk_yudisium' => 'Tgl Sk Yudisium',
            'no_ijazah' => 'No Ijazah',
            'status_mahasiswa' => 'Status Mahasiswa',
            'kampus' => 'Kelas',
            'jur_thn_smta' => 'Jur Thn Smta',
            'is_synced' => 'Is Synced',
            'kode_pd' => 'Kode Pd',
            'va_code' => 'Va Code',
            'kamar_id' => 'Kamar ID',
            'is_eligible' => 'Is Eligible',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getNamaWali()
    {

        $results = null;

        foreach($this->simakMahasiswaOrtus as $ortu) {
            if($ortu->hubungan == 'WALI'){
                $results = ucwords(strtolower($ortu->nama));
                break;
            }

            else if($ortu->hubungan == 'AYAH'){
                $results = ucwords(strtolower($ortu->nama));
                break;
            }
        }


        return $results;
    }

    public function getAlamatWali()
    {

        $results = null;

        foreach($this->simakMahasiswaOrtus as $ortu) {
            if($ortu->hubungan == 'WALI'){
                $results = ucwords(strtolower($ortu->alamat));
                break;
            }

            else if($ortu->hubungan == 'AYAH'){
                $results = ucwords(strtolower($ortu->alamat));
                break;
            }
        }


        return $results;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagihans()
    {
        return $this->hasMany(Tagihan::className(), ['nim' => 'nim_mhs']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransaksis()
    {
        return $this->hasMany(Transaksi::className(), ['CUSTID' => 'nim_mhs']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getErpRiwayatPelanggarans()
    {
        return $this->hasMany(ErpRiwayatPelanggaran::className(), ['nim' => 'nim_mhs']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMahasiswaOrtus()
    {
        return $this->hasMany(SimakMahasiswaOrtu::className(), ['nim' => 'nim_mhs']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMahasiswaProgramTambahans()
    {
        return $this->hasMany(SimakMahasiswaProgramTambahan::className(), ['nim' => 'nim_mhs']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKodeProdi()
    {
        return $this->hasOne(SimakMasterprogramstudi::className(), ['kode_prodi' => 'kode_prodi']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKamar()
    {
        return $this->hasOne(ErpKamar::className(), ['id' => 'kamar_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKampus0()
    {
        return $this->hasOne(SimakKampus::className(), ['kode_kampus' => 'kampus']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSimakPencekalans()
    {
        return $this->hasMany(SimakPencekalan::className(), ['nim' => 'nim_mhs']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSimakTahfidzKelompokAnggota()
    {
        return $this->hasMany(SimakTahfidzKelompokAnggotum::className(), ['nim' => 'nim_mhs']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSimakTahfidzNilais()
    {
        return $this->hasMany(SimakTahfidzNilai::className(), ['nim' => 'nim_mhs']);
    }

    public function getSaldo()
    {
        $query = (new \yii\db\Query())->from('bill_transaksi');
        $query->where(['custid'=>$this->nim_mhs]);
        $sumDebet = $query->sum('DEBET');
        $sumKredit = $query->sum('KREDIT');
        $saldo = $sumKredit - $sumDebet;
        return $saldo;
    }

    public function getNamaKampus()
    {
        return $this->kampus0->nama_kampus;
    }

    public function getNamaProdi()
    {
        return $this->kodeProdi->nama_prodi;
    }
}
