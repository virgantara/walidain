<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\EvaluasiDiri */

$this->title = 'Evaluasi Diri '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Evaluasi Diri', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="evaluasi-diri-view">

    <h1><?= Html::encode('Evaluasi Diri') ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?php 

        
        if(Yii::$app->user->can('admin'))
        {
            $label = 'Verifikasi';
            $kode = 1;
            $warna = 'info';
            $kode = !$model->is_verified;

            if($model->is_verified){
                $warna = 'warning';
                $label = 'Batal Verifikasi';
                
            }

            echo Html::a($label, ['verify', 'id' => $model->id,'kode'=>$kode], [
                'class' => 'btn btn-'.$warna,
                'data' => [
                    'confirm' => $label.' data ini?',
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           
            'tanggal',

            'strength:html',
            'weakness:html',
            'opportunity:html',
            'threat:html',
            'created_at',
            'updated_at',
            [
                'attribute'=>'attachment',
                'label'=>'File PPEPP',
                'format'=>'raw',
                'value'=>Html::a('<i class="fa fa-download"></i> Download File',['download','id'=>$model->id],['class'=>'btn btn-success btn-sm']),
              ],
        ],
    ]) ?>

</div>
