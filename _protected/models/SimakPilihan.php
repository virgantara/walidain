<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "simak_pilihan".
 *
 * @property int $id
 * @property string $kode
 * @property string|null $kode_feeder
 * @property string $nama
 * @property string $value
 * @property string $label
 * @property string|null $label_en
 * @property int|null $aktif
 *
 * @property SimakMahasiswaOrtu[] $simakMahasiswaOrtus
 * @property SimakMahasiswaOrtu[] $simakMahasiswaOrtus0
 * @property SimakMahasiswaOrtu[] $simakMahasiswaOrtus1
 * @property SimakMahasiswaOrtu[] $simakMahasiswaOrtus2
 * @property SimakMastermahasiswa[] $simakMastermahasiswas
 * @property SimakMastermahasiswa[] $simakMastermahasiswas0
 * @property SimakMastermahasiswa[] $simakMastermahasiswas1
 * @property SimakMatakuliah[] $simakMatakuliahs
 */
class SimakPilihan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'simak_pilihan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode', 'nama', 'value', 'label'], 'required'],
            [['aktif'], 'integer'],
            [['kode', 'value'], 'string', 'max' => 10],
            [['kode_feeder', 'label_en'], 'string', 'max' => 255],
            [['nama', 'label'], 'string', 'max' => 100],
            [['kode', 'value'], 'unique', 'targetAttribute' => ['kode', 'value']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode' => 'Kode',
            'kode_feeder' => 'Kode Feeder',
            'nama' => 'Nama',
            'value' => 'Value',
            'label' => 'Label',
            'label_en' => 'Label En',
            'aktif' => 'Aktif',
        ];
    }

    /**
     * Gets query for [[SimakMahasiswaOrtus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMahasiswaOrtus()
    {
        return $this->hasMany(SimakMahasiswaOrtu::className(), ['agama' => 'value']);
    }

    /**
     * Gets query for [[SimakMahasiswaOrtus0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMahasiswaOrtus0()
    {
        return $this->hasMany(SimakMahasiswaOrtu::className(), ['pendidikan' => 'value']);
    }

    /**
     * Gets query for [[SimakMahasiswaOrtus1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMahasiswaOrtus1()
    {
        return $this->hasMany(SimakMahasiswaOrtu::className(), ['pekerjaan' => 'value']);
    }

    /**
     * Gets query for [[SimakMahasiswaOrtus2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMahasiswaOrtus2()
    {
        return $this->hasMany(SimakMahasiswaOrtu::className(), ['penghasilan' => 'value']);
    }

    /**
     * Gets query for [[SimakMastermahasiswas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMastermahasiswas()
    {
        return $this->hasMany(SimakMastermahasiswa::className(), ['id_jenis_daftar' => 'id']);
    }

    /**
     * Gets query for [[SimakMastermahasiswas0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMastermahasiswas0()
    {
        return $this->hasMany(SimakMastermahasiswa::className(), ['id_pembiayaan' => 'id']);
    }

    /**
     * Gets query for [[SimakMastermahasiswas1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMastermahasiswas1()
    {
        return $this->hasMany(SimakMastermahasiswa::className(), ['id_jalur_daftar' => 'id']);
    }

    /**
     * Gets query for [[SimakMatakuliahs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMatakuliahs()
    {
        return $this->hasMany(SimakMatakuliah::className(), ['jenis_mk' => 'id']);
    }
}
