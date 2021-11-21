<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SimakJadwal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="simak-jadwal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'hari')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jam')->textInput() ?>

    <?= $form->field($model, 'kode_mk')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'matkul_id')->textInput() ?>

    <?= $form->field($model, 'kode_dosen')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kode_pengampu_nidn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'semester')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kelas')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fakultas')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prodi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kd_ruangan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tahun_akademik')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kuota_kelas')->textInput() ?>

    <?= $form->field($model, 'kampus')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'presensi')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'materi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bobot_formatif')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bobot_uts')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bobot_uas')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bobot_harian1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bobot_harian')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jadwal_temp_id')->textInput() ?>

    <?= $form->field($model, 'jumlah_tatap_muka')->textInput() ?>

    <?= $form->field($model, 'kode_feeder')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'a_selenggara_pditt')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bahasan_case')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'lingkup_kelas')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mode_kuliah')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tgl_mulai_koas')->textInput() ?>

    <?= $form->field($model, 'tgl_selesai_koas')->textInput() ?>

    <?= $form->field($model, 'classroom_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alternateLink')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'enrollment_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tgl_tutup_daftar')->textInput() ?>

    <?= $form->field($model, 'kapasitas')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
