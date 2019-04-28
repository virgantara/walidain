<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EvaluasiDiri */

$this->title = 'Update Evaluasi Diri: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Evaluasi Diri', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="evaluasi-diri-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
