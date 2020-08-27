<?php

namespace app\controllers;

use Yii;
use app\models\TagihanSearch;
use app\models\Transaksi;
use app\models\TransaksiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\helpers\MyHelper;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use app\models\Bulan;
use app\models\SimakKampus;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * PenjualanController implements the CRUD actions for Penjualan model.
 */
class LaporanController extends Controller
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

    public function actionAjaxRincianTagihanExport()
    {
        if(Yii::$app->request->isPost)
        {
            
            $dataItem = $_POST['dataItem'];
            $tahun = $dataItem['tahun'];
            $status_aktivitas = $dataItem['status_aktivitas'];
            $singkatan = $dataItem['singkatan'];
            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);
            $client_token = Yii::$app->params['client_token'];

            $headers = ['x-access-token'=>$client_token];
            
            $params = [
                'tahun' => $tahun,
                'status_aktivitas' => $status_aktivitas,
                'singkatan' => $singkatan
            ];

            $results = [];

            $response = $client->get('/tagihan/rincian', $params,$headers)->send();
        
            if ($response->isOk) {
                $results = $response->data['values'];

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                
                
                // Add column headers
                $sheet->setCellValue('A3', 'No')
                    ->setCellValue('B3', 'NIM')
                    ->setCellValue('C3', 'NAMA')
                    ->setCellValue('D3', 'SMT')
                    ->setCellValue('E3', 'KAMPUS')
                    ->setCellValue('F3', 'PRODI')
                    ->setCellValue('G3', 'TAGIHAN')
                    ->setCellValue('H3', 'TERBAYAR')
                    ->setCellValue('I3', 'SISA TAGIHAN');


                $sheet->mergeCells('A1:I1')->getStyle('A1:I1')->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('A1','DATA PEMBAYARAN MAHASISWA');


                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(25);
                $sheet->getColumnDimension('C')->setWidth(20);
                $sheet->getColumnDimension('D')->setWidth(35);
                $sheet->getColumnDimension('E')->setWidth(30);
                $sheet->getColumnDimension('F')->setWidth(20);
                $sheet->getColumnDimension('G')->setWidth(20);
                $sheet->getColumnDimension('H')->setWidth(20);
                $sheet->getColumnDimension('I')->setWidth(20);
                // $sheet->getColumnDimension('G')->setWidth(20);
                $i= 0;
                $ii = 4;

            
                $total_terbayar = 0;
                $total_piutang = 0;
                $total_tagihan = 0;
                foreach ($results as $q => $d) {

                    $sheet->setCellValue('A'.$ii, $q+1);
                    $sheet->setCellValue('B'.$ii, $d['nim_mhs']);
                    $sheet->setCellValue('C'.$ii, $d['nama_mahasiswa']);
                    $sheet->setCellValue('D'.$ii, $d['semester']);
                    $sheet->setCellValue('E'.$ii, $d['nama_kampus']);
                    $sheet->setCellValue('F'.$ii, $d['singkatan']);
                    $sheet->setCellValue('G'.$ii, $d['tagihan']);
                    $sheet->setCellValue('H'.$ii, $d['terbayar']);
                    $sheet->setCellValue('I'.$ii, $d['piutang']);

                    $sheet->getStyle('G'.$ii)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getStyle('H'.$ii)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getStyle('I'.$ii)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
                    // $sheet->setCellValue('G'.$ii, $d['nl']);
                    $ii++;

                    $total_piutang += $d['piutang'];
                    $total_terbayar += $d['terbayar'];
                    $total_tagihan += $d['tagihan'];
                }

                
                $sheet->setCellValue('A'.$ii, '');
                $sheet->setCellValue('B'.$ii, '');
                $sheet->setCellValue('C'.$ii, '');
                $sheet->setCellValue('D'.$ii, '');
                $sheet->setCellValue('E'.$ii, '');
                $sheet->setCellValue('F'.$ii, 'Total');
                $sheet->setCellValue('G'.$ii, $total_tagihan);
                $sheet->setCellValue('H'.$ii, $total_terbayar);
                $sheet->setCellValue('I'.$ii, $total_piutang);

                $sheet->getStyle('G'.$ii)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getStyle('H'.$ii)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getStyle('I'.$ii)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
                $sheet->setTitle('Data Pembayaran Mahasiswa');
                
                // ob_end_clean();
                // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                // header('Content-Disposition: attachment;filename="data_pembayaran.xlsx"');
                // header('Cache-Control: max-age=0');
                
                $writer = new Xlsx($spreadsheet);
                ob_start();
                $writer->save('php://output');
                $xlsData = ob_get_contents();
                ob_end_clean();
                $response =  array(
                    'op' => 'ok',
                    'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
                );

                die(json_encode($response));
            }

        }
    }

    public function actionTransaksi(){

        $model = new TransaksiSearch();

        if(!empty($_GET['export']))
        {
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            
            // Add column headers
            $sheet->setCellValue('A3', 'No')
                ->setCellValue('B3', 'Trx Date')
                ->setCellValue('C3', 'NIM')
                ->setCellValue('D3', 'NAMA')
                ->setCellValue('E3', 'PRODI')
                // ->setCellValue('F3', 'No Reff')
                ->setCellValue('F3', 'Nominal');


            $sheet->mergeCells('A1:F1')->getStyle('A1:F1')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A1','LAPORAN TRANSAKSI');

            $sheet->mergeCells('A2:F2')->getStyle('A2:F2')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A2','Tanggal '.$_GET['TransaksiSearch']['tanggal_awal'].' s/d '.$_GET['TransaksiSearch']['tanggal_akhir']);

            //Put each record in a new cell

            $sheet->getColumnDimension('A')->setWidth(5);
            $sheet->getColumnDimension('B')->setWidth(25);
            $sheet->getColumnDimension('C')->setWidth(20);
            $sheet->getColumnDimension('D')->setWidth(35);
            $sheet->getColumnDimension('E')->setWidth(30);
            $sheet->getColumnDimension('F')->setWidth(20);
            // $sheet->getColumnDimension('G')->setWidth(20);
            $i= 0;
            $ii = 4;

            $total = 0;

            $sd = date('Ymd',strtotime($_GET['TransaksiSearch']['tanggal_awal'])).'000001';
            $ed = date('Ymd',strtotime($_GET['TransaksiSearch']['tanggal_akhir'])).'235959';
            
            // $list = Pasien::find()->addFilterWhere(['like',])
            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);
            $client_token = Yii::$app->params['client_token'];
            $headers = ['x-access-token'=>$client_token];
            $response = $client->get('/b/transaksi/list', ['startdate' => $sd,'enddate'=>$ed],$headers)->send();
            
            if ($response->isOk) {
                $result = $response->data['values'];
                $total_sisa = 0;
                $total_terbayar = 0;
                foreach ($result as $q => $d) {
                    $total_sisa += $d['sisa'];
                    $total_terbayar += $d['terbayar'];
                    
                    $sheet->setCellValue('A'.$ii, $q+1);
                    $sheet->setCellValue('B'.$ii, $d['d']);
                    $sheet->setCellValue('C'.$ii, $d['nim']);
                    $sheet->setCellValue('D'.$ii, $d['n']);
                    $sheet->setCellValue('E'.$ii, $d['p']);
                    $sheet->setCellValue('F'.$ii, $d['nl']);
                    // $sheet->setCellValue('G'.$ii, $d['nl']);
                    $ii++;
                }

                
                // $sheet->setCellValue('A'.$ii, '');
                // $sheet->setCellValue('B'.$ii, '');
                // $sheet->setCellValue('C'.$ii, '');
                // $sheet->setCellValue('D'.$ii, '');
                // $sheet->setCellValue('E'.$ii, '');
                // $sheet->setCellValue('F'.$ii, 'Total');
                // $sheet->setCellValue('G'.$ii, $total_terbayar);
                // $sheet->setCellValue('H'.$ii, $total_sisa);
                // $sheet->setCellValue('I'.$ii, '');
                $sheet->setTitle('Laporan Transaksi');
                
                // ob_end_clean();
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="laporan_transaksi.xlsx"');
                header('Cache-Control: max-age=0');
                
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
                exit;
            }
            
            

        }

        return $this->render('transaksi',[
            'model' => $model
        ]);
    }


    public function actionRekapPembayaran(){

        $model = new TagihanSearch();
        $listBulan = Bulan::find()->orderBy(['id'=>SORT_ASC])->all();
        $listKampus = SimakKampus::find()->orderBy(['kode_kampus'=>SORT_ASC])->all();

        $results = [];
        if(!empty($_GET['btn-search']) && !empty($_GET['kampus']) && !empty($_GET['prodi']) && !empty($_GET['tahun'] && !empty($_GET['status_aktivitas'])))
        {
            $client_token = Yii::$app->params['client_token'];
            $headers = ['x-access-token'=>$client_token];

            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);

            $response = $client->get('/b/rekap/pembayaran', [
                'kampus' => $_GET['kampus'],
                'prodi'=>$_GET['prodi'],
                'tahun'=>$_GET['tahun'],
                'status_aktivitas' => $_GET['status_aktivitas']
            ],$headers)->send();
            
            if ($response->isOk) {
                $results = $response->data['values'];
            }
        }

        else if(!empty($_GET['export']))
        {
            
            $client_token = Yii::$app->params['client_token'];
            $headers = ['x-access-token'=>$client_token];

            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);

            $response = $client->get('/b/rekap/pembayaran', [
                'kampus' => $_GET['kampus'],
                'prodi'=>$_GET['prodi'],
                'tahun'=>$_GET['tahun'],
                'status_aktivitas' => $_GET['status_aktivitas']
            ],$headers)->send();
            
            if ($response->isOk) {
                $results = $response->data['values'];
            }

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            
            // Add column headers
            $sheet->setCellValue('A3', 'No')
                ->setCellValue('B3', 'NIM')
                ->setCellValue('C3', 'Nama Mahasiswa')
                ->setCellValue('D3', 'Prodi')
                ->setCellValue('E3', 'Semester')
                ->setCellValue('F3', 'DU Genap/Ganjil')
                ->setCellValue('G3', 'SPP Bulanan');

            $sheet->mergeCells('G3:R3')->getStyle('G3:R3')->getAlignment()->setHorizontal('center');
            $sheet->mergeCells('A3:A4');
            $sheet->mergeCells('B3:B4');
            $sheet->mergeCells('C3:C4');
            $sheet->mergeCells('D3:D4');
            $sheet->mergeCells('E3:E4');
            $sheet->mergeCells('F3:F4');
            
            $col = 7;
            foreach($listBulan as $q => $b)
            {
                
                $sheet->setCellValueByColumnAndRow($col,4,$b->nama);
                $col++;
            }
                

            $sheet->mergeCells('A1:R1')->getStyle('A1:R1')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A1','LAPORAN REKAP PEMBAYARAN');


            //Put each record in a new cell

            $sheet->getColumnDimension('A')->setWidth(5);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(35);
            $sheet->getColumnDimension('D')->setWidth(7);
            $sheet->getColumnDimension('E')->setWidth(10);
            $sheet->getColumnDimension('F')->setWidth(15);
            $sheet->getColumnDimension('G')->setWidth(15);
            $sheet->getColumnDimension('H')->setWidth(15);
            $sheet->getColumnDimension('I')->setWidth(15);
            $sheet->getColumnDimension('J')->setWidth(15);
            $sheet->getColumnDimension('K')->setWidth(15);
            $sheet->getColumnDimension('L')->setWidth(15);
            $sheet->getColumnDimension('M')->setWidth(15);
            $sheet->getColumnDimension('N')->setWidth(15);
            $sheet->getColumnDimension('O')->setWidth(15);
            $sheet->getColumnDimension('P')->setWidth(15);
            $sheet->getColumnDimension('Q')->setWidth(15);
            $sheet->getColumnDimension('R')->setWidth(15);
            $sheet->getStyle('F')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('G')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('H')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('I')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('J')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('K')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('L')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('M')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('N')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('O')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('P')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('Q')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('R')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $i= 0;
            $rows = 5;

            $total = 0;

            $response = $client->get('/b/rekap/pembayaran', [
                'kampus' => $_GET['kampus'],
                'prodi'=>$_GET['prodi'],
                'tahun'=>$_GET['tahun'],
                'status_aktivitas' => $_GET['status_aktivitas']
            ],$headers)->send();
            
            if ($response->isOk) {
                $results = $response->data['values'];

                foreach($results as $q => $v)
                {
                    $sheet->setCellValue('A'.$rows, $q+1);
                    $sheet->setCellValue('B'.$rows, $v['nim_mhs']);
                    $sheet->setCellValue('C'.$rows, $v['nama_mahasiswa']);
                    $sheet->setCellValue('D'.$rows, $v['singkatan']);
                    $sheet->setCellValue('E'.$rows, $v['semester']);
                    $sheet->setCellValue('F'.$rows, $v['du']);
                    $sheet->setCellValue('G'.$rows, $v['syawal']);
                    $sheet->setCellValue('H'.$rows, $v['dzulqodah']);
                    $sheet->setCellValue('I'.$rows, $v['dzulhijjah']);
                    $sheet->setCellValue('J'.$rows, $v['muharram']);
                    $sheet->setCellValue('K'.$rows, $v['shafar']);
                    $sheet->setCellValue('L'.$rows, $v['rabiulawal']);
                    $sheet->setCellValue('M'.$rows, $v['rabiulakhir']);
                    $sheet->setCellValue('N'.$rows, $v['jumadilula']);
                    $sheet->setCellValue('O'.$rows, $v['jumadiltsani']);
                    $sheet->setCellValue('P'.$rows, $v['rajab']);
                    $sheet->setCellValue('Q'.$rows, $v['syaban']);
                    $sheet->setCellValue('R'.$rows, $v['ramadan']);



                    $rows++;
                }

                
                $sheet->setTitle('Laporan Rekap Pembayaran');
                
                // ob_end_clean();
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="laporan_rekap_pembayaran.xlsx"');
                header('Cache-Control: max-age=0');
                
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
                exit;
            }


        }


        return $this->render('tunggakan_rekap',[
            'model' => $model,
            'results' => $results,
            'listBulan' => $listBulan,
            'listKampus' => $listKampus
        ]);
    }

    public function actionTunggakan(){

        $model = new TagihanSearch();

        if(!empty($_GET['export']))
        {
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            
            // Add column headers
            $sheet->setCellValue('A3', 'No')
                ->setCellValue('B3', 'Komponen')
                ->setCellValue('C3', 'Nama')
                ->setCellValue('D3', 'Smt')
                ->setCellValue('E3', 'Prodi')
                ->setCellValue('F3', 'Nilai')
                ->setCellValue('G3', 'Terbayar')
                ->setCellValue('H3', 'Sisa')
                ->setCellValue('I3', 'Tanggal');
;

            $sheet->mergeCells('A1:L1')->getStyle('A1:I1')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A1','LAPORAN TUNGGAKAN');

            $sheet->mergeCells('A2:L2')->getStyle('A2:I2')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A2','Tanggal '.$_GET['TagihanSearch']['tanggal_awal'].' s/d '.$_GET['TagihanSearch']['tanggal_akhir']);

            //Put each record in a new cell

            $sheet->getColumnDimension('A')->setWidth(5);
            $sheet->getColumnDimension('B')->setWidth(25);
            $sheet->getColumnDimension('C')->setWidth(35);
            $sheet->getColumnDimension('D')->setWidth(8);
            $sheet->getColumnDimension('E')->setWidth(10);
            $sheet->getColumnDimension('F')->setWidth(20);
            $sheet->getColumnDimension('G')->setWidth(20);
            $sheet->getColumnDimension('H')->setWidth(20);
            $sheet->getColumnDimension('I')->setWidth(25);
            $i= 0;
            $ii = 4;

            $total = 0;

            $sd = date('Ymd',strtotime($_GET['TagihanSearch']['tanggal_awal'])).'000001';
            $ed = date('Ymd',strtotime($_GET['TagihanSearch']['tanggal_akhir'])).'235959';
             $kampus = $_GET['kampus'];
            $prodi = $_GET['prodi'];
            $komponen = $_GET['komponen'];
            
            // $list = Pasien::find()->addFilterWhere(['like',])
            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);
            $client_token = Yii::$app->params['client_token'];
            $headers = ['x-access-token'=>$client_token];
            $response = $client->get('/b/tagihan/periode/tunggakan', [
                
                'kampus' => $kampus,
                'prodi' => $prodi,
                'komponen' => $komponen
            ],$headers)->send();
            
            if ($response->isOk) {
                $result = $response->data['values'];
                    
                $total_sisa = 0;
                $total_terbayar = 0;
                foreach ($result as $q => $d) {
                    $total_sisa += $d['sisa'];
                    $total_terbayar += $d['terbayar'];
                    $sheet->setCellValue('A'.$ii, $q+1);
                    $sheet->setCellValue('B'.$ii, $d['komponen']);
                    $sheet->setCellValue('C'.$ii, $d['nama_mahasiswa']);
                    $sheet->setCellValue('D'.$ii, $d['semester']);
                    $sheet->setCellValue('E'.$ii, $d['prodi']);
                    $sheet->setCellValue('F'.$ii, $d['nilai']);
                    $sheet->setCellValue('G'.$ii, $d['terbayar']);
                    $sheet->setCellValue('H'.$ii, $d['sisa']);
                    $sheet->setCellValue('I'.$ii, $d['created_at']);
                    $sheet->setCellValue('J'.$ii, $d['updated_at']);
                    $ii++;
                }

                
                $sheet->setCellValue('A'.$ii, '');
                $sheet->setCellValue('B'.$ii, '');
                $sheet->setCellValue('C'.$ii, '');
                $sheet->setCellValue('D'.$ii, '');
                $sheet->setCellValue('E'.$ii, '');
                $sheet->setCellValue('F'.$ii, 'Total');
                $sheet->setCellValue('G'.$ii, $total_terbayar);
                $sheet->setCellValue('H'.$ii, $total_sisa);
                $sheet->setCellValue('I'.$ii, '');
                $sheet->setCellValue('J'.$ii, '');
                $sheet->setTitle('Laporan Tunggakan');
                
                // ob_end_clean();
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="laporan_tunggakan.xlsx"');
                header('Cache-Control: max-age=0');
                
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
                die();
            }
            
            

        }

        return $this->render('tunggakan',[
            'model' => $model
        ]);
    }

    public function actionPembayaran(){

        $model = new TagihanSearch();

        if(!empty($_GET['export']))
        {
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            
            // Add column headers
            $sheet->setCellValue('A3', 'No')
                ->setCellValue('B3', 'Komponen')
                ->setCellValue('C3', 'Nama')
                ->setCellValue('D3', 'Smt')
                ->setCellValue('E3', 'Prodi')
                ->setCellValue('F3', 'Nilai')
                ->setCellValue('G3', 'Terbayar')
                ->setCellValue('H3', 'Sisa')
                ->setCellValue('I3', 'Tanggal');


            $sheet->mergeCells('A1:L1')->getStyle('A1:I1')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A1','LAPORAN PEMBAYARAN');

            $sheet->mergeCells('A2:L2')->getStyle('A2:I2')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A2','Tanggal '.$_GET['TagihanSearch']['tanggal_awal'].' s/d '.$_GET['TagihanSearch']['tanggal_akhir']);

            //Put each record in a new cell

            $sheet->getColumnDimension('A')->setWidth(5);
            $sheet->getColumnDimension('B')->setWidth(25);
            $sheet->getColumnDimension('C')->setWidth(35);
            $sheet->getColumnDimension('D')->setWidth(8);
            $sheet->getColumnDimension('E')->setWidth(25);
            $sheet->getColumnDimension('F')->setWidth(20);
            $sheet->getColumnDimension('G')->setWidth(20);
            $sheet->getColumnDimension('H')->setWidth(20);
            $sheet->getColumnDimension('I')->setWidth(25);
            $i= 0;
            $ii = 4;

            $total = 0;

            $sd = date('Ymd',strtotime($_GET['TagihanSearch']['tanggal_awal'])).'000001';
            $ed = date('Ymd',strtotime($_GET['TagihanSearch']['tanggal_akhir'])).'235959';
             $kampus = $_GET['kampus'];
            $prodi = $_GET['prodi'];
            $komponen = $_GET['komponen'];
            $tahun = $_GET['tahun'];
            
            // $list = Pasien::find()->addFilterWhere(['like',])
            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);
            $client_token = Yii::$app->params['client_token'];
             $headers = ['x-access-token'=>$client_token];
            $response = $client->get('/b/tagihan/periode', [
                'startdate' => $sd,
                'enddate'=>$ed,
                'kampus'=>$kampus,
                'prodi' => $prodi,
                'komponen' => $komponen,
                'tahun' => $tahun
            ],$headers)->send();
            
            if ($response->isOk) {
                $result = $response->data['values'];
                $total_sisa = 0;
                $total_terbayar = 0;
                foreach ($result as $q => $d) {
                    $total_sisa += $d['sisa'];
                    $total_terbayar += $d['terbayar'];
                    
                    $sheet->setCellValue('A'.$ii, $q+1);
                    $sheet->setCellValue('B'.$ii, $d['komponen']);
                    $sheet->setCellValue('C'.$ii, $d['nama_mahasiswa']);
                    $sheet->setCellValue('D'.$ii, $d['semester']);
                    $sheet->setCellValue('E'.$ii, $d['prodi']);
                    $sheet->setCellValue('F'.$ii, $d['nilai']);
                    $sheet->setCellValue('G'.$ii, $d['terbayar']);
                    $sheet->setCellValue('H'.$ii, $d['sisa']);
                    $sheet->setCellValue('I'.$ii, $d['created_at']);
                    $sheet->setCellValue('J'.$ii, $d['updated_at']);
                    $ii++;
                }

                
                $sheet->setCellValue('A'.$ii, '');
                $sheet->setCellValue('B'.$ii, '');
                $sheet->setCellValue('C'.$ii, '');
                $sheet->setCellValue('D'.$ii, '');
                $sheet->setCellValue('E'.$ii, '');
                $sheet->setCellValue('F'.$ii, 'Total');
                $sheet->setCellValue('G'.$ii, $total_terbayar);
                $sheet->setCellValue('H'.$ii, $total_sisa);
                $sheet->setCellValue('I'.$ii, '');
                $sheet->setCellValue('J'.$ii, '');
                $sheet->setTitle('Laporan Pembayaran');
                
                // ob_end_clean();
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="laporan_pembayaran.xlsx"');
                header('Cache-Control: max-age=0');
                
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
                exit;
            }
            
            

        }


        return $this->render('pembayaran',[
            'model' => $model
        ]);
    }

    
}
