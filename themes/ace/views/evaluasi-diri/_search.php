<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EvaluasiDiriSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="evaluasi-diri-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'departemen_id') ?>

    <?= $form->field($model, 'tanggal') ?>

    <?= $form->field($model, 'strength') ?>

    <?= $form->field($model, 'weakness') ?>

    <?php // echo $form->field($model, 'opportunity') ?>

    <?php // echo $form->field($model, 'threat') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
