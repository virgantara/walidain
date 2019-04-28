<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill_periode_tagihan".
 *
 * @property int $id
 * @property string $nama
 * @property string $created_at
 * @property string $updated_at
 *
 * @property KomponenBiaya[] $komponenBiayas
 */
class PeriodeTagihan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bill_periode_tagihan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['nama'], 'string', 'max' => 100],
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKomponenBiayas()
    {
        return $this->hasMany(KomponenBiaya::className(), ['periode_tagihan_id' => 'id']);
    }
}
