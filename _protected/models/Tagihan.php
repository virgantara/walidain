<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill_tagihan".
 *
 * @property int $id
 * @property int $urutan
 * @property int $semester
 * @property int $tahun
 * @property string $nim
 * @property int $komponen_id
 * @property double $nilai
 * @property double $terbayar
 * @property int $edit
 * @property int $status_bayar
 * @property string $created_at
 * @property string $updated_at
 *
 * @property KomponenBiaya $komponen
 */
class Tagihan extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bill_tagihan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urutan', 'semester', 'tahun', 'komponen_id', 'edit', 'status_bayar'], 'integer'],
            [['semester', 'tahun', 'komponen_id', 'nilai'], 'required'],
            [['nilai', 'terbayar'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['nim'], 'string', 'max' => 20],
            [['komponen_id'], 'exist', 'skipOnError' => true, 'targetClass' => KomponenBiaya::className(), 'targetAttribute' => ['komponen_id' => 'id']],
            [['nim'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['nim' => 'custid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'urutan' => 'Urutan',
            'semester' => 'Semester',
            'tahun' => 'Tahun',
            'nim' => 'Nim',
            'komponen_id' => 'Komponen ID',
            'nilai' => 'Nilai',
            'terbayar' => 'Terbayar',
            'edit' => 'Edit',
            'status_bayar' => 'Status Bayar',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'namaKomponen' => 'Komponen',
            'namaCustomer' => 'Nama'
        ];
    }

    public function getNamaKomponen()
    {
        return $this->komponen->nama;
    }

    public function getNamaCustomer()
    {
        return $this->customer->nama;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKomponen()
    {
        return $this->hasOne(KomponenBiaya::className(), ['id' => 'komponen_id']);
    }

    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['custid' => 'nim']);
    }
}
