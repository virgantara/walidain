<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TransaksiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transaksi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'urut') ?>

    <?= $form->field($model, 'CUSTID') ?>

    <?= $form->field($model, 'METODE') ?>

    <?= $form->field($model, 'TRXDATE') ?>

    <?= $form->field($model, 'NOREFF') ?>

    <?php // echo $form->field($model, 'FIDBANK') ?>

    <?php // echo $form->field($model, 'KDCHANNEL') ?>

    <?php // echo $form->field($model, 'DEBET') ?>

    <?php // echo $form->field($model, 'KREDIT') ?>

    <?php // echo $form->field($model, 'REFFBANK') ?>

    <?php // echo $form->field($model, 'TRANSNO') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
