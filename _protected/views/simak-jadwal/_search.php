<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SimakJadwalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="simak-jadwal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'hari') ?>

    <?= $form->field($model, 'jam') ?>

    <?= $form->field($model, 'kode_mk') ?>

    <?= $form->field($model, 'matkul_id') ?>

    <?php // echo $form->field($model, 'kode_dosen') ?>

    <?php // echo $form->field($model, 'kode_pengampu_nidn') ?>

    <?php // echo $form->field($model, 'semester') ?>

    <?php // echo $form->field($model, 'kelas') ?>

    <?php // echo $form->field($model, 'fakultas') ?>

    <?php // echo $form->field($model, 'prodi') ?>

    <?php // echo $form->field($model, 'kd_ruangan') ?>

    <?php // echo $form->field($model, 'tahun_akademik') ?>

    <?php // echo $form->field($model, 'kuota_kelas') ?>

    <?php // echo $form->field($model, 'kampus') ?>

    <?php // echo $form->field($model, 'presensi') ?>

    <?php // echo $form->field($model, 'materi') ?>

    <?php // echo $form->field($model, 'bobot_formatif') ?>

    <?php // echo $form->field($model, 'bobot_uts') ?>

    <?php // echo $form->field($model, 'bobot_uas') ?>

    <?php // echo $form->field($model, 'bobot_harian1') ?>

    <?php // echo $form->field($model, 'bobot_harian') ?>

    <?php // echo $form->field($model, 'jadwal_temp_id') ?>

    <?php // echo $form->field($model, 'jumlah_tatap_muka') ?>

    <?php // echo $form->field($model, 'kode_feeder') ?>

    <?php // echo $form->field($model, 'a_selenggara_pditt') ?>

    <?php // echo $form->field($model, 'bahasan_case') ?>

    <?php // echo $form->field($model, 'lingkup_kelas') ?>

    <?php // echo $form->field($model, 'mode_kuliah') ?>

    <?php // echo $form->field($model, 'tgl_mulai_koas') ?>

    <?php // echo $form->field($model, 'tgl_selesai_koas') ?>

    <?php // echo $form->field($model, 'classroom_id') ?>

    <?php // echo $form->field($model, 'alternateLink') ?>

    <?php // echo $form->field($model, 'enrollment_code') ?>

    <?php // echo $form->field($model, 'tgl_tutup_daftar') ?>

    <?php // echo $form->field($model, 'kapasitas') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
