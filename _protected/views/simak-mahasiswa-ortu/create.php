<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SimakMahasiswaOrtu */

$this->title = 'Create Simak Mahasiswa Ortu';
$this->params['breadcrumbs'][] = ['label' => 'Simak Mahasiswa Ortus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="simak-mahasiswa-ortu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
