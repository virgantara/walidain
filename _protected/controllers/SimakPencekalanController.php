<?php

namespace app\controllers;

use Yii;
use app\models\Tagihan;
use app\models\TagihanSearch;
use app\models\Tahun;
use app\models\SimakPencekalan;
use app\models\SimakPencekalanSearch;
use app\models\SimakTahunakademik;
use app\models\SimakMastermahasiswa;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SimakPencekalanController implements the CRUD actions for SimakPencekalan model.
 */
class SimakPencekalanController extends AppController
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

    /**
     * Lists all SimakPencekalan models.
     * @return mixed
     */
    public function actionIndex()
    {

        $tahun_tagihan = Tahun::find()->all();
        $results = [];
        $tahun_akademik = SimakTahunakademik::find()->where(['buka'=>'Y'])->one();
        $tahunaktif = $tahun_akademik->tahun_id;
        if(!empty($_GET['btn-lihat']))
        {

            $searchModel = new TagihanSearch;
            $searchModel->tahun = $_GET['tahun_tagihan'];
            $searchModel->namaKampus = $_GET['kampus'];
            $searchModel->namaProdi = $_GET['prodi'];
            $searchModel->excludeWisuda = $_GET['exclude_wisuda'];
            $results = $searchModel->searchManual();


        }

        if(!empty($_POST['btn-simpan']))
        {
            
            $searchModel = new TagihanSearch;
            $searchModel->tahun = $_POST['tahun_tagihan'];
            $searchModel->namaKampus = $_POST['kampus'];
            $searchModel->namaProdi = $_POST['prodi'];
            $searchModel->excludeWisuda = $_POST['exclude_wisuda'];
            $results = $searchModel->searchManual();
            $transaction = \Yii::$app->db->beginTransaction();
            $errors = '';

            // print_r($results);exit;
            try 
            {
                foreach($results as $item)
                {
                    $p = SimakPencekalan::find()->where([
                        'tahun_id' => $tahunaktif,
                        'nim' => $item->nim
                    ])->one();

                    if(empty($p))
                        $p = new SimakPencekalan;

                    // print_r($_POST);exit;
                    // $p->tahfidz = !empty($_POST['tahfidz_'.$tahunaktif.'_'.$item->nim_mhs]) ? 1 : 0;
                    // $p->akpam = !empty($_POST['akpam_'.$tahunaktif.'_'.$item->nim_mhs]) ? 1 : 0;
                    $p->adm = 1;
                    // $p->akademik = !empty($_POST['akademik_'.$tahunaktif.'_'.$item->nim_mhs]) ? 1 : 0;
                    $p->nim = $item->nim;
                    $p->tahun_id = $tahunaktif;

                    // print_r($p->attributes);exit;

                    if(!$p->save())
                    {
                        $errors = \app\helpers\MyHelper::logError($p);
                        Yii::$app->session->setFlash('danger', $errors);
                    }
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Data successfully saved'));
                // $this->redirect(['index']);
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                
                throw $e;
            }

            
        }
        // $searchModel = new SimakPencekalanSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'results' => $results,
            'tahunaktif' => $tahunaktif,
            'tahun_tagihan' => $tahun_tagihan
            // 'searchModel' => $searchModel,
            // 'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SimakPencekalan model.
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
     * Creates a new SimakPencekalan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SimakPencekalan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SimakPencekalan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SimakPencekalan model.
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
     * Finds the SimakPencekalan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SimakPencekalan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SimakPencekalan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
