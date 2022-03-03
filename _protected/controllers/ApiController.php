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
class ApiController extends AppController
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



        $id = $_POST['id'];
        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);
        $client_token = Yii::$app->params['client_token'];
        $headers = ['x-access-token'=>$client_token];
        $params = ['key'=>$id];
        $response = $client->get('/k/p/list', $params,$headers)->send();

        $results = [
            'kode' => '',
            'nama' => '- Pilih Prodi -' 
        ];

        if ($response->isOk) {
            $results = $response->data['values'];

            
        }
        header('Content-Type: application/json');
        echo \yii\helpers\Json::encode($results); 
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

    public function actionTunggakan() 
    {
        $dataPost = $_POST['dataPost'];
        $out = [];
        if(!empty($dataPost['kampus']) && !empty($dataPost['tahun']))
        {
            // $sd = date('Ymd',strtotime($_POST['sd'])).'000001';
            // $ed = date('Ymd',strtotime($_POST['ed'])).'235959';
            $kampus = $dataPost['kampus'];
            $prodi = $dataPost['prodi'];
            $komponen = !empty($dataPost['komponen']) ? $dataPost['komponen'] : null;
            $tahun = $dataPost['tahun'];
            // $list = Pasien::find()->addFilterWhere(['like',])
            // $api_baseurl = Yii::$app->params['api_baseurl'];
            // $client = new Client(['baseUrl' => $api_baseurl]);
            // $client_token = Yii::$app->params['client_token'];
            // $headers = ['x-access-token'=>$client_token];
            // $response = $client->get('/b/tagihan/periode/tunggakan', [
            //     // 'startdate' => $sd,
            //     // 'enddate'=>$ed,
            //     'kampus' => $kampus,
            //     'prodi' => $prodi,
            //     'komponen' => $komponen
            // ],$headers)->send();
            
            $query = \app\models\Tagihan::find();
            $query->alias('p');
            $query->joinWith(['nim0 as m']);
            $query->joinWith(['komponen as k']);
            $query->where([
                'p.tahun' => $tahun,
                'm.kampus' => $kampus
            ]);
            
            if(!empty($prodi)){
                $query->andWhere(['m.kode_prodi' => $prodi]);
            }



            if(!empty($komponen))
            {
                $query->andWhere(['k.kategori_id' => $komponen]);   
            }

            if(!empty($dataPost['status_aktivitas']))
            {
                $query->andWhere(['m.status_aktivitas' => $dataPost['status_aktivitas']]);
            }

            $query->andWhere('p.terbayar < p.nilai');

            $result = $query->all();
            
            // if ($response->isOk) {
                // $result = $response->data['values'];
                $total_sisa = 0;
                $total_terbayar = 0;
                $out['values'] = [];
                foreach ($result as $d) {
                    $sisa = $d->nilai - $d->terbayar;
                    $terbayar = $d->terbayar;
                    $total_sisa += $sisa;
                    $total_terbayar += $terbayar;
                    $out['values'][] = [
                        'id' => $d->id,
                        'custid' => $d->nim,
                        'komponen'=> $d->komponen->nama,
                        'nama_mahasiswa'=> $d->nim0->nama_mahasiswa,
                        'semester' => $d->semester,
                        'status_mhs' => $d->nim0->status_aktivitas,
                        'prodi'=> $d->nim0->kodeProdi->nama_prodi,
                        'nilai' => \app\helpers\MyHelper::formatRupiah($d->nilai),
                        'terbayar' => \app\helpers\MyHelper::formatRupiah($d->terbayar),
                        'sisa' => \app\helpers\MyHelper::formatRupiah($sisa),
                        'sisaAmount' => $sisa,
                        'created_at' => $d->created_at,
                        'updated_at' => $d->updated_at,
                    ];
                }

                $out['total_sisa'] = \app\helpers\MyHelper::formatRupiah($total_sisa);
                $out['total_terbayar'] = \app\helpers\MyHelper::formatRupiah($total_terbayar);
            // }
        
        }
        
        echo json_encode($out);
        die();
    }


    public function actionTagihan() {

        $out = [];
        
        $dataPost = $_POST['dataPost'];
        if(!empty($dataPost['sd']) && !empty($dataPost['ed']))
        {
            $sd = date('Ymd',strtotime($dataPost['sd'])).'000001';
            $ed = date('Ymd',strtotime($dataPost['ed'])).'235959';
            $kampus = $dataPost['kampus'];
            $prodi = $dataPost['prodi'];
            $komponen = $dataPost['komponen'];
            $tahun = $dataPost['tahun'];
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
                'komponen' => $komponen,
                'tahun' => $tahun
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
                        'updated_at' => $d['updated_at'],
                    ];
                }

                $out['total_sisa'] = \app\helpers\MyHelper::formatRupiah($total_sisa);
                $out['total_terbayar'] = \app\helpers\MyHelper::formatRupiah($total_terbayar);
            }
        
        }
        
        header('Content-Type: application/json');
        echo \yii\helpers\Json::encode($out);

        die();
    }

    public function actionAjaxDataTagihan()
    {
        if(Yii::$app->request->isPost)
        {
            
            $dataItem = $_POST['dataItem'];
            $nim = $dataItem['nim'];
            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);
            $client_token = Yii::$app->params['client_token'];

            $headers = ['x-access-token'=>$client_token];
            
            $params = [
                'nim' => $nim,
            ];

            $results = [];

            $response = $client->get('/tagihan/rekap', $params,$headers)->send();
        
            if ($response->isOk) {
                
                $tmp = $response->data['values'];


                $total_tagihan = 0;
                $total_terbayar = 0;
                foreach($tmp as $t)
                {
                    $total_tagihan += $t['tagihan'];
                    $total_terbayar += $t['terbayar'];
                }

                $total_piutang = $total_tagihan - $total_terbayar;

                $results['rincian'] = $tmp;
                $results['total_tagihan'] = $total_tagihan;
                $results['total_terbayar'] = $total_terbayar;
                $results['total_piutang'] = $total_piutang;
            }

            echo json_encode($results);

            die();
        }
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
                    'label'=> $d['nim_mhs'].' - '.$d['nama_mahasiswa'].' - '.$d['nama_prodi'].' - Semester '.$d['semester']
                ];
            }
        }
        
        echo \yii\helpers\Json::encode($out);

      
    }
    
}
