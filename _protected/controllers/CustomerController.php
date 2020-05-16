<?php

namespace app\controllers;

use Yii;
use app\models\Customer;
use app\models\CustomerSearch;
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
class CustomerController extends Controller
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
                'only' => ['view','index','generate-va'],
                'rules' => [
                    [
                        'actions' => [
                            'index','view'
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => [
                            'index','view','generate-va'
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

    public function actionGenerateVa()
    {

        $model = new SimakMastermahasiswa;
        $count = 0;
        if($model->load(Yii::$app->request->get()))
        {
            $prodi = $model->kode_prodi;
            $kampus = $model->kampus;
            $status_aktivitas = $model->status_aktivitas;

            $list = SimakMastermahasiswa::find()->where([
                'kode_prodi' => $prodi,
                'status_aktivitas'=>$status_aktivitas,
                'kampus' => $kampus
            ])->all();

            $prefix = '751050';

            $transaction = \Yii::$app->db->beginTransaction();
            $errors = '';

            // print_r($listCustomer);exit;
            try 
            {
                foreach($list as $m)
                {
                    if(empty($m->va_code) || $m->va_code == '-')
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

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
