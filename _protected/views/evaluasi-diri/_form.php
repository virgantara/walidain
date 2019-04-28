<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EvaluasiDiri */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="evaluasi-diri-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'departemen_id')->textInput() ?>

    <?= $form->field($model, 'tanggal')->textInput() ?>

    <?= $form->field($model, 'strength')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'weakness')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'opportunity')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'threat')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
