<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\KomponenBiaya */

$this->title = 'Create Komponen Biaya';
$this->params['breadcrumbs'][] = ['label' => 'Komponen Biayas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="komponen-biaya-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'kategori' => $kategori,
        'tahun' => $tahun
    ]) ?>

</div>
