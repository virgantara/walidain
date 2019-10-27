<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SimakPencekalan */

$this->title = 'Update Simak Pencekalan: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Simak Pencekalans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="simak-pencekalan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
