<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\KomponenBiaya */

$this->title = 'Update Komponen Biaya: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Komponen Biayas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="komponen-biaya-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'kategori' => $kategori,
        'tahun' => $tahun,
        'list_prioritas' => $list_prioritas
    ]) ?>

</div>
