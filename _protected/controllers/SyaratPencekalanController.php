<?php

namespace app\controllers;

use Yii;
use app\models\KomponenBiaya;
use app\models\SyaratPencekalan;
use app\models\SyaratPencekalanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SyaratPencekalanController implements the CRUD actions for SyaratPencekalan model.
 */
class SyaratPencekalanController extends Controller
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
     * Lists all SyaratPencekalan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SyaratPencekalanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SyaratPencekalan model.
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
     * Creates a new SyaratPencekalan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SyaratPencekalan();

        $results = [];
        if(!empty($_GET['btn-search']))
        {
            $model->tahun_id = $_GET['SyaratPencekalan']['tahun_id'];
            $query = KomponenBiaya::find()->where([
                'tahun'=>$_GET['SyaratPencekalan']['tahun_id']
            ]);

            if(!empty($_GET['kampus']))
                $query->andWhere(['kampus_id' => $_GET['kampus']]);

            $results = $query->all();

        }

        if (!empty($_POST['btn-simpan'])) {

            $tahun = $_POST['SyaratPencekalan']['tahun_id'];
            $query = KomponenBiaya::find()->where([
                'tahun'=>$tahun
            ]);

            if(!empty($_POST['kampus']))
                $query->andWhere(['kampus_id' => $_POST['kampus']]);

            $results = $query->all();

            $transaction = \Yii::$app->db->beginTransaction();
            $errors = '';
            try 
            {
                foreach($results as $q => $v)
                {
                    $nilai_minimal = $_POST['nilai_minimal_'.$v->id.'_'.$tahun];

                    $m = SyaratPencekalan::find()->where(['komponen_id'=>$v->id,'tahun_id' => $tahun])->one();

                    if(empty($m))
                        $m = new SyaratPencekalan;

                    $m->komponen_id = $v->id;
                    $m->nilai_minimal = $nilai_minimal;
                    $m->tahun_id = $tahun;
                    if(!$m->save())
                    {
                        $errors .= \app\helpers\MyHelper::logError($m);
                        throw new Exception;
                    }
                }

                $transaction->commit();
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                $errors .= $e->getMessage();
                $model->addError('id', $errors);
                $transaction->rollBack();
                
            } catch (\Throwable $e) {
                $errors .= $e->getMessage();
                $model->addError('id', $errors);
                $transaction->rollBack();
                
                
            }
            
        }

        return $this->render('create', [
            'model' => $model,
            'results' => $results
        ]);
    }

    /**
     * Updates an existing SyaratPencekalan model.
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
     * Deletes an existing SyaratPencekalan model.
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
     * Finds the SyaratPencekalan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SyaratPencekalan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SyaratPencekalan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
