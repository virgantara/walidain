<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\PerusahaanSub */

$this->title = $model->nama.' - '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Unit', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="perusahaan-sub-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update-profile', 'id' => $model->id,'p'=>$p], ['class' => 'btn btn-primary']) ?>
        
    </p>

    <p>
        
        <?php 
        if($p == 'visi-misi'){
            ?>
            <h2>Visi dan Misi</h2>
            <h4>A. Visi</h4>
        
            <?=$model->visi;?>
            <h4>B. Misi</h4>
        
            <?=$model->misi;?>
        <?php }
        else if($p == 'sasaran-tujuan'){
         ?>
             <h2>Tujuan dan Sasaran</h2>
             <h4>Tujuan</h4>
        
            <?=$model->tujuan;?>
            <h4>Sasaran</h4>
        
            <?=$model->sasaran;?>
            
         <?php   
            }
         ?>
        
        
    </p>


</div>
