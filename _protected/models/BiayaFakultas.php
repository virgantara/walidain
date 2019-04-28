<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill_biaya_fakultas".
 *
 * @property int $id
 * @property string $kode
 * @property int $fakultas_id
 * @property int $bill_komponen_biaya_id
 * @property int $tahun_akademik
 * @property int $semester
 * @property double $biaya
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SimakMasterfakultas $fakultas
 * @property KomponenBiaya $billKomponenBiaya
 * @property Tahun $tahunAkademik
 */
class BiayaFakultas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bill_biaya_fakultas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode', 'fakultas_id', 'bill_komponen_biaya_id', 'tahun_akademik', 'semester', 'biaya'], 'required'],
            [['fakultas_id', 'bill_komponen_biaya_id', 'tahun_akademik', 'semester'], 'integer'],
            [['biaya'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['kode'], 'string', 'max' => 20],
            [['fakultas_id'], 'exist', 'skipOnError' => true, 'targetClass' => SimakMasterfakultas::className(), 'targetAttribute' => ['fakultas_id' => 'id']],
            [['bill_komponen_biaya_id'], 'exist', 'skipOnError' => true, 'targetClass' => KomponenBiaya::className(), 'targetAttribute' => ['bill_komponen_biaya_id' => 'id']],
            [['tahun_akademik'], 'exist', 'skipOnError' => true, 'targetClass' => Tahun::className(), 'targetAttribute' => ['tahun_akademik' => 'id']],
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
            'fakultas_id' => 'Fakultas ID',
            'bill_komponen_biaya_id' => 'Bill Komponen Biaya ID',
            'tahun_akademik' => 'Tahun Akademik',
            'semester' => 'Semester',
            'biaya' => 'Biaya',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFakultas()
    {
        return $this->hasOne(SimakMasterfakultas::className(), ['id' => 'fakultas_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBillKomponenBiaya()
    {
        return $this->hasOne(KomponenBiaya::className(), ['id' => 'bill_komponen_biaya_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTahunAkademik()
    {
        return $this->hasOne(Tahun::className(), ['id' => 'tahun_akademik']);
    }
}
