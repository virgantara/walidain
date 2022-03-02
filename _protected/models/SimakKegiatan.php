<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "simak_kegiatan".
 *
 * @property int $id
 * @property string $nama_kegiatan
 * @property string $sub_kegiatan
 * @property int $nilai
 * @property string $sk_unida_siman
 * @property string $sk_unida_cabang
 * @property int $id_jenis_kegiatan
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property SimakKegiatanMahasiswa[] $simakKegiatanMahasiswas
 */
class SimakKegiatan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'simak_kegiatan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_kegiatan', 'sub_kegiatan', 'nilai', 'sk_unida_siman', 'sk_unida_cabang', 'id_jenis_kegiatan'], 'required'],
            [['id_jenis_kegiatan'], 'integer'],
            [['nilai'], 'number'],
            [['created_at', 'updated_at','is_active'], 'safe'],
            [['nama_kegiatan', 'sub_kegiatan', 'sk_unida_siman', 'sk_unida_cabang'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_kegiatan' => 'Nama Kegiatan',
            'sub_kegiatan' => 'Sub Kegiatan',
            'nilai' => 'Nilai',
            'is_active' => 'Status',
            'sk_unida_siman' => 'Sk Unida Siman',
            'sk_unida_cabang' => 'Sk Unida Cabang',
            'id_jenis_kegiatan' => 'Id Jenis Kegiatan',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[SimakKegiatanMahasiswas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakKegiatanMahasiswas()
    {
        return $this->hasMany(SimakKegiatanMahasiswa::className(), ['id_kegiatan' => 'id']);
    }
}
