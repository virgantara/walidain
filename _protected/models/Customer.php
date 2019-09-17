<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill_customer".
 *
 * @property int $id
 * @property string $custid
 * @property string $nama
 * @property string $va_code
 * @property string $kampus
 * @property string $nama_kampus
 * @property string $kode_prodi
 * @property string $nama_prodi
 * @property string $created_at
 * @property string $updated_at
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bill_customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['custid', 'nama', 'va_code', 'kampus', 'nama_kampus', 'kode_prodi', 'nama_prodi'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['custid', 'va_code', 'nama_kampus'], 'string', 'max' => 25],
            [['nama'], 'string', 'max' => 255],
            [['kampus', 'kode_prodi'], 'string', 'max' => 11],
            [['nama_prodi'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'custid' => 'Custid',
            'nama' => 'Nama',
            'va_code' => 'Va Code',
            'kampus' => 'Kampus',
            'nama_kampus' => 'Nama Kampus',
            'kode_prodi' => 'Kode Prodi',
            'nama_prodi' => 'Nama Prodi',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getSaldo()
    {
        $query = (new \yii\db\Query())->from('bill_transaksi');
        $query->where(['custid'=>$this->custid]);
        $sumDebet = $query->sum('DEBET');
        $sumKredit = $query->sum('KREDIT');
        $saldo = $sumKredit - $sumDebet;
        return $saldo;
    }
}
