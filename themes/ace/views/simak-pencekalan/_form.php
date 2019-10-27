<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SimakPencekalan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="simak-pencekalan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tahun_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nim')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tahfidz')->textInput() ?>

    <?= $form->field($model, 'adm')->textInput() ?>

    <?= $form->field($model, 'akpam')->textInput() ?>

    <?= $form->field($model, 'akademik')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
