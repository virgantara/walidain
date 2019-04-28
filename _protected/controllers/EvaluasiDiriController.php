<?php

namespace app\controllers;

use Yii;
use app\models\EvaluasiDiri;
use app\models\EvaluasiDiriSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
/**
 * EvaluasiDiriController implements the CRUD actions for EvaluasiDiri model.
 */
class EvaluasiDiriController extends Controller
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

    public function actionRekap(){

        $results = [];

        if(!empty($_POST['tanggal']) && !empty($_POST['dept_id']))
        {


            $tanggal = date('d',strtotime($_POST['tanggal']));
            $bulan = date('m',strtotime($_POST['tanggal']));
            $tahun = date('Y',strtotime($_POST['tanggal']));
            $query = \app\models\EvaluasiDiri::find();
            $query->where(['departemen_id'=>$_POST['dept_id']]);
            // $query->orderBy(['depa'=>SORT_ASC]);
            $list = $query->all();

            $total = 0;
            foreach($list as $q => $m)
            {
                
                $results[] = [
                    'id' => $m->id,
                    'unit' => $m->namaDepartemen,
                    'strength' => $m->strength,
                    'weakness' => $m->weakness,
                    'opportunity' => $m->opportunity,
                    'threat' => $m->threat,
                    
                ];
            }


            if(!empty($_POST['export']) && empty($_POST['search']))
            {
                return $this->renderPartial('_tabel_swot', [
                    'list' => $results,
                    'model' => $model,
                    'export' => 1
                ]); 
            }

        }

        return $this->render('rekap', [
            'list' => $results,
            'model' => $model,

        ]);
    }

    public function actionDownload($id)
    {
        $model = $this->findModel($id); 
        $path = Yii::getAlias('@webroot').'/uploads/'.$model->attachment;

        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path);
        }
    }

    public function actionVerify($id, $kode)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try 
        {
            $model = $this->findModel($id);
            $model->is_verified = $kode;

            $model->save();


            $transaction->commit();
            Yii::$app->session->setFlash('success', "Data tersimpan");
            return $this->redirect(['view','id'=>$id]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Lists all EvaluasiDiri models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EvaluasiDiriSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EvaluasiDiri model.
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
     * Creates a new EvaluasiDiri model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EvaluasiDiri();

        if ($model->load(Yii::$app->request->post())) {
            $model->tanggal = date('Y-m-d',strtotime($model->tanggal));
            $model->departemen_id = Yii::$app->user->identity->departemen;

            $imageFile = UploadedFile::getInstance($model, 'attachment');
            if($imageFile){
                $filename = date('YmdHis').'_'.$model->id.'.'.$imageFile->extension;
                $model->attachment = $filename;
                $imageFile->saveAs('uploads/' . $filename);
            }

            $model->save();
            
            
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing EvaluasiDiri model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->tanggal = date('Y-m-d',strtotime($model->tanggal));
            
            $imageFile = UploadedFile::getInstance($model, 'attachment');
            if($imageFile){
                $filename = date('YmdHis').'_'.$model->id.'.'.$imageFile->extension;
                $model->attachment = $filename;
                $imageFile->saveAs('uploads/' . $filename);
            }

            $model->save();
            
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EvaluasiDiri model.
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
     * Finds the EvaluasiDiri model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EvaluasiDiri the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EvaluasiDiri::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
