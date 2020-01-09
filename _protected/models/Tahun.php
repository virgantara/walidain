<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill_tahun".
 *
 * @property int $id
 * @property string $nama
 * @property string $hijriyah
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BiayaFakultas[] $biayaFakultas
 */
class Tahun extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bill_tahun';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'nama', 'hijriyah'], 'required'],
            [['id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['nama', 'hijriyah'], 'string', 'max' => 150],
            [['id'], 'unique'],
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
            'hijriyah' => 'Hijriyah',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBiayaFakultas()
    {
        return $this->hasMany(BiayaFakultas::className(), ['tahun_akademik' => 'id']);
    }
}
