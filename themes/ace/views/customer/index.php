<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'custid',
            'nama',
            'va_code',
            // 'kampus',
            'nama_kampus',
            //'kode_prodi',
            'nama_prodi',
            'saldo',
            //'created_at',
            //'updated_at',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
