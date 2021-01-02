<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Customer */

$this->title = $model->nama_mahasiswa;
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="customer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-sm-12">
            
            <div class="widget-box">
                
                <div class="widget-body">
                    <div class="widget-main">
                        

                       
                        <address>
                            <strong>Alamat</strong>
                            <br>
                            <?=$model->alamat;?>
                            <br>
                            RT <?=$model->rt;?> / RW <?=$model->rw;?>
                            <br>
                            Dusun <?=$model->dusun;?>
                            <br>
                            Desa <?=$model->desa;?>
                            <br>
                            Kec. <?=$model->kecamatan;?>
                            <br>
                            Kab/Kota <?=!empty($kabupaten) ? $kabupaten->kab : '-';?>
                            <br>
                            Prov. <?=!empty($kabupaten) ? $kabupaten->provinsi->prov : '-';?>
                            <br>
                            <abbr title="Phone">Telp:</abbr>
                            <?=$model->hp;?>

                            <br>Email: <a href="mailto:#"><?=$model->email;?></a>
                        </address>
                        Orang Tua
                        <?php 
                        $listOrtu = $model->simakMahasiswaOrtus;
                        foreach($listOrtu as $q => $ortu)
                        {
                        ?>
                        <address>
                            
                            <strong><?=ucwords(strtolower($ortu->hubungan));?></strong>

                            : <?=$ortu->nama;?>
                            <br>
                            Pekerjaan : <?=$ortu->pekerjaan0->label;?>

                        </address>
                        <?php 
                        }
                        ?>
                    </div>
                </div>
            </div>
                                    
        </div>
    </div>

</div>
