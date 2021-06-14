<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">

    <h1><?= Html::encode($this->title) ?></h1>

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
        'nim_mhs',
        
        'nama_mahasiswa',
        'tgl_lahir',
        [
            'attribute' => 'jenis_kelamin',
            'format' => 'raw',
            'filter' => ['L'=>'L','P'=>'P'],
            'value' => function($data){
                return $data->jenis_kelamin;
            }
        ],
        [
            'attribute'=>'kampus',
            'filter' => \app\helpers\MyHelper::getKampusList(),
            'value' => function ($data) {
                return $data->kampus0->nama_kampus;
            },
            'contentOptions'=>function($model, $key, $index, $column) {
                return ['class'=>\app\helpers\CssHelper::roleCss($model->kampus)];
            }
        ],
        
        //'kode_prodi',
        [
            'attribute' => 'kode_prodi',
            'label' => 'Prodi',
            'format' => 'raw',
            'filter'=>\yii\helpers\ArrayHelper::map(\app\models\SimakMasterprogramstudi::getProdiList(),'kode_prodi','nama_prodi'),
            'value'=>function($model,$url){

                
                return $model->kodeProdi->nama_prodi;
                
            },
        ],
        // 'kampus',
        'semester',
         [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'va_code',
            'readonly' => !Yii::$app->user->can('theCreator'),
            'editableOptions' => [
                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                
                'asPopover' => false
                
                
            ],
        ],
        [
            'attribute'=>'status_aktivitas',
            'filter' => \app\helpers\MyHelper::getStatusAktivitas(),
            'value' => function ($data) {
                $tmp = \app\helpers\MyHelper::getStatusAktivitas();
                return $tmp[$data->status_aktivitas];
            },
            'contentOptions'=>function($model, $key, $index, $column) {
                return ['class'=>\app\helpers\CssHelper::roleCss($model->status_aktivitas)];
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {detil}',
            'buttons' => [
               
                'detil' => function ($url, $model) {
                                 return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url , ['class' => 'detil', 'data-pjax' => '0']);
                            },
            ],
            // 'urlCreator' => function ($action, $model, $key, $index) {
                    
            //     if ($action === 'view') {
            //         $url =\yii\helpers\Url::to(['customer/view','id'=>$model->id]);
            //         return $url;
            //     }

            //     else if ($action === 'detil') {
                    
            //         return "javascript:void(0)";
            //     }
              
            // }
        ]
    ]
    ?>
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
<?php
    yii\bootstrap\Modal::begin(['id' =>'pModal']);
    yii\bootstrap\Modal::end();

$this->registerJs(
 "$(document).on('ready pjax:success', function() {  // 'pjax:success' use if you have used pjax
    $('.detil').click(function(e){
       e.preventDefault();      
       $('#pModal').modal('show')
                  .find('.modal-content')
                  .load($(this).attr('href'));  
   });
});
"
);
?>

