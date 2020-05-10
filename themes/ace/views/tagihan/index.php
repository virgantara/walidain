<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TagihanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tagihan Pembayaran Mahasiswa';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tagihan-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?> <?=$tahun->nama;?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tagihan', ['tagihan/instant'], ['class' => 'btn btn-success']) ?>
    </p>
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
             'nim',
            'namaCustomer',
            'namaProdi',
            [
                'attribute' => 'namaKampus',
                'label' => 'Kampus',
                'format' => 'raw',
                'filter'=>["Siman"=>"Siman","Mantingan"=>"Mantingan","Mantingan Reguler"=>"Mantingan Reguler"],
                'value'=>function($model,$url){

                    
                    return $model->namaKampus;
                    
                },
            ],
            [
                'attribute' => 'tahun',
                'label' => 'Tahun',
                'format' => 'raw',
                'filter'=>ArrayHelper::map($listTahun,'id','nama'),
                'value'=>function($model,$url){

                    
                    return $model->tahun;
                    
                },
            ],
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

                    switch($model->statusPembayaran)
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
            'created_at',
            'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    
                    'delete' => function ($model) {
                        return $model->status_bayar != 1;
                    },
                ],
                // 'buttons' => [
                   
                //     'delete' => function ($url, $model) {
                //         return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                //                    'title'        => 'delete',
                //                     'onclick' => "
                //                     if (confirm('Buang data ini?')) {
                //                         $.ajax('$url', {
                //                             type: 'POST'
                //                         }).done(function(data) {
                //                             $.pjax.reload({container: '#pjax-container'});
                                            
                //                         });
                //                     }
                //                     return false;
                //                 ",
                //                     // 'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                //                     // 'data-method'  => 'post',
                //         ]);
                //     }
                // ],
                // 'urlCreator' => function ($action, $model, $key, $index) {
                    
                //     if ($action === 'delete') {
                //         $url =Url::to(['tagihan/delete','id'=>$model->id]);
                //         return $url;
                //     }

                //     else if ($action === 'update') {
                //         $url =Url::to(['tagihan/update','id'=>$model->id]);
                //         return $url;
                //     }

                //     else if ($action === 'view') {
                //         $url =Url::to(['tagihan/view','id'=>$model->id]);
                //         return $url;
                //     }

                  
                // }
            ],
            
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
                    ['content'=> $this->title, 'options'=>['colspan'=>16, 'class'=>'text-center warning']], //cuma satu 
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
