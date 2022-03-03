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

$list_color = ['default','danger','warning','success','info','info'];

?>
<div class="tagihan-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?> <?=$tahun->nama;?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="table-responsive">
        <?php
                  foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                      echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
                  } ?>
          <?php
    $gridColumns = [
    [
        'class'=>'kartik\grid\SerialColumn',
        'contentOptions'=>['class'=>'kartik-sheet-style'],
        // 'width'=>'36px',
        'pageSummary'=>'Total',
        'pageSummaryOptions' => ['colspan' => 10],
        'header'=>'',
        'headerOptions'=>['class'=>'kartik-sheet-style']
    ],
            [
                'attribute' => 'nim',
                'header' => 'Nama Mahasiswa',
                'value' => function($data){
                    return $data->nim0->nama_mahasiswa;
                },
                'filter'=>\yii\helpers\ArrayHelper::map($list_anak,'nim_mhs','nama_mahasiswa'),
            ],
            
            [
                'attribute' => 'namaProdi',
                'filter' => $list_prodi
            ],
            [
                'attribute' => 'namaKampus',
                
                'filter'=>\yii\helpers\ArrayHelper::map($list_kampus,'kode_kampus','nama_kampus'),
                
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
                'value'=>function($model,$url) {

                    // $label = $listPrioritas[$model->urutan];
                    
                    return (!empty($model->komponen) ? $model->komponen->nama : '-');
                    
                },
            ],
            'namaSemester',
            //'komponen_id',
            [
                 'class' => 'kartik\grid\EditableColumn',
                'attribute'=>'nilai',
                'readonly' => true,
                'contentOptions' => ['class' => 'text-right'],
                'format' => ['decimal',2],
                
            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'nilai_minimal',
                'format' => ['decimal',2],
                'contentOptions' => ['class' => 'text-right'],
                'readonly' => !Yii::$app->user->can('admin'),
                'refreshGrid' => true,
                'editableOptions' => [
                    'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                    
                    'asPopover' => false
                    
                    
                ],
            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'terbayar',
                'format' => ['decimal',2],
                'contentOptions' => ['class' => 'text-right'],
                'refreshGrid' => true,
                'readonly' => !Yii::$app->user->can('admin'),
                'editableOptions' => [
                    'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                    
                    'asPopover' => false
                    
                    
                ],
            ],
            // [
                
            //     'attribute' => 'urutan',
            //     'label' => 'Prioritas',
            //     'format' => 'raw',
            //     'filter'=>[
            //         '1' => 'HIGH',
            //         '2' => 'MED',
            //         '3' => 'LOW',
            //         '4' => 'SLIGHTLY LOW',
            //         '5' => 'LOWEST',

            //     ],
            //     'value'=>function($model,$url) use ($list_color){
            //         $listPrioritas = [
            //             '1' => 'HIGH',
            //             '2' => 'MED',
            //             '3' => 'LOW',
            //             '4' => 'SLIGHTLY LOW',
            //             '5' => 'LOWEST',

            //         ];
            //         $label = $listPrioritas[$model->urutan];
            //         $st = $list_color[$model->urutan];
            //         return '<span class="label label-'.$st.'" >'.$label.'</span>';
                    
            //     },
            // ],
            [
                'attribute' => 'status_bayar',
                'label' => 'Status',
                'format' => 'raw',
                // 'filter'=>["1"=>"LUNAS","2"=>"CICILAN","3"=>"BELUM LUNAS"],
                'value'=>function($model,$url)use ($list_color){
                    $simbol = '';
                    switch($model->statusPembayaran)
                    {
                        case 1 : 
                            $st = 'success';
                            $simbol = '<i class="fa fa-check"></i>';
                            $label = 'LUNAS';
                        break;
                        case 2 :
                            $st = 'warning';
                            $simbol = '<i class="fa fa-exclamation"></i>';
                            $label = 'CICILAN'; 
                        break;
                        case 3 :
                            $st = 'danger';
                            $simbol = '<i class="fa fa-exclamation-triangle"></i>';
                            $label = 'BELUM LUNAS';
                        break;
                        default:
                            $st = '';
                            $label = '';
                        break;
                    }

                    
                    return '<span  class="label label-'.$st.' " >'.$simbol.' '.$label.'</span>';
                    
                },
            ],


            [
                'class' => 'yii\grid\ActionColumn',
                'template' => ' ',
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
        'responsiveWrap' => false,
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
              text: result.value.message,
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