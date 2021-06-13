<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "simak_kampus".
 *
 * @property int $id
 * @property string $kode_kampus
 * @property string $nama_kampus
 *
 * @property SimakMasterkela[] $simakMasterkelas
 */
class SimakKampus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'simak_kampus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode_kampus', 'nama_kampus'], 'required'],
            [['kode_kampus'], 'string', 'max' => 2],
            [['nama_kampus'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_kampus' => 'Kode Kampus',
            'nama_kampus' => 'Nama Kampus',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSimakMasterkelas()
    {
        return $this->hasMany(SimakMasterkela::className(), ['id_kampus' => 'id']);
    }

    public static function getList()
    {
        return SimakKampus::find()->all();
    }
}
