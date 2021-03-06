<?php

use yii\helpers\Html;

use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TransaksiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transaksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaksi-index">

    <h1><?= Html::encode($this->title) ?></h1>
<p>
    <?= Html::a('Manual Jurnal Entry', ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'METODE',
                'label' => 'Metode',
                'format' => 'raw',
                'filter'=>["TOPUP"=>"TOPUP","PAYMENT"=>"PAYMENT","REVERSAL"=>"REVERSAL"],
                'value'=>function($model,$url){

                    
                    return $model->METODE;
                    
                },
            ],
            'CUSTID',
            'namaCustomer',
            [
                'attribute' => 'va_code',
                'value' => function($data){
                    return !empty($data->cUST) ? $data->cUST->va_code : '-';
                }
            ],
            [
                'attribute' => 'semester',
                'value' => function($data){
                    return !empty($data->cUST) ? $data->cUST->semester : '-';
                }
            ],
            
            [
                'attribute' => 'namaProdi',
                'label' => 'Prodi',
                'format' => 'raw',
                'filter'=>\yii\helpers\ArrayHelper::map(\app\models\SimakMasterprogramstudi::getProdiList(),'kode_prodi','nama_prodi'),
                'value' => function($data){
                    return !empty($data->cUST) && !empty($data->cUST->kodeProdi) ? $data->cUST->kodeProdi->nama_prodi : '-';
                }
            ],
            [
                'attribute'=>'namaKampus',
                'label' => 'Kampus',
                'filter' => \app\helpers\MyHelper::getKampusList(),
                'value' => function ($data) {
                    return !empty($data->cUST) && !empty($data->cUST->kampus0) ? $data->cUST->kampus0->nama_kampus : '-';
                },
                
            ],
            'TRXDATE',
            'NOREFF',
            //'FIDBANK',
            //'KDCHANNEL',
            [
                'attribute'=>'DEBET',
                'format' => ['decimal',2],
                'contentOptions' => ['class' => 'text-right'],
            ],
            [
                'attribute'=>'KREDIT',
                'format' => ['decimal',2],
                'contentOptions' => ['class' => 'text-right'],
            ],
            //'REFFBANK',
            //'TRANSNO',
            [
                'attribute' => 'tagihan_id',
                'format' => 'raw',
                'value'=>function($model,$url){

                    
                    return !empty($model->tagihan) ? $model->tagihan->namaKomponen : '-';
                    
                },
            ],
            'created_at',
            'updated_at',

            [
                'template' => ' {oppose} {view} {update} {delete}',
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'oppose'=>function ($url) {
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-copy']);
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'oppose') {
                        return Url::toRoute(['transaksi/oppose', 'id' => $model->urut]);
                    } else {
                        return Url::toRoute([$action, 'id' => $model->urut]);
                    }
                },
                'visibleButtons' => [
                    'view' => function ($model) {
                        return \Yii::$app->user->can('theCreator');
                    },
                    'update' => function ($model) {
                        return \Yii::$app->user->can('theCreator');
                    },
                    'delete' => function ($model) {
                        return \Yii::$app->user->can('admin');
                    },
                    'oppose' => function ($model) {
                        return \Yii::$app->user->can('admin');
                    },
                ],
            ]

    ];
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


";
$this->registerJs(
    $script,
    \yii\web\View::POS_READY
);
// $this->registerJs($script);
?>