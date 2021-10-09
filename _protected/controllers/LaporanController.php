<?php

namespace app\controllers;

use Yii;
use app\models\SimakMasterprogramstudi;
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
use app\models\Tagihan;
use app\models\Tahun;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

/**
 * PenjualanController implements the CRUD actions for Penjualan model.
 */
class LaporanController extends AppController
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

    public function actionRekapHitungPembayaran()
    {
        $results = [];
        $tahun = Tahun::find()->where(['buka' => 'Y'])->one();
        $list_jenjang = ['A'=>'Doktoral','B'=>'Magister','C'=>'Sarjana','D'=>'Diploma'];
        foreach($list_jenjang as $q => $v)
        {
            $list_prodi = SimakMasterprogramstudi::find()->where(['kode_jenjang_studi'=>$q])->orderBy(['kode_fakultas'=>SORT_ASC,'nama_prodi'=>SORT_ASC])->all();

            foreach($list_prodi as $p)
            {
                $query = Tagihan::find();
                $query->alias('t');
                $query->joinWith(['komponen as k','nim0 as mhs','komponen.kategori as kk']);
                $query->andWhere([
                    'kk.kode' => '01',
                    't.tahun' => $tahun->id,
                    'mhs.kode_prodi' => $p->kode_prodi,
                    
                ]);
                $query->andWhere('terbayar >= nilai');
                $lunas = $query->count();

                $query = Tagihan::find();
                $query->alias('t');
                $query->joinWith(['komponen as k','nim0 as mhs','komponen.kategori as kk']);
                $query->andWhere([
                    'kk.kode' => '01',
                    't.tahun' => $tahun->id,
                    'mhs.kode_prodi' => $p->kode_prodi,
                    
                ]);
                $query->andWhere('terbayar < nilai_minimal AND terbayar > 0');
                $kurang_50 = $query->count();

                $query = Tagihan::find();
                $query->alias('t');
                $query->joinWith(['komponen as k','nim0 as mhs','komponen.kategori as kk']);
                $query->andWhere([
                    'kk.kode' => '01',
                    't.tahun' => $tahun->id,
                    'mhs.kode_prodi' => $p->kode_prodi,
                    
                ]);
                $query->andWhere('terbayar >= nilai_minimal AND terbayar < nilai');
                $minimal = $query->count();

                $query = Tagihan::find();
                $query->alias('t');
                $query->joinWith(['komponen as k','nim0 as mhs','komponen.kategori as kk']);
                $query->andWhere([
                    'kk.kode' => '01',
                    't.tahun' => $tahun->id,
                    'mhs.kode_prodi' => $p->kode_prodi,
                    
                ]);
                $query->andWhere('nilai > 0 AND terbayar = 0');
                $belum_bayar = $query->count();

                $results[$q][] = [
                    'prodi' => $p->nama_prodi,
                    'kode_prodi' => $p->kode_prodi,
                    'total_lunas' => $lunas,
                    'total_kurang_50' => $kurang_50,
                    'total_minimal' => $minimal,
                    'total_belum_bayar' => $belum_bayar
                ];
            }
        } 
         
//         echo '<pre>';
//         print_r($results);
//         echo '</pre>';
// exit;
        return $this->render('rekap_hitung_pembayaran',[
            'results' => $results,
            'list_jenjang' => $list_jenjang
        ]);
    }

    public function actionAjaxRincianTagihanExport()
    {
        if(Yii::$app->request->isPost)
        {
            
            $dataItem = $_POST['dataItem'];
            $tahun = $dataItem['tahun'];
            $kampus = $dataItem['kampus'];
            $status_aktivitas = $dataItem['status_aktivitas'];
            $singkatan = $dataItem['singkatan'];
            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);
            $client_token = Yii::$app->params['client_token'];

            $headers = ['x-access-token'=>$client_token];
            
            $params = [
                'tahun' => $tahun,
                'kampus' => $kampus,
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

                    $sheet->getStyle('G'.$ii)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getStyle('H'.$ii)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getStyle('I'.$ii)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
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

                $sheet->getStyle('G'.$ii)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getStyle('H'.$ii)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getStyle('I'.$ii)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
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

        $list_komponen = [];

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

            $rows = (new \yii\db\Query())
            ->select(['k.id', 'k.nama'])
            ->from('bill_tagihan t')
            ->join('JOIN','bill_komponen_biaya kb', 't.komponen_id = kb.id')
            ->join('JOIN','bill_kategori k', 'kb.kategori_id = k.id')
            ->join('JOIN','simak_mastermahasiswa m', 'm.nim_mhs = t.nim')
            ->where([
                'm.kode_prodi' => $_GET['prodi'],
                'm.kampus' => $_GET['kampus'],
                't.tahun' => $_GET['tahun'],
                'm.status_aktivitas' => $_GET['status_aktivitas']
            ])
            ->groupBy(['k.id'])->orderBy(['k.id' => SORT_ASC]);

            $list_cat = $rows->all();

            foreach($list_cat as $cat)
            {
                $rows = (new \yii\db\Query())
                ->select(['kb.id', 'kb.nama','kb.biaya_awal'])
                ->from('bill_tagihan t')
                ->join('JOIN','bill_komponen_biaya kb', 't.komponen_id = kb.id')
                ->join('JOIN','bill_kategori k', 'kb.kategori_id = k.id')
                ->join('JOIN','simak_mastermahasiswa m', 'm.nim_mhs = t.nim')
                ->where([
                    'm.kode_prodi' => $_GET['prodi'],
                    'm.kampus' => $_GET['kampus'],
                    't.tahun' => $_GET['tahun'],
                    'm.status_aktivitas' => $_GET['status_aktivitas'],
                    'k.id' => $cat['id']
                ])
                ->groupBy(['kb.id'])->orderBy(['kb.id' => SORT_ASC]);
                $hasil = $rows->all();
                $list_komponen[$cat['id']] = [
                    'nama' => $cat['nama'],
                    'items' => $hasil
                ];
                
            }
            
        }

        else if(!empty($_GET['export']))
        {

            ini_set('max_execution_time', '300');
            
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
            
            $rows = (new \yii\db\Query())
            ->select(['k.id', 'k.nama'])
            ->from('bill_tagihan t')
            ->join('JOIN','bill_komponen_biaya kb', 't.komponen_id = kb.id')
            ->join('JOIN','bill_kategori k', 'kb.kategori_id = k.id')
            ->join('JOIN','simak_mastermahasiswa m', 'm.nim_mhs = t.nim')
            ->where([
                'm.kode_prodi' => $_GET['prodi'],
                'm.kampus' => $_GET['kampus'],
                't.tahun' => $_GET['tahun'],
                'm.status_aktivitas' => $_GET['status_aktivitas']
            ])
            ->groupBy(['k.id'])->orderBy(['k.id' => SORT_ASC]);

            $list_cat = $rows->all();
            $sheet->mergeCells('A2:A3');
            $sheet->mergeCells('B2:B3');
            $sheet->mergeCells('C2:C3');
            $sheet->mergeCells('D2:D3');
            $sheet->mergeCells('E2:E3');
            $sheet->setCellValue('A2', 'No')
                ->setCellValue('B2', 'NIM')
                ->setCellValue('C2', 'Nama Mahasiswa')
                ->setCellValue('D2', 'Prodi')
                ->setCellValue('E2', 'Semester');
            
            $col = 6;
            foreach($list_cat as $cat)
            {
                $rows = (new \yii\db\Query())
                ->select(['kb.id', 'kb.nama','kb.biaya_awal'])
                ->from('bill_tagihan t')
                ->join('JOIN','bill_komponen_biaya kb', 't.komponen_id = kb.id')
                ->join('JOIN','bill_kategori k', 'kb.kategori_id = k.id')
                ->join('JOIN','simak_mastermahasiswa m', 'm.nim_mhs = t.nim')
                ->where([
                    'm.kode_prodi' => $_GET['prodi'],
                    'm.kampus' => $_GET['kampus'],
                    't.tahun' => $_GET['tahun'],
                    'm.status_aktivitas' => $_GET['status_aktivitas'],
                    'k.id' => $cat['id']
                ])
                ->groupBy(['kb.id'])->orderBy(['kb.id' => SORT_ASC]);
                $hasil = $rows->all();
                $list_komponen[$cat['id']] = [
                    'nama' => $cat['nama'],
                    'items' => $hasil
                ];

                $countItems = count($hasil)-1;
                $sheet->setCellValueByColumnAndRow($col,2,$cat['nama']);
                $sheet->mergeCellsByColumnAndRow($col,2,$col+$countItems,2);
                $col = $col+$countItems;
                $col++;

            }

            $col = 6;
            foreach($list_komponen as $cats)
            {
                foreach($cats['items'] as $cat)
                {
                    $sheet->setCellValueByColumnAndRow($col,3,$cat['nama']);
                    $cell = $sheet->getCellByColumnAndRow($col, 3);
                    $cell->getStyle()->getAlignment()->setTextRotation(90)->setWrapText(true);
                    $col++;
                }
            }    

            $row = 4;
            foreach($results as $q => $m)
            {
                $sheet->setCellValueByColumnAndRow(1,$row,$q+1);
                $sheet->setCellValueByColumnAndRow(2,$row,$m['nim_mhs']);
                $sheet->setCellValueByColumnAndRow(3,$row,$m['nama_mahasiswa']);
                $sheet->setCellValueByColumnAndRow(4,$row,$m['singkatan']);
                $sheet->setCellValueByColumnAndRow(5,$row,$m['semester']);

                $col = 6;
                foreach($list_komponen as $cats)
                {
                    foreach($cats['items'] as $cat)
                    {
                        $t = Tagihan::find()->where([
                            'komponen_id' => $cat['id'],
                            'nim' => $m['nim_mhs'],
                        ])->one();

                        $terbayar = '-';
                        $style = '';
                        if(!empty($t))
                        {
                            if($t->terbayar < $t->nilai){
                                $style="danger";
                            }



                            $terbayar = $t->terbayar;
                        }

                        $kolom = Coordinate::stringFromColumnIndex($col);
                        $sheet->getStyle($kolom)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $sheet->setCellValueByColumnAndRow($col,$row,$terbayar);
                        if($style != ''){
                            
                            $sheet->getStyle($kolom.$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d9534f');
                            $sheet->getStyle($kolom.$row)->getFont()->getColor()->setRGB('ffffff');
                        }

                        
                        $col++;
                    }
                }



                $row++;
            }
                
            



            // $sheet->mergeCells('G3:R3')->getStyle('G3:R3')->getAlignment()->setHorizontal('center');
            // $sheet->mergeCells('A3:A4');
            // $sheet->mergeCells('B3:B4');
            // $sheet->mergeCells('C3:C4');
            // $sheet->mergeCells('D3:D4');
            // $sheet->mergeCells('E3:E4');
            // $sheet->mergeCells('F3:F4');
            
            // $col = 7;
            // foreach($listBulan as $q => $b)
            // {
                
            //     $sheet->setCellValueByColumnAndRow($col,4,$b->nama);
            //     $col++;
            // }
                

            $sheet->mergeCells('A1:E1')->getStyle('A1:E1')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A1','LAPORAN REKAP PEMBAYARAN');


            // //Put each record in a new cell

            // // $sheet->getColumnDimension('A')->setWidth(5);
          
            // // $sheet->getStyle('F')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            foreach(range('A','Z') as $columnID) {
                $sheet->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }
                    
            $sheet->setTitle('Laporan Rekap Pembayaran');
            
            // ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="laporan_rekap_pembayaran.xlsx"');
            header('Cache-Control: max-age=0');
            
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
            // }


        }


        return $this->render('tunggakan_rekap',[
            'model' => $model,
            'results' => $results,
            'listBulan' => $listBulan,
            'listKampus' => $listKampus,
            'list_komponen' => $list_komponen
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
                ->setCellValue('D3', 'Status')
                ->setCellValue('E3', 'Smt')
                ->setCellValue('F3', 'Prodi')
                ->setCellValue('G3', 'Nilai')
                ->setCellValue('H3', 'Terbayar')
                ->setCellValue('I3', 'Sisa');


            $sheet->mergeCells('A1:I1')->getStyle('A1:I1')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A1','LAPORAN TUNGGAKAN');

            $sheet->mergeCells('A2:I2')->getStyle('A2:I2')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A2','TA: '.$_GET['tahun']);

            //Put each record in a new cell

            $sheet->getColumnDimension('A')->setWidth(5);
            $sheet->getColumnDimension('B')->setWidth(35);
            $sheet->getColumnDimension('C')->setWidth(35);
            $sheet->getColumnDimension('D')->setWidth(8);
            $sheet->getColumnDimension('E')->setWidth(8);
            $sheet->getColumnDimension('F')->setWidth(20);
            $sheet->getColumnDimension('G')->setWidth(20);
            $sheet->getColumnDimension('H')->setWidth(20);
            $sheet->getColumnDimension('I')->setWidth(20);
            $i= 0;
            $ii = 4;

            $total = 0;
            // print_r($_GET);exit;
            // $sd = date('Ymd',strtotime($_GET['TagihanSearch']['tanggal_awal'])).'000001';
            // $ed = date('Ymd',strtotime($_GET['TagihanSearch']['tanggal_akhir'])).'235959';
            $kampus = $_GET['kampus'];
            $prodi = $_GET['prodi'];
            $tahun = $_GET['tahun'];
            $komponen = $_GET['komponen'];
            
            
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

            $query->andWhere('p.terbayar < p.nilai');

            $result = $query->all();
            
            // if ($response->isOk) {
            //     $result = $response->data['values'];
                    
            $total_sisa = 0;
            $total_terbayar = 0;
            foreach ($result as $q => $d) {
                $sisa = $d->nilai - $d->terbayar;
                $terbayar = $d->terbayar;
                $total_sisa += $sisa;
                $total_terbayar += $terbayar;
                $sheet->setCellValue('A'.$ii, $q+1);
                $sheet->setCellValue('B'.$ii, $d->komponen->nama);
                $sheet->setCellValue('C'.$ii, $d->nim0->nama_mahasiswa);
                $sheet->setCellValue('D'.$ii, $d->nim0->status_aktivitas);
                $sheet->setCellValue('E'.$ii, $d->semester);
                $sheet->setCellValue('F'.$ii, $d->nim0->kodeProdi->nama_prodi);
                $sheet->setCellValue('G'.$ii, $d->nilai);
                $sheet->setCellValue('H'.$ii, $d->terbayar);
                $sheet->setCellValue('I'.$ii, $sisa);
                $ii++;
            }

            
            $sheet->setCellValue('A'.$ii, '');
            $sheet->setCellValue('B'.$ii, '');
            $sheet->setCellValue('C'.$ii, '');
            $sheet->setCellValue('D'.$ii, '');
            $sheet->setCellValue('E'.$ii, '');
            $sheet->setCellValue('F'.$ii, '');
            $sheet->setCellValue('G'.$ii, 'Total');
            $sheet->setCellValue('H'.$ii, $total_terbayar);
            $sheet->setCellValue('I'.$ii,$total_sisa);
            $sheet->setTitle('Laporan Tunggakan');
            
            // ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="laporan_tunggakan_'.rand(1,100).'.xlsx"');
            header('Cache-Control: max-age=0');
            
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            die();
            // }
            
            

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
