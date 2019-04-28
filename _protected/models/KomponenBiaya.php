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
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BiayaFakultas[] $biayaFakultas
 * @property PeriodeTagihan $periodeTagihan
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
            [['kode', 'nama', 'periode_tagihan_id'], 'required'],
            [['periode_tagihan_id', 'prioritas'], 'integer'],
            [['biaya_awal'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['kode'], 'string', 'max' => 5],
            [['nama'], 'string', 'max' => 255],
            [['periode_tagihan_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodeTagihan::className(), 'targetAttribute' => ['periode_tagihan_id' => 'id']],
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
            'periode_tagihan_id' => 'Periode Tagihan ID',
            'biaya_awal' => 'Biaya Awal',
            'prioritas' => 'Prioritas',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
    public function getPeriodeTagihan()
    {
        return $this->hasOne(PeriodeTagihan::className(), ['id' => 'periode_tagihan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagihans()
    {
        return $this->hasMany(Tagihan::className(), ['komponen_id' => 'id']);
    }
}
