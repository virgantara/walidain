<?php

namespace app\controllers;

use Yii;
use app\models\SimakKonfirmasipembayaran;
use app\models\Customer;
use app\models\CustomerSearch;
use app\models\Tagihan;
use app\models\Tahun;
use app\models\SimakMastermahasiswa;
use app\models\SimakMahasiswaOrtu;
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
                'only' => ['view','index','detil','list'],
                'rules' => [
                    [
                        'actions' => [
                            'index','view','detil'
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => [
                            'list','view','detil'
                        ],
                        'allow' => true,
                        'roles' => ['ortu'],
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

      public function actionAjaxCariMahasiswa() {

        $q = $_GET['term'];
        
        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);
        $client_token = Yii::$app->params['client_token'];
        $headers = ['x-access-token'=>$client_token];

        $prodi = !empty($_GET['prodi']) ? $_GET['prodi'] : null;
        if(Yii::$app->user->identity->access_role == 'sekretearis')
        {
            $prodi = Yii::$app->user->identity->prodi;
        }

        $params = [
            'key' => $q,
            'kampus' => !empty($_GET['kampus']) ? $_GET['kampus'] : null,
            'prodi' => $prodi,
            'semester' => !empty($_GET['semester']) ? $_GET['semester'] : null,
            'status' => !empty($_GET['status']) ? $_GET['status'] : null
        ];
        $response = $client->get('/m/cari', $params,$headers)->send();
        
        $out = [];

        
        if ($response->isOk) {
            $result = $response->data['values'];
            // print_r($result);exit;
            if(!empty($result))
            {
                foreach ($result as $d) {
                    $out[] = [
                        'id' => $d['id'],
                        'nim' => $d['nim_mhs'],
                        'label'=> $d['nim_mhs'].' - '.$d['nama_mahasiswa'].' - '.$d['nama_prodi'].' - '.$d['nama_kampus'],
                        'prodi' => $d['kode_prodi'],
                        'nama_prodi' => $d['nama_prodi'],
                        'kampus' => $d['kampus'],
                        'nama_kampus' => $d['nama_kampus'],
                        'semester' => $d['semester']

                    ];
                }
            }

            else
            {
                $out[] = [
                    'id' => 0,
                    'label'=> 'Data mahasiswa tidak ditemukan',

                ];
            }
        }
        

        echo \yii\helpers\Json::encode($out);


    }

    public function actionList()
    {
        $searchModel = new SimakMastermahasiswaSearch();
        $dataProvider = $searchModel->searchAnanda(Yii::$app->request->queryParams);

        // if (Yii::$app->request->post('hasEditable')) {
        //     // instantiate your book model for saving
        //     $id = Yii::$app->request->post('editableKey');
        //     $model = SimakMastermahasiswa::findOne($id);

        //     // store a default json response as desired by editable
        //     $out = json_encode(['output'=>'', 'message'=>'']);

            
        //     $posted = current($_POST['SimakMastermahasiswa']);
        //     $post = ['SimakMastermahasiswa' => $posted];

        //     // load model like any single model validation
        //     if ($model->load($post)) {
        //     // can save model or do something before saving model
        //         if($model->save(false,['va_code']))
        //         {
        //             // $out = json_encode(['output'=>'', 'message'=>'']);
        //         }

        //         else{
        //             $errors = \app\helpers\MyHelper::logError($model);
        //             $out = json_encode(['output'=>'', 'message'=>$errors]);
        //         }
        //     }

        //     echo $out;

        //     // return ajax json encoded response and exit
            
        //     return ;
        // }

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
