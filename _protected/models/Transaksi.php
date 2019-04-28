<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill_transaksi".
 *
 * @property int $urut
 * @property string $CUSTID
 * @property string $METODE
 * @property string $TRXDATE
 * @property string $NOREFF
 * @property string $FIDBANK
 * @property string $KDCHANNEL
 * @property int $DEBET
 * @property int $KREDIT
 * @property string $REFFBANK
 * @property string $TRANSNO
 * @property string $created_at
 * @property string $updated_at
 */
class Transaksi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bill_transaksi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['TRXDATE'], 'required'],
            [['TRXDATE', 'created_at', 'updated_at'], 'safe'],
            [['DEBET', 'KREDIT'], 'integer'],
            [['CUSTID'], 'string', 'max' => 25],
            [['METODE'], 'string', 'max' => 15],
            [['NOREFF'], 'string', 'max' => 20],
            [['FIDBANK'], 'string', 'max' => 10],
            [['KDCHANNEL'], 'string', 'max' => 5],
            [['REFFBANK'], 'string', 'max' => 14],
            [['TRANSNO'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'urut' => 'Urut',
            'CUSTID' => 'Custid',
            'METODE' => 'Metode',
            'TRXDATE' => 'Trxdate',
            'NOREFF' => 'Noreff',
            'FIDBANK' => 'Fidbank',
            'KDCHANNEL' => 'Kdchannel',
            'DEBET' => 'Debet',
            'KREDIT' => 'Kredit',
            'REFFBANK' => 'Reffbank',
            'TRANSNO' => 'Transno',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
