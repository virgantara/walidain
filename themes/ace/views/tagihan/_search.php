<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TagihanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tagihan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'urutan') ?>

    <?= $form->field($model, 'semester') ?>

    <?= $form->field($model, 'tahun') ?>

    <?= $form->field($model, 'nim') ?>

    <?php // echo $form->field($model, 'komponen_id') ?>

    <?php // echo $form->field($model, 'nilai') ?>

    <?php // echo $form->field($model, 'terbayar') ?>

    <?php // echo $form->field($model, 'edit') ?>

    <?php // echo $form->field($model, 'status_bayar') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
