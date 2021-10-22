<?php

namespace app\console\controllers;

use Yii;
use app\models\Tahun;
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

    public function actionAktivasi($status_aktivitas, $tahun_id)
    {


        $query = SimakMastermahasiswa::find()->where([
            'status_aktivitas' => $status_aktivitas
        ]);


        $listCustomer = $query->all();
        $transaction = \Yii::$app->db->beginTransaction();
        $errors = '';
        
        try 
        {

            

            $counter = 0;
            foreach($listCustomer as $c)
            {

                $query = Tagihan::find();
                $query->alias('t');
                $query->joinWith(['komponen as k','komponen.kategori as kk']);
                $query->andWhere([
                    'kk.kode' => '01',
                    't.nim' => $c->nim_mhs,
                    't.tahun' => $tahun_id,
                ]);

                // $query->andWhere('terbayar >= nilai_minimal');
                $lunas = $query->one();
                
                if(!empty($lunas))
                {
                    $status_lunas = (int)(($lunas->terbayar >= $lunas->nilai_minimal && $lunas->terbayar < $lunas->nilai) ||  $lunas->terbayar >=$lunas->nilai);
                    
                    if($status_lunas == 1)
                    {
                        $c->status_aktivitas = 'A';
                    }

                    else
                    {
                        $c->status_aktivitas = 'N';
                    }

                    
                    if($c->save(false,['status_aktivitas']))
                    {
                        $konfirm = SimakKonfirmasipembayaran::find()->where([
                            'nim' =>$c->nim_mhs,
                            'pembayaran' => '01',
                            'semester' => $c->semester,
                            'jumlah' => $lunas->terbayar,
                            'bank' => 'nama_bank',
                            'tahun_id' => $tahun_id
                        ])->one();

                        if(empty($konfirm))
                        {
                            $konfirm = new SimakKonfirmasipembayaran;
                            $konfirm->nim = $c->nim_mhs;
                            $konfirm->pembayaran = '01';
                            $konfirm->semester = $c->semester;
                            $konfirm->jumlah = $lunas->terbayar;
                            $konfirm->bank = 'nama_bank';
                            $konfirm->tahun_id = $tahun_id;
                            $konfirm->status = $status_lunas;
                        }

                        else{
                            $konfirm->pembayaran = '01';
                            $konfirm->jumlah = $lunas->terbayar;
                            $konfirm->status = $status_lunas;
                        }

                        if($konfirm->save())
                        {
                            $counter++;
                        }

                        else{
                            $errors .= \app\helpers\MyHelper::logError($konfirm);
                            throw new \Exception;
                        }
                        
                    }    

                    else{
                        $errors .= \app\helpers\MyHelper::logError($c);
                        throw new \Exception;
                    }
                }



            }
            $transaction->commit();
            echo $counter." Data mahasiswa telah diaktifkan";

            
        } catch (\Exception $e) {
            $errors .= $e->getMessage();
            $transaction->rollBack();
            echo $errors;
            
            
        } catch (\Throwable $e) {
            $errors .= $e->getMessage();
            $transaction->rollBack();
            echo $errors;
            
            
            
        }
        
        echo "\n";
        return ExitCode::OK;
        
    }


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
                $query->andWhere('t.terbayar < t.nilai_minimal and t.nilai > 0');


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
