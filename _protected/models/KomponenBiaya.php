<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill_komponen_biaya".
 *
 * @property int $id
 * @property string $kode
 * @property string $nama
 * @property int $periode_tagihan_id
 * @property double $biaya_awal
 * @property int $prioritas
 * @property int $kategori_id
 * @property int $tahun
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BiayaFakultas[] $biayaFakultas
 * @property PeriodeTagihan $periodeTagihan
 * @property Kategori $kategori
 * @property Tagihan[] $tagihans
 */
class KomponenBiaya extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bill_komponen_biaya';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode', 'nama', 'kategori_id', 'tahun'], 'required'],
            [['prioritas', 'kategori_id', 'tahun'], 'integer'],
            [['biaya_awal'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['kode'], 'string', 'max' => 5],
            [['nama'], 'string', 'max' => 255],
            [['kategori_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kategori::className(), 'targetAttribute' => ['kategori_id' => 'id']],
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
            'nama' => 'Nama',
            'biaya_awal' => 'Biaya Awal',
            'prioritas' => 'Prioritas',
            'kategori_id' => 'Kategori ID',
            'tahun' => 'Tahun',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'namaKategori' => 'Kategori'
        ];
    }

    public function getNamaKategori(){
        return $this->kategori->nama;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBiayaFakultas()
    {
        return $this->hasMany(BiayaFakultas::className(), ['bill_komponen_biaya_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
   

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKategori()
    {
        return $this->hasOne(Kategori::className(), ['id' => 'kategori_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagihans()
    {
        return $this->hasMany(Tagihan::className(), ['komponen_id' => 'id']);
    }
}
