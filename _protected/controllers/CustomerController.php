<?php

namespace app\controllers;

use Yii;
use app\models\Customer;
use app\models\CustomerSearch;
use app\models\SimakMastermahasiswa;

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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionSinkronisasi()
    {

        $model = new Customer;

        if(!empty($_GET['kampus']) && !empty($_GET['prodi']))
        {
             $out = [];

            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);
            // $tahun = $_GET['tahun_masuk'];
            $kampus = $_GET['kampus'];
            $prodi = $_GET['prodi'];

            $response = $client->get('/m/kampus/prodi', [
                'kampus' => $kampus,
                'prodi' => $prodi,
            ])->send();
            
            if ($response->isOk) {
                $result = $response->data['values'];
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                $errors = '';
                try 
                {

                    foreach($result as $item)
                    {
                        $mhs = SimakMastermahasiswa::find()->where(['nim_mhs'=>$item['nim']])->one();
                        $header = '';
                        switch ($mhs->kampus) {
                            case 1:
                                $header = '751050';
                                $mhs->va_code = \app\helpers\MyHelper::parseVACode($header, $mhs->nim_mhs);
                                $mhs->save(false,['va_code']);
                            break;
                            case 3:
                            case 8:
                                $header = '751051';
                                $mhs->va_code = \app\helpers\MyHelper::parseVACode($header, $mhs->nim_mhs);
                                $mhs->save(false,['va_code']);
                                break;
                            
                            default:
                                # code...
                                break;
                        }

                        

                        $cust = Customer::findOne($item['nim']);
                        if(empty($cust))
                            $cust = new Customer;

                        $cust->custid = $item['nim'];
                        $cust->nama = $item['nm'];
                        $cust->va_code = $mhs->va_code;
                        $cust->kampus = $item['k'];
                        $cust->nama_kampus = $item['nmk'];
                        $cust->kode_prodi = $item['kdp'];
                        $cust->nama_prodi = $item['nmp'];
                        $cust->save();   
                        if($cust->validate())
                        {
                            $cust->save();
                        }

                        else
                        {
                            
                            foreach($cust->getErrors() as $attribute){
                                foreach($attribute as $error){
                                    $errors .= $error.' ';
                                }
                            }

                            throw new Exception;
                            
                        }

                    }

                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data successfully synced'));
                    $transaction->commit();
                } catch (\Exception $e) {
                    Yii::$app->session->setFlash('error', Yii::t('app', $errors));
                    $transaction->rollBack();
                    throw $e;
                } catch (\Throwable $e) {
                    Yii::$app->session->setFlash('error', Yii::t('app', $errors));
                    $transaction->rollBack();
                    throw $e;
                }
            }
            
        }

        return $this->render('sinkronisasi',[
            'model' => $model
        ]);
    }

    /**
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Customer model.
     * @param string $id
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
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Customer();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->custid]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->custid]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Customer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
