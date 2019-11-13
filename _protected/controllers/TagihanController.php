<?php

namespace app\controllers;

use Yii;
use app\models\Tagihan;
use app\models\TagihanSearch;
use app\models\Tahun;
use app\models\KomponenBiaya;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use app\models\Customer;
use app\models\SimakMastermahasiswa;

/**
 * TagihanController implements the CRUD actions for Tagihan model.
 */
class TagihanController extends Controller
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

    public function actionDu()
    {
        $model = new Tagihan;
        $tahun = ArrayHelper::map(Tahun::find()->all(),'id',function($data){
            return $data->id.' - '.$data->nama.' / '.$data->hijriyah;
        });
        $komponen = ArrayHelper::map(KomponenBiaya::find()->all(),'id','nama');


        if(!empty($_POST['nilai']))
        {
            $query = SimakMastermahasiswa::find()->where([
                'kode_prodi' => $_POST['prodi'],
                'kampus' => $_POST['kampus'],
                'semester' => $_POST['semester_mhs']
            ]);

            $listCustomer = $query->all();
            $k = KomponenBiaya::findOne($_POST['komponen']);
            $transaction = \Yii::$app->db->beginTransaction();
            $errors = '';

            // print_r($listCustomer);exit;
            try 
            {
                foreach($listCustomer as $c)
                {

                    $t = Tagihan::find()->where([
                        'komponen_id' => $_POST['komponen'],
                        'nim' => $c->nim_mhs,
                        'tahun' => $_POST['tahun'],

                    ])->one();

                    if(!empty($t)) continue;
                    
                    $t = new Tagihan;
                    $t->tahun = $_POST['tahun'];
                    $t->komponen_id = $_POST['komponen'];
                    $t->nilai = $_POST['nilai'];
                    $t->nilai_minimal = $_POST['nilai_minimal'];
                    $t->urutan = $k->prioritas;
                    $t->nim = $c->nim_mhs;
                    $t->semester = $c->semester;


                    
                    if(!$t->save())
                    {
                        // print_r($c->attributes);exit;
                        $errors = \app\helpers\MyHelper::logError($t);
                        Yii::$app->session->setFlash('danger', $errors);

                        return $this->render('du',[
                            'model' => $model,
                            'tahun' => $tahun,
                            'komponen' => $komponen
                        ]);
                    }

                }

                Yii::$app->session->setFlash('success', " Data telah tersimpan");

                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                
                throw $e;
            }
        }

        return $this->render('du',[
            'model' => $model,
            'tahun' => $tahun,
            'komponen' => $komponen
        ]);
    }

    public function actionGenerateInstant(){
        $tahun_id = $_POST['Tagihan']['tahun_id'];
        $komponen_id = $_POST['Tagihan']['komponen_id'];
        $semester_biaya = $_POST['Tagihan']['semester_biaya'];
        $nim = $_POST['Tagihan']['nim'];
            
        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);

        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('/b/tagihan/generate/mhs')
            ->addHeaders(['content-type' => 'application/x-www-form-urlencoded'])
            ->setData([
                'tahun' => $tahun_id,
                'komponen_id' => $komponen_id,
                'semester_biaya' => $semester_biaya,
                'nim' => $nim
            ])
            ->send();

        
        if ($response->isOk) {
            $result = $response->data;
            $out['values'] = $result;
        }

        echo \yii\helpers\Json::encode($out);
    }

    public function actionGenerate(){
        $fakultas_id = $_POST['Tagihan']['fakultas_id'];
        $tahun_id = $_POST['Tagihan']['tahun_id'];
        $komponen_id = $_POST['Tagihan']['komponen_id'];
        $semester_biaya = $_POST['Tagihan']['semester_biaya'];
        $semester_mhs = $_POST['Tagihan']['semester_mhs'];
        $kampus = $_POST['Tagihan']['kampus'];
            
        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);

        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('/b/tagihan/generate')
            ->addHeaders(['content-type' => 'application/x-www-form-urlencoded'])
            ->setData([
                'fid'=>$fakultas_id,
                'tahun' => $tahun_id,
                'komponen_id' => $komponen_id,
                'semester_biaya' => $semester_biaya,
                'semester_mhs' => $semester_mhs,
                'kampus' => $kampus
            ])
            ->send();

        
        if ($response->isOk) {
            $result = $response->data;
            $out['values'] = $result;
        }

        echo \yii\helpers\Json::encode($out);
    }

    private function getSubcatList($id){
        $list = KomponenBiaya::find()->where(['tahun'=>$id])->all();
        $result = [];
        foreach($list as $item)
        {
            $result[] = [
                'id' => $item->id,
                'name' => $item->kode.' - '.$item->nama
            ];
        }

        return $result;
    }

    public function actionKomponenTahun() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = self::getSubCatList($cat_id); 
                // the getSubCatList function will query the database based on the
                // cat_id and return an array like below:
                // [
                //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
                //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
                // ]
                return ['output'=>$out, 'selected'=>''];
            }
        }
        return ['output'=>'', 'selected'=>''];
    }

    public function actionBulanan()
    {
        $model = new Tagihan;
        $tahun = ArrayHelper::map(Tahun::find()->all(),'id',function($data){
            return $data->nama.' - '.$data->hijriyah.' H';
        });
        $komponen = ArrayHelper::map(KomponenBiaya::find()->all(),'id','nama');


        if(!empty($_POST['nilai']))
        {


            $query = SimakMastermahasiswa::find()->where([
                'kode_prodi' => $_POST['prodi'],
                'kampus' => $_POST['kampus'],
                'semester' => $_POST['semester_mhs']
            ]);

            $listCustomer = $query->all();
            $k = KomponenBiaya::findOne($_POST['komponen']);
            $transaction = \Yii::$app->db->beginTransaction();
            $errors = '';
            try 
            {
                foreach($listCustomer as $c)
                {

                    $t = Tagihan::find()->where([
                        'komponen_id' => $_POST['komponen'],
                        'nim' => $c->nim_mhs,
                        'tahun' => $_POST['tahun'],

                    ])->one();

                    if(!empty($t)) continue;
                    
                    $t = new Tagihan;
                    $t->tahun = $_POST['tahun'];
                    $t->komponen_id = $_POST['komponen'];
                    $t->nilai = $_POST['nilai'];
                    $t->urutan = $k->prioritas;
                    $t->nim = $c->nim_mhs;
                    $t->semester = $c->semester;
                    if(!$t->save())
                    {
                        $errors = \app\helpers\MyHelper::logError($t);
                        Yii::$app->session->setFlash('danger', $errors);

                        return $this->render('bulanan',[
                            'model' => $model,
                            'tahun' => $tahun,
                            'komponen' => $komponen
                        ]);
                    }

                }

                Yii::$app->session->setFlash('success', " Data telah tersimpan");

                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                
                throw $e;
            }
        }

        return $this->render('bulanan',[
            'model' => $model,
            'tahun' => $tahun,
            'komponen' => $komponen
        ]);
    }

    public function actionBulk()
    {
        $model = new Tagihan;
        $tahun = ArrayHelper::map(Tahun::find()->all(),'id','nama');
        $komponen = ArrayHelper::map(KomponenBiaya::find()->all(),'id','nama');
        return $this->render('bulk',[
            'model' => $model,
            'tahun' => $tahun,
            'komponen' => $komponen
        ]);
    }

    public function actionInstant()
    {
        $model = new Tagihan;
        $tahun = ArrayHelper::map(Tahun::find()->all(),'id','nama');
        $komponen = ArrayHelper::map(KomponenBiaya::find()->all(),'id','nama');
        return $this->render('instant',[
            'model' => $model,
            'tahun' => $tahun,
            'komponen' => $komponen
        ]);
    }

    /**
     * Lists all Tagihan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TagihanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tagihan model.
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
     * Creates a new Tagihan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tagihan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Tagihan model.
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
     * Deletes an existing Tagihan model.
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
     * Finds the Tagihan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tagihan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tagihan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
