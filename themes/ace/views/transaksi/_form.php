<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model app\models\Transaksi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transaksi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'METODE')->dropDownList(['TOPUP'=>'TOPUP','PAYMENT'=>'PAYMENT']) ?>
        <?php 
    AutoComplete::widget([
    'name' => 'nama_mhs',
    'id' => 'nama_mhs',
    'clientOptions' => [
         'source' =>new JsExpression('function(request, response) {
                        $.getJSON("'.Url::to(['api/ajax-cari-mhs/']).'", {
                            term: request.term
                        }, response);
             }'),
    // 'source' => Url::to(['api/ajax-pasien-daftar/']),
        'autoFill'=>true,
        'minLength'=>'1',
        'select' => new JsExpression("function( event, ui ) {
            if(ui.item.id != 0){
                $('#nim').val(ui.item.nim);
            }
            

         }")
    ],
    'options' => [
        'size' => '40'
    ]
 ]); 
 ?> 
    <div class="form-group"> 
    <label>NIM</label>
    <input type="text" placeholder="Ketik Nama/NIM Mhs" class="form-control" id="nama_mhs" name="nama_mhs"/>
    </div>  
    <?= $form->field($model, 'CUSTID')->hiddenInput(['maxlength' => true,'id' => 'nim'])->label(false) ?>
    
    <?php
    echo $form->field($model, 'TRXDATE')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => 'Enter trx date ...'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd hh:ii:ss'
        ]
    ]);?>


    <?= $form->field($model, 'NOREFF')->textInput(['maxlength' => true]) ?>



    <?= $form->field($model, 'DEBET')->textInput() ?>

    <?= $form->field($model, 'KREDIT')->textInput() ?>

    <?= $form->field($model, 'keterangan')->textArea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
