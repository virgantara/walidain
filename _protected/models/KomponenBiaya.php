<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill_komponen_biaya".
 *
 * @property int $id
 * @property string $kode
 * @property string $nama
 * @property double $biaya_awal
 * @property double $biaya_minimal
 * @property int $prioritas
 * @property int $kategori_id
 * @property int $tahun
 * @property string $created_at
 * @property string $updated_at
 *
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
            [['kode', 'nama', 'kategori_id', 'tahun','kampus_id'], 'required'],
            [['biaya_awal', 'biaya_minimal'], 'number'],
            [['prioritas', 'kategori_id', 'tahun'], 'integer'],
            [['created_at', 'updated_at','kampus_id','bulan_id','prodi_id','semester','biaya_minimal','biaya_awal','is_pencekalan'], 'safe'],
            [['kode'], 'string', 'max' => 5],
            [['nama'], 'string', 'max' => 255],
            [['kategori_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kategori::className(), 'targetAttribute' => ['kategori_id' => 'id']],
            [['bulan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bulan::className(), 'targetAttribute' => ['bulan_id' => 'id']],
            [['kampus_id'], 'exist', 'skipOnError' => true, 'targetClass' => SimakKampus::className(), 'targetAttribute' => ['kampus_id' => 'kode_kampus']],

         
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
            'biaya_awal' => 'Nominal Biaya',
            'biaya_minimal' => 'Biaya Minimal',
            'prioritas' => 'Prioritas',
            'kategori_id' => 'Kategori ID',
            'bulan_id' => 'Bulan',
            'kampus_id' => 'Kampus',
            'tahun' => 'Tahun',
            'is_pencekalan' => 'Pencekalan ?',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    
    public function getNamaKategori(){
        return $this->kategori->nama;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKategori()
    {
        return $this->hasOne(Kategori::className(), ['id' => 'kategori_id']);
    }

   

    public function getKampus()
    {
        return $this->hasOne(SimakKampus::className(), ['kode_kampus' => 'kampus_id']);
    }

    public function getBulan()
    {
        return $this->hasOne(Bulan::className(), ['id' => 'bulan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagihans()
    {
        return $this->hasMany(Tagihan::className(), ['komponen_id' => 'id']);
    }
}
