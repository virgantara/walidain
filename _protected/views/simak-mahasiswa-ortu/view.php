<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SimakMahasiswaOrtu */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Simak Mahasiswa Ortus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="simak-mahasiswa-ortu-view">

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
            'nik',
            'nim',
            'hubungan',
            'nama',
            'tanggal_lahir',
            'agama',
            'pendidikan',
            'pekerjaan',
            'penghasilan',
            'hidup',
            'alamat',
            'kota',
            'propinsi',
            'negara',
            'pos',
            'telepon',
            'hp',
            'email:email',
            'is_synced',
            'ortu_user_id',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
