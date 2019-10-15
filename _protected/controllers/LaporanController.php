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
use yii\httpclient\Client;

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

            $response = $client->get('/b/transaksi/list', ['startdate' => $sd,'enddate'=>$ed])->send();
            
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


    public function actionRekapTunggakan(){

        $model = new TagihanSearch();

        if(!empty($_GET['export']))
        {
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            
            // Add column headers
            $sheet->setCellValue('A3', 'No')
                ->setCellValue('B3', 'Prodi')
                ->setCellValue('C3', 'Semester')
                ->setCellValue('D3', 'Nominal')
                ->setCellValue('E3', 'Jml Mhs');
;

            $sheet->mergeCells('A1:E1')->getStyle('A1:E1')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A1','LAPORAN REKAP TUNGGAKAN');

            $sheet->mergeCells('A2:E2')->getStyle('A2:E2')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A2','Tanggal '.$_GET['TagihanSearch']['tanggal_awal'].' s/d '.$_GET['TagihanSearch']['tanggal_akhir']);

            //Put each record in a new cell

            $sheet->getColumnDimension('A')->setWidth(5);
            $sheet->getColumnDimension('B')->setWidth(25);
            $sheet->getColumnDimension('C')->setWidth(8);
            $sheet->getColumnDimension('D')->setWidth(15);
            $sheet->getColumnDimension('E')->setWidth(8);
            $i= 0;
            $ii = 4;

            $total = 0;

            $sd = date('Ymd',strtotime($_GET['TagihanSearch']['tanggal_awal'])).'000001';
            $ed = date('Ymd',strtotime($_GET['TagihanSearch']['tanggal_akhir'])).'235959';
            
            // $list = Pasien::find()->addFilterWhere(['like',])
            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);

            $response = $client->get('/b/tunggakan/rekap', ['startdate' => $sd,'enddate'=>$ed])->send();
            
            if ($response->isOk) {
                $result = $response->data['values'];
                $total_sisa = 0;
                $total_terbayar = 0;
                foreach ($result as $q => $d) {
                    $total_sisa += $d['sisa'];
                    // $total_terbayar += $d['terbayar'];
                    
                    $sheet->setCellValue('A'.$ii, $q+1);
                    $sheet->setCellValue('B'.$ii, $d['prodi']);
                    $sheet->setCellValue('C'.$ii, $d['semester']);
                    $sheet->setCellValue('D'.$ii, $d['sisa']);
                    $sheet->setCellValue('E'.$ii, $d['total']);
                    $ii++;
                }

                
                $sheet->setCellValue('A'.$ii, '');
                $sheet->setCellValue('B'.$ii, '');
                $sheet->setCellValue('C'.$ii, 'Total');
                $sheet->setCellValue('D'.$ii, $total_sisa);
                // $sheet->setCellValue('E'.$ii, $total_sisa);
                $sheet->setCellValue('E'.$ii, '');
                $sheet->setTitle('Laporan Tunggakan');
                
                // ob_end_clean();
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="laporan_tunggakan.xlsx"');
                header('Cache-Control: max-age=0');
                
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
                exit;
            }
            
            

        }

        return $this->render('tunggakan_rekap',[
            'model' => $model
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
            
            // print_r($_GET);exit;
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
            $sheet->setCellValue('A1','LAPORAN PEMBAYARAN');

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
            
            // $list = Pasien::find()->addFilterWhere(['like',])
            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);

            $response = $client->get('/b/tagihan/periode', [
                'startdate' => $sd,
                'enddate'=>$ed,
                'kampus'=>$kampus,
                'prodi' => $prodi
            ])->send();
            
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
