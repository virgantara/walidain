<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SimakTahunakademikSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="simak-tahunakademik-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'tahun_id') ?>

    <?= $form->field($model, 'tahun') ?>

    <?= $form->field($model, 'semester') ?>

    <?= $form->field($model, 'nama_tahun') ?>

    <?php // echo $form->field($model, 'krs_mulai') ?>

    <?php // echo $form->field($model, 'krs_selesai') ?>

    <?php // echo $form->field($model, 'krs_online_mulai') ?>

    <?php // echo $form->field($model, 'krs_online_selesai') ?>

    <?php // echo $form->field($model, 'krs_ubah_mulai') ?>

    <?php // echo $form->field($model, 'krs_ubah_selesai') ?>

    <?php // echo $form->field($model, 'kss_cetak_mulai') ?>

    <?php // echo $form->field($model, 'kss_cetak_selesai') ?>

    <?php // echo $form->field($model, 'cuti') ?>

    <?php // echo $form->field($model, 'mundur') ?>

    <?php // echo $form->field($model, 'bayar_mulai') ?>

    <?php // echo $form->field($model, 'bayar_selesai') ?>

    <?php // echo $form->field($model, 'kuliah_mulai') ?>

    <?php // echo $form->field($model, 'kuliah_selesai') ?>

    <?php // echo $form->field($model, 'uts_mulai') ?>

    <?php // echo $form->field($model, 'uts_selesai') ?>

    <?php // echo $form->field($model, 'uas_mulai') ?>

    <?php // echo $form->field($model, 'uas_selesai') ?>

    <?php // echo $form->field($model, 'nilai') ?>

    <?php // echo $form->field($model, 'akhir_kss') ?>

    <?php // echo $form->field($model, 'proses_buka') ?>

    <?php // echo $form->field($model, 'proses_ipk') ?>

    <?php // echo $form->field($model, 'proses_tutup') ?>

    <?php // echo $form->field($model, 'buka') ?>

    <?php // echo $form->field($model, 'syarat_krs') ?>

    <?php // echo $form->field($model, 'syarat_krs_ips') ?>

    <?php // echo $form->field($model, 'cuti_selesai') ?>

    <?php // echo $form->field($model, 'max_sks') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
