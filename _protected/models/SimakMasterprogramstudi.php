<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "simak_masterprogramstudi".
 *
 * @property int $id
 * @property string $kode_fakultas
 * @property string $kode_jurusan
 * @property string $kode_prodi
 * @property string $kode_jenjang_studi
 * @property string $gelar_lulusan
 * @property string $gelar_lulusan_en
 * @property string $gelar_lulusan_short
 * @property string $nama_prodi
 * @property string $nama_prodi_en
 * @property string $semester_awal
 * @property string $no_sk_dikti
 * @property string $tgl_sk_dikti
 * @property string $tgl_akhir_sk_dikti
 * @property string $jml_sks_lulus
 * @property string $kode_status
 * @property string $tahun_semester_mulai
 * @property string $email_prodi
 * @property string $tgl_pendirian_program_studi
 * @property string $no_sk_akreditasi
 * @property string $tgl_sk_akreditasi
 * @property string $tgl_akhir_sk_akreditasi
 * @property string $kode_status_akreditasi
 * @property string $frekuensi_kurikulum
 * @property string $pelaksanaan_kurikulum
 * @property string $nidn_ketua_prodi
 * @property string $telp_ketua_prodi
 * @property string $fax_prodi
 * @property string $nama_operator
 * @property string $hp_operator
 * @property string $telepon_program_studi
 * @property string $singkatan
 * @property string $kode_feeder
 *
 * @property SimakMastermahasiswa[] $simakMastermahasiswas
 * @property SimakMasterfakulta $kodeFakultas
 * @property SimakProdiCapem[] $simakProdiCapems
 */
class SimakMasterprogramstudi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'simak_masterprogramstudi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode_fakultas', 'kode_prodi', 'kode_jenjang_studi', 'nama_prodi', 'semester_awal', 'no_sk_dikti', 'jml_sks_lulus', 'kode_status', 'tahun_semester_mulai', 'email_prodi', 'no_sk_akreditasi', 'kode_status_akreditasi', 'frekuensi_kurikulum', 'pelaksanaan_kurikulum', 'nidn_ketua_prodi', 'telp_ketua_prodi', 'fax_prodi', 'nama_operator', 'hp_operator', 'telepon_program_studi'], 'required'],
            [['tgl_sk_dikti', 'tgl_akhir_sk_dikti', 'tgl_pendirian_program_studi', 'tgl_sk_akreditasi', 'tgl_akhir_sk_akreditasi'], 'safe'],
            [['kode_fakultas', 'kode_jurusan', 'kode_jenjang_studi', 'semester_awal', 'jml_sks_lulus', 'tahun_semester_mulai', 'kode_status_akreditasi'], 'string', 'max' => 5],
            [['kode_prodi'], 'string', 'max' => 15],
            [['gelar_lulusan', 'gelar_lulusan_en', 'gelar_lulusan_short', 'nama_prodi_en', 'singkatan', 'kode_feeder'], 'string', 'max' => 255],
            [['nama_prodi', 'no_sk_dikti', 'email_prodi', 'nama_operator'], 'string', 'max' => 50],
            [['kode_status'], 'string', 'max' => 1],
            [['no_sk_akreditasi', 'nidn_ketua_prodi', 'telp_ketua_prodi', 'fax_prodi', 'hp_operator', 'telepon_program_studi'], 'string', 'max' => 25],
            [['frekuensi_kurikulum', 'pelaksanaan_kurikulum'], 'string', 'max' => 10],
            [['kode_prodi'], 'unique'],
            [['kode_fakultas'], 'exist', 'skipOnError' => true, 'targetClass' => SimakMasterfakulta::className(), 'targetAttribute' => ['kode_fakultas' => 'kode_fakultas']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_fakultas' => 'Kode Fakultas',
            'kode_jurusan' => 'Kode Jurusan',
            'kode_prodi' => 'Kode Prodi',
            'kode_jenjang_studi' => 'Kode Jenjang Studi',
            'gelar_lulusan' => 'Gelar Lulusan',
            'gelar_lulusan_en' => 'Gelar Lulusan En',
            'gelar_lulusan_short' => 'Gelar Lulusan Short',
            'nama_prodi' => 'Nama Prodi',
            'nama_prodi_en' => 'Nama Prodi En',
            'semester_awal' => 'Semester Awal',
            'no_sk_dikti' => 'No Sk Dikti',
            'tgl_sk_dikti' => 'Tgl Sk Dikti',
            'tgl_akhir_sk_dikti' => 'Tgl Akhir Sk Dikti',
            'jml_sks_lulus' => 'Jml Sks Lulus',
            'kode_status' => 'Kode Status',
            'tahun_semester_mulai' => 'Tahun Semester Mulai',
            'email_prodi' => 'Email Prodi',
            'tgl_pendirian_program_studi' => 'Tgl Pendirian Program Studi',
            'no_sk_akreditasi' => 'No Sk Akreditasi',
            'tgl_sk_akreditasi' => 'Tgl Sk Akreditasi',
            'tgl_akhir_sk_akreditasi' => 'Tgl Akhir Sk Akreditasi',
            'kode_status_akreditasi' => 'Kode Status Akreditasi',
            'frekuensi_kurikulum' => 'Frekuensi Kurikulum',
            'pelaksanaan_kurikulum' => 'Pelaksanaan Kurikulum',
            'nidn_ketua_prodi' => 'Nidn Ketua Prodi',
            'telp_ketua_prodi' => 'Telp Ketua Prodi',
            'fax_prodi' => 'Fax Prodi',
            'nama_operator' => 'Nama Operator',
            'hp_operator' => 'Hp Operator',
            'telepon_program_studi' => 'Telepon Program Studi',
            'singkatan' => 'Singkatan',
            'kode_feeder' => 'Kode Feeder',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMastermahasiswas()
    {
        return $this->hasMany(SimakMastermahasiswa::className(), ['kode_prodi' => 'kode_prodi']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKodeFakultas()
    {
        return $this->hasOne(SimakMasterfakulta::className(), ['kode_fakultas' => 'kode_fakultas']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSimakProdiCapems()
    {
        return $this->hasMany(SimakProdiCapem::className(), ['prodi_id' => 'kode_prodi']);
    }
}
