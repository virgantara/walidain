<?php

namespace app\console\controllers;

use Yii;
use app\models\Tagihan;
use app\models\SimakMastermahasiswa;
use app\models\SimakKonfirmasipembayaran;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\web\NotFoundHttpException;


/**
 * TendikController implements the CRUD actions for Tendik model.
 */
class BillingController extends Controller
{

    public function actionUpdateStatus($tahun)
    {
        
        $list = SimakMastermahasiswa::find()->where(['status_aktivitas' => 'A'])->all();
        $transaction = Yii::$app->db->beginTransaction();
            // exit;
        $errors = '';
        try 
        {
            $counter = 0;
            $labels = '';
            foreach($list as $mhs)
            {
                $query = Tagihan::find();
                $query->alias('t');
                $query->joinWith(['komponen as k']);
                $query->andWhere(['k.kode' => '01','t.tahun'=>$tahun,'nim'=>$mhs->nim_mhs]);
                $query->andWhere('t.terbayar < t.nilai_minimal');

                $tagihan = $query->one();
                if(!empty($tagihan))
                {
                    $konfirmasi = \app\models\SimakKonfirmasipembayaran::find()->where([
                        'pembayaran' => '01',
                        'nim' => $mhs->nim_mhs,
                        'tahun_id' => $tahun
                    ])->one();    

                    if(!empty($konfirmasi))
                    {
                        if(in_array($mhs->kampus,[1,8]))
                        {
                            $konfirmasi->delete();
                            $labels .= "Mhs: ".$mhs->nama_mahasiswa." \n";
                            $counter++;
                        }
                    }
                }
            }
            echo 'Data updated. Konfirmasi deleted='.$counter;
            $transaction->commit();
        }

        catch(\Exception $e)
        {
            $errors .= $e->getMessage();
            $transaction->rollBack();
            echo $errors;
        }

        echo "\n";
        return ExitCode::OK;
    }


}
