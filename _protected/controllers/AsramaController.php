<?php

namespace app\controllers;

use Yii;

use yii\helpers\ArrayHelper;
use app\models\SimakKampus;
use app\models\ErpRiwayatPelanggaran;
use app\models\ErpRiwayatKamar;
use app\models\SimakJenisKegiatan;
use app\models\SimakKegiatanMahasiswa;
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
class AsramaController extends Controller
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
                'only' => ['create','update','delete','index','nilai','krs','khs','export-khs','export-krs','perwalian','pembatalan','ajaxKrsList','akpam','pelanggaran','update-nilai','konversi-mk','krs-paket','ajax-presensi','absensi', 'rekap-krs','kegiatan'],
                'rules' => [
                    // [
                    //     'actions' => [],
                    //     'allow' => true,
                    //     'roles' => ['?'],
                    // ],
                    [
                        'actions' => ['list','kegiatan','kesantrian'],
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

    public function actionKesantrian()
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
        $nim = '';
        $model = null;
        $listMhs = [];
        $dosen = \app\models\SimakMasterdosen::find()->where(['nidn'=>Yii::$app->user->identity->nim])->one();
        if(Yii::$app->user->identity->access_role == 'Dosen')
        {        
            $listMhs = SimakMastermahasiswa::find()->where([
                'nip_promotor'=>!empty($dosen) ? $dosen->id : '-',
                'status_aktivitas' => 'A'
            ])->orderBy(['nama_mahasiswa'=>SORT_ASC])->all();
        }

        else if(Yii::$app->user->identity->access_role == 'Mahasiswa')
        { 
            $listMhs = SimakMastermahasiswa::find()->where([
                'nim_mhs'=>Yii::$app->user->identity->nim,
                'status_aktivitas' => 'A'
            ])->orderBy(['nama_mahasiswa'=>SORT_ASC])->all();

        }

        else if(Yii::$app->user->identity->access_role == ('sekretearis'))
        { 
            $listMhs = SimakMastermahasiswa::find()->where([
                'kode_prodi'=>Yii::$app->user->identity->prodi,
                'status_aktivitas' => 'A'
            ])->orderBy(['nama_mahasiswa'=>SORT_ASC])->all();

        }

        $riwayat = [];
        $riwayatKamar = [];
        $querykrsmhs = [];
        $riwayatPembayaran = [];
        if(!empty($_GET['btn-cari']))
        {
            $nim = $_GET['nim'];

            $model = SimakMastermahasiswa::find()->where(['nim_mhs'=>$nim])->one();
            $query = ErpRiwayatPelanggaran::find()->where([
                'nim'=> $model->nim_mhs
            ]);

            $query->orderBy(['created_at'=>SORT_DESC]);

            $riwayat = $query->all();

            $query = \app\models\ErpRiwayatKamar::find()->where([
                'nim'=> $model->nim_mhs
            ]);

            

            $query->orderBy(['created_at'=>SORT_DESC]);

            $riwayatKamar = $query->all();

            
        }
        

        return $this->render('kesantrian', [
            'model' => $model,
            'nim' => $nim,
            'list_anak' => $list_anak,
            'riwayat' => $riwayat,
            'riwayatKamar' => $riwayatKamar,
            'listMhs' => $listMhs,
        ]);
    

    }


    public function actionKegiatan()
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
        $nim = '-';
        $tahun = '';
        if(!empty($_GET['btn-cari']))
        {
            
            $tahun = $_GET['tahun_id'];
            $nim = !empty($_GET['nim']) ? $_GET['nim'] : '-';
        }


        $ta = \app\models\SimakTahunakademik::find()->where(['tahun_id'=>$tahun])->one();
        if(empty($ta))
        {
            $ta = \app\models\SimakTahunakademik::getTahunAktif();
        }

        $konfirmasi = \app\models\SimakKonfirmasipembayaran::find()->where([
            'pembayaran' => '01',
            'status' => 1,
            'nim' => Yii::$app->user->identity->nim,
            'tahun_id' => $ta->tahun_id
        ])->one();

        $tahun_id = $ta->tahun_id;
        
        $mhs = null;
        $results = [];

        $listJenisKegiatan = SimakJenisKegiatan::find()->all();

        foreach($listJenisKegiatan as $jk)
        {
            if(!empty($nim))
            {
                $results[$jk->id] = SimakKegiatanMahasiswa::find()->where([
                    'nim'=>$nim,
                    'tahun_akademik' => $ta->tahun_id,
                    'id_jenis_kegiatan' => $jk->id
                ])->all();
     
                // print_r($results);exit;
              
            }    
        }

        

        $listMhs = [];

        if(Yii::$app->user->identity->access_role == 'Mahasiswa')
        { 
            $listMhs = SimakMastermahasiswa::find()->where([
                'nim_mhs'=>Yii::$app->user->identity->nim,
                'status_aktivitas' => 'A'
            ])->orderBy(['nama_mahasiswa'=>SORT_ASC])->all();

        }

        return $this->render('kegiatan', [
            'results' => $results,
            'listJenisKegiatan' => $listJenisKegiatan,
            'tahun_id' => $tahun_id,
            'listTahun' => $listTahun,
            'tahun_akademik' => $ta,
            'listMhs' => $listMhs,
            'mhs' => $mhs,
            'konfirmasi' => $konfirmasi,
            'list_anak' => $list_anak
        ]);
    }
}
