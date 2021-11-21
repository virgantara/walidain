<?php

namespace app\controllers;

use app\models\SimakMasterdosen;
use app\models\SimakMasterdosenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * SimakMasterdosenController implements the CRUD actions for SimakMasterdosen model.
 */
class SimakMasterdosenController extends Controller
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

    public function actionSubdosenTemp() {
        // Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $prodi = $parents[1];
                $tahun = $parents[0];
                $out = (new \yii\db\Query())
                    ->select(['d.id', 'd.nama_dosen as name'])
                    ->from('simak_masterdosen d')
                    ->join('JOIN','simak_jadwal_temp j','j.kode_dosen = d.nidn')
                    ->where([
                      'j.prodi' => $prodi,
                      'j.tahun_akademik' => $tahun,

                    ])
                    ->groupBy(['d.id','d.nama_dosen'])
                    ->orderBy(['d.nama_dosen'=>SORT_ASC])
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
        // return ['output'=>'', 'selected'=>''];
    }

    
    /**
     * Finds the SimakMasterdosen model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SimakMasterdosen the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SimakMasterdosen::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
