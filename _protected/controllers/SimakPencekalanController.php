<?php

namespace app\controllers;

use Yii;
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
class SimakPencekalanController extends Controller
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
        $results = [];
        $tahun_akademik = SimakTahunakademik::find()->where(['buka'=>'Y'])->one();
        $tahunaktif = $tahun_akademik->tahun_id;
        if(!empty($_GET['btn-lihat']))
        {
            $results = SimakMastermahasiswa::find()->where([
                'kampus'=>$_GET['kampus'],
                'kode_prodi'=>$_GET['prodi'],
                'status_aktivitas' => 'A'
            ])
            ->orderBy(['nama_mahasiswa'=>SORT_ASC])
            ->all();
        }

        if(!empty($_POST['btn-simpan']))
        {
            $results = SimakMastermahasiswa::find()->where([
                'kampus'=>$_POST['kampus'],
                'kode_prodi'=>$_POST['prodi'],
                'status_aktivitas' => 'A'
            ])
            ->orderBy(['nama_mahasiswa'=>SORT_ASC])
            ->all();

            foreach($results as $item)
            {
                $p = SimakPencekalan::find()->where([
                    'tahun_id' => $tahunaktif,
                    'nim' => $item->nim_mhs
                ])->one();

                if(empty($p))
                    $p = new SimakPencekalan;

                // print_r($_POST);exit;
                // $p->tahfidz = !empty($_POST['tahfidz_'.$tahunaktif.'_'.$item->nim_mhs]) ? 1 : 0;
                // $p->akpam = !empty($_POST['akpam_'.$tahunaktif.'_'.$item->nim_mhs]) ? 1 : 0;
                $p->adm = !empty($_POST['adm_'.$tahunaktif.'_'.$item->nim_mhs]) ? 1 : 0;
                // $p->akademik = !empty($_POST['akademik_'.$tahunaktif.'_'.$item->nim_mhs]) ? 1 : 0;
                $p->nim = $item->nim_mhs;
                $p->tahun_id = $tahunaktif;

                $p->save();
            }

            Yii::$app->session->setFlash('success', Yii::t('app', 'Data successfully saved'));
        }
        // $searchModel = new SimakPencekalanSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'results' => $results,
            'tahunaktif' => $tahunaktif
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
