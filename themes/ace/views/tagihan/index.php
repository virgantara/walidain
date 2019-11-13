<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TagihanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tagihan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tagihan-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tagihan', ['tagihan/instant'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nim',
            'namaCustomer',
            'namaProdi',
            'namaKampus',
            'namaTahun',
            'namaKomponen',
            'semester',
            //'komponen_id',
            'nilai',
            'nilai_minimal',
            'terbayar',
            [
                
                'attribute' => 'urutan',
                'label' => 'Prioritas',
                'format' => 'raw',
                'filter'=>[
                    '1' => 'HIGH',
                    '2' => 'MED',
                    '3' => 'LOW',
                    '4' => 'SLIGHTLY LOW',
                    '5' => 'LOWEST',

                ],
                'value'=>function($model,$url){
                    $listPrioritas = [
                        '1' => 'HIGH',
                        '2' => 'MED',
                        '3' => 'LOW',
                        '4' => 'SLIGHTLY LOW',
                        '5' => 'LOWEST',

                    ];
                    
                    $label = $listPrioritas[$model->urutan];
                    
                    return '<button type="button" class="btn btn-success btn-sm" >
                               <span>'.$label.'</span>
                            </button>';
                    
                },
            ],
            [
                'attribute' => 'status_bayar',
                'label' => 'Status',
                'format' => 'raw',
                'filter'=>["1"=>"LUNAS","2"=>"CICILAN","0"=>"BELUM LUNAS"],
                'value'=>function($model,$url){

                    switch($model->status_bayar)
                    {
                        case 1 : 
                            $st = 'success';
                            $label = 'LUNAS';
                        break;
                        case 2 :
                            $st = 'warning';
                            $label = 'CICILAN'; 
                        break;
                        default:
                            $st = 'danger';
                            $label = 'BELUM LUNAS';
                        break;
                    }
                    
                    return '<button type="button" class="btn btn-'.$st.' btn-sm" >
                               <span>'.$label.'</span>
                            </button>';
                    
                },
            ],
            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    
                    'delete' => function ($model) {
                        return $model->status_bayar != 1;
                    },
                ]
            ],
        ],
    ]); ?>
</div>
