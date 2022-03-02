<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SimakMahasiswaOrtuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Simak Mahasiswa Ortus';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="simak-mahasiswa-ortu-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Simak Mahasiswa Ortu', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nik',
            'nim',
            'hubungan',
            'nama',
            //'tanggal_lahir',
            //'agama',
            //'pendidikan',
            //'pekerjaan',
            //'penghasilan',
            //'hidup',
            //'alamat',
            //'kota',
            //'propinsi',
            //'negara',
            //'pos',
            //'telepon',
            //'hp',
            //'email:email',
            //'is_synced',
            //'ortu_user_id',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
