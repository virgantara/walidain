<?php

namespace app\controllers;

use Yii;
use app\models\KomponenBiaya;
use app\models\KomponenBiayaSearch;
use app\models\Kategori;
use app\models\Tahun;

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


        $tahun = ArrayHelper::map(Tahun::find()->all(),'id','nama');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'kategori' => $kategori,
            'tahun' => $tahun,
            'list_prioritas' => $list_prioritas
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
        
         $list_prioritas = [
            '1' => 'HIGH',
            '2' => 'MED',
            '3' => 'LOW',
            '4' => 'SLIGHTLY LOW',
            '5' => 'LOWEST',

        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'kategori' => $kategori,
            'tahun' => $tahun,
            'list_prioritas' => $list_prioritas
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
