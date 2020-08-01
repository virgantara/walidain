<?php

namespace app\controllers;

use Yii;
use app\models\KomponenBiaya;
use app\models\KomponenBiayaSearch;
use app\models\Kategori;
use app\models\Tahun;
use app\models\SimakKampus;
use app\models\Bulan;

use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * KomponenBiayaController implements the CRUD actions for KomponenBiaya model.
 */
class KomponenBiayaController extends Controller
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

    public function actionAjaxGetKomponen()
    {
        $out = [];
        if(Yii::$app->request->isAjax)
        {
            $id = !empty($_POST['id']) ? $_POST['id'] : 0;
            $model = KomponenBiaya::findOne($id);

            if(!empty($model))
            {
                $out = [
                    'kd' => $model->kode,
                    'nm' => $model->nama,
                    'pr' => $model->prioritas,
                    'b' => $model->biaya_awal,
                    'm' => $model->biaya_minimal,
                    't' => $model->tahun
                ];    
            }
            
        }
        
        header('Content-Type: application/json');
        echo \yii\helpers\Json::encode($out);
    }

    public function actionSubkomponen()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $tahun = $parents[0];
                $out = (new \yii\db\Query())
                    ->select(['id', 'nama as name'])
                    ->from('bill_komponen_biaya')
                    ->where([
                      'tahun' => $tahun
                    ])
                    ->orderBy(['kode'=>SORT_ASC])
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
     * Lists all KomponenBiaya models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KomponenBiayaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single KomponenBiaya model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new KomponenBiaya model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KomponenBiaya();
        $kategori = ArrayHelper::map(Kategori::find()->all(),'id',function($data){
            return $data->kode.' - '.$data->nama;
        });

        $list_prioritas = [
            '1' => 'HIGH',
            '2' => 'MED',
            '3' => 'LOW',
            '4' => 'SLIGHTLY LOW',
            '5' => 'LOWEST',

        ];


        $tahun = ArrayHelper::map(Tahun::find()->where(['buka' => 'Y'])->orderBy(['id'=>SORT_DESC])->all(),'id','nama');

        $listKampus = ArrayHelper::map(SimakKampus::find()->orderBy(['kode_kampus'=>SORT_ASC])->all(),'kode_kampus','nama_kampus');

        $listBulan = ArrayHelper::map(Bulan::find()->orderBy(['id'=>SORT_ASC])->all(),'id','nama');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'kategori' => $kategori,
            'tahun' => $tahun,
            'list_prioritas' => $list_prioritas,
            'listKampus' => $listKampus,
            'listBulan' => $listBulan
        ]);
    }

    /**
     * Updates an existing KomponenBiaya model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $kategori = ArrayHelper::map(Kategori::find()->all(),'id','nama');
        $tahun = ArrayHelper::map(Tahun::find()->all(),'id','nama');
        
        $listKampus = ArrayHelper::map(SimakKampus::find()->orderBy(['kode_kampus'=>SORT_ASC])->all(),'kode_kampus','nama_kampus');

        $listBulan = ArrayHelper::map(Bulan::find()->orderBy(['id'=>SORT_ASC])->all(),'id','nama');

        $list_prioritas = [
            '1' => 'HIGH',
            '2' => 'MED',
            '3' => 'LOW',
            '4' => 'SLIGHTLY LOW',
            '5' => 'LOWEST',

        ];

        if ($model->load(Yii::$app->request->post())) {

            $transaction = \Yii::$app->db->beginTransaction();
            $errors = '';
            try 
            {
                $counter = 0;
                if($model->save())
                {

                    foreach($model->tagihans as $t)
                    {

                        $t->nilai = $model->biaya_awal;
                        $t->nilai_minimal = $model->biaya_minimal;

                        if($t->save(false,['nilai','nilai_minimal']))
                        {
                            $counter++;
                        }

                        else{
                            $errors .= \app\helpers\MyHelper::logError($t);
                            throw new Exception;
                        }
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', $counter." tagihan telah diupdate");
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } 
            
            catch (\Exception $e) 
            {
                $errors .= $e->getMessage();
                $model->addError('id',$errors);
                $transaction->rollBack();
                
            } 

            catch (\Throwable $e) 
            {
                $errors .= $e->getMessage();
                $model->addError('id',$errors);
                $transaction->rollBack();
                
                
            }
            
        }

        return $this->render('update', [
            'model' => $model,
            'kategori' => $kategori,
            'tahun' => $tahun,
            'list_prioritas' => $list_prioritas,
            'listKampus' => $listKampus,
            'listBulan' => $listBulan
        ]);
    }

    /**
     * Deletes an existing KomponenBiaya model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the KomponenBiaya model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KomponenBiaya the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KomponenBiaya::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
