<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "simak_induk_kegiatan".
 *
 * @property int $id
 * @property string $nama
 *
 * @property SimakJenisKegiatan[] $simakJenisKegiatans
 */
class SimakIndukKegiatan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'simak_induk_kegiatan';
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
     * Gets query for [[SimakJenisKegiatans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakIndukKegiatanKompetensis()
    {
        return $this->hasMany(SimakIndukKegiatanKompetensi::className(), ['induk_id' => 'id']);
    }

    /**
     * Gets query for [[SimakJenisKegiatans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimakJenisKegiatans()
    {
        return $this->hasMany(SimakJenisKegiatan::className(), ['induk_id' => 'id']);
    }

    public function getMaxKompetensi()
    {
        $query = SimakKompetensiRangeNilai::find()->select(['nilai_maksimal']);
        $query->joinWith(['indukKompetensi as ik']);

        $query->where([
            'ik.induk_id' => $this->id,
            'label' => 'Excellent'
        ]);

        $query->orderBy(['nilai_maksimal'=>SORT_DESC]);
        $total = $query->sum('nilai_maksimal');
        
        
        return $total;
    }

    public function getRangeNilai($value)
    {
        $query = SimakIndukRangeNilai::find();

        $query->where([
            'induk_id' => $this->id
        ]);

        $query->andWhere($value. ' >= nilai_minimal AND '.$value.' <= nilai_maksimal');

        return $query->one();
    }
}
