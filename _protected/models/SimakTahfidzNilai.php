<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "simak_tahfidz_nilai".
 *
 * @property int $id
 * @property string|null $tahun_id
 * @property string $nim
 * @property float $hafalan
 * @property float $kelancaran
 * @property float $makhrojul_huruf
 * @property float $tajwid
 * @property float $nilai_angka
 * @property string $nilai_huruf
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property SimakMastermahasiswa $nim0
 * @property SimakTahunakademik $tahun
 */
class SimakTahfidzNilai extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'simak_tahfidz_nilai';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nim','nilai_huruf'], 'required'],
            [['hafalan', 'kelancaran', 'makhrojul_huruf', 'tajwid', 'nilai_angka'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['tahun_id'], 'string', 'max' => 5],
            [['nim'], 'string', 'max' => 25],
            [['nilai_huruf'], 'string', 'max' => 3],
            [['tahun_id', 'nim'], 'unique', 'targetAttribute' => ['tahun_id', 'nim']],
            [['nim'], 'exist', 'skipOnError' => true, 'targetClass' => SimakMastermahasiswa::className(), 'targetAttribute' => ['nim' => 'nim_mhs']],
            [['tahun_id'], 'exist', 'skipOnError' => true, 'targetClass' => SimakTahunakademik::className(), 'targetAttribute' => ['tahun_id' => 'tahun_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tahun_id' => 'Tahun ID',
            'nim' => 'Nim',
            'hafalan' => 'Hafalan',
            'kelancaran' => 'Kelancaran',
            'makhrojul_huruf' => 'Makhrojul Huruf',
            'tajwid' => 'Tajwid',
            'nilai_angka' => 'Nilai Angka',
            'nilai_huruf' => 'Nilai Huruf',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
     * Gets query for [[Tahun]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTahun()
    {
        return $this->hasOne(SimakTahunakademik::className(), ['tahun_id' => 'tahun_id']);
    }
}
