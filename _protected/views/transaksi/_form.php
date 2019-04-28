<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Transaksi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transaksi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'CUSTID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'METODE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'TRXDATE')->textInput() ?>

    <?= $form->field($model, 'NOREFF')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'FIDBANK')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'KDCHANNEL')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'DEBET')->textInput() ?>

    <?= $form->field($model, 'KREDIT')->textInput() ?>

    <?= $form->field($model, 'REFFBANK')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'TRANSNO')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
