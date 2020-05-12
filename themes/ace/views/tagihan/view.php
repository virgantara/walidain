<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Tagihan */

$this->title = $model->namaKomponen;
$this->params['breadcrumbs'][] = ['label' => 'Tagihans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tagihan-view">

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
            
            'semester',
            'tahun',
            'nim',
            'namaKomponen',
            'nilai',
            'terbayar',
            'edit',
            [
                'label' => 'status_bayar',

                'value' => function($data){
                    switch($data->statusPembayaran)
                    {
                        case 1 : 
                            return 'LUNAS';
                        break;
                        case 2 :
                            
                            return 'CICILAN'; 
                        break;
                        default:
                            
                            return 'BELUM LUNAS';
                        break;
                    }
                }   
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
