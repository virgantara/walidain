<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\KomponenBiaya */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="komponen-biaya-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'tahun')->dropDownList($tahun,['prompt'=>'Pilih Tahun']) ?>

    <?= $form->field($model, 'kategori_id')->dropDownList($kategori,['prompt'=>'Pilih Kategori']) ?>
    <?= $form->field($model, 'prioritas')->dropDownList($list_prioritas) ?>

    
    <?= $form->field($model, 'kode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'biaya_awal')->textInput() ?>
    <?= $form->field($model, 'biaya_minimal')->textInput() ?>

  
    
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

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

', \yii\web\View::POS_READY);

?>