<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill_syarat_pencekalan".
 *
 * @property int $id
 * @property int $tahun_id
 * @property int $komponen_id
 * @property double $nilai_minimal
 *
 * @property Tahun $tahun
 * @property KomponenBiaya $komponen
 */
class SyaratPencekalan extends \yii\db\ActiveRecord
{

    public $namaKomponen;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bill_syarat_pencekalan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tahun_id', 'komponen_id', 'nilai_minimal'], 'required'],
            [['tahun_id', 'komponen_id'], 'integer'],
            [['nilai_minimal'], 'number'],
            [['tahun_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tahun::className(), 'targetAttribute' => ['tahun_id' => 'id']],
            [['komponen_id'], 'exist', 'skipOnError' => true, 'targetClass' => KomponenBiaya::className(), 'targetAttribute' => ['komponen_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tahun_id' => 'Tahun',
            'komponen_id' => 'Komponen',
            'nilai_minimal' => 'Nilai Minimal',
        ];
    }

    public function getNamaKomponen(){
        return $this->komponen->nama;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTahun()
    {
        return $this->hasOne(Tahun::className(), ['id' => 'tahun_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKomponen()
    {
        return $this->hasOne(KomponenBiaya::className(), ['id' => 'komponen_id']);
    }
}
