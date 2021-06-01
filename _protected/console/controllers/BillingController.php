<?php

namespace app\console\controllers;

use Yii;
use app\models\Tagihan;
use app\models\SimakDatakrs;
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
            $krs_counter = 0;
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
                    $konfirmasis = \app\models\SimakKonfirmasipembayaran::find()->where([
                        'pembayaran' => '01',
                        'nim' => $mhs->nim_mhs,
                        'tahun_id' => $tahun
                    ])->all();  

                    foreach($konfirmasis as $konfirmasi)
                    {
                        if(!empty($konfirmasi))
                        {
                            if(in_array($mhs->kampus,[1,8]))
                            {
                                $list_krs = SimakDatakrs::find()->where(['mahasiswa'=>$mhs->nim_mhs,'tahun_akademik'=>$tahun])->all();

                                foreach($list_krs as $krs)
                                {
                                    if(!empty($krs->nilai_huruf))
                                    {
                                        $krs->delete();
                                        $krs_counter++;
                                    }
                                }

                                $konfirmasi->delete();
                                $labels .= "Mhs: \n".$mhs->nim_mhs." - ".$mhs->nama_mahasiswa." KRS deleted : ".$krs_counter." \n";
                                $counter++;
                            }
                        } 
                    }  

                    
                }
            }
            echo 'Data updated. Konfirmasi deleted='.$counter.' ket: '.$labels;
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
