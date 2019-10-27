<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SimakTahunakademik */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Simak Tahunakademiks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="simak-tahunakademik-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
            'id',
            'tahun_id',
            'tahun',
            'semester',
            'nama_tahun',
            'krs_mulai',
            'krs_selesai',
            'krs_online_mulai',
            'krs_online_selesai',
            'krs_ubah_mulai',
            'krs_ubah_selesai',
            'kss_cetak_mulai',
            'kss_cetak_selesai',
            'cuti',
            'mundur',
            'bayar_mulai',
            'bayar_selesai',
            'kuliah_mulai',
            'kuliah_selesai',
            'uts_mulai',
            'uts_selesai',
            'uas_mulai',
            'uas_selesai',
            'nilai',
            'akhir_kss',
            'proses_buka',
            'proses_ipk',
            'proses_tutup',
            'buka',
            'syarat_krs',
            'syarat_krs_ips',
            'cuti_selesai',
            'max_sks',
        ],
    ]) ?>

</div>
