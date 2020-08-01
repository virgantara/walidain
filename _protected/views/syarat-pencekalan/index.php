<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SyaratPencekalanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Syarat Pencekalan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="syarat-pencekalan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Syarat Pencekalan', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tahun_id',
            [
                'attribute'=>'komponen_id',
                'value' => function ($data) {
                    return $data->komponen->nama;
                },
                // 'contentOptions'=>function($model, $key, $index, $column) {
                //     return ['class'=>CssHelper::userStatusCss($model->status)];
                // }
            ],
            'nilai_minimal',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
