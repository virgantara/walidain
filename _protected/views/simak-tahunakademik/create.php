<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SimakTahunakademik */

$this->title = 'Create Simak Tahunakademik';
$this->params['breadcrumbs'][] = ['label' => 'Simak Tahunakademiks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="simak-tahunakademik-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
