<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tagihan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tagihan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'urutan')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'semester')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'tahun')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'nim')->textInput(['maxlength' => true,'readonly'=>true]) ?>

    <?= $form->field($model, 'namaKomponen')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'nilai')->textInput() ?>

    <?= $form->field($model, 'terbayar')->textInput() ?>

    <?php 
    $list_status = [
        0 => 'BELUM LUNAS',
        1 => 'LUNAS',
        2 => 'CICILAN'
    ];
    ?>
    <?= $form->field($model, 'status_bayar')->dropDownList($list_status) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
