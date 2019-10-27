<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "simak_tahunakademik".
 *
 * @property int $id
 * @property string $tahun_id
 * @property string $tahun
 * @property int $semester
 * @property string $nama_tahun
 * @property string $krs_mulai
 * @property string $krs_selesai
 * @property string $krs_online_mulai
 * @property string $krs_online_selesai
 * @property string $krs_ubah_mulai
 * @property string $krs_ubah_selesai
 * @property string $kss_cetak_mulai
 * @property string $kss_cetak_selesai
 * @property string $cuti
 * @property string $mundur
 * @property string $bayar_mulai
 * @property string $bayar_selesai
 * @property string $kuliah_mulai
 * @property string $kuliah_selesai
 * @property string $uts_mulai
 * @property string $uts_selesai
 * @property string $uas_mulai
 * @property string $uas_selesai
 * @property string $nilai
 * @property string $akhir_kss
 * @property int $proses_buka
 * @property int $proses_ipk
 * @property int $proses_tutup
 * @property string $buka
 * @property string $syarat_krs
 * @property string $syarat_krs_ips
 * @property string $cuti_selesai
 * @property int $max_sks
 *
 * @property SimakPencekalan[] $simakPencekalans
 * @property SimakTahfidzPeriode[] $simakTahfidzPeriodes
 */
class SimakTahunakademik extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'simak_tahunakademik';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['semester', 'proses_buka', 'proses_ipk', 'proses_tutup', 'max_sks'], 'integer'],
            [['nama_tahun'], 'required'],
            [['krs_mulai', 'krs_selesai', 'krs_online_mulai', 'krs_online_selesai', 'krs_ubah_mulai', 'krs_ubah_selesai', 'kss_cetak_mulai', 'kss_cetak_selesai', 'cuti', 'mundur', 'bayar_mulai', 'bayar_selesai', 'kuliah_mulai', 'kuliah_selesai', 'uts_mulai', 'uts_selesai', 'uas_mulai', 'uas_selesai', 'nilai', 'akhir_kss', 'cuti_selesai'], 'safe'],
            [['tahun_id'], 'string', 'max' => 5],
            [['tahun'], 'string', 'max' => 4],
            [['nama_tahun'], 'string', 'max' => 50],
            [['buka', 'syarat_krs', 'syarat_krs_ips'], 'string', 'max' => 10],
            [['tahun_id'], 'unique'],
            [['tahun', 'semester'], 'unique', 'targetAttribute' => ['tahun', 'semester']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tahun_id' => 'Tahun ID',
            'tahun' => 'Tahun',
            'semester' => 'Semester',
            'nama_tahun' => 'Nama Tahun',
            'krs_mulai' => 'Krs Mulai',
            'krs_selesai' => 'Krs Selesai',
            'krs_online_mulai' => 'Krs Online Mulai',
            'krs_online_selesai' => 'Krs Online Selesai',
            'krs_ubah_mulai' => 'Krs Ubah Mulai',
            'krs_ubah_selesai' => 'Krs Ubah Selesai',
            'kss_cetak_mulai' => 'Kss Cetak Mulai',
            'kss_cetak_selesai' => 'Kss Cetak Selesai',
            'cuti' => 'Cuti',
            'mundur' => 'Mundur',
            'bayar_mulai' => 'Bayar Mulai',
            'bayar_selesai' => 'Bayar Selesai',
            'kuliah_mulai' => 'Kuliah Mulai',
            'kuliah_selesai' => 'Kuliah Selesai',
            'uts_mulai' => 'Uts Mulai',
            'uts_selesai' => 'Uts Selesai',
            'uas_mulai' => 'Uas Mulai',
            'uas_selesai' => 'Uas Selesai',
            'nilai' => 'Nilai',
            'akhir_kss' => 'Akhir Kss',
            'proses_buka' => 'Proses Buka',
            'proses_ipk' => 'Proses Ipk',
            'proses_tutup' => 'Proses Tutup',
            'buka' => 'Buka',
            'syarat_krs' => 'Syarat Krs',
            'syarat_krs_ips' => 'Syarat Krs Ips',
            'cuti_selesai' => 'Cuti Selesai',
            'max_sks' => 'Max Sks',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSimakPencekalans()
    {
        return $this->hasMany(SimakPencekalan::className(), ['tahun_id' => 'tahun_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSimakTahfidzPeriodes()
    {
        return $this->hasMany(SimakTahfidzPeriode::className(), ['tahun_id' => 'tahun_id']);
    }
}
