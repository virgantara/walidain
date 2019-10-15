<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

// use app\models\TagihanSearch;

// use keygenqt\autocompleteAjax\AutocompleteAjax;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SalesStokGudangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Laporan Transaksi';
$this->params['breadcrumbs'][] = $this->title;

$model->tanggal_awal = !empty($_GET['Transaksi']['tanggal_awal']) ? $_GET['Transaksi']['tanggal_awal'] : date('Y-m-01');
$model->tanggal_akhir = !empty($_GET['Transaksi']['tanggal_akhir']) ? $_GET['Transaksi']['tanggal_akhir'] : date('Y-m-d');
?>
<div class="sales-stok-gudang-index">

    <h1><?= Html::encode($this->title) ?></h1>
  
    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['laporan/transaksi'],
        'options' => [
            'class' => 'form-horizontal'
        ]
    ]); ?>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Tanggal Awal</label>
        <div class="col-lg-2 col-sm-10">
          <?= yii\jui\DatePicker::widget(
            [
                'model' => $model,
                'attribute' => 'tanggal_awal',
            // 'value' => date('d-m-Y'),
            'options' => ['placeholder' => 'Pilih tanggal awal ...'],
            // 'formatter' => [
                'dateFormat' => 'php:d-m-Y',
                // 'todayHighlight' => true
            // ]
        ]      
    ) ?> 
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Tanggal Akhir</label>
        <div class="col-lg-2 col-sm-10">
          <?= yii\jui\DatePicker::widget(
            [
                'model' => $model,
                'attribute' => 'tanggal_akhir',
            // 'value' => date('d-m-Y'),
            'options' => ['placeholder' => 'Pilih tanggal akhir ...'],
            // 'formatter' => [
                'dateFormat' => 'php:d-m-Y',
                // 'todayHighlight' => true
            // ]
        ]      
    ) ?> 
        </div>
    </div>
    
    <div class="col-sm-2">
        
    </div>
<div class="col-sm-3">

    <div class="form-group">
        <?= Html::button(' <i class="ace-icon fa fa-check bigger-110"></i>Cari', ['class' => 'btn btn-info','id'=>'search','value'=>1]) ?>    
        <?= Html::submitButton(' <i class="ace-icon fa fa-check bigger-110"></i>Export XLS', ['class' => 'btn btn-success','name'=>'export','value'=>1]) ?>    
        <div class="lds-facebook" id="loading" style="height: 32px;display: none"><div></div><div></div><div></div></div>
    </div>

</div>
     


    <?php ActiveForm::end(); ?>

<table id="tabel_transaksi" class="table table-bordered table-striped">
    
    
</table>
   
</div>
</div>
<?php

$uid = !empty($_GET['unit_id']) ? $_GET['unit_id'] : '';
$script = "

function getTransaksi(){
    let sd = $('#transaksisearch-tanggal_awal').val();
    let ed = $('#transaksisearch-tanggal_akhir').val();
    
    $.ajax({
        type : 'POST',
        data : 'sd='+sd+'&ed='+ed,
        url : '/api/rekap-transaksi',
        beforeSend : function(){
            $('#loading').show();
        },
        error : function(err){
            console.log(err);
            $('#loading').hide();
        },
        success : function(data){
            
            var data = $.parseJSON(data);
            
            $('#loading').hide();  
            $('#tabel_transaksi').empty();
            var row = '<thead>';
                   row += '<tr><th>No</th><th>Trx Date</th><th>NIM</th><th>Nama</th><th>Prodi</th><th>No Ref</th><th>Nilai</th></tr>';
                row += '</thead>';
                row += '<tbody>';

            $.each(data.values,function(i, obj){
                row += '<tr>';
                row += '<td>'+eval(i+1)+'</td>';
                row += '<td>'+obj.d+'</td>';
                row += '<td>'+obj.nim+'</td>';
                row += '<td>'+obj.n+'</td>';
                row += '<td>'+obj.p+'</td>';
                row += '<td>'+obj.nr+'</td>';
               
                row += '<td style=\"text-align:right\">'+obj.nl+'</td>';
                row += '</tr>';
            });

            // row += '<tr>';
            // row += '<td colspan=\"4\"  style=\"text-align:right\">Total</td>';
            // row += '<td style=\"text-align:right\">'+data.total_sisa+'</td>';
            // row += '</tr>';            
            row += '</tbody>';
            $('#tabel_transaksi').append(row);
            
        }

    });
}



$(document).ready(function(){
    $('#search').click(function(){
        getTransaksi();
    });
});

";
$this->registerJs(
    $script,
    \yii\web\View::POS_READY
);
// $this->registerJs($script);
?>