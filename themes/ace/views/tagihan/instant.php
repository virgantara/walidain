<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use kartik\depdrop\DepDrop;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

use kartik\number\NumberControl;
// use app\models\TagihanSearch;

// use keygenqt\autocompleteAjax\AutocompleteAjax;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SalesStokGudangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Generate Tagihan Instant';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="sales-stok-gudang-index">

    <h1><?= Html::encode($this->title) ?></h1>
  
    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['laporan/pembayaran'],
        'options' => [
            'class' => 'form-horizontal'
        ]
    ]); ?>
    <div id="msg" style="display: none;"></div>
    
    
    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Tahun Aktif Tagihan</label>
        <div class="col-sm-9">
          <?= Html::textInput('tahun',$model->tahun, ['id'=>'tahun_id','class'=>'form-control','readonly'=>'readonly']) ?>
        </div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Komponen</label>
        <div class="col-sm-9">
            <?= Html::dropDownList('komponen','',$komponen,['id'=>'komponen_id','class'=>'form-control','prompt'=>'- Pilih Komponen -']);?>
                
        </div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Nilai Tagihan</label>
        <div class="col-sm-9">

          <?= NumberControl::widget([
            'name' => 'nilai',
            'value' => '',
            'maskedInputOptions' => [
                'prefix' => 'Rp ',
                'groupSeparator' => '.',
                'radixPoint' => ','
            ]
            ,'displayOptions'=>['id'=>'nilai_tagihan','class'=>'','name'=>'nilai']]) 
            ?>
        </div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Nilai Minimal</label>
        <div class="col-sm-9">
            <?= NumberControl::widget([
            'name' => 'nilai_minimal',
            'value' => '',
            'maskedInputOptions' => [
                'prefix' => 'Rp ',
                'groupSeparator' => '.',
                'radixPoint' => ','
            ]
            ,'displayOptions'=>['id'=>'nilai_minimal','class'=>'','name'=>'nilai_minimal']]) 
            ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> NIM</label>
        <div class="col-sm-9">
             <?php 
    AutoComplete::widget([
    'name' => 'nama_mhs',
    'id' => 'nama_mhs',
    'clientOptions' => [
         'source' =>new JsExpression('function(request, response) {
                        $.getJSON("'.Url::to(['api/ajax-cari-mhs/']).'", {
                            term: request.term
                        }, response);
             }'),
    // 'source' => Url::to(['api/ajax-pasien-daftar/']),
        'autoFill'=>true,
        'minLength'=>'1',
        'select' => new JsExpression("function( event, ui ) {
            if(ui.item.id != 0){
                $('#nim').val(ui.item.nim);
                $('#semester_biaya').val(ui.item.smt);
            }
            

         }")
    ],
    'options' => [
        'size' => '40'
    ]
 ]); 
 ?>  
        <input type="text" placeholder="Ketik Nama/NIM Mhs" class="form-control" id="nama_mhs" name="nama_mhs"/>
         <?= Html::input('hidden','semester_biaya','', ['id'=>'semester_biaya']) ?>
      <?= Html::input('hidden','nim','', ['id'=>'nim']) ?>
        </div>
    </div>
    
    <div class="col-sm-3">
        
    </div>
<div class="col-sm-3">

    <div class="form-group">
        <?= Html::button(' <i class="ace-icon fa fa-check bigger-110"></i>Generate', ['class' => 'btn btn-info','id'=>'generate','value'=>1]) ?>    
        
        <div class="lds-facebook" id="loading" style="height: 32px;display: none"><div></div><div></div><div></div></div>
    </div>

</div>
     


    <?php ActiveForm::end(); ?>

<table id="tabel_tagihan" class="table table-bordered table-striped">
    
    
</table>
   
</div>
</div>
<?php

$script = "

function generate(){
    let obj = new Object;
    obj.tahun_id = $('#tahun_id').val();
    obj.komponen_id = $('#komponen_id').val();
    obj.semester_biaya = $('#semester_biaya').val();
    obj.nim = $('#nim').val();

    $.ajax({
        type : 'POST',
        data : {
            Tagihan : obj
        },
        url : '/tagihan/generate-instant',
        beforeSend : function(){
            $('#msg').hide();
            $('#loading').show();
        },
        error : function(err){
            console.log(err);
            $('#loading').hide();
        },
        success : function(data){

            var data = $.parseJSON(data);
            $('#msg').show();
            $('#msg').html('<div class=\"alert alert-success\">Data sudah digenerate</div>');
            $('#loading').hide();  
            
        }

    });
}


function getKomponen(id){
    $.ajax({
        type : 'POST',
        url : '".Url::to(['komponen-biaya/ajax-get-komponen'])."',
        data : 'id='+id,
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
            $('#nilai_tagihan').val(data.b);
            $('#nilai_minimal').val(data.m);
        }

    });
}

$(document).ready(function(){
    
    $('#komponen_id').change(function(){
        var id = $(this).val();

        getKomponen(id);
    });
    $('#generate').click(function(){
        generate();
    });
});

";
$this->registerJs(
    $script,
    \yii\web\View::POS_READY
);
// $this->registerJs($script);
?>