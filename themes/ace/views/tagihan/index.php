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
            [
                'attribute' => 'komponen_id',
                'label' => 'Komponen',
                'format' => 'raw',
                'filter'=>ArrayHelper::map($listKomponen,'id','nama'),
                'value'=>function($model,$url){

                    
                    return $model->komponen->nama;
                    
                },
            ],
            'namaSemester',
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
                'filter'=>["1"=>"LUNAS","2"=>"CICILAN","3"=>"BELUM LUNAS"],
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
                        case 3 :
                            $st = 'danger';
                            $label = 'BELUM LUNAS';
                        break;
                        default:
                            $st = '';
                            $label = '';
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
                'template' => '{quick-update} {view} {update} {delete}',
                'visibleButtons' => [
                    
                    'delete' => function ($model) {
                        return $model->status_bayar != 1;
                    },
                ],
                'buttons' => [
                   
                    'quick-update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-edit"></span>', $url, [
                                   'title'        => 'Quick Set Terbayar',
                                   'class' => 'btn-quick-update',
                                   'data-item' => $model->id,
                                   
                                    
                        ]);
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    
                    if ($action === 'delete') {
                        $url =Url::to(['tagihan/delete','id'=>$model->id]);
                        return $url;
                    }

                    else if ($action === 'update') {
                        $url =Url::to(['tagihan/update','id'=>$model->id]);
                        return $url;
                    }

                    else if ($action === 'view') {
                        $url =Url::to(['tagihan/view','id'=>$model->id]);
                        return $url;
                    }

                    else if ($action === 'quick-update') {
                        
                        return "javascript:void(0)";
                    }
                  
                }
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
        'pjaxSettings' =>[
            'neverTimeout'=>true,
            'options'=>[
                'id'=>'pjax-container',
            ]
        ],  
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

$script = "


$(document).on('click','.btn-quick-update',function(e){

    e.preventDefault();
    var id = $(this).data('item');



    Swal.fire({
      title: 'Update nominal terbayar',
      input: 'text',
      inputAttributes: {
        autocapitalize: 'off'
      },
      showCancelButton: true,
      confirmButtonText: 'Update',
      showLoaderOnConfirm: true,
      preConfirm: (nominal) => {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: \"POST\",
                url: \"".Url::to(['tagihan/ajax-quick-update'])."\",
                data: { 'id': id,'nominal':nominal},
                cache: false,
                success: function(response) {
                    var hsl = $.parseJSON(response);
                    resolve(hsl)
                },
                failure: function (response) {
                    reject(500)
                }
            });

        })
        
        
      },
      allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
      if (result) {
        if(result.value.code == 200){
            Swal.fire({
              icon: 'success',
              title: 'Yeay...',
              text: 'Data updated!',
            })    ;
            $.pjax.reload({container:'#pjax-container'});
        }

        else if (result.value.code == 500){
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: result.value.message,
            })   ;
            $.pjax.reload({container:'#pjax-container'});
        }
        
      }
    })
});

";
$this->registerJs(
    $script,
    \yii\web\View::POS_READY
);
// $this->registerJs($script);
?>