<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BatasPembayaran */

$this->title = 'Update Batas Pembayaran: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Batas Pembayarans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="batas-pembayaran-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
