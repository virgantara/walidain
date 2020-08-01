<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill_tagihan".
 *
 * @property int $id
 * @property int $urutan
 * @property int $semester
 * @property int $tahun
 * @property string $nim
 * @property int $komponen_id
 * @property double $nilai
 * @property double $nilai_minimal
 * @property double $terbayar
 * @property int $edit
 * @property int $status_bayar
 * @property string $created_at
 * @property string $updated_at
 *
 * @property KomponenBiaya $komponen
 * @property SimakMastermahasiswa $nim0
 * @property Tahun $tahun0
 */
class Tagihan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bill_tagihan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urutan', 'semester', 'tahun', 'komponen_id', 'edit', 'status_bayar'], 'integer'],
            [['semester', 'tahun', 'nim', 'komponen_id', 'nilai','nilai','nilai_minimal'], 'required'],
            [['nilai', 'nilai_minimal', 'terbayar'], 'number'],
            [['created_at', 'updated_at','nilai','nilai_minimal','is_tercekal'], 'safe'],
            [['nim'], 'string', 'max' => 25],
            [['komponen_id'], 'exist', 'skipOnError' => true, 'targetClass' => KomponenBiaya::className(), 'targetAttribute' => ['komponen_id' => 'id']],
            [['nim'], 'exist', 'skipOnError' => true, 'targetClass' => SimakMastermahasiswa::className(), 'targetAttribute' => ['nim' => 'nim_mhs']],
            [['tahun'], 'exist', 'skipOnError' => true, 'targetClass' => Tahun::className(), 'targetAttribute' => ['tahun' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'urutan' => 'Urutan',
            'semester' => 'Semester',
            'tahun' => 'Tahun',
            'nim' => 'NIM',
            'namaCustomer' => 'Nama Mahasiswa',
            'komponen_id' => 'Komponen ID',
            'nilai' => 'Nilai Tagihan',
            'nilai_minimal' => 'Nilai Minimal',
            'terbayar' => 'Terbayar',
            'edit' => 'Edit',
            'status_bayar' => 'Status Bayar',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'namaSemester' => 'Semester'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKomponen()
    {
        return $this->hasOne(KomponenBiaya::className(), ['id' => 'komponen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNim0()
    {
        return $this->hasOne(SimakMastermahasiswa::className(), ['nim_mhs' => 'nim']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTahun0()
    {
        return $this->hasOne(Tahun::className(), ['id' => 'tahun']);
    }

    
    public function getNamaTahun()
    {
        return $this->tahun0->nama.' '.$this->tahun0->hijriyah;
    }

    public function getNamaKomponen()
    {
        return $this->komponen->nama;
    }

    public function getNamaCustomer()
    {
        return $this->nim0->nama_mahasiswa;
    }

    public function getNamaProdi()
    {
        return $this->nim0->kodeProdi->nama_prodi;
    }

    public function getNamaSemester()
    {
        return $this->nim0->semester;
    }

    public function getNamaKampus()
    {
        $query = (new \yii\db\Query())->from('simak_kampus');
        $query->where(['kode_kampus'=>$this->nim0->kampus]);
        $row = $query->one();

        return !empty($row) ? $row['nama_kampus'] : '';
    }

    public function getStatusPembayaran()
    {
        if($this->terbayar >= $this->nilai)
            return 1;
        else if($this->terbayar > 0 && $this->terbayar < $this->nilai)
            return 2;
        else if($this->terbayar == 0)
            return 3;
        else
            return 0;
        
    }
}
