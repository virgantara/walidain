<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KomponenBiayaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Komponen Biayas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="komponen-biaya-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Komponen Biaya', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'namaKategori',
            'kode',
            'nama',
            // 'periode_tagihan_id',
            'biaya_awal',
            'prioritas',
            
            'tahun',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
