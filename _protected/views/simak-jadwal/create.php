<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SimakJadwal */

$this->title = 'Create Simak Jadwal';
$this->params['breadcrumbs'][] = ['label' => 'Simak Jadwals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="simak-jadwal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
