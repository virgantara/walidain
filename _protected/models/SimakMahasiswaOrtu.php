<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "simak_mahasiswa_ortu".
 *
 * @property int $id
 * @property string|null $nim
 * @property string|null $hubungan
 * @property string|null $nama
 * @property string|null $agama
 * @property string|null $pendidikan
 * @property string|null $pekerjaan
 * @property string|null $penghasilan
 * @property string|null $hidup
 * @property string|null $alamat
 * @property string|null $kota
 * @property string|null $propinsi
 * @property string|null $negara
 * @property string|null $pos
 * @property string|null $telepon
 * @property string|null $hp
 * @property string|null $email
 * @property int|null $is_synced
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property SimakPilihan $agama0
 * @property SimakMastermahasiswa $nim0
 * @property SimakPilihan $pekerjaan0
 * @property SimakPilihan $pendidikan0
 * @property SimakPilihan $penghasilan0
 */
class SimakMahasiswaOrtu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'simak_mahasiswa_ortu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hubungan'], 'string'],
            [['is_synced'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['nim', 'kota', 'propinsi', 'negara', 'telepon', 'hp', 'email'], 'string', 'max' => 20],
            [['nama'], 'string', 'max' => 100],
            [['agama', 'pendidikan', 'pekerjaan', 'penghasilan', 'hidup', 'pos'], 'string', 'max' => 10],
            [['alamat'], 'string', 'max' => 255],
            [['nim'], 'exist', 'skipOnError' => true, 'targetClass' => SimakMastermahasiswa::className(), 'targetAttribute' => ['nim' => 'nim_mhs']],
            [['agama'], 'exist', 'skipOnError' => true, 'targetClass' => SimakPilihan::className(), 'targetAttribute' => ['agama' => 'value']],
            [['pendidikan'], 'exist', 'skipOnError' => true, 'targetClass' => SimakPilihan::className(), 'targetAttribute' => ['pendidikan' => 'value']],
            [['pekerjaan'], 'exist', 'skipOnError' => true, 'targetClass' => SimakPilihan::className(), 'targetAttribute' => ['pekerjaan' => 'value']],
            [['penghasilan'], 'exist', 'skipOnError' => true, 'targetClass' => SimakPilihan::className(), 'targetAttribute' => ['penghasilan' => 'value']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nim' => 'Nim',
            'hubungan' => 'Hubungan',
            'nama' => 'Nama',
            'agama' => 'Agama',
            'pendidikan' => 'Pendidikan',
            'pekerjaan' => 'Pekerjaan',
            'penghasilan' => 'Penghasilan',
            'hidup' => 'Hidup',
            'alamat' => 'Alamat',
            'kota' => 'Kota',
            'propinsi' => 'Propinsi',
            'negara' => 'Negara',
            'pos' => 'Pos',
            'telepon' => 'Telepon',
            'hp' => 'Hp',
            'email' => 'Email',
            'is_synced' => 'Is Synced',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Agama0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgama0()
    {
        return $this->hasOne(SimakPilihan::className(), ['value' => 'agama']);
    }

    /**
     * Gets query for [[Nim0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNim0()
    {
        return $this->hasOne(SimakMastermahasiswa::className(), ['nim_mhs' => 'nim']);
    }

    /**
     * Gets query for [[Pekerjaan0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPekerjaan0()
    {
        return $this->hasOne(SimakPilihan::className(), ['value' => 'pekerjaan'])->onCondition(['kode' => '55']);
    }

    /**
     * Gets query for [[Pendidikan0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPendidikan0()
    {
        return $this->hasOne(SimakPilihan::className(), ['value' => 'pendidikan'])->onCondition(['kode' => '01']);
    }

    /**
     * Gets query for [[Penghasilan0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPenghasilan0()
    {
        return $this->hasOne(SimakPilihan::className(), ['value' => 'penghasilan'])->onCondition(['kode' => '69']);
    }
}
