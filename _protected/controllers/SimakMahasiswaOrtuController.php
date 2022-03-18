<?php

namespace app\controllers;

use Yii;
use app\models\SimakMastermahasiswa;
use app\models\SimakMahasiswaOrtu;
use app\models\SimakMahasiswaOrtuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SimakMahasiswaOrtuController implements the CRUD actions for SimakMahasiswaOrtu model.
 */
class SimakMahasiswaOrtuController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionAjaxRemove()
    {
        $dataPost = $_POST['dataPost'];
        $results = [];
        $model = SimakMastermahasiswa::find()->where([
            'nim_mhs'=>$dataPost['mahasiswa']
        ])->one();
        
        
        if(!empty($model))
        {   
            $nim = $model->nim_mhs;
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            $errors = '';

            try 
            {
                $ortu_id = Yii::$app->user->identity->id;

                $list_ortu = SimakMahasiswaOrtu::find()->where([
                    'nim' => $nim,
                    'ortu_user_id' => $ortu_id
                ])->all();
                
                foreach($list_ortu as $ortu){
                    $ortu->ortu_user_id = null;
                    $ortu->save(false,['ortu_user_id']);
                }

                $transaction->commit();

                $results = [
                    'code' => 200,
                    'message' => 'Data telah diupdate'
                ];
                
            }

            catch (\Exception $e) {
                $transaction->rollBack();
                $errors .= $e->getMessage();
                $results = [
                    'code' => 500,
                    'message' => $errors
                ];
                
            } catch (\Throwable $e) {
                $transaction->rollBack();
                $errors .= $e->getMessage();
                $results = [
                    'code' => 500,
                    'message' => $errors
                ];
            }

            
            

        }

        else
        {
            $results = [
                'code' => 500,
                'message' => 'Mahasiswa tidak ditemukan'
            ];
        }

        echo json_encode($results);
        exit;
    }


    public function actionAjaxUpdate()
    {
        $results = [];
        $dataPost = $_POST['dataPost'];
        
        if(empty($dataPost['mahasiswa'])){
            $results = [
                'code' => 400,
                'message' => 'Oops, silakan isi NIM atau Nama Mahasiswa '
            ];
            echo json_encode($results);
            exit;
        }

        if(empty($dataPost['ktp'])){
            $results = [
                'code' => 400,
                'message' => 'Oops, silakan isi NIK atau No KTP '
            ];
            echo json_encode($results);
            exit;
        }

        $model = SimakMastermahasiswa::find()->where([
            'nim_mhs'=>$dataPost['mahasiswa'],
            'ktp' => $dataPost['ktp']
        ])->one();
        
        
        if(!empty($model))
        {   
            $nim = $model->nim_mhs;
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            $errors = '';

            try 
            {
                $ortu_id = Yii::$app->user->identity->id;

                $list_ortu = SimakMahasiswaOrtu::find()->where([
                    'nim' => $nim
                ])->all();
                
                foreach($list_ortu as $ortu){
                    $ortu->ortu_user_id = $ortu_id;
                    $ortu->save(false,['ortu_user_id']);
                }

                $transaction->commit();

                $results = [
                    'code' => 200,
                    'message' => 'Data Ananda telah berhasil diklaim'
                ];
                
            }

            catch (\Exception $e) {
                $transaction->rollBack();
                $errors .= $e->getMessage();
                $results = [
                    'code' => 500,
                    'message' => $errors
                ];
                
            } catch (\Throwable $e) {
                $transaction->rollBack();
                $errors .= $e->getMessage();
                $results = [
                    'code' => 500,
                    'message' => $errors
                ];
            }

            
            

        }

        else
        {
            $results = [
                'code' => 404,
                'message' => 'Mahasiswa dengan NIK dan NIM tersebut tidak ditemukan di database kami.'
            ];
        }

        echo json_encode($results);
        exit;
    }


    /**
     * Lists all SimakMahasiswaOrtu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SimakMahasiswaOrtuSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SimakMahasiswaOrtu model.
     * @param int $id ID
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
     * Creates a new SimakMahasiswaOrtu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SimakMahasiswaOrtu();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SimakMahasiswaOrtu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SimakMahasiswaOrtu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SimakMahasiswaOrtu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return SimakMahasiswaOrtu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SimakMahasiswaOrtu::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
