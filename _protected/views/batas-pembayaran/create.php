<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BatasPembayaran */

$this->title = 'Create Batas Pembayaran';
$this->params['breadcrumbs'][] = ['label' => 'Batas Pembayarans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="batas-pembayaran-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
