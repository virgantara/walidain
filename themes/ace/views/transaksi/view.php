<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Transaksi */

$this->title = $model->urut;
$this->params['breadcrumbs'][] = ['label' => 'Transaksis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="transaksi-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Opposed Trx', ['oppose', 'id' => $model->urut], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Update', ['update', 'id' => $model->urut], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->urut], [
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
            'urut',
            'CUSTID',
            'METODE',
            'TRXDATE',
            'NOREFF',
            'FIDBANK',
            'KDCHANNEL',
            'DEBET',
            'KREDIT',
            'REFFBANK',
            'TRANSNO',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
