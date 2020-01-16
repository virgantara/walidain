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

    

    public function actionRekapTransaksi() 
    {

        $out = [];
        ob_start();
        if(!empty($_POST['sd']) && !empty($_POST['ed']))
        {
            $sd = date('Ymd',strtotime($_POST['sd'])).'000001';
            $ed = date('Ymd',strtotime($_POST['ed'])).'235959';
            // $list = Pasien::find()->addFilterWhere(['like',])
            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);
            $client_token = Yii::$app->params['client_token'];
            $headers = ['x-access-token'=>$client_token];
            $response = $client->get('/b/transaksi/list', [
                'startdate' => $sd,
                'enddate'=>$ed
            ],$headers)->send();
            
            
            
            if ($response->isOk) {
                $result = $response->data['values'];
                $total_sisa = 0;
                $total_terbayar = 0;
                $out['values'] = [];
                $list_nim = [];
                foreach ($result as $d) {
                    $total_sisa += $d['sisa'];

                    if(!in_array($d['nim'], $list_nim))
                    {
                        $list_nim[] = $d['nim'];
                        $out['values'][] = [
                        // 'id' => $d['id'],
                            'p' => $d['p'],
                            'nim' => $d['nim'],
                            'n'=> $d['n'],
                            'nr' => $d['nr'],
                            'nl'=> \app\helpers\MyHelper::formatRupiah($d['nl']),
                            'd' => $d['d'],
                        ];
                    }    

                    else
                    {
                        $out['values'][] = [
                            // 'id' => $d['id'],
                            'p' => '',
                            'nim' => '',
                            'n'=> '',
                            'nr' => $d['nr'],
                            'nl'=> \app\helpers\MyHelper::formatRupiah($d['nl']),
                            'd' => $d['d'],
                        ];
                    }

                    // $total_terbayar += $d['terbayar'];
                    
                }

                // $out['total_sisa'] = \app\helpers\MyHelper::formatRupiah($total_sisa);
                // $out['total_terbayar'] = \app\helpers\MyHelper::formatRupiah($total_terbayar);
            }
        
        }
        
        header('Content-Type: application/json');
        echo \yii\helpers\Json::encode($out);
        
      
    }

    public function actionListKampus() {

        $out = [];

        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);
        $client_token = Yii::$app->params['client_token'];
        $headers = ['x-access-token'=>$client_token];
        $response = $client->get('/k/list',[],$headers)->send();
        
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
        $client_token = Yii::$app->params['client_token'];
        $headers = ['x-access-token'=>$client_token];
        $response = $client->get('/p/list',[],$headers)->send();
        
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
         $client_token = Yii::$app->params['client_token'];
        $headers = ['x-access-token'=>$client_token];
        $response = $client->get('/f/list',[],$headers)->send();
        
        if ($response->isOk) {
            $result = $response->data['values'];
            $total_sisa = 0;
            $total_terbayar = 0;
            $out['values'] = $result;
        }
        header('Content-Type: application/json');
        echo \yii\helpers\Json::encode($out); 
    }

    public function actionRekapTunggakan() 
    {

        $out = [];
        ob_start();
        if(!empty($_POST['sd']) && !empty($_POST['ed']))
        {
            $sd = date('Ymd',strtotime($_POST['sd'])).'000001';
            $ed = date('Ymd',strtotime($_POST['ed'])).'235959';
            // $list = Pasien::find()->addFilterWhere(['like',])
            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);
            $client_token = Yii::$app->params['client_token'];
            $headers = ['x-access-token'=>$client_token];
            $response = $client->get('/b/tunggakan/rekap', [
                'startdate' => $sd,
                'enddate'=>$ed
            ],$headers)->send();
            
            
            
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
            $client_token = Yii::$app->params['client_token'];
           $headers = ['x-access-token'=>$client_token];
            $response = $client->get('/b/tagihan/periode/tunggakan', [
                'startdate' => $sd,
                'enddate'=>$ed,
                'kampus' => $kampus,
                'prodi' => $prodi
            ],$headers)->send();
            
            
            
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
            $komponen = $_POST['komponen'];
            $client_token = Yii::$app->params['client_token'];
            // $list = Pasien::find()->addFilterWhere(['like',])
            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);
            $client_token = Yii::$app->params['client_token'];
            $headers = ['x-access-token'=>$client_token];
            $response = $client->get('/b/tagihan/periode', [
                'startdate' => $sd,
                'enddate'=>$ed,
                'kampus' => $kampus,
                'prodi' => $prodi,
                'komponen' => $komponen
            ],$headers)->send();
            
            
            
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
        $client_token = Yii::$app->params['client_token'];
        $headers = ['x-access-token'=>$client_token];
        $response = $client->get('/d/ekd', ['tahun' => $ta,'prodi'=>$prodi],$headers)->send();
        
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

    

    public function actionAjaxCariMhs() {

        $q = $_GET['term'];
        
        // $list = Pasien::find()->addFilterWhere(['like',])
       
        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);
         $client_token = Yii::$app->params['client_token'];
        $headers = ['x-access-token'=>$client_token];
        $response = $client->get('/m/cari', ['key' => $q],$headers)->send();
        
        $out = [];
        
        if ($response->isOk) {
            $result = $response->data['values'];
            foreach ($result as $d) {
                $out[] = [
                    'id' => $d['id'],
                    'nim' => $d['nim_mhs'],
                    'smt' => $d['semester'],
                    'label'=> $d['nim_mhs'].' - '.$d['nama_mahasiswa']
                ];
            }
        }
        
        echo \yii\helpers\Json::encode($out);

      
    }
    
}
