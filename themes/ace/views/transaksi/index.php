<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TransaksiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transaksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaksi-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="table-responsive">
    <?php Pjax::begin(['id' => 'pjax-container']); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'METODE',
            'CUSTID',
            
            'TRXDATE',
            'NOREFF',
            //'FIDBANK',
            //'KDCHANNEL',
            'DEBET',
            'KREDIT',
            //'REFFBANK',
            //'TRANSNO',
            'created_at',
            'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'view' => function ($model) {
                        return \Yii::$app->user->can('theCreator');
                    },
                    'update' => function ($model) {
                        return \Yii::$app->user->can('theCreator');
                    },
                    'delete' => function ($model) {
                        return \Yii::$app->user->can('theCreator');
                    },
                ],
                'buttons' => [
                   
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                   'title'        => 'delete',
                                    'onclick' => "
                                    if (confirm('Hapus data ini?')) {
                                        $.ajax('$url', {
                                            type: 'POST'
                                        }).done(function(data) {
                                            $.pjax.reload({container: '#pjax-container'});
                                            
                                        });
                                    }
                                    return false;
                                ",
                                    // 'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                    // 'data-method'  => 'post',
                        ]);
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    
                    if ($action === 'delete') {
                        $url =Url::to(['transaksi/delete','id'=>$model->urut]);
                        return $url;
                    }

                    // else if ($action === 'update') {
                    //     $url =Url::to(['request-order-item/update','id'=>$model->id,'ro_id'=>$model->ro_id]);
                    //     return $url;
                    // }

                    // else if ($action === 'updateMinta') {
                    //     $url =Url::to(['request-order-item/update','id'=>$model->id,'ro_id'=>$model->ro_id]);
                    //     return $url;
                    // }

                    // else if ($action === 'view') {
                    //     $url =Url::to(['request-order-item/view','id'=>$model->id]);
                    //     return $url;
                    // }

                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
    </div>
</div>
