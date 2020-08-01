<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\number\NumberControl;

/* @var $this yii\web\View */
/* @var $model app\models\KomponenBiaya */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="row">
    <div class="col-xs-12">

        <?php $form = ActiveForm::begin(); ?>

        <?=$form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']);?>
        
        <?= $form->field($model, 'kampus_id')->dropDownList($listKampus,['id'=>'kampus','prompt'=>'- Pilih Kampus -']) ?>

        <?= $form->field($model, 'bulan_id')->dropDownList($listBulan,['prompt'=>'Pilih Bulan']) ?>

        <?= $form->field($model, 'tahun')->dropDownList($tahun,['prompt'=>'Pilih Tahun']) ?>

        <?= $form->field($model, 'semester')->dropDownList(\app\helpers\MyHelper::getListSemester(),['prompt'=>'- Pilih Semester -']) ?>

        <?= $form->field($model, 'kategori_id')->dropDownList($kategori,['prompt'=>'Pilih Kategori']) ?>
        <?= $form->field($model, 'prioritas')->dropDownList($list_prioritas) ?>

        
        
        <?= $form->field($model, 'kode')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>


        <?= $form->field($model, 'biaya_awal')->widget(NumberControl::className(),[
            'maskedInputOptions' => [
                'prefix' => 'Rp ',
                'groupSeparator' => '.',
                'radixPoint' => ','
            ]
        ]) ?>
        <?= $form->field($model, 'biaya_minimal')->widget(NumberControl::className(),[
            'maskedInputOptions' => [
                'prefix' => 'Rp ',
                'groupSeparator' => '.',
                'radixPoint' => ','
            ]
        ]) ?>

      
        
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>


<?php 

$this->registerJs(' 

function pad(n, width, z) {
  z = z || \'0\';
  n = n + \'\';
  return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
}


$("#komponenbiaya-kategori_id").change(function(){
    $("#komponenbiaya-kode").val(pad($(this).val(),2));
});

$(document).on("keyup","#komponenbiaya-biaya_awal-disp",function(){
    $("#komponenbiaya-biaya_minimal-disp").val($(this).val());
});

$(\'#kampus\').change(function(){
    getListProdi($(this).val());
});
getListKampus();
$(\'#komponen_id\').change(function(){
    var id = $(this).val();

    getKomponen(id);
});

function getListKampus(){
    $.ajax({
        type : \'POST\',
        url : "'.Url::to(['/api/list-kampus']).'",
        beforeSend : function(){
            $(\'#loading\').show();
        },
        error : function(err){
            console.log(err);
            $(\'#loading\').hide();
        },
        success : function(data){

            var data = $.parseJSON(data);
            
            $(\'#loading\').hide();  
            $(\'#kampus\').empty();
             var row = \'<option value=\"\">- Pilih Kampus -</option>\';
                   
            $.each(data.values,function(i, obj){
                row += \'<option value="\'+obj.kode_kampus+\'">\'+obj.kode_kampus+\' - \'+obj.nama_kampus+\'</option>\';
                
            });

           
            $(\'#kampus\').append(row);
            
        }

    });
}

function getListProdi(kampus){
    $.ajax({
        type : \'POST\',
        url : "'.Url::to(['/api/list-prodi']).'",
        data : \'id=\'+kampus,
        beforeSend : function(){
            $(\'#loading\').show();
        },
        error : function(err){
            console.log(err);
            $(\'#loading\').hide();
        },
        success : function(data){

            var data = $.parseJSON(data);
            $(\'#loading\').hide();  
            $(\'#prodi\').empty();
            var row = \'<option value=\"\">- Pilih Prodi -</option>\';
                   
            $.each(data,function(i, obj){

                row += \'<option value="\'+obj.kode_prodi+\'">\'+obj.kode_prodi+\' - \'+obj.nama_prodi+\'</option>\';
                
            });

           
            $(\'#prodi\').append(row);
            
        }

    });
}

', \yii\web\View::POS_READY);

?>