<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "erp_kamar".
 *
 * @property int $id
 * @property string $nama
 * @property int $asrama_id
 * @property int $kapasitas
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ErpAsrama $asrama
 * @property ErpRiwayatKamar[] $erpRiwayatKamars
 * @property ErpRiwayatKamar[] $erpRiwayatKamars0
 */
class ErpKamar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'erp_kamar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama', 'asrama_id'], 'required'],
            [['asrama_id', 'kapasitas'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['nama'], 'string', 'max' => 255],
            [['asrama_id'], 'exist', 'skipOnError' => true, 'targetClass' => ErpAsrama::className(), 'targetAttribute' => ['asrama_id' => 'id']],
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
            'asrama_id' => 'Asrama ID',
            'kapasitas' => 'Kapasitas',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsrama()
    {
        return $this->hasOne(ErpAsrama::className(), ['id' => 'asrama_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getErpRiwayatKamars()
    {
        return $this->hasMany(ErpRiwayatKamar::className(), ['kamar_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getErpRiwayatKamars0()
    {
        return $this->hasMany(ErpRiwayatKamar::className(), ['dari_kamar_id' => 'id']);
    }
}
