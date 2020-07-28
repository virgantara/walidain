<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\KomponenBiaya */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Komponen Biayas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="komponen-biaya-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
      foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
          echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
     } ?>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
            'kode',
            'nama',
            'biaya_awal',
            'biaya_minimal',
            'prioritas',
            'kategori_id',
            'tahun',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
