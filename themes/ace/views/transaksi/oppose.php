<?php

use yii\helpers\Html;

use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\Transaksi */

$this->title = 'Buat Lawan Transaksi dari '.$old->urut;
$this->params['breadcrumbs'][] = ['label' => 'Transaksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $old->urut, 'url' => ['view', 'id' => $old->urut]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transaksi-update">

    <h1><?= Html::encode($this->title) ?></h1>

     <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'CUSTID')->hiddenInput(['maxlength' => true])->label(false) ?>

    <?= $form->field($model, 'METODE')->dropDownlist(["TOPUP"=>"TOPUP","PAYMENT"=>"PAYMENT","REVERSAL"=>"REVERSAL"]) ?>

    <?= $form->field($model, 'TRXDATE')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'NOREFF')->hiddenInput(['maxlength' => true])->label(false) ?>

    <?= $form->field($model, 'FIDBANK')->hiddenInput(['maxlength' => true])->label(false) ?>

    <?= $form->field($model, 'KDCHANNEL')->hiddenInput(['maxlength' => true])->label(false) ?>

    <?= $form->field($model, 'DEBET')->textInput() ?>

    <?= $form->field($model, 'KREDIT')->textInput() ?>

    <?= $form->field($model, 'REFFBANK')->hiddenInput(['maxlength' => true])->label(false) ?>

    <?= $form->field($model, 'TRANSNO')->hiddenInput(['maxlength' => true])->label(false) ?>

  

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
