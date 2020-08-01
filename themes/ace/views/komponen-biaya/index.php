<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KomponenBiayaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Komponen Biaya';
$this->params['breadcrumbs'][] = $this->title;

$list_prioritas = [
    '1' => 'HIGH',
    '2' => 'MED',
    '3' => 'LOW',
    '4' => 'SLIGHTLY LOW',
    '5' => 'LOWEST',

];
?>
<div class="komponen-biaya-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Komponen Biaya', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<div class="table-responsive">

        <?php
    $gridColumns = [
    [
        'class'=>'kartik\grid\SerialColumn',
        'contentOptions'=>['class'=>'kartik-sheet-style'],
        'width'=>'36px',
        'pageSummary'=>'Total',
        'pageSummaryOptions' => ['colspan' => 6],
        'header'=>'',
        'headerOptions'=>['class'=>'kartik-sheet-style']
    ],
            
            [
                'attribute'=>'kampus_id',
                'filter' => $searchModel->kampusList,
                'value' => function ($data) {
                    return !empty($data->kampus) ? $data->kampus->nama_kampus : '(not set)';
                },
                // 'contentOptions'=>function($model, $key, $index, $column) {
                //     return ['class'=>CssHelper::userStatusCss($model->status)];
                // }
            ],
            [
                'attribute'=>'bulan_id',
                'filter' => $searchModel->bulanList,
                'value' => function ($data) {
                    return !empty($data->bulan) ? $data->bulan->nama : '(not set)';
                },
                // 'contentOptions'=>function($model, $key, $index, $column) {
                //     return ['class'=>CssHelper::userStatusCss($model->status)];
                // }
            ],
            [
                'attribute'=>'tahun',
                'filter' => $searchModel->tahunList,
                'value' => function ($data) {
                    return $data->tahun;
                },
               
            ],

            [
                'attribute'=>'kategori_id',
                'filter' => $searchModel->kategoriList,
                'value' => function ($data) {
                    return $data->kategori->nama;
                },
                // 'contentOptions'=>function($model, $key, $index, $column) {
                //     return ['class'=>CssHelper::userStatusCss($model->status)];
                // }
            ],

            'kode',
            'nama',
            // 'periode_tagihan_id',
            
            'biaya_awal',
            'biaya_minimal',
           [
                'attribute'=>'prioritas',
                'filter' => $list_prioritas,
                'value' => function ($data) {
                    $list_prioritas = [
                        '1' => 'HIGH',
                        '2' => 'MED',
                        '3' => 'LOW',
                        '4' => 'SLIGHTLY LOW',
                        '5' => 'LOWEST',

                    ];
                    return $list_prioritas[$data->prioritas];
                },
            ],
            
            
    ['class' => 'yii\grid\ActionColumn']
];?>    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'containerOptions' => ['style' => 'overflow: auto'], 
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'containerOptions' => ['style'=>'overflow: auto'], 
        'beforeHeader'=>[
            [
                'columns'=>[
                    ['content'=> $this->title, 'options'=>['colspan'=>14, 'class'=>'text-center warning']], //cuma satu 
                ], 
                'options'=>['class'=>'skip-export'] 
            ]
        ],
        'exportConfig' => [
              GridView::PDF => ['label' => 'Save as PDF'],
              GridView::EXCEL => ['label' => 'Save as EXCEL'], //untuk menghidupkan button export ke Excell
              GridView::HTML => ['label' => 'Save as HTML'], //untuk menghidupkan button export ke HTML
              GridView::CSV => ['label' => 'Save as CSV'], //untuk menghidupkan button export ke CVS
          ],
          
        'toolbar' =>  [
            '{export}', 

           '{toggleData}' //uncoment untuk menghidupkan button menampilkan semua data..
        ],
        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
    // set export properties
        'export' => [
            'fontAwesome' => true
        ],
        'pjax' => true,
        'bordered' => true,
        'striped' => true,
        // 'condensed' => false,
        // 'responsive' => false,
        'hover' => true,
        // 'floatHeader' => true,
        // 'showPageSummary' => true, //true untuk menjumlahkan nilai di suatu kolom, kebetulan pada contoh tidak ada angka.
        'panel' => [
            'type' => GridView::TYPE_PRIMARY
        ],
    ]); ?>
</div>

</div>
