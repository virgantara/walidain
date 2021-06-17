<?php 
use yii\helpers\Url;
use devgroup\dropzone\DropZone;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Update Tagihan';
 ?>


<div class="row">
   <div class="col-md-12">
        <div class="panel">
             <div class="panel-heading">
        
            </div>

            <div class="panel-body ">
            	<h1>Ekstensi file .xlsx. Format file: Kolom A adalah NIM, Kolom B adalah nilai tagihan mahasiswa, C nilai minimal, D terbayar</h1>
            	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
            	    <?php
			      foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
			          echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
			      } ?>
			      <?=$form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']);?>
            	
            	<?= $form->field($model, 'fileUpload')->fileInput(['maxlength' => true]) ?>

            	<div class="form-group">
			        <?= Html::submitButton('Upload Now', ['class' => 'btn btn-success']) ?>
			    </div>

			    <?php ActiveForm::end(); ?>

            </div>
        </div>

    </div>
</div>