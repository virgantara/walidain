<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill_bulan".
 *
 * @property int $id
 * @property string $nama
 *
 * @property KomponenBiaya[] $komponenBiayas
 */
class Bulan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bill_bulan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKomponenBiayas()
    {
        return $this->hasMany(KomponenBiaya::className(), ['bulan_id' => 'id']);
    }
}
