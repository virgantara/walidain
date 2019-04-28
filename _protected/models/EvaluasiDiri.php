<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%evaluasi_diri}}".
 *
 * @property int $id
 * @property int $departemen_id
 * @property string $tanggal
 * @property string $strength
 * @property string $weakness
 * @property string $opportunity
 * @property string $threat
 * @property string $attachment
 * @property int $is_verified
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Departemen $departemen
 */
class EvaluasiDiri extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%evaluasi_diri}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['departemen_id', 'tanggal', 'strength', 'weakness', 'opportunity', 'threat'], 'required'],
            [['departemen_id', 'is_verified'], 'integer'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['strength', 'weakness', 'opportunity', 'threat'], 'string'],
            [['attachment'], 'string', 'max' => 255],
            [['departemen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Departemen::className(), 'targetAttribute' => ['departemen_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'departemen_id' => 'Departemen ID',
            'tanggal' => 'Tanggal',
            'strength' => 'Strength',
            'weakness' => 'Weakness',
            'opportunity' => 'Opportunity',
            'threat' => 'Threat',
            'attachment' => 'Attachment',
            'is_verified' => 'Is Verified',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getNamaDepartemen(){
        return $this->departemen->nama;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartemen()
    {
        return $this->hasOne(Departemen::className(), ['id' => 'departemen_id']);
    }
}
