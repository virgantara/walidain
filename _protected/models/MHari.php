<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_hari".
 *
 * @property int $id
 * @property string|null $nama_hari
 * @property int $urutan
 *
 * @property SimakJadwal[] $simakJadwals
 */
class MHari extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'm_hari';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urutan'], 'integer'],
            [['nama_hari'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_hari' => 'Nama Hari',
            'urutan' => 'Urutan',
        ];
    }

    /**
     * Gets query for [[SimakJadwals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakJadwals()
    {
        return $this->hasMany(SimakJadwal::className(), ['hari' => 'nama_hari']);
    }
}
