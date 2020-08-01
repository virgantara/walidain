<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SyaratPencekalan */

$this->title = 'Create Syarat Pencekalan';
$this->params['breadcrumbs'][] = ['label' => 'Syarat Pencekalans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="syarat-pencekalan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'results' => $results
    ]) ?>

</div>
