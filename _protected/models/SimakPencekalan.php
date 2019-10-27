<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "simak_pencekalan".
 *
 * @property int $id
 * @property string $tahun_id
 * @property string $nim
 * @property int $tahfidz
 * @property int $adm
 * @property int $akpam
 * @property int $akademik
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SimakMastermahasiswa $nim0
 * @property SimakTahunakademik $tahun
 */
class SimakPencekalan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'simak_pencekalan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tahun_id', 'nim'], 'required'],
            [['tahfidz', 'adm', 'akpam', 'akademik'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['tahun_id'], 'string', 'max' => 5],
            [['nim'], 'string', 'max' => 25],
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
            'tahfidz' => 'Tahfidz',
            'adm' => 'Adm',
            'akpam' => 'Akpam',
            'akademik' => 'Akademik',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNim0()
    {
        return $this->hasOne(SimakMastermahasiswa::className(), ['nim_mhs' => 'nim']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTahun()
    {
        return $this->hasOne(SimakTahunakademik::className(), ['tahun_id' => 'tahun_id']);
    }
}
