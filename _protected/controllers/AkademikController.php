<?php

namespace app\controllers;

use Yii;

use yii\helpers\ArrayHelper;
use app\models\SimakKampus;
use app\models\SimakAbsenHarian;
use app\models\SimakMastermahasiswa;
use app\models\SimakTahunakademik;
use app\models\SimakMastermatakuliah;
use app\models\SimakMatakuliah;
use app\models\SimakRangeNilai;
use app\models\SimakMasterdosen;
use app\models\SimakMasterdosenSearch;
use app\models\SimakDatakrs;
use app\models\SimakDatakrsSearch;
use app\models\SimakJadwal;
use app\models\SimakJadwalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\httpclient\Client;
use tecnickcom\tcpdf\TCPDF;
use yii\helpers\Url;


/**
 * SimakDatakrsController implements the CRUD actions for SimakDatakrs model.
 */
class AkademikController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\ForbiddenHttpException('You are not allowed to access this page');
                },
                'only' => ['create','update','delete','index','nilai','krs','khs','export-khs','export-krs','perwalian','pembatalan','ajaxKrsList','akpam','pelanggaran','update-nilai','konversi-mk','krs-paket','ajax-presensi','absensi', 'rekap-krs'],
                'rules' => [
                    // [
                    //     'actions' => [],
                    //     'allow' => true,
                    //     'roles' => ['?'],
                    // ],
                    [
                        'actions' => ['index','riwayat-khs','jadwal','kehadiran','transkrip','akpam'],
                        'allow' => true,
                        'roles' => ['ortu'],
                    ],

                    [
                        'actions' => [
                            'create','update','delete','index','index','krs','khs','export-khs','export-krs','ajaxKrsList','transkrip','print-transkrip','nilai','export','perwalian','pembatalan','update-nilai','krs-paket'
                        ],
                        'allow' => true,
                        'roles' => ['theCreator'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionKehadiran()
    {

        $query = SimakMastermahasiswa::find();
        $query->joinWith(['kampus0 as k','simakMahasiswaOrtus as ortu']);

        if(!Yii::$app->user->isGuest){
            $query->andWhere(['ortu.ortu_user_id' => Yii::$app->user->identity->id]);
        }

        $list_anak = $query->all();



        $listTahun = SimakTahunakademik::find()->orderBy(['tahun_id'=>SORT_DESC])->all();
        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);
        $client_token = Yii::$app->params['client_token'];
        $headers = ['x-access-token'=>$client_token];
        $mhs = null;
        $results = [];
        $listKrs = [];
        $tahun_akademik = null;
        $tahun_id = null;
        if(!empty($_GET['nim'])){
            $nim = $_GET['nim'];
            $mhs = SimakMastermahasiswa::find()->where(['nim_mhs'=>$nim])->one();

            if(!empty($_GET['btn-cari'])) {
                $tahun_id = $_GET['tahun_id'];
            }

            else
                $tahun_id = \app\helpers\MyHelper::getTahunAktif();

            $tahun_akademik = SimakTahunakademik::find()->where(['tahun_id'=>$tahun_id])->one();
            $params = [
                'nim' => $mhs->nim_mhs,
                'tahun' => $tahun_id
            ];
            $response = $client->get('/m/krs', $params,$headers)->send();
            
            $results = [];
            $listKrs = [];
            if ($response->isOk) {
                $results = $response->data['values'];
                foreach($results['krs'] as $q => $m)
                {
                    $krs = SimakDatakrs::findOne($m['idkrs']);
                    $matkul = SimakMatakuliah::find()->where([
                        'kode_mk' => trim($krs->kode_mk),
                        'prodi' => $krs->kodeJadwal->prodi
                    ])->one();

                    $dosen = SimakMasterdosen::find()->where(['nidn' => $krs->kodeJadwal->kode_dosen])->one();

                    $listKrs[$m['idkrs']] = [
                        'krs' => $krs,
                        'matkul' => $matkul,
                        'dosen' => $dosen,
                        'jadwal' => $krs->kodeJadwal
                    ];    
                }
                
            }
        }

        return $this->render('kehadiran', [
            'results' => $results,
            'listKrs' => $listKrs,
            'tahun_id' => $tahun_id,
            'listTahun' => $listTahun,
            'tahun_akademik' => $tahun_akademik,
            'mhs' => $mhs,
            'list_anak' => $list_anak
        ]);
    }

    public function actionJadwal()
    {

        $mhs = null;
        $results = [];
        $listKrs = [];
        $tahun_akademik = null;
        $tahun_id = null;
        
        $query = SimakMastermahasiswa::find();
        $query->joinWith(['kampus0 as k','simakMahasiswaOrtus as ortu']);

        if(!Yii::$app->user->isGuest){
            $query->andWhere(['ortu.ortu_user_id' => Yii::$app->user->identity->id]);
        }

        $list_anak = $query->all();

        $session = Yii::$app->session;

        if(count($list_anak) == 1){
            $mhs = $list_anak[0];
            $session->set('nim',$mhs->nim_mhs);

        }
        $nim = '-';
        $listTahun = SimakTahunakademik::find()->orderBy(['tahun_id'=>SORT_DESC])->all();
        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);
        $client_token = Yii::$app->params['client_token'];
        $headers = ['x-access-token'=>$client_token];

        if(!empty($_GET['nim']) || $session->has('nim')){
            
            if($session->has('nim')){
                if(!empty($_GET['nim']) && ($_GET['nim'] == $session->get('nim')))
                    $nim = $session->get('nim');
                else if(!empty($_GET['nim'])){
                    $nim = $_GET['nim'];
                    $session->set('nim',$nim);
                }
                else
                    $nim = $session->get('nim');

            }
            else{
                $nim = $_GET['nim'];
                $session->set('nim',$nim);
            }

            $mhs = SimakMastermahasiswa::find()->where(['nim_mhs'=>$nim])->one();

            if(!empty($_GET['btn-cari'])) {
                $tahun_id = $_GET['tahun_id'];
            }

            else
                $tahun_id = \app\helpers\MyHelper::getTahunAktif();



            $tahun_akademik = SimakTahunakademik::find()->where(['tahun_id'=>$tahun_id])->one();
            $params = [
                'nim' => $mhs->nim_mhs,
                'tahun' => $tahun_id
            ];
            $response = $client->get('/m/krs', $params,$headers)->send();
            
            $results = [];
            $listKrs = [];
            if ($response->isOk) {
                $results = $response->data['values'];
                foreach($results['krs'] as $q => $m)
                {
                    $krs = SimakDatakrs::findOne($m['idkrs']);
                    $matkul = SimakMatakuliah::find()->where([
                        'kode_mk' => trim($krs->kode_mk),
                        'prodi' => $krs->kodeJadwal->prodi
                    ])->one();

                    $dosen = SimakMasterdosen::find()->where(['nidn' => $krs->kodeJadwal->kode_dosen])->one();

                    $listKrs[$m['idkrs']] = [
                        'krs' => $krs,
                        'matkul' => $matkul,
                        'dosen' => $dosen,
                        'jadwal' => $krs->kodeJadwal
                    ];    
                }
                
            }
        }

        return $this->render('jadwal', [
            'results' => $results,
            'listKrs' => $listKrs,
            'tahun_id' => $tahun_id,
            'listTahun' => $listTahun,
            'tahun_akademik' => $tahun_akademik,
            'mhs' => $mhs,
            'list_anak' => $list_anak
        ]);
    }

    public function actionRiwayatKhs()
    {
        // $this->layout = 'popup';
        $results = [];
        $listTahun = [];
        $mhs = [];

        $query = SimakMastermahasiswa::find();
        $query->joinWith(['kampus0 as k','simakMahasiswaOrtus as ortu']);

        if(!Yii::$app->user->isGuest){
            $query->andWhere(['ortu.ortu_user_id' => Yii::$app->user->identity->id]);
        }

        $list_anak = $query->all();

        $session = Yii::$app->session;

        if(count($list_anak) == 1){
            $mhs = $list_anak[0];
            $session->set('nim',$mhs->nim_mhs);

        }

        if(!empty($_GET['nim']) || $session->has('nim')){
            if(!empty($_GET['nim']) && ($_GET['nim'] == $session->get('nim'))){
                $nim = $session->get('nim');

            }
            else if(!empty($_GET['nim'])){
                $nim = $_GET['nim'];
                $session->set('nim',$nim);
            }
            else{
                $nim = $session->get('nim');
            }
            $mhs = SimakMastermahasiswa::find()->where(['nim_mhs'=>!empty($nim) ? $nim : 0])->one(); 
            $listTahun = SimakTahunakademik::find()->orderBy(['tahun_id'=>SORT_DESC])->all();
        
            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);
            $client_token = Yii::$app->params['client_token'];
            $headers = ['x-access-token'=>$client_token];

            $tahun = SimakTahunakademik::getTahunAktif();
            $selisih = $tahun->tahun - $mhs->tahun_masuk + 1;
            $semester = $tahun->tahun_id % 2 == 0 ? 2 : 1;
            $results = [];
            $tahun_id = $tahun->tahun_id;
            for($i=$mhs->tahun_masuk; $i <=$tahun->tahun;$i++)
            {
                $ta1 = $i.'1';
                $ta2 = $i.'2';
                $params = [
                    'nim' => $mhs->nim_mhs,
                    'tahun' => $ta1
                ];
                $response = $client->get('/m/krs', $params,$headers)->send();
               
                if ($response->isOk) {
                    $results[$ta1] = $response->data['values'];
                }

                $params = [
                    'nim' => $mhs->nim_mhs,
                    'tahun' => $ta2
                ];
                $response = $client->get('/m/krs', $params,$headers)->send();
               
                if ($response->isOk) {
                    $results[$ta2] = $response->data['values'];
                }
            }
        }

        
        
        return $this->render('riwayat_khs', [
            'results' => $results,
            'list_anak' => $list_anak,
            'listTahun' => $listTahun,
            'mhs' => $mhs
        ]);
    }

    


    public function actionPrintTranskrip($nim='')
    {   

        
        if(Yii::$app->user->identity->access_role != ('Mahasiswa'))
        {
            $mhs = SimakMastermahasiswa::find()->where(['nim_mhs'=>$nim ?: 0])->one(); 
            
        }
        else if(Yii::$app->user->identity->access_role == ('Mahasiswa'))
        {
            $mhs = SimakMastermahasiswa::find()->where(['nim_mhs'=>Yii::$app->user->identity->nim])->one();
        }
        
        if(!empty($mhs))
        {

            $query = SimakDatakrs::find()
                ->where(['mahasiswa'=>$mhs->nim_mhs])
                ->orderBy(['id'=>SORT_ASC,'semester'=>SORT_ASC]);

            $datakrs = $query->all();
                
            $hasil = [];

            foreach($datakrs as $d)
            {

                 if($d->kode_mk == '-') 
                    continue;

                if(empty($d->nilai_huruf)) 
                    continue;

                if(in_array($d->nilai_huruf,['D','E','F'])) 
                    continue;

                
                $jadwal = SimakJadwal::findOne([
                    'kode_mk' => $d->kode_mk,
                    'prodi' => $mhs->kode_prodi,
                    'tahun_akademik' => $d->tahun_akademik
                ]);

                $matkul = SimakMatakuliah::find()->where([
                    'kode_mk' => trim($d->kode_mk),
                    'prodi' => $mhs->kode_prodi
                ])->one();

               
                if(!empty($matkul) && $matkul->sks_mk != 0){
                    $hasil[$d->kode_mk] = [
                        'kode_mk' => !empty($matkul) ? $matkul->kode_mk : '-',
                        'nama_mk' => !empty($matkul) ? $matkul->nama_mk : '-',
                        'nama_mk_en' => !empty($matkul) ? $matkul->nama_mk_en : '-',
                        'semester' => !empty($jadwal) ? $jadwal->semester : '-',
                        'nilai_huruf' => !empty($matkul) ? $d->nilai_huruf : '-',
                        'sks' => !empty($matkul) ? $matkul->sks_mk : 0,
                        'bobot_sks' => !empty($matkul) ? $d->bobot_sks : 0,
                    ];
                }
            }

            $results = [];
            $konversi_nilai = \app\helpers\MyHelper::getListKonversi();
            
            $bobot = 0;
            foreach($hasil as $tmp)
            {
                $results[$tmp['semester']][] = $tmp;
            }

            
            $predikat_label = '';
            $predikat_label_en = '';
            $predikat = \app\helpers\MyHelper::getPredikat($mhs->getIpk(), $mhs->kodeProdi->kode_jenjang_studi);
            if(!empty($predikat))
            {
              $predikat_label = $predikat->predikat;
              $predikat_label_en = $predikat->predikat_en;
            }

            $list_konversi_lama = [
                'A+' => 4,
                'A' => 3.75,
                'A-' => 3.5,
                'B+' => 3.25,
                'B' => 3,
                'B-' => 2.75,
                'C+' => 2.5,
                'C' => 2.25,
            ];

            try
            {

                $list_semester = \app\helpers\MyHelper::getSemester();

                
                $this->layout = '';
                
                ob_start();
                $pdf = new \TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
                $pdf->SetAutoPageBreak(TRUE, 0);
                $fontpath = Yii::getAlias('@webroot').'/themes/klorofil/assets/fonts/pala.ttf';
                $fontpathbold = Yii::getAlias('@webroot').'/themes/klorofil/assets/fonts/palab.ttf';
                $fontpathitalic = Yii::getAlias('@webroot').'/themes/klorofil/assets/fonts/palai.ttf';
                $fontreg = \TCPDF_FONTS::addTTFfont($fontpath, 'TrueTypeUnicode', '', 86);
                $fontbold = \TCPDF_FONTS::addTTFfont($fontpathbold, 'TrueTypeUnicode', '', 86);
                $fontitalic = \TCPDF_FONTS::addTTFfont($fontpathitalic, 'TrueTypeUnicode', '', 86);

                $pdf->SetFont($fontreg, '', 8, '', false);
                $pdf->SetPrintHeader(false);
                $pdf->SetPrintFooter(false);
                $pdf->AddPage();
                // $imgdata = Yii::getAlias('@webroot').'/themes/klorofil/assets/img/logo-ori.png';
                // $imgdata = Yii::getAlias('@webroot').'/themes/klorofil/assets/img/logo_full.png';
                // $size = 50;
                // $pdf->Image($imgdata,10,5,15);

                // $header = '<span style="text-align:center;">UNIVERSITAS DARUSSALAM GONTOR</span>';

                $pdf->write2DBarcode(Url::toRoute('transkrip/verify',true).'?token='.$mhs->token_link, 'QRCODE,H', 180, 5, 30, 30);
                $pdf->setXY(10,40);
                
                $font_reg_size = 8;
                
                // $pdf->SetFont($fontbold, '', 8);
                // $pdf->writeHTML($header, true, 0, true, true);
                $pdf->SetFont($fontreg, '', $font_reg_size);
                $txt = 'Surat Keputusan Akreditasi Perguruan Tinggi: 1035/SK/BAN-PT/Akred/PT/XII/2020';
                $pdf->Ln(6);
                // $pdf->Ln(4);
                $pdf->Cell('', '', $txt, 0, 1, 'C', 0);
                
                $txt = 'TRANSKRIP AKADEMIK PROGRAM '.strtoupper($mhs->kodeProdi->jenjang->label_id).' ('.strtoupper($mhs->kodeProdi->jenjang->label).')';
                $pdf->Cell('', '', $txt, 0, 1, 'C', 0);
                $pdf->SetFont($fontreg, 'I', 8);
                $txt = 'Academic Transcript for '.$mhs->kodeProdi->jenjang->label_en.' Degree';
                $pdf->Cell('', '', $txt, 0, 1, 'C', 0);
                $pdf->SetFont($fontreg, '', $font_reg_size);
                $txt = ($mhs->no_transkrip ?: 'Nomor transkrip belum diisi');
                $pdf->Cell('', '', $txt, 0, 1, 'C', 0);
         
                ob_start();
                echo $this->renderPartial('transkrip_identitas', [
                    'mhs' => $mhs,
                ]);

                $data = ob_get_clean();
                $pdf->SetFont($fontreg, '', 8);
                $pdf->writeHTML($data);
                
                    

                $style = ['width' => 0.4, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => [0, 0, 0]];
                $smt_start = 0;
                $smt_end = 1; //hanya 4 semester awal
                ob_start();
                echo $this->renderPartial('print_transkrip', [
                    'results' => $results,
                    'mhs' => $mhs,
                    'smt_start' => $smt_start,
                    'smt_end' => $smt_end
                ]);
                
                $data = ob_get_clean();
                $pdf->SetFont($fontreg, '', 8);
                $pdf->writeHTML($data);

                if(in_array($mhs->kodeProdi->kode_jenjang_studi, ['C','D']))
                {

                    $pdf->AddPage();
                    $pdf->Ln(30);
                    $smt_start = 2;
                    $smt_end = 3; //hanya 4 semester akhir
                    ob_start();
                    echo $this->renderPartial('print_transkrip', [
                        'results' => $results,
                        'mhs' => $mhs,
                        'smt_start' => $smt_start,
                        'smt_end' => $smt_end
                    ]);

                    $data = ob_get_clean();
                    $pdf->SetFont($fontreg, '', 8);
                    $pdf->writeHTML($data);
                }


                // print_r($pdf->getY());exit;
     
                $pdf->Ln();
                // $html = '<table cellpadding="1" border="1" cellspacing="0" width="100%">';
                $grades = '';
                $grades .= '<table width="100%" cellpadding="1" border="1">';
                $grades .= '<tr>';
                $grades .= '<td colspan="2">Catatan Nilai / Grade Explanation</td>';
                $grades .= '</tr>';
                $grades .= '<tr>';
                $grades .= '<td width="50%" style="text-align:center">2014 - 2020</td>';
                $grades .= '<td width="50%" style="text-align:center;">2021 - 2025</td>';
                $grades .= '</tr>';
                $grades .= '<tr>';
                $grades .= '<td width="50%">';
                $grades .= '<table width="100%" cellpadding="0" >';
                foreach ($list_konversi_lama as $key => $value) 
                {

                    $grades .= '<tr>';
                    $grades .= '<td width="30%"  style="text-align:left">&nbsp;&nbsp;'.$key.'</td>';
                    $grades .= '<td width="70%" style="text-align:left">: '.$value.'</td>';
                    $grades .= '</tr>';
                }

                $grades .= '</table>';
                $grades .= '</td>';
                $grades .= '<td width="50%">';
                $grades .= '<table width="100%" cellpadding="0">';
                
                foreach ($konversi_nilai as $key => $value) 
                {
                    if($value <= 2) continue;

                    $grades .= '<tr>';
                    $grades .= '<td width="30%"  style="text-align:left;">&nbsp;&nbsp;'.$key.'</td>';
                    $grades .= '<td width="70%" style="text-align:left">: '.$value.'</td>';
                    $grades .= '</tr>';
                }

                $grades .= '<tr>';
                $grades .= '<td width="30%"  style="text-align:left;">&nbsp;</td>';
                $grades .= '<td width="70%" style="text-align:left">&nbsp;</td>';
                $grades .= '</tr>';


                $grades .= '<tr>';
                $grades .= '<td width="30%"  style="text-align:left;">&nbsp;</td>';
                $grades .= '<td width="70%" style="text-align:left">&nbsp;</td>';
                $grades .= '</tr>';

                $grades .= '</table>';
                $grades .= '</td>';
                $grades .= '</tr>';
                $grades .= '</table>';
                
                
                $y = $pdf->getY();
                $pdf->SetFont($fontreg, '', $font_reg_size);
                $pdf->writeHTMLCell(50, 38, '', $y, $grades, 0, 0, 0, true, 'J', true);
                // $pdf->writeHTMLCell(50, 10,  '', $y, '2014', 1, 0, 0, false, 'J', true);
                
                $pdf->SetFont($fontreg, 'B', 8);

                $skripsi = strip_tags($mhs->judul_skripsi).'<br>';
                if(!empty($mhs->judul_skripsi_ar))
                {
                    $font_arab_path = Yii::getAlias('@webroot').'/themes/klorofil/assets/fonts/trado.ttf';
                    $font_arab = \TCPDF_FONTS::addTTFfont($font_arab_path, 'TrueTypeUnicode', '', 86);
                
                    $skripsi .= '<span style="font-size:12px;direction: rtl;">'.strip_tags($mhs->judul_skripsi_ar).'</span>';
                    $pdf->SetFont($font_arab, '', 8);
                    if(in_array($mhs->kodeProdi->kode_jenjang_studi, ['C','D']))
                    {
                        $pdf->writeHTMLCell($pdf->GetPageWidth() - 70, 24, '', '', '<strong>Skripsi / Thesis</strong><br>'.$skripsi, 1, 0, 0, true, 'C', true);
                    }

                    else{
                        $pdf->writeHTMLCell($pdf->GetPageWidth() - 70, 24, '', '', '<strong>Tesis / Thesis</strong><br>'.$skripsi, 1, 0, 0, true, 'C', true);
                    }
                    
                    $pdf->setRTL(false);
                }

                else
                {
                    $skripsi .= '<span style="font-style:italic;">'.strip_tags($mhs->judul_skripsi_en).'</span>';
                    $pdf->SetFont($fontreg, '', $font_reg_size);
                    if(in_array($mhs->kodeProdi->kode_jenjang_studi, ['C','D']))
                    {
                        $pdf->writeHTMLCell($pdf->GetPageWidth() - 70, 24, '', '', '<strong>Skripsi / Thesis</strong><br>'.$skripsi, 1, 0, 0, true, 'C', true);
                    }

                    else
                    {
                        $pdf->writeHTMLCell($pdf->GetPageWidth() - 70, 24, '', '', '<strong>Tesis / Thesis</strong><br>'.$skripsi, 1, 0, 0, true, 'C', true);
                    }
                }
                
                $pdf->Ln();

                $page_div_3 = ($pdf->GetPageWidth() - 70) / 3;
                $pdf->SetFont($fontreg, '', $font_reg_size);
                $pdf->writeHTMLCell($page_div_3, 13.5, 60, '', '<strong>Total SKS</strong> / Total of Credits<br>'.$mhs->SKSLulus, 1, 0, 0, true, 'C', true);
                $pdf->writeHTMLCell($page_div_3, 13.5, 60+$page_div_3, '', '<strong>IPK</strong> / GPA<br>'.\app\helpers\MyHelper::formatRupiah($mhs->getIpk(),2), 1, 0, 0, true, 'C', true);
                $pdf->writeHTMLCell($page_div_3, 13.5, 60+$page_div_3+$page_div_3, '', '<strong>Predikat</strong> / Predicate<br><strong>'.strtoupper($predikat_label).'</strong><br>'.strtoupper($predikat_label_en), 1, 0, 0, true, 'C', true);
                
              
                $pdf->Ln();
                $pdf->Ln(2);
                
                $rektor = \app\helpers\MyHelper::getRektor();
                $nama_rektor = !empty($rektor) ? $rektor->rektor0->nama_dosen : 'contoh nama rektor';
                $niy = !empty($rektor) ? $rektor->rektor0->nidn_asli : '';
                $label_dekan_id = !in_array($mhs->kodeProdi->kode_jenjang_studi, ['A','B']) ? 'Dekan,' : 'Direktur,';
                $label_dekan_en = !in_array($mhs->kodeProdi->kode_jenjang_studi, ['A','B']) ? 'Dean,' : 'Director,';

                $pdf->MultiCell($pdf->GetPageWidth() - 80, '', '', 0, 'C', 0, 0);
                $pdf->MultiCell('', '', 'Ponorogo, '.(isset($mhs->tgl_lulus)? \app\helpers\MyHelper::convertTanggalIndo($mhs->tgl_lulus) : 'not set'), 0, 'L', 0, 0);
                $pdf->Ln();
                $pdf->writeHTMLCell($page_div_3+20, '', 15, '', 'Rektor,', 0, 0, 0, true, 'C', true);
                $pdf->writeHTMLCell($page_div_3+5, '', '', '', '', 0, 0, 0, true, 'C', true);
                $pdf->writeHTMLCell($page_div_3+10, '', '', '', $label_dekan_id, 0, 0, 0, true, 'C', true);
                $pdf->Ln();
                $pdf->writeHTMLCell($page_div_3+20, '', 15, '', '<i>Rector,</i>', 0, 0, 0, true, 'C', true);
                $pdf->writeHTMLCell($page_div_3+5, '', '', '', '<br><br><br><br>Pas foto 2x3', 0, 0, 0, true, 'C', true);
                $pdf->writeHTMLCell($page_div_3+10, '', '', '', '<i>'.$label_dekan_en.'</i>', 0, 0, 0, true, 'C', true);
                // $pdf->MultiCell(($pdf->GetPageWidth() - 20) / 3, '', '<i>Rector</i>,', 0, 'C', 0, 0,15);
                // $pdf->MultiCell(($pdf->GetPageWidth() - 20) / 3, '', '', 0, 'C', 0, 0);
                // $pdf->MultiCell(($pdf->GetPageWidth() - 20) / 3 - 20, '', '<i>'.$label_dekan_en.'</i>', 0, 'C', 0, 0);
                // $pdf->Ln();
                // $pdf->writeHTMLCell($page_div_3+20, '', '15', '', 'a', 1, 0, 0, true, 'C', true);
                // if($pdf->getY() > 247) // ada mata kuliah yang panjang
                //     $pdf->Ln(18);
                // else
                // if(!in_array($mhs->kodeProdi->kode_jenjang_studi, ['C','D']))
                //     $pdf->Ln(16);
                // else
                $pdf->Ln(18);


                $nama_dekan = '';
                if(!empty($mhs->kodeProdi->kodeFakultas->pejabat0)){
                    $nama_dekan = $mhs->kodeProdi->kodeFakultas->pejabat0->nama_dosen;
                }

                $pdf->writeHTMLCell($page_div_3+25, '', 15, '', '<strong><u>'.$nama_rektor.'</u></strong>', 0, 0, 0, true, 'L', true);
                $pdf->writeHTMLCell($page_div_3+5, '', '', '', '', 0, 0, 0, true, 'L', true);
                $pdf->writeHTMLCell($page_div_3+10, '', '', '', '<strong><u>'.$nama_dekan.'</u></strong>', 0, 0, 0, true, 'L', true);
                $pdf->Ln();
                $pdf->writeHTMLCell($page_div_3+20, '', 15, '', 'NIDN: '.$niy, 0, 0, 0, true, 'L', true);
                $pdf->writeHTMLCell($page_div_3+10, '', '', '', '', 0, 0, 0, true, 'L', true);
                $pdf->writeHTMLCell($page_div_3+10, '', '', '', 'NIDN: '.$mhs->kodeProdi->kodeFakultas->pejabat0->nidn_asli, 0, 0, 0, true, 'L', true);
               
                $pdf->Output('transkrip_'.$mhs->nim_mhs.'.pdf','I');
                die();
            }
            catch(\HTML2PDF_exception $e) {
                echo $e;
                exit;
            }
            
        }
        die();
    }

    public function actionTranskrip()
    {
        
        $ipk = 0;
        $total_sks = 0;
        $bobot = 0;    
        $hasil = [];
        $results = [];
        $tahun_akademik = \app\models\SimakTahunakademik::getTahunAktif();
        $list_semester = \app\helpers\MyHelper::getSemester();
        $mhs = null;
        $query = SimakMastermahasiswa::find();
        $query->joinWith(['kampus0 as k','simakMahasiswaOrtus as ortu']);

        if(!Yii::$app->user->isGuest){
            $query->andWhere(['ortu.ortu_user_id' => Yii::$app->user->identity->id]);
        }

        $list_anak = $query->all();
        $session = Yii::$app->session;

        if(count($list_anak) == 1){
            $mhs = $list_anak[0];
            $session->set('nim',$mhs->nim_mhs);

        }
        
        $nim = '-';
        
        if(!empty($_GET['nim']) || $session->has('nim')){
            
            if(!empty($_GET['nim']) && ($_GET['nim'] == $session->get('nim'))){
                $nim = $session->get('nim');

            }
            else if(!empty($_GET['nim'])){
                $nim = $_GET['nim'];
                $session->set('nim',$nim);
            }
            else{
                $nim = $session->get('nim');
            }

            $mhs = SimakMastermahasiswa::find()->where(['nim_mhs'=>$nim])->one();
            

            if(!empty($mhs))
            {

                $query = SimakDatakrs::find()
                    ->where(['mahasiswa'=>$mhs->nim_mhs])
                    ->orderBy(['id'=>SORT_ASC]);

                $datakrs = $query->all();
                foreach($datakrs as $d)
                {

                    if($d->kode_mk == '-') 
                        continue;

                    if(empty($d->nilai_huruf)) 
                        continue;

                    if(in_array($d->nilai_huruf,['D','E','F'])) 
                        continue;

                    $jadwal = SimakJadwal::findOne([
                        'kode_mk' => $d->kode_mk,
                        'prodi' => $mhs->kode_prodi,
                        'tahun_akademik' => $d->tahun_akademik
                    ]);

                    // $mk = SimakMastermatakuliah::find()
                    // ->where([
                    //     'kode_mata_kuliah' => $d->kode_mk,
                    //     'kode_prodi' => $mhs->kode_prodi,
                    //     'tahun_akademik' => $d->tahun_akademik
                    // ])
                    // ->orderBy(['semester'=>SORT_ASC])
                    // ->one();


                    $matkul = SimakMatakuliah::find()->where([
                        'kode_mk' => trim($d->kode_mk),
                        'prodi' => $mhs->kode_prodi
                    ])->one();
                    // print_r($jadwal->semester);
                    // if($jadwal->semester == 8){

                    // }

                    if(!empty($matkul) && $matkul->sks_mk != 0){
                        $hasil[$d->kode_mk] = [
                            'kode_mk' => !empty($matkul) ? $matkul->kode_mk : $d->kode_mk.' tidak ada di Perkuliahan > Mata kuliah',
                            'nama_mk' => !empty($matkul) ? $matkul->nama_mk : ' tidak ada di Perkuliahan > Mata kuliah',
                            'nama_mk_en' => !empty($matkul) ? $matkul->nama_mk_en : ' tidak ada di Perkuliahan > Mata kuliah',
                            'semester' => !empty($jadwal) ? $jadwal->semester : '-',
                            'nilai_huruf' => !empty($matkul) ? $d->nilai_huruf : '-',
                            'sks' => !empty($matkul) ? $matkul->sks_mk : 0,
                            'bobot_sks' => !empty($matkul) ? $d->bobot_sks : 0,
                        ];
                    }
                }
                
                
                foreach($hasil as $tmp)
                {
                    
                    $results[$tmp['semester']][] = $tmp;
                }

                // if($total_sks > 0)
                // {
                //     $ipk = round($bobot / $total_sks,2);
                // }


            }
        }
        
        
        
        

        
        return $this->render('transkrip', [
            'results' => $results,
            'mhs' => $mhs,
            'tahun_akademik' => $tahun_akademik,
            'bobot' => $bobot,
            'total_sks' => $total_sks,
            'list_semester' => $list_semester,
            'list_anak' => $list_anak
        ]);
    }

    public function actionAkpam()
    {
        $listTahun = SimakTahunakademik::find()->orderBy(['tahun_id'=>SORT_DESC])->all();
        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);
        $client_token = Yii::$app->params['client_token'];
        $headers = ['x-access-token'=>$client_token];
        $dosen = SimakMasterdosen::find()->where(['nidn'=>Yii::$app->user->identity->nim])->one();
        $nim = '';
        if(!empty($_GET['btn-cari']))
        {
            $tahun_id = $_GET['tahun_id'];
            $nim = $_GET['nim'];
        }

        else
            $tahun_id = \app\helpers\MyHelper::getTahunAktif();
        
        $tahun_akademik = SimakTahunakademik::find()->where(['tahun_id'=>$tahun_id])->one();
        $mhs = null;
        $results = [];
        $listKrs = [];
        if(!empty($_GET['btn-cari']))
        {
            $mhs = SimakMastermahasiswa::find()->where(['nim_mhs'=>$nim])->one();
        

           
            $params = [
                'nim' => $mhs->nim_mhs,
                'tahun' => $tahun_id
            ];
            $response = $client->get('/m/krs', $params,$headers)->send();
            if ($response->isOk) {
                $results = $response->data['values'];
                foreach($results['krs'] as $q => $m)
                {
                    $krs = SimakDatakrs::findOne($m['idkrs']);
                    $listKrs[$m['idkrs']] = $krs;    
                }
                
            }
          
        }

        $listBimbingan = SimakMastermahasiswa::find()->where([
            'nip_promotor'=>!empty($dosen) ? $dosen->id : '-',
            'status_aktivitas' => 'A'
        ])->orderBy(['nama_mahasiswa'=>SORT_ASC])->all();

        return $this->render('akpam', [
            'results' => $results,
            'tahun_id' => $tahun_id,
            'listTahun' => $listTahun,
            'tahun_akademik' => $tahun_akademik,
            'listKrs' => $listKrs,
            'listBimbingan' => $listBimbingan,
            'mhs' => $mhs
        ]);
    }


    /**
     * Finds the SimakDatakrs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SimakDatakrs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SimakDatakrs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
