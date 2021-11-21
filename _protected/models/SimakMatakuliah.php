<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "simak_matakuliah".
 *
 * @property int $id
 * @property string $kode_mk
 * @property string|null $nama_mk
 * @property string|null $nama_mk_en
 * @property string|null $kode_feeder
 * @property string|null $kurikulum_feeder
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string $prodi
 * @property int $jenis_mk
 * @property int $sks_mk
 * @property int|null $sks_tm
 * @property int|null $sks_prak
 * @property int|null $sks_prak_lap
 * @property int|null $sks_sim
 * @property string|null $metode_pelaksanaan_kuliah
 * @property string|null $tgl_mulai_efektif
 * @property string|null $tgl_akhir_efektif
 *
 * @property SimakPilihan $jenisMk
 * @property SimakMasterprogramstudi $prodi0
 * @property SimakCpmk[] $simakCpmks
 * @property SimakJadwal[] $simakJadwals
 * @property SimakKurikulumMk[] $simakKurikulumMks
 */
class SimakMatakuliah extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'simak_matakuliah';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode_mk', 'prodi', 'jenis_mk', 'sks_mk'], 'required'],
            [['created_at', 'updated_at', 'tgl_mulai_efektif', 'tgl_akhir_efektif'], 'safe'],
            [['jenis_mk', 'sks_mk', 'sks_tm', 'sks_prak', 'sks_prak_lap', 'sks_sim'], 'integer'],
            [['kode_mk'], 'string', 'max' => 25],
            [['nama_mk', 'nama_mk_en', 'kode_feeder', 'metode_pelaksanaan_kuliah'], 'string', 'max' => 255],
            [['kurikulum_feeder'], 'string', 'max' => 100],
            [['prodi'], 'string', 'max' => 10],
            [['prodi'], 'exist', 'skipOnError' => true, 'targetClass' => SimakMasterprogramstudi::className(), 'targetAttribute' => ['prodi' => 'kode_prodi']],
            [['jenis_mk'], 'exist', 'skipOnError' => true, 'targetClass' => SimakPilihan::className(), 'targetAttribute' => ['jenis_mk' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_mk' => 'Kode Mk',
            'nama_mk' => 'Nama Mk',
            'nama_mk_en' => 'Nama Mk En',
            'kode_feeder' => 'Kode Feeder',
            'kurikulum_feeder' => 'Kurikulum Feeder',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'prodi' => 'Prodi',
            'jenis_mk' => 'Jenis Mk',
            'sks_mk' => 'Sks Mk',
            'sks_tm' => 'Sks Tm',
            'sks_prak' => 'Sks Prak',
            'sks_prak_lap' => 'Sks Prak Lap',
            'sks_sim' => 'Sks Sim',
            'metode_pelaksanaan_kuliah' => 'Metode Pelaksanaan Kuliah',
            'tgl_mulai_efektif' => 'Tgl Mulai Efektif',
            'tgl_akhir_efektif' => 'Tgl Akhir Efektif',
        ];
    }

    /**
     * Gets query for [[JenisMk]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenisMk()
    {
        return $this->hasOne(SimakPilihan::className(), ['id' => 'jenis_mk']);
    }

    /**
     * Gets query for [[Prodi0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProdi0()
    {
        return $this->hasOne(SimakMasterprogramstudi::className(), ['kode_prodi' => 'prodi']);
    }

    /**
     * Gets query for [[SimakCpmks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakCpmks()
    {
        return $this->hasMany(SimakCpmk::className(), ['matkul_id' => 'id']);
    }

    /**
     * Gets query for [[SimakJadwals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakJadwals()
    {
        return $this->hasMany(SimakJadwal::className(), ['matkul_id' => 'id']);
    }

    /**
     * Gets query for [[SimakKurikulumMks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakKurikulumMks()
    {
        return $this->hasMany(SimakKurikulumMk::className(), ['matakuliah_id' => 'id']);
    }
}
