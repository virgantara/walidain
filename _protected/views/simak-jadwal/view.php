<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SimakJadwal */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Simak Jadwals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="simak-jadwal-view">

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
            'hari',
            'jam',
            'kode_mk',
            'matkul_id',
            'kode_dosen',
            'kode_pengampu_nidn',
            'semester',
            'kelas',
            'fakultas',
            'prodi',
            'kd_ruangan',
            'tahun_akademik',
            'kuota_kelas',
            'kampus',
            'presensi:ntext',
            'materi',
            'bobot_formatif',
            'bobot_uts',
            'bobot_uas',
            'bobot_harian1',
            'bobot_harian',
            'jadwal_temp_id',
            'jumlah_tatap_muka',
            'kode_feeder',
            'a_selenggara_pditt',
            'bahasan_case:ntext',
            'lingkup_kelas',
            'mode_kuliah',
            'tgl_mulai_koas',
            'tgl_selesai_koas',
            'classroom_id',
            'alternateLink',
            'enrollment_code',
            'tgl_tutup_daftar',
            'kapasitas',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
