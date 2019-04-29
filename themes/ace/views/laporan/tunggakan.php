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

$this->title = 'Laporan Tunggakan';
$this->params['breadcrumbs'][] = $this->title;

$model->tanggal_awal = !empty($_GET['Tagihan']['tanggal_awal']) ? $_GET['Tagihan']['tanggal_awal'] : date('Y-m-d');
$model->tanggal_akhir = !empty($_GET['Tagihan']['tanggal_akhir']) ? $_GET['Tagihan']['tanggal_akhir'] : date('Y-m-d');
?>
<div class="sales-stok-gudang-index">

    <h1><?= Html::encode($this->title) ?></h1>
  
    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['laporan/tunggakan'],
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
     <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Kampus</label>
        <div class="col-lg-2 col-sm-10">
          <select id="kampus">
              
          </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Prodi</label>
        <div class="col-lg-2 col-sm-10">
          <select id="prodi">
              
          </select>
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

<table id="tabel_tagihan" class="table table-bordered table-striped">
    
    
</table>
   
</div>
</div>
<?php

$uid = !empty($_GET['unit_id']) ? $_GET['unit_id'] : '';
$script = "

function getTagihan(){
    let sd = $('#tagihansearch-tanggal_awal').val();
    let ed = $('#tagihansearch-tanggal_akhir').val();
    let kampus = $('#kampus').val();
    let prodi = $('#prodi').val();
    $.ajax({
        type : 'POST',
        data : 'sd='+sd+'&ed='+ed+'&kampus='+kampus+'&prodi='+prodi,
        url : '/api/tunggakan',
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
            $('#tabel_tagihan').empty();
            var row = '<thead>';
                   row += '<tr><th>No</th><th>Komponen</th><th>Nama</th><th>Prodi</th><th>Semester</th><th>Nilai</th><th>Terbayar</th><th>Sisa Tagihan</th><th>Tanggal</th></tr>';
                row += '</thead>';
                row += '<tbody>';

            $.each(data.values,function(i, obj){
                row += '<tr>';
                row += '<td>'+eval(i+1)+'</td>';
                row += '<td>'+obj.komponen+'</td>';
                row += '<td>'+obj.nama_mahasiswa+'</td>';
                row += '<td>'+obj.prodi+'</td>';
                row += '<td>'+obj.semester+'</td>';
                row += '<td style=\"text-align:right\">'+obj.nilai+'</td>';
                row += '<td style=\"text-align:right\">'+obj.terbayar+'</td>';
                row += '<td style=\"text-align:right\">'+obj.sisa+'</td>';
                row += '<td>'+obj.created_at+'</td>';
                row += '</tr>';
            });

            row += '<tr>';
            row += '<td colspan=\"6\"  style=\"text-align:right\">Total</td>';
            row += '<td style=\"text-align:right\">'+data.total_terbayar+'</td>';
            row += '<td style=\"text-align:right\">'+data.total_sisa+'</td>';
            row += '<td>&nbsp;</td>';
            row += '</tr>';            
            row += '</tbody>';
            $('#tabel_tagihan').append(row);
            
        }

    });
}


function getListKampus(){
    $.ajax({
        type : 'POST',
        url : '/api/list-kampus',
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
            $('#kampus').empty();
            var row = '';
                   
            $.each(data.values,function(i, obj){
                row += '<option value=\"'+obj.kode_kampus+'\">'+obj.nama_kampus+'</option>';
                
            });

           
            $('#kampus').append(row);
            
        }

    });
}

function getListProdi(){
    $.ajax({
        type : 'POST',
        url : '/api/list-prodi',
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
            $('#prodi').empty();
            var row = '';
                   
            $.each(data.values,function(i, obj){
                row += '<option value=\"'+obj.kode_prodi+'\">'+obj.nama_prodi+'</option>';
                
            });

           
            $('#prodi').append(row);
            
        }

    });
}


$(document).ready(function(){
    getListKampus();
    getListProdi();
    $('#search').click(function(){
        getTagihan();
    });
});

";
$this->registerJs(
    $script,
    \yii\web\View::POS_READY
);
// $this->registerJs($script);
?>