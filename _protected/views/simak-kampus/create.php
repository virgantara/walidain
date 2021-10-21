<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SimakKampus */

$this->title = 'Create Simak Kampus';
$this->params['breadcrumbs'][] = ['label' => 'Simak Kampuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="simak-kampus-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
