<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use kartik\depdrop\DepDrop;

use kartik\number\NumberControl;
// use app\models\TagihanSearch;

// use keygenqt\autocompleteAjax\AutocompleteAjax;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SalesStokGudangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Generate Bulk Tagihan Per Angkatan / Tahun Masuk ';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="row">
    <div class="col-xs-6">
    <h1><?= Html::encode($this->title) ?></h1>
    <h2><?=$tahun->nama;?></h2>
  
    <?php $form = ActiveForm::begin([
        // 'method' => 'get',
        // 'action' => ['laporan/pembayaran'],
        'options' => [
            'id' => 'form-tagihan',
            'class' => 'form-horizontal'
        ]
    ]); 
    echo $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']);
    ?>
    <div id="msg" style="display: none;"></div>
    <?php  
     foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
         echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
     } ?>  <?= Html::hiddenInput('tahun',$model->tahun, ['id'=>'tahun_id','class'=>'form-control','readonly'=>'readonly']) ?>
        
    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Kampus</label>
        <div class="col-sm-9">
          <select id="kampus" name="kampus" class="form-control">
              
          </select>
        </div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Prodi</label>
        <div class="col-sm-9">
           <?= Html::dropDownList('prodi','',[],['id'=>'prodi','class'=>'form-control','prompt'=>'- Pilih Prodi -']);?>
        </div>
    </div>
   
    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Tahun Masuk</label>
        <div class="col-sm-9">
            <?= DepDrop::widget([
                'name' => 'tahun_masuk',
                'options' => ['id'=>'tahun_masuk','class'=>'form-control'],
                // 'pluginEvents'=> [
                //     "depdrop.afterChange"=>"function(event, id, value) { 
                //         console.log('value: ' + value + ' id: ' + id); 
                //     }"
                // ],
                'pluginOptions'=>[
                    'depends'=>['prodi','kampus'],
                    'initialize' => true,
                    'placeholder' => 'Pilih Tahun Masuk...',
                    'url' => Url::to(['/customer/subangkatan'])
                ]   
            ]) ?>

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
          <?= $form->field($model, 'nilai',['options'=>['tag'=>false]])->textInput(['class' => 'form-control','id'=>'nilai_tagihan','readonly'=>'readonly'])->label(false) ?>
        </div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Nilai Minimal</label>
        <div class="col-sm-9">
           <?= $form->field($model, 'nilai_minimal',['options'=>['tag'=>false]])->textInput(['class' => 'form-control','id'=>'nilai_minimal','readonly'=>'readonly'])->label(false) ?>
        </div>
    </div>
    <div class="col-sm-2">
        
    </div>
    <div class="col-sm-3">

        <div class="form-group">
            <?= Html::submitButton(' <i class="ace-icon fa fa-check bigger-110"></i>Generate', ['class' => 'btn btn-info','id'=>'generate','value'=>1]) ?>    
            
            <div class="lds-facebook" id="loading" style="height: 32px;display: none"><div></div><div></div><div></div></div>
        </div>

    </div>
     


    <?php ActiveForm::end(); ?>
 
</div>
</div>
<?php

$script = "


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


function getListKampus(){
    $.ajax({
        type : 'POST',
        url : '".Url::to(['/api/list-kampus'])."',
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
                row += '<option value=\"'+obj.kode_kampus+'\">'+obj.kode_kampus+' - '+obj.nama_kampus+'</option>';
                
            });

           
            $('#kampus').append(row);
            
        }

    });
}

function getListProdi(){
    $.ajax({
        type : 'POST',
        url : '".Url::to(['/api/list-prodi'])."',
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
            var row = '<option value=\"\">- Pilih Prodi -</option>';
                   
            $.each(data.values,function(i, obj){
                row += '<option value=\"'+obj.kode_prodi+'\">'+obj.nama_prodi+'</option>';
                
            });

           
            $('#prodi').append(row);
            
        }

    });
}

$(document).on('click','#generate',function(e){
    e.preventDefault();
    var obj = new Object;
    obj.prodi = $('#prodi').val();
    obj.kampus = $('#kampus').val();
    obj.tahun_masuk = $('#tahun_masuk').val();
    obj.komponen_id = $('#komponen_id').val();
    $.ajax({
        type : 'POST',
        data : {
            dataPost : obj
        },

        url : '".Url::to(['/customer/get-jumlah-mahasiswa-per-angkatan'])."',
        beforeSend : function(){
        },
        error : function(err){
            console.log(err);
        },
        success : function(data){

            var data = $.parseJSON(data);
            
            Swal.fire({
              title: 'Konfirmasi Pembuatan Tagihan!',
              text: 'Jumlah Mahasiswa yang akan ditagih ada '+data.jumlah+' orang. Setujui Pembuatan Tagihan ini?',
              icon: 'info',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Ya, Setujui!'
            }).then((result) => {
              if (result.value) {
                $('#form-tagihan').submit();
              }
            });
        }

    });
    
    
});

$(document).ready(function(){
    
    getListProdi();
    getListKampus();
    $('#komponen_id').change(function(){
        var id = $(this).val();

        getKomponen(id);
    });
});

";
$this->registerJs(
    $script,
    \yii\web\View::POS_READY
);
// $this->registerJs($script);
?>