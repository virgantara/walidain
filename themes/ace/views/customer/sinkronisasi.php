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

$this->title = 'Sinkronisasi Data Mahasiswa';
$this->params['breadcrumbs'][] = $this->title;
// $tahun_masuk = !empty($_GET['tahun_masuk']) ? $_GET['tahun_masuk'] : '';
?>
<div class="sales-stok-gudang-index">

    <h1><?= Html::encode($this->title) ?></h1>
  
    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['customer/sinkronisasi'],
        'options' => [
            'class' => 'form-horizontal'
        ]
    ]); ?>
    <?php
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
            echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";
        }
    ?>

    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Kampus</label>
        <div class="col-lg-2 col-sm-10">
          <select id="kampus" name="kampus" class="form-control">
              
          </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Prodi</label>
        <div class="col-lg-2 col-sm-10">
          <select id="prodi" name="prodi" class="form-control">
              
          </select>
        </div>
    </div>
   
    <div class="col-sm-2">
        
    </div>
<div class="col-sm-3">

    <div class="form-group">
        <?= Html::submitButton(' <i class="ace-icon fa fa-check bigger-110"></i>Sync', ['class' => 'btn btn-info']) ?>    
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
            $('#kampus').val(".(!empty($_GET['kampus']) ? $_GET['kampus'] : 0).");
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
            $('#prodi').val(".(!empty($_GET['prodi']) ? $_GET['prodi'] : 0).");
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