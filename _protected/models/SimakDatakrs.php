<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "simak_datakrs".
 *
 * @property int $id
 * @property string|null $kode_pt
 * @property string|null $kode_fak
 * @property string|null $kode_jenjang
 * @property string|null $kode_jurusan
 * @property string|null $kode_prodi
 * @property string|null $kode_feeder
 * @property string $kode_mk
 * @property string|null $nama_mk
 * @property string $sks
 * @property string|null $mahasiswa
 * @property string|null $kode_dosen
 * @property string|null $namadosen
 * @property int $semester
 * @property int|null $kode_jadwal
 * @property string|null $kelas
 * @property string|null $harian
 * @property string|null $normatif
 * @property string|null $uts
 * @property string|null $uas
 * @property string|null $nilai_angka
 * @property string|null $nilai_huruf
 * @property float|null $nilai_angka_backup
 * @property string|null $nilai_huruf_backup
 * @property string|null $bobot_nilai
 * @property string|null $tahun_akademik
 * @property string|null $status
 * @property string|null $semester_matakuliah
 * @property int $status_publis
 * @property string|null $jumlah_nilai
 * @property string|null $status_krs
 * @property string|null $lulus
 * @property string|null $pindahan
 * @property int|null $is_approved
 * @property int|null $is_tercekal
 * @property string|null $keterangan_tercekal
 * @property int|null $sudah_ekd
 * @property int|null $is_locked
 * @property float|null $score_ekd
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property SimakJadwal $kodeJadwal
 * @property SimakMasterprogramstudi $kodeProdi
 * @property SimakMastermahasiswa $mahasiswa0
 * @property SimakEkdJawaban[] $simakEkdJawabans
 */
class SimakDatakrs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'simak_datakrs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode_mk', 'sks', 'semester'], 'required'],
            [['semester', 'kode_jadwal', 'status_publis', 'is_approved', 'is_tercekal', 'sudah_ekd', 'is_locked'], 'integer'],
            [['nilai_angka_backup', 'score_ekd'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['kode_pt', 'kode_fak', 'kode_jurusan', 'kode_prodi', 'uas', 'nilai_huruf_backup', 'bobot_nilai', 'jumlah_nilai', 'status_krs'], 'string', 'max' => 10],
            [['kode_jenjang', 'lulus'], 'string', 'max' => 3],
            [['kode_feeder', 'nama_mk', 'namadosen'], 'string', 'max' => 100],
            [['kode_mk', 'mahasiswa', 'kode_dosen', 'kelas'], 'string', 'max' => 20],
            [['sks', 'harian', 'normatif', 'uts', 'nilai_huruf'], 'string', 'max' => 5],
            [['nilai_angka'], 'string', 'max' => 15],
            [['tahun_akademik'], 'string', 'max' => 6],
            [['status', 'pindahan'], 'string', 'max' => 2],
            [['semester_matakuliah'], 'string', 'max' => 4],
            [['keterangan_tercekal'], 'string', 'max' => 255],
            [['kode_jadwal'], 'exist', 'skipOnError' => true, 'targetClass' => SimakJadwal::className(), 'targetAttribute' => ['kode_jadwal' => 'id']],
            [['kode_prodi'], 'exist', 'skipOnError' => true, 'targetClass' => SimakMasterprogramstudi::className(), 'targetAttribute' => ['kode_prodi' => 'kode_prodi']],
            [['mahasiswa'], 'exist', 'skipOnError' => true, 'targetClass' => SimakMastermahasiswa::className(), 'targetAttribute' => ['mahasiswa' => 'nim_mhs']],
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
            'kode_fak' => 'Kode Fak',
            'kode_jenjang' => 'Kode Jenjang',
            'kode_jurusan' => 'Kode Jurusan',
            'kode_prodi' => 'Kode Prodi',
            'kode_feeder' => 'Kode Feeder',
            'kode_mk' => 'Kode Mk',
            'nama_mk' => 'Nama Mk',
            'sks' => 'Sks',
            'mahasiswa' => 'Mahasiswa',
            'kode_dosen' => 'Kode Dosen',
            'namadosen' => 'Namadosen',
            'semester' => 'Semester',
            'kode_jadwal' => 'Kode Jadwal',
            'kelas' => 'Kelas',
            'harian' => 'Harian',
            'normatif' => 'Normatif',
            'uts' => 'Uts',
            'uas' => 'Uas',
            'nilai_angka' => 'Nilai Angka',
            'nilai_huruf' => 'Nilai Huruf',
            'nilai_angka_backup' => 'Nilai Angka Backup',
            'nilai_huruf_backup' => 'Nilai Huruf Backup',
            'bobot_nilai' => 'Bobot Nilai',
            'tahun_akademik' => 'Tahun Akademik',
            'status' => 'Status',
            'semester_matakuliah' => 'Semester Matakuliah',
            'status_publis' => 'Status Publis',
            'jumlah_nilai' => 'Jumlah Nilai',
            'status_krs' => 'Status Krs',
            'lulus' => 'Lulus',
            'pindahan' => 'Pindahan',
            'is_approved' => 'Is Approved',
            'is_tercekal' => 'Is Tercekal',
            'keterangan_tercekal' => 'Keterangan Tercekal',
            'sudah_ekd' => 'Sudah Ekd',
            'is_locked' => 'Is Locked',
            'score_ekd' => 'Score Ekd',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[KodeJadwal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKodeJadwal()
    {
        return $this->hasOne(SimakJadwal::className(), ['id' => 'kode_jadwal']);
    }

    /**
     * Gets query for [[KodeProdi]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKodeProdi()
    {
        return $this->hasOne(SimakMasterprogramstudi::className(), ['kode_prodi' => 'kode_prodi']);
    }

    /**
     * Gets query for [[Mahasiswa0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMahasiswa0()
    {
        return $this->hasOne(SimakMastermahasiswa::className(), ['nim_mhs' => 'mahasiswa']);
    }

    /**
     * Gets query for [[SimakEkdJawabans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakEkdJawabans()
    {
        return $this->hasMany(SimakEkdJawaban::className(), ['simak_datakrs_id' => 'id']);
    }
}
