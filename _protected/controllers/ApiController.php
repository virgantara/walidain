<?php

namespace app\controllers;

use Yii;


use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\helpers\MyHelper;
use yii\httpclient\Client;


/**
 * PenjualanController implements the CRUD actions for Penjualan model.
 */
class ApiController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionListKampus() {

        $out = [];

        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);

        $response = $client->get('/k/list')->send();
        
        if ($response->isOk) {
            $result = $response->data['values'];
            $out['values'] = $result;
        }
        header('Content-Type: application/json');
        echo \yii\helpers\Json::encode($out); 
    }

    public function actionListProdi() {

        $out = [];

        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);

        $response = $client->get('/p/list')->send();
        
        if ($response->isOk) {
            $result = $response->data['values'];
            $out['values'] = $result;
        }
        header('Content-Type: application/json');
        echo \yii\helpers\Json::encode($out); 
    }

    public function actionListFakultas() {

        $out = [];

        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);

        $response = $client->get('/f/list')->send();
        
        if ($response->isOk) {
            $result = $response->data['values'];
            $total_sisa = 0;
            $total_terbayar = 0;
            $out['values'] = $result;
        }
        header('Content-Type: application/json');
        echo \yii\helpers\Json::encode($out); 
    }

    public function actionRekapTunggakan() {

        $out = [];
        ob_start();
        if(!empty($_POST['sd']) && !empty($_POST['ed']))
        {
            $sd = date('Ymd',strtotime($_POST['sd'])).'000001';
            $ed = date('Ymd',strtotime($_POST['ed'])).'235959';
            // $list = Pasien::find()->addFilterWhere(['like',])
            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);

            $response = $client->get('/b/tunggakan/rekap', [
                'startdate' => $sd,
                'enddate'=>$ed
            ])->send();
            
            
            
            if ($response->isOk) {
                $result = $response->data['values'];
                $total_sisa = 0;
                $total_terbayar = 0;
                $out['values'] = [];
                foreach ($result as $d) {
                    $total_sisa += $d['sisa'];
                    // $total_terbayar += $d['terbayar'];
                    $out['values'][] = [
                        // 'id' => $d['id'],
                        'prodi' => $d['prodi'],
                        'semester'=> $d['semester'],
                        'sisa'=> \app\helpers\MyHelper::formatRupiah($d['sisa']),
                        'total' => $d['total'],
                    ];
                }

                $out['total_sisa'] = \app\helpers\MyHelper::formatRupiah($total_sisa);
                // $out['total_terbayar'] = \app\helpers\MyHelper::formatRupiah($total_terbayar);
            }
        
        }
        
        header('Content-Type: application/json');
        echo \yii\helpers\Json::encode($out);
        
      
    }

    public function actionTunggakan() {

        $out = [];
        ob_start();
        if(!empty($_POST['sd']) && !empty($_POST['ed']))
        {
            $sd = date('Ymd',strtotime($_POST['sd'])).'000001';
            $ed = date('Ymd',strtotime($_POST['ed'])).'235959';
            $kampus = $_POST['kampus'];
            $prodi = $_POST['prodi'];
            // $list = Pasien::find()->addFilterWhere(['like',])
            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);

            $response = $client->get('/b/tagihan/periode/tunggakan', [
                'startdate' => $sd,
                'enddate'=>$ed,
                'kampus' => $kampus,
                'prodi' => $prodi
            ])->send();
            
            
            
            if ($response->isOk) {
                $result = $response->data['values'];
                $total_sisa = 0;
                $total_terbayar = 0;
                $out['values'] = [];
                foreach ($result as $d) {
                    $total_sisa += $d['sisa'];
                    $total_terbayar += $d['terbayar'];
                    $out['values'][] = [
                        'id' => $d['id'],
                        'custid' => $d['custid'],
                        'komponen'=> $d['komponen'],
                        'nama_mahasiswa'=> $d['nama_mahasiswa'],
                        'semester' => $d['semester'],
                        'prodi'=> $d['prodi'],
                        'nilai' => \app\helpers\MyHelper::formatRupiah($d['nilai']),
                        'terbayar' => \app\helpers\MyHelper::formatRupiah($d['terbayar']),
                        'sisa' => \app\helpers\MyHelper::formatRupiah($d['sisa']),
                        'created_at' => $d['created_at'],
                    ];
                }

                $out['total_sisa'] = \app\helpers\MyHelper::formatRupiah($total_sisa);
                $out['total_terbayar'] = \app\helpers\MyHelper::formatRupiah($total_terbayar);
            }
        
        }
        
        header('Content-Type: application/json');
        echo \yii\helpers\Json::encode($out);
        
      
    }


    public function actionTagihan() {

        $out = [];
         ob_start();
        if(!empty($_POST['sd']) && !empty($_POST['ed']))
        {
            $sd = date('Ymd',strtotime($_POST['sd'])).'000001';
            $ed = date('Ymd',strtotime($_POST['ed'])).'235959';
            $kampus = $_POST['kampus'];
            $prodi = $_POST['prodi'];
            
            // $list = Pasien::find()->addFilterWhere(['like',])
            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);

            $response = $client->get('/b/tagihan/periode', [
                'startdate' => $sd,
                'enddate'=>$ed,
                'kampus' => $kampus,
                'prodi' => $prodi
            ])->send();
            
            
            
            if ($response->isOk) {
                $result = $response->data['values'];
                $total_sisa = 0;
                $total_terbayar = 0;
                $out['values'] = [];
                foreach ($result as $d) {
                    $total_sisa += $d['sisa'];
                    $total_terbayar += $d['terbayar'];
                    $out['values'][] = [
                        'id' => $d['id'],
                        'komponen'=> $d['komponen'],
                        'custid' => $d['custid'],
                        'nama_mahasiswa'=> $d['nama_mahasiswa'],
                        'semester' => $d['semester'],
                        'prodi'=> $d['prodi'],
                        'nilai' => \app\helpers\MyHelper::formatRupiah($d['nilai']),
                        'terbayar' => \app\helpers\MyHelper::formatRupiah($d['terbayar']),
                        'sisa' => \app\helpers\MyHelper::formatRupiah($d['sisa']),
                        'created_at' => $d['created_at'],
                    ];
                }

                $out['total_sisa'] = \app\helpers\MyHelper::formatRupiah($total_sisa);
                $out['total_terbayar'] = \app\helpers\MyHelper::formatRupiah($total_terbayar);
            }
        
        }
        
        header('Content-Type: application/json');
        echo \yii\helpers\Json::encode($out);

      
    }

    public function actionAjaxGetEkd() {

        $tahun = $_POST['tahun'];
        $semester = $_POST['semester'];
        $prodi = $_POST['prodi'];
        $ta = $tahun.$semester;
        
        // $list = Pasien::find()->addFilterWhere(['like',])
        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);
        $response = $client->get('/d/ekd', ['tahun' => $ta,'prodi'=>$prodi])->send();
        
        $out = [];
        
        if ($response->isOk) {
            $out = $response->data['values'][0];
            // foreach ($result as $d) {
            //     $out[] = [
            //         'kode' => $d['kode'],
            //         'nama'=> $d['nama'],
            //         'kode_mk' => $d['kode_mk'],
            //         'nama_mk'=> $d['nama_mk'],
            //         'sks'=> $d['sks'],
            //         'angka' => $d['nilai_angka'],
            //         'huruf' => $d['nilai_huruf'],
            //         'keterangan' => $d['keterangan'],
            //     ];
            // }
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 
        \Yii::$app->response->data  =  $out;
        
        // echo \yii\helpers\Json::encode($out);

      
    }

    public function actionAjaxPasienDaftar() {

        $q = $_GET['term'];
        
        // $list = Pasien::find()->addFilterWhere(['like',])
        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);
        $jenis_rawat = $_GET['jenis_rawat'];
        $response = $client->get('/p/daftar', ['key' => $q,'jenis'=>$jenis_rawat])->send();
        
        $out = [];
        
        if ($response->isOk) {
            $result = $response->data['values'];
            foreach ($result as $d) {
                $out[] = [
                    'id' => $d['NoMedrec'],
                    'label'=> $d['NAMA'].' '.$d['NoMedrec'],
                    'nodaftar'=> $d['NODAFTAR'],
                    'jenispx'=> $d['KodeGol'],
                    'namagol' => $d['NamaGol'],
                    'tgldaftar' => $d['TGLDAFTAR'],
                    'jamdaftar' => $d['JamDaftar'],
                    'kodeunit' => $d['KodeUnit'],
                    'namaunit' => $d['unit_tipe'] == 2 ? 'Poli '.$d['NamaUnit'] : $d['NamaUnit']  
                ];
            }
        }
        

        echo \yii\helpers\Json::encode($out);

      
    }

    public function actionAjaxPasien() {

        $q = $_GET['term'];
        
        // $list = Pasien::find()->addFilterWhere(['like',])
       
        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);
        $response = $client->get('/pasien/nama', ['key' => $q])->send();
        
        $out = [];
        
        if ($response->isOk) {
            $result = $response->data['values'];
            foreach ($result as $d) {
                $out[] = [
                    'id' => $d['NoMedrec'],
                    'label'=> $d['NAMA'].' - '.$d['NoMedrec']
                ];
            }
        }
        
        echo \yii\helpers\Json::encode($out);

      
    }
    
}
