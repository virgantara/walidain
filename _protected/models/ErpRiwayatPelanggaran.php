<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "erp_riwayat_pelanggaran".
 *
 * @property int $id
 * @property int $pelanggaran_id
 * @property string $tanggal
 * @property string $nim
 * @property int $tahun_id
 * @property string|null $pelapor
 * @property string|null $bukti
 * @property string|null $surat_pernyataan
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property ErpRiwayatHukuman[] $erpRiwayatHukumen
 * @property SimakMastermahasiswa $nim0
 * @property ErpPelanggaran $pelanggaran
 */
class ErpRiwayatPelanggaran extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'erp_riwayat_pelanggaran';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pelanggaran_id', 'tanggal', 'nim', 'tahun_id'], 'required'],
            [['pelanggaran_id', 'tahun_id'], 'integer'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['nim'], 'string', 'max' => 25],
            [['pelapor', 'bukti', 'surat_pernyataan'], 'string', 'max' => 255],
            [['pelanggaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => ErpPelanggaran::className(), 'targetAttribute' => ['pelanggaran_id' => 'id']],
            [['nim'], 'exist', 'skipOnError' => true, 'targetClass' => SimakMastermahasiswa::className(), 'targetAttribute' => ['nim' => 'nim_mhs']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pelanggaran_id' => 'Pelanggaran ID',
            'tanggal' => 'Tanggal',
            'nim' => 'Nim',
            'tahun_id' => 'Tahun ID',
            'pelapor' => 'Pelapor',
            'bukti' => 'Bukti',
            'surat_pernyataan' => 'Surat Pernyataan',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[ErpRiwayatHukumen]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getErpRiwayatHukumen()
    {
        return $this->hasMany(ErpRiwayatHukuman::className(), ['pelanggaran_id' => 'id']);
    }

    /**
     * Gets query for [[Nim0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNim0()
    {
        return $this->hasOne(SimakMastermahasiswa::className(), ['nim_mhs' => 'nim']);
    }

    /**
     * Gets query for [[Pelanggaran]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPelanggaran()
    {
        return $this->hasOne(ErpPelanggaran::className(), ['id' => 'pelanggaran_id']);
    }
}
