<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SyaratPencekalan */

$this->title = 'Update Syarat Pencekalan: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Syarat Pencekalans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="syarat-pencekalan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
