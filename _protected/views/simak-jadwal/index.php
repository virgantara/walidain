<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SimakJadwalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Simak Jadwals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="simak-jadwal-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Simak Jadwal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'hari',
            'jam',
            'kode_mk',
            'matkul_id',
            //'kode_dosen',
            //'kode_pengampu_nidn',
            //'semester',
            //'kelas',
            //'fakultas',
            //'prodi',
            //'kd_ruangan',
            //'tahun_akademik',
            //'kuota_kelas',
            //'kampus',
            //'presensi:ntext',
            //'materi',
            //'bobot_formatif',
            //'bobot_uts',
            //'bobot_uas',
            //'bobot_harian1',
            //'bobot_harian',
            //'jadwal_temp_id',
            //'jumlah_tatap_muka',
            //'kode_feeder',
            //'a_selenggara_pditt',
            //'bahasan_case:ntext',
            //'lingkup_kelas',
            //'mode_kuliah',
            //'tgl_mulai_koas',
            //'tgl_selesai_koas',
            //'classroom_id',
            //'alternateLink',
            //'enrollment_code',
            //'tgl_tutup_daftar',
            //'kapasitas',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
