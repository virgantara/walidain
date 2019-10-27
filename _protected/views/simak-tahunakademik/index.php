<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SimakTahunakademikSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Simak Tahunakademiks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="simak-tahunakademik-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Simak Tahunakademik', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'tahun_id',
            'tahun',
            'semester',
            'nama_tahun',
            //'krs_mulai',
            //'krs_selesai',
            //'krs_online_mulai',
            //'krs_online_selesai',
            //'krs_ubah_mulai',
            //'krs_ubah_selesai',
            //'kss_cetak_mulai',
            //'kss_cetak_selesai',
            //'cuti',
            //'mundur',
            //'bayar_mulai',
            //'bayar_selesai',
            //'kuliah_mulai',
            //'kuliah_selesai',
            //'uts_mulai',
            //'uts_selesai',
            //'uas_mulai',
            //'uas_selesai',
            //'nilai',
            //'akhir_kss',
            //'proses_buka',
            //'proses_ipk',
            //'proses_tutup',
            //'buka',
            //'syarat_krs',
            //'syarat_krs_ips',
            //'cuti_selesai',
            //'max_sks',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
