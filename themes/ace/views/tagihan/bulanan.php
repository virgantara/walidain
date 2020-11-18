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

$this->title = 'Generate Bulk Tagihan Bulanan';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="sales-stok-gudang-index">

    <h1><?= Html::encode($this->title) ?></h1>
  
    <?php $form = ActiveForm::begin([
        // 'method' => 'get',
        // 'action' => ['laporan/pembayaran'],
        'options' => [
            'class' => 'form-horizontal'
        ]
    ]); ?>
    <div id="msg" style="display: none;"></div>
      <?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <i class="icon fa fa-check"></i><?= Yii::$app->session->getFlash('success') ?>
         
    </div>
<?php endif; ?>
 <?php if (Yii::$app->session->hasFlash('danger')): ?>
    <div class="alert alert-danger alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <i class="icon fa fa-check"></i><?= Yii::$app->session->getFlash('danger') ?>
         
    </div>
<?php endif; ?>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Kelas</label>
        <div class="col-lg-2 col-sm-10">
          <select id="kampus" name="kampus">
              
          </select>
        </div>
    </div>
     <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Prodi</label>
        <div class="col-lg-2 col-sm-10">
          <select id="prodi" name="prodi">
              
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
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Semester</label>
        <div class="col-lg-2 col-sm-10">
          <?= Html::input('text','semester_mhs','', ['id'=>'semester_mhs']) ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1">Nilai Tagihan</label>
        <div class="col-lg-2 col-sm-10">
          <?= Html::input('text','nilai','') ?>
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
    
    getListProdi();
    getListKampus();
    // $('#generate').click(function(){
    //     generate();
    // });
});

";
$this->registerJs(
    $script,
    \yii\web\View::POS_READY
);
// $this->registerJs($script);
?>