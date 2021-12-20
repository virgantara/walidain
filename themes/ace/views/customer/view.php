<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Customer */

$this->title = $model->nim_mhs;
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$propinsi = \app\models\SimakPropinsi::findOne([
    'id' => $model->provinsi
]);

$kabupaten = \app\models\SimakKabupaten::findOne([
    'id' => $model->kabupaten
]);
?>
<h1><?= Html::encode($this->title) ?></h1>
<div class="row">
    <div class="col-md-6">
    

   

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nim_mhs',
            'nama_mahasiswa',
            'va_code',
            'namaProdi',
            'namaKampus',
            'status_aktivitas',
            'saldo',
            [
                'attribute' => 'alamatWali',
                'format' => 'raw',
                'value' => function($data){
                    return $data->alamatWali;
                }
            ]
        ],
    ]) ?>
    </div>
    <div class="col-md-6">
    

   

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'telepon',
            'hp',
            'email:email',
            'alamat',
            'rt',
            'rw',
            'dusun',
            'kode_pos',
            'desa',
            'kecamatan',
            [
                'attribute' => 'kabupaten',
                'value' => function($data) use ($kabupaten){
                    return !empty($kabupaten) ? $kabupaten->kab : null;
                }
            ],
            [
                'attribute' => 'provinsi',
                'value' => function($data) use ($propinsi){
                    return !empty($propinsi) ? $propinsi->prov : null;
                }
            ],
            
        ],
    ]) ?>
    </div>
</div>
