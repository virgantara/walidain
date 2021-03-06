<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransaksiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transaksis';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaksi-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Transaksi', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'urut',
            'CUSTID',
            'METODE',
            'TRXDATE',
            'NOREFF',
            //'FIDBANK',
            //'KDCHANNEL',
            //'DEBET',
            //'KREDIT',
            //'REFFBANK',
            //'TRANSNO',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
