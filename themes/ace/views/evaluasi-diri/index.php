<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EvaluasiDiriSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Evaluasi Diri';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="evaluasi-diri-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Evaluasi Diri', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            
            [
                'header' => 'Departemen',
                'value' => function($model,$url){
                    $result = $model->departemen->nama;
                    
                    return $result;
                },
                'visible' => Yii::$app->user->can('admin')
            ],
            'tanggal',
            'strength:ntext',
            'weakness:ntext',
            'opportunity:ntext',
            'threat:ntext',
            //'created_at',
            'updated_at',
            
            [
                'attribute' => 'is_verified',
                'label' => 'Diverifikasi',
                'format' => 'raw',
                'filter'=>["1"=>"Sudah Diverifikasi","0"=>"Belum Diverifikasi","2"=>"Diproses"],
                'value'=>function($model,$url){

                    $st = '';
                    $label = '';

                    switch ($model->is_verified) {
                        case 1:
                            $label = 'Sudah Diverifikasi';
                            $st = 'success';
                            break;
                        case 2:
                            $label = 'Diproses';
                            $st = 'warning';
                            break;
                        default:
                            $label = 'Belum Diverifikasi';
                            $st = 'danger';
                            break;
                    }
                    
                    return '<button type="button" class="btn btn-'.$st.' btn-sm" >
                               <span>'.$label.'</span>
                            </button>';
                    
                },
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
