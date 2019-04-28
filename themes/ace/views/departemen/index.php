<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PerusahaanSubSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Unit';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="perusahaan-sub-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Unit', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nama',
            'namaPerusahaan',
            'visi',
            'misi',
            'created_at',

            [

                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'view' => function ($model) {
                        return \Yii::$app->user->can('admin') || \Yii::$app->user->can('gudang');
                    },
                    'update' => function ($model) {
                        return \Yii::$app->user->can('admin') || \Yii::$app->user->can('gudang');
                    },
                    'delete' => function ($model) {
                        return \Yii::$app->user->can('admin') || \Yii::$app->user->can('gudang');
                    },
                ]
            ],
        ],
    ]); ?>
</div>
