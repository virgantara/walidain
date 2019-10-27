<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SimakPencekalanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="simak-pencekalan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'tahun_id') ?>

    <?= $form->field($model, 'nim') ?>

    <?= $form->field($model, 'tahfidz') ?>

    <?= $form->field($model, 'adm') ?>

    <?php // echo $form->field($model, 'akpam') ?>

    <?php // echo $form->field($model, 'akademik') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
