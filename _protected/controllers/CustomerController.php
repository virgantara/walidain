<?php

namespace app\controllers;

use Yii;
use app\models\SimakKonfirmasipembayaran;
use app\models\Customer;
use app\models\CustomerSearch;
use app\models\Tagihan;
use app\models\Tahun;
use app\models\SimakMastermahasiswa;
use app\models\SimakMastermahasiswaSearch;

use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\httpclient\Client;


/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends AppController
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
                'only' => ['view','index','generate-va','detil','generate-va-oppal'],
                'rules' => [
                    [
                        'actions' => [
                            'index','view','generate-va','detil','aktivasi','generate-va-oppal'
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => [
                            'index','view','generate-va','detil','aktivasi','generate-va-oppal'
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

    public function actionGenerateVaOppal()
    {

        $model = new SimakMastermahasiswa;
        $count = 0;
        if($model->load(Yii::$app->request->get()))
        {
            $prodi = $model->kode_prodi;
            $kampus = $model->kampus;
            $status_aktivitas = $model->status_aktivitas;

            $query = SimakMastermahasiswa::find()->where([
                
                'status_aktivitas'=>$status_aktivitas,
                'kampus' => $kampus
            ]);

            if(!empty($prodi))
            {
                $query->andWhere(['kode_prodi' => $prodi]);
            }

            $list = $query->all();

            $kampus = \app\models\SimakKampus::find()->where(['kode_kampus'=>$kampus])->one();

            // $prefix = '751050';

            $transaction = \Yii::$app->db->beginTransaction();
            $errors = '';

            // print_r($listCustomer);exit;
            try 
            {
                foreach($list as $m)
                {
                    
                    $nim = str_replace('.', '', $m->nim_mhs);
                    if(strlen($nim) < 8)
                    {
                        $suffix = $nim;
                    }

                    else
                    {
                        $suffix = str_replace(substr($nim, 2, 4),'',$nim);
                    }

                    
                    $prefix = '792600';
                    $code = $prefix.\app\helpers\MyHelper::appendZeros($suffix, 10);

                    $m->va_oppal = $code;
                    if($m->save(false,['va_oppal']))
                    {
                        $count++;
                    }

                    else
                    {
                        $errors .= \app\helpers\MyHelper::logError($m);
                        throw new Exception;
                        
                    }
                    
                }  
                Yii::$app->session->setFlash('success', $count." virtual account Oppal sudah dibuat");
                $transaction->commit();

                return $this->redirect(['generate-va-oppal']);
            } catch (\Exception $e) {
                $errors .= $e->getMessage();
                Yii::$app->session->setFlash('danger', $errors);
                $transaction->rollBack();
                
            } catch (\Throwable $e) {
                $errors .= $e->getMessage();
                Yii::$app->session->setFlash('danger', $errors);
                $transaction->rollBack();
                
                
            }
            die(); 
        }

        return $this->render('generate_va_oppal',[
            'model' => $model,

        ]);   
    }

    public function actionAktivasi()
    {
        $model = new Tagihan;
        $tahun = Tahun::getTahunAktif();
        

        if(!empty($_POST['status_aktivitas']) && !empty($_POST['prodi']) && !empty($_POST['kampus'])&& !empty($_POST['tahun_masuk']))
        {

            $sa = $_POST['status_aktivitas'];
            $query = SimakMastermahasiswa::find()->where([
                'kode_prodi' => $_POST['prodi'],
                'kampus' => $_POST['kampus'],
                'tahun_masuk' => $_POST['tahun_masuk'],
                'status_aktivitas' => $sa
            ]);


            $listCustomer = $query->all();
            $transaction = \Yii::$app->db->beginTransaction();
            $errors = '';

            // print_r($listCustomer);exit;
            try 
            {

                if(empty($_POST['prodi']))
                {
                    $errors .= 'Prodi harus diisi';
                        
                    throw new \Exception;
                }

                if(empty($_POST['kampus']))
                {
                    $errors .= 'Kelas harus diisi';
                        
                    throw new \Exception;
                }

                $counter = 0;
                foreach($listCustomer as $c)
                {

                    $query = Tagihan::find();
                    $query->alias('t');
                    $query->joinWith(['komponen as k','komponen.kategori as kk']);
                    $query->andWhere([
                        'kk.kode' => '01',
                        't.nim' => $c->nim_mhs,
                        't.tahun' => $tahun->id,
                    ]);

                    $query->andWhere('terbayar >= nilai_minimal');
                    $lunas = $query->one();
                    
                    if(!empty($lunas))
                    {
                        $c->status_aktivitas = 'A';
                        if($c->save(false,['status_aktivitas']))
                        {
                            $konfirm = SimakKonfirmasipembayaran::find()->where([
                                'nim' =>$c->nim_mhs,
                                'pembayaran' => '01',
                                'semester' => $c->semester,
                                'jumlah' => $lunas->terbayar,
                                'bank' => 'nama_bank',
                                'tahun_id' => $tahun->id
                            ])->one();

                            if(empty($konfirm))
                            {
                                $konfirm = new SimakKonfirmasipembayaran;
                                $konfirm->nim = $c->nim_mhs;
                                $konfirm->pembayaran = '01';
                                $konfirm->semester = $c->semester;
                                $konfirm->jumlah = $lunas->terbayar;
                                $konfirm->bank = 'nama_bank';
                                $konfirm->tahun_id = $tahun->id;
                                $konfirm->status = 1;
                            }

                            else{
                                $konfirm->pembayaran = '01';
                                $konfirm->jumlah = $lunas->terbayar;
                                $konfirm->status = 1;
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
                Yii::$app->session->setFlash('success', $counter." Data mahasiswa telah diaktifkan");
                
            } catch (\Exception $e) {
                $errors .= $e->getMessage();
                $transaction->rollBack();
                Yii::$app->session->setFlash('danger', $errors);
                
                
            } catch (\Throwable $e) {
                $errors .= $e->getMessage();
                $transaction->rollBack();
                Yii::$app->session->setFlash('danger', $errors);
                
                
                
            }
        }

        return $this->render('aktivasi',[
            'model' => $model,
            'tahun' => $tahun,
        ]);
    }

    public function actionGenerateVa()
    {

        $model = new SimakMastermahasiswa;
        $count = 0;
        if($model->load(Yii::$app->request->get()))
        {
            $prodi = $model->kode_prodi;
            $kampus = $model->kampus;
            $status_aktivitas = $model->status_aktivitas;

            $query = SimakMastermahasiswa::find()->where([
                
                'status_aktivitas'=>$status_aktivitas,
                'kampus' => $kampus
            ]);

            if(!empty($prodi))
            {
                $query->andWhere(['kode_prodi' => $prodi]);
            }

            $list = $query->all();

            $kampus = \app\models\SimakKampus::find()->where(['kode_kampus'=>$kampus])->one();

            // $prefix = '751050';

            $transaction = \Yii::$app->db->beginTransaction();
            $errors = '';

            // print_r($listCustomer);exit;
            try 
            {
                foreach($list as $m)
                {
                    
                    $nim = str_replace('.', '', $m->nim_mhs);
                    if(strlen($nim) < 8)
                    {
                        $suffix = $nim;
                    }

                    else
                    {
                        $suffix = str_replace(substr($nim, 2, 4),'',$nim);
                    }

                    
                    $prefix = $kampus->prefix;
                    $code = $prefix.\app\helpers\MyHelper::appendZeros($suffix, 10);

                    $m->va_code = $code;
                    if($m->save(false,['va_code']))
                    {
                        $count++;
                    }

                    else
                    {
                        $errors .= \app\helpers\MyHelper::logError($m);
                        throw new Exception;
                        
                    }
                    
                }  
                Yii::$app->session->setFlash('success', $count." virtual account sudah dibuat");
                $transaction->commit();

                return $this->redirect(['generate-va']);
            } catch (\Exception $e) {
                $errors .= $e->getMessage();
                Yii::$app->session->setFlash('danger', $errors);
                $transaction->rollBack();
                
            } catch (\Throwable $e) {
                $errors .= $e->getMessage();
                Yii::$app->session->setFlash('danger', $errors);
                $transaction->rollBack();
                
                
            }
            die(); 
        }

        return $this->render('generateVa',[
            'model' => $model,

        ]);   
    }

    public function actionGetJumlahMahasiswaPerProdi()
    {
        $dataPost = $_POST['dataPost'];
        $prodi = $dataPost['prodi'];
        $tahun_masuk = $dataPost['tahun_masuk'];
        $kampus = $dataPost['kampus'];
        
        $status_aktivitas = $dataPost['status_aktivitas'];
        

        $query = SimakMastermahasiswa::find()->where([
            'kode_prodi' => $prodi,
            'tahun_masuk' => $tahun_masuk,
            'kampus' => $kampus,    
            'status_aktivitas' => $status_aktivitas
        ]);
        $tahun = Tahun::getTahunAktif();
        // $query->andWhere(['NOT IN','status_aktivitas',['A','K']]);

        $list = $query->all();
        $counter=0;
        foreach($list as $mhs)
        {
            
            $query = Tagihan::find();
            $query->alias('t');
            $query->joinWith(['komponen as k','nim0 as mhs','komponen.kategori as kk']);
            $query->andWhere([
                'kk.kode' => '01',
                't.tahun' => $tahun->id,
                't.nim' => $mhs->nim_mhs
            ]);

            $query->andWhere('terbayar >= nilai_minimal');
            $lunas = $query->count();

            if($lunas > 0)
                $counter++;
            
        }        
        
        $out = [
            'prodi' => $prodi,
            'jumlah' => $counter
        ];

        echo \yii\helpers\Json::encode($out);
        die();
    }


    public function actionGetJumlahMahasiswaPerAngkatan()
    {
        $dataPost = $_POST['dataPost'];
        $prodi = $dataPost['prodi'];
        $tahun_masuk = $dataPost['tahun_masuk'];
        $kampus = $dataPost['kampus'];
        $komponen_id = $dataPost['komponen_id'];
        $status_aktivitas = $dataPost['status_aktivitas'];
        // $sa = ['A','N'];
        $tahun = Tahun::getTahunAktif();

        $listMhs = SimakMastermahasiswa::find()->where([
            'kode_prodi' => $prodi,
            'tahun_masuk' => $tahun_masuk,
            'kampus' => $kampus,
            'status_aktivitas' => $status_aktivitas
        ])->all();        

        $counter = 0;
        foreach($listMhs as $m)
        {
            $t = Tagihan::find()->where([
                'komponen_id'=>$komponen_id,
                'tahun' => $tahun->id,
                'nim' =>$m->nim_mhs       
            ])->one();

            if(empty($t))
                $counter++;
        }

        // $out = (new \yii\db\Query())
        //     ->select(['semester as id', 'semester as name'])
        //     ->from('simak_mastermahasiswa')

        //     ->where([
        //       'kode_prodi' => $prodi,
        //       'tahun_masuk' => $tahun_masuk
        //     ])
        //     ->andWhere(['in','status_aktivitas',$sa])
        //     ->groupBy(['semester'])
        //     ->orderBy(['semester'=>SORT_ASC])
        //     ->all();

        $out = [
            'prodi' => $prodi,
            'jumlah' => $counter
        ];

        echo \yii\helpers\Json::encode($out);
        die();
    }

    public function actionGetJumlahMahasiswaPerSemester()
    {
        $dataPost = $_POST['dataPost'];
        $prodi = $dataPost['prodi'];
        $semester = $dataPost['semester'];
        $kampus = $dataPost['kampus'];

        $jml = SimakMastermahasiswa::find()->where([
            'kode_prodi' => $prodi,
            'status_aktivitas'=>'A',
            'semester' => $semester,
            'kampus' => $kampus
        ])->count();        

        $out = [
            'prodi' => $prodi,
            'jumlah' => $jml
        ];

        echo \yii\helpers\Json::encode($out);
        die();
    }

    public function actionSubsemester()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $prodi = $parents[0];
                $out = (new \yii\db\Query())
                    ->select(['semester as id', 'semester as name'])
                    ->from('simak_mastermahasiswa')
                    ->where([
                      'kode_prodi' => $prodi,
                      'status_aktivitas' => 'A'
                    ])
                    ->groupBy(['semester'])
                    ->orderBy(['semester'=>SORT_ASC])
                    ->all();

                // the getSubCatList function will query the database based on the
                // cat_id and return an array like below:
                // [
                //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
                //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
                // ]
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        // select semester from simak_mastermahasiswa where kode_prodi = 1 and status_aktivitas = 'A' group by semester order by semester
    }

    public function actionSubangkatan()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $prodi = $parents[0];
                $kampus = $parents[1];
                // $kampus = $parents[2];
                $sa = ['A','N'];
                $out = (new \yii\db\Query())
                    ->select(['tahun_masuk as id', 'tahun_masuk as name'])
                    ->from('simak_mastermahasiswa')
                    ->where([
                      'kode_prodi' => $prodi,
                      
                      'kampus' => $kampus
                    ])
                    ->andWhere(['in','status_aktivitas',$sa])
                    ->groupBy(['tahun_masuk'])
                    ->orderBy(['tahun_masuk'=>SORT_DESC])
                    ->all();

                // the getSubCatList function will query the database based on the
                // cat_id and return an array like below:
                // [
                //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
                //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
                // ]
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        // select semester from simak_mastermahasiswa where kode_prodi = 1 and status_aktivitas = 'A' group by semester order by semester
    }

    public function actionFixPembayaran($tahun)
    {
        $listKonfirmasi = \app\models\SimakKonfirmasipembayaran::find()->where([
            'pembayaran' => '01',
            'tahun_id' => $tahun
        ])->all();

        foreach($listKonfirmasi as $item)
        {
            $mhs = SimakMastermahasiswa::find()->where(['nim_mhs'=>$item->nim])->one();

            if(!empty($mhs))
            {
                $mhs->status_aktivitas = 'A';
                $mhs->save(false,['status_aktivitas']);
            }
        }

        die();
    }


    /**
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SimakMastermahasiswaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->post('hasEditable')) {
            // instantiate your book model for saving
            $id = Yii::$app->request->post('editableKey');
            $model = SimakMastermahasiswa::findOne($id);

            // store a default json response as desired by editable
            $out = json_encode(['output'=>'', 'message'=>'']);

            
            $posted = current($_POST['SimakMastermahasiswa']);
            $post = ['SimakMastermahasiswa' => $posted];

            // load model like any single model validation
            if ($model->load($post)) {
            // can save model or do something before saving model
                if($model->save(false,['va_code']))
                {
                    // $out = json_encode(['output'=>'', 'message'=>'']);
                }

                else{
                    $errors = \app\helpers\MyHelper::logError($model);
                    $out = json_encode(['output'=>'', 'message'=>$errors]);
                }
            }

            echo $out;

            // return ajax json encoded response and exit
            
            return ;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDetil($id)
    {

        $model = $this->findModel($id);
            
        $kabupaten = \app\models\SimakKabupaten::find()->where(['id'=>$model->kabupaten])->one();
        return $this->renderAjax('detil', [
            'model' => $model,
            'kabupaten' => $kabupaten
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
  
    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SimakMastermahasiswa::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
