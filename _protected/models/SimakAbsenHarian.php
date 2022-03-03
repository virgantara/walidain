<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "simak_absen_harian".
 *
 * @property int $id
 * @property string $mhs
 * @property string|null $jurusan
 * @property string|null $dosen
 * @property string|null $kelas
 * @property string|null $kode_mk
 * @property int $pertemuan
 * @property int|null $status_kehadiran
 * @property int|null $kode_jadwal
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property SimakMasterprogramstudi $jurusan0
 * @property SimakJadwal $kodeJadwal
 * @property SimakMastermahasiswa $mhs0
 */
class SimakAbsenHarian extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'simak_absen_harian';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mhs', 'pertemuan'], 'required'],
            [['pertemuan', 'status_kehadiran', 'kode_jadwal'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['mhs'], 'string', 'max' => 25],
            [['jurusan'], 'string', 'max' => 15],
            [['dosen', 'kelas', 'kode_mk'], 'string', 'max' => 10],
            [['mhs'], 'exist', 'skipOnError' => true, 'targetClass' => SimakMastermahasiswa::className(), 'targetAttribute' => ['mhs' => 'nim_mhs']],
            [['jurusan'], 'exist', 'skipOnError' => true, 'targetClass' => SimakMasterprogramstudi::className(), 'targetAttribute' => ['jurusan' => 'kode_prodi']],
            [['kode_jadwal'], 'exist', 'skipOnError' => true, 'targetClass' => SimakJadwal::className(), 'targetAttribute' => ['kode_jadwal' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mhs' => 'Mhs',
            'jurusan' => 'Jurusan',
            'dosen' => 'Dosen',
            'kelas' => 'Kelas',
            'kode_mk' => 'Kode Mk',
            'pertemuan' => 'Pertemuan',
            'status_kehadiran' => 'Status Kehadiran',
            'kode_jadwal' => 'Kode Jadwal',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Jurusan0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJurusan0()
    {
        return $this->hasOne(SimakMasterprogramstudi::className(), ['kode_prodi' => 'jurusan']);
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
     * Gets query for [[Mhs0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMhs0()
    {
        return $this->hasOne(SimakMastermahasiswa::className(), ['nim_mhs' => 'mhs']);
    }

    public static function getAbsenMahasiswa($nim, $pertemuan, $jadwal_id)
    {
        $model = SimakAbsenHarian::findOne([
            'mhs' => $nim,
            'pertemuan' => $pertemuan,
            'kode_jadwal' => $jadwal_id
        ]);

        return $model;
    }
}
