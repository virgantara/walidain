<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "simak_kampus".
 *
 * @property int $id
 * @property string $kode_kampus
 * @property string $nama_kampus
 * @property int|null $kmi
 * @property string|null $prefix
 *
 * @property ErpAsrama[] $erpAsramas
 * @property ErpDapur[] $erpDapurs
 * @property ErpOrganisasiMahasiswa[] $erpOrganisasiMahasiswas
 * @property Events[] $events
 * @property SimakMasterprogramstudi[] $kodeProdis
 * @property KomponenBiaya[] $komponenBiayas
 * @property SimakJadwal[] $simakJadwals
 * @property SimakKampusProdi[] $simakKampusProdis
 * @property SimakMasterkelas[] $simakMasterkelas
 * @property SimakMastermahasiswa[] $simakMastermahasiswas
 */
class SimakKampus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'simak_kampus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode_kampus', 'nama_kampus'], 'required'],
            [['kmi'], 'integer'],
            [['kode_kampus'], 'string', 'max' => 2],
            [['nama_kampus'], 'string', 'max' => 100],
            [['prefix'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_kampus' => 'Kode Kampus',
            'nama_kampus' => 'Nama Kampus',
            'kmi' => 'Kmi',
            'prefix' => 'Prefix',
        ];
    }

    /**
     * Gets query for [[ErpAsramas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getErpAsramas()
    {
        return $this->hasMany(ErpAsrama::className(), ['kampus_id' => 'kode_kampus']);
    }

    /**
     * Gets query for [[ErpDapurs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getErpDapurs()
    {
        return $this->hasMany(ErpDapur::className(), ['kampus' => 'kode_kampus']);
    }

    /**
     * Gets query for [[ErpOrganisasiMahasiswas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getErpOrganisasiMahasiswas()
    {
        return $this->hasMany(ErpOrganisasiMahasiswa::className(), ['kampus' => 'kode_kampus']);
    }

    /**
     * Gets query for [[Events]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Events::className(), ['kampus' => 'kode_kampus']);
    }

    /**
     * Gets query for [[KodeProdis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKodeProdis()
    {
        return $this->hasMany(SimakMasterprogramstudi::className(), ['kode_prodi' => 'kode_prodi'])->viaTable('simak_kampus_prodi', ['kode_kampus' => 'kode_kampus']);
    }

    /**
     * Gets query for [[KomponenBiayas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKomponenBiayas()
    {
        return $this->hasMany(KomponenBiaya::className(), ['kampus_id' => 'kode_kampus']);
    }

    /**
     * Gets query for [[SimakJadwals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakJadwals()
    {
        return $this->hasMany(SimakJadwal::className(), ['kampus' => 'kode_kampus']);
    }

    /**
     * Gets query for [[SimakKampusProdis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakKampusProdis()
    {
        return $this->hasMany(SimakKampusProdi::className(), ['kode_kampus' => 'kode_kampus']);
    }

    /**
     * Gets query for [[SimakMasterkelas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMasterkelas()
    {
        return $this->hasMany(SimakMasterkelas::className(), ['id_kampus' => 'id']);
    }

    /**
     * Gets query for [[SimakMastermahasiswas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMastermahasiswas()
    {
        return $this->hasMany(SimakMastermahasiswa::className(), ['kampus' => 'kode_kampus']);
    }

    public static function getList()
    {
        return SimakKampus::find()->all();
    }
}
