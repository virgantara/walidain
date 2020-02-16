<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "simak_konfirmasipembayaran".
 *
 * @property int $id
 * @property string $nim
 * @property string $pembayaran
 * @property string $semester
 * @property int $tahun_id
 * @property string $jumlah
 * @property string $tanggal
 * @property string $bank
 * @property string $file
 * @property string $keterangan
 * @property string $date_created
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 */
class SimakKonfirmasipembayaran extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'simak_konfirmasipembayaran';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nim', 'pembayaran', 'semester', 'jumlah', 'bank'], 'required'],
            [['tahun_id', 'status'], 'integer'],
            [['tanggal', 'date_created', 'created_at', 'updated_at'], 'safe'],
            [['file', 'keterangan'], 'string'],
            [['nim'], 'string', 'max' => 25],
            [['pembayaran'], 'string', 'max' => 30],
            [['semester'], 'string', 'max' => 5],
            [['jumlah', 'bank'], 'string', 'max' => 20],
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
            'pembayaran' => 'Pembayaran',
            'semester' => 'Semester',
            'tahun_id' => 'Tahun ID',
            'jumlah' => 'Jumlah',
            'tanggal' => 'Tanggal',
            'bank' => 'Bank',
            'file' => 'File',
            'keterangan' => 'Keterangan',
            'date_created' => 'Date Created',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
