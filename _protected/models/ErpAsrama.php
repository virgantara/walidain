<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "erp_asrama".
 *
 * @property int $id
 * @property string $kampus_id
 * @property string $nama
 * @property string|null $is_zona_quran
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property ErpKamar[] $erpKamars
 * @property SimakKampus $kampus
 */
class ErpAsrama extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'erp_asrama';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kampus_id', 'nama'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['kampus_id'], 'string', 'max' => 2],
            [['nama'], 'string', 'max' => 255],
            [['is_zona_quran'], 'string', 'max' => 1],
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
            'kampus_id' => 'Kampus ID',
            'nama' => 'Nama',
            'is_zona_quran' => 'Is Zona Quran',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[ErpKamars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getErpKamars()
    {
        return $this->hasMany(ErpKamar::className(), ['asrama_id' => 'id']);
    }

    /**
     * Gets query for [[Kampus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKampus()
    {
        return $this->hasOne(SimakKampus::className(), ['kode_kampus' => 'kampus_id']);
    }
}
