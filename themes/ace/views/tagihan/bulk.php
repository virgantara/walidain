<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use kartik\depdrop\DepDrop;

// use app\models\TagihanSearch;

// use keygenqt\autocompleteAjax\AutocompleteAjax;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SalesStokGudangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Generate Bulk Tagihan';
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
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Fakultas</label>
        <div class="col-lg-2 col-sm-10">
          <select id="fakultas">
              
          </select>
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Tahun</label>
        <div class="col-lg-2 col-sm-10">
          <?= Html::dropDownList('tahun','',$tahun, ['prompt'=>'..Pilih Tahun..','id'=>'tahun_id']) ?>
        </div>
    </div>
     <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Komponen</label>
        <div class="col-lg-2 col-sm-10">
            <?= DepDrop::widget([
                'name' => 'komponen',
                'options' => ['id'=>'komponen_id'],
                'pluginOptions'=>[
                    'depends'=>['tahun_id'],
                    'placeholder' => 'Pilih Komponen...',
                    'url' => Url::to(['/tagihan/komponen-tahun'])
                ]   
            ]) ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Kelas</label>
        <div class="col-lg-2 col-sm-10">
          <select id="kampus">
              
          </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Semester Biaya</label>
        <div class="col-lg-2 col-sm-10">
          <?= Html::input('text','semester_biaya','', ['id'=>'semester_biaya']) ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Semester Mahasiswa</label>
        <div class="col-lg-2 col-sm-10">
          <?= Html::input('text','semester_mhs','', ['id'=>'semester_mhs']) ?>
        </div>
    </div>
    <div class="col-sm-2">
        
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

function generate(){
    let obj = new Object;
    obj.fakultas_id = $('#fakultas').val();
    obj.tahun_id = $('#tahun_id').val();
    obj.komponen_id = $('#komponen_id').val();
    obj.semester_biaya = $('#semester_biaya').val();
    obj.semester_mhs = $('#semester_mhs').val();
    obj.kampus = $('#kampus').val();

    $.ajax({
        type : 'POST',
        data : {
            Tagihan : obj
        },
        url : '/tagihan/generate',
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
function getListFakultas(){
    $.ajax({
        type : 'POST',
        url : '/api/list-fakultas',
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
            $('#fakultas').empty();
            var row = '';
                   
            $.each(data.values,function(i, obj){
                row += '<option value=\"'+obj.kode_fakultas+'\">'+obj.nama_fakultas+'</option>';
                
            });

           
            $('#fakultas').append(row);
            
        }

    });
}

$(document).ready(function(){
    
    getListFakultas();
    getListKampus();
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