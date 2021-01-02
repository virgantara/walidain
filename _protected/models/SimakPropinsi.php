<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "simak_propinsi".
 *
 * @property int $kode
 * @property int $id
 * @property string $prov
 * @property string|null $keterangan
 * @property string $map_id
 * @property int|null $created_by
 * @property string|null $date_created
 * @property int|null $updated_by
 * @property int|null $last_updated
 *
 * @property SimakKabupaten[] $simakKabupatens
 */
class SimakPropinsi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'simak_propinsi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'prov', 'map_id'], 'required'],
            [['id', 'created_by', 'updated_by', 'last_updated'], 'integer'],
            [['date_created'], 'safe'],
            [['prov', 'keterangan'], 'string', 'max' => 100],
            [['map_id'], 'string', 'max' => 11],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kode' => 'Kode',
            'id' => 'ID',
            'prov' => 'Prov',
            'keterangan' => 'Keterangan',
            'map_id' => 'Map ID',
            'created_by' => 'Created By',
            'date_created' => 'Date Created',
            'updated_by' => 'Updated By',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * Gets query for [[SimakKabupatens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakKabupatens()
    {
        return $this->hasMany(SimakKabupaten::className(), ['id_provinsi' => 'id']);
    }
}
