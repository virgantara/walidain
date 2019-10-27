<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SimakPencekalan */

$this->title = 'Create Simak Pencekalan';
$this->params['breadcrumbs'][] = ['label' => 'Simak Pencekalans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="simak-pencekalan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
