<?php

namespace app\controllers;

use Yii;
use app\models\SimakTahunakademik;
use app\models\SimakJadwal;
use app\models\SimakJadwalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * SimakJadwalController implements the CRUD actions for SimakJadwal model.
 */
class SimakJadwalController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\ForbiddenHttpException('You are not allowed to access this page');
                },
                'only' => ['beban-ajar'],
                'rules' => [
                    [
                        'actions' => ['beban-ajar'],
                        'allow' => true,
                        'roles' => ['admin','theCreator'],
                    ],
                    
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionBebanAjar()
    {
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }

        // $searchModel = new SimakJadwalSearch();
        $listTahun = SimakTahunakademik::find()->orderBy(['tahun_id'=>SORT_DESC])->all();
        
        $tahun_aktif = SimakTahunakademik::getTahunAktif();
        $tahun_id = $tahun_aktif->tahun_id;
        $results = [];
        $tahun_akademik = null;
        if(!empty($_GET['btn-cari']) && !empty($_GET['tahun_id']) && !empty($_GET['prodi']))
        {
            $tahun_id = $_GET['tahun_id'];
            $prodi = $_GET['prodi'];
            $tahun_akademik = SimakTahunakademik::find()->where(['tahun_id'=>$tahun_id])->one();

            $out = (new \yii\db\Query())
                ->select(['d.nidn', 'd.nama_dosen as nama'])
                ->from('simak_masterdosen d')
                ->where([
                    'd.status_aktif' => 'aktif',
                    'd.kode_prodi' => $prodi,
                ]);
            if(!empty($_GET['dosen']))
            {
                $out = $out->andWhere(['d.id' => $_GET['dosen']]);
            }

            $out = $out->groupBy(['d.nidn','d.nama_dosen'])
                ->orderBy(['d.nama_dosen'=>SORT_ASC])
                ->all();

            foreach($out as $d)
            {
                $j = SimakJadwal::find()->where([
                    'kode_dosen' => $d['nidn'],
                    'tahun_akademik' => $tahun_id
                ])->all();

                $results[] = [
                    'nidn' => $d['nidn'],
                    'nama' => $d['nama'],
                    'items' => $j
                ];
            }
        }


           
        return $this->render('beban_ajar', [
            'results' => $results,
            'tahun_id' => $tahun_id,
            'listTahun' => $listTahun,
            'tahun_akademik' => $tahun_akademik,
            // 'tahun_akademik_aktif' => $tahun_akademik_aktif,

        ]);
    }


    /**
     * Finds the SimakJadwal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SimakJadwal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SimakJadwal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
