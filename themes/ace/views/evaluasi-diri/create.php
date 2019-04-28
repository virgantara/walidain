<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EvaluasiDiri */

$this->title = 'Create Evaluasi Diri';
$this->params['breadcrumbs'][] = ['label' => 'Evaluasi Diri', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="evaluasi-diri-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
