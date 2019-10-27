<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SimakTahunakademik */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="simak-tahunakademik-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tahun_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tahun')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'semester')->textInput() ?>

    <?= $form->field($model, 'nama_tahun')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'krs_mulai')->textInput() ?>

    <?= $form->field($model, 'krs_selesai')->textInput() ?>

    <?= $form->field($model, 'krs_online_mulai')->textInput() ?>

    <?= $form->field($model, 'krs_online_selesai')->textInput() ?>

    <?= $form->field($model, 'krs_ubah_mulai')->textInput() ?>

    <?= $form->field($model, 'krs_ubah_selesai')->textInput() ?>

    <?= $form->field($model, 'kss_cetak_mulai')->textInput() ?>

    <?= $form->field($model, 'kss_cetak_selesai')->textInput() ?>

    <?= $form->field($model, 'cuti')->textInput() ?>

    <?= $form->field($model, 'mundur')->textInput() ?>

    <?= $form->field($model, 'bayar_mulai')->textInput() ?>

    <?= $form->field($model, 'bayar_selesai')->textInput() ?>

    <?= $form->field($model, 'kuliah_mulai')->textInput() ?>

    <?= $form->field($model, 'kuliah_selesai')->textInput() ?>

    <?= $form->field($model, 'uts_mulai')->textInput() ?>

    <?= $form->field($model, 'uts_selesai')->textInput() ?>

    <?= $form->field($model, 'uas_mulai')->textInput() ?>

    <?= $form->field($model, 'uas_selesai')->textInput() ?>

    <?= $form->field($model, 'nilai')->textInput() ?>

    <?= $form->field($model, 'akhir_kss')->textInput() ?>

    <?= $form->field($model, 'proses_buka')->textInput() ?>

    <?= $form->field($model, 'proses_ipk')->textInput() ?>

    <?= $form->field($model, 'proses_tutup')->textInput() ?>

    <?= $form->field($model, 'buka')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'syarat_krs')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'syarat_krs_ips')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cuti_selesai')->textInput() ?>

    <?= $form->field($model, 'max_sks')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
