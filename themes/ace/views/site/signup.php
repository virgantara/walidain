<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\SignupForm */

use kartik\password\PasswordInput;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Form Pendaftaran Aplikasi Walidain UNIDA Gontor');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="col-md-5 well bs-component">

        <p><?= Yii::t('app', 'Please fill out the following fields to signup:') ?></p>

        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
        <?php
          foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
              echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
          } ?>
          <?=$form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']);?>
                
            <?= $form->field($model, 'username')->textInput(
                ['placeholder' => Yii::t('app', 'Create your username'), 'autofocus' => true]) ?>

            <?= $form->field($model, 'email')->input('email', ['placeholder' => Yii::t('app', 'Enter your e-mail')]) ?>

            <?= $form->field($model, 'password')->widget(PasswordInput::classname(), 
                ['options' => ['placeholder' => Yii::t('app', 'Create your password')]]) ?>

            <div class="form-group">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    <div class="g-recaptcha" data-sitekey="<?=Yii::$app->params['reCaptcha']['site_key'];?>"></div>
                </div>
            </div>
            <br>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Signup'), 
                    ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>

        <?php if ($model->scenario === 'rna'): ?>
            <div style="color:#666;margin:1em 0">
                <i>*<?= Yii::t('app', 'We will send you an email with account activation link.') ?></i>
            </div>
        <?php endif ?>

    </div>
</div>

<?php 
$this->registerJsFile('https://www.google.com/recaptcha/api.js',['position' => \yii\web\View::POS_HEAD]);

 ?>