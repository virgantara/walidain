<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\widgets\DetailView;
use app\models\SimakPropinsi;

use app\helpers\MyHelper;
use app\models\SimakKabupaten;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SimakJadwalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Data Kesantrian';
$this->params['breadcrumbs'][] = $this->title;

$nim = !empty($mhs) ? $mhs->nim_mhs : '';
$tahun = !empty($_GET['tahun']) ? $_GET['tahun'] : '';
$kabupaten = null;
$provinsi = null;
if(!empty($model)){
    $kabupaten = \app\models\SimakKabupaten::find()->where(['id'=>$model->kabupaten])->one();
    $provinsi = \app\models\SimakPropinsi::find()->where(['id'=>$model->provinsi])->one();
}
?>
<div class="row">
  <div class="col-md-3 col-lg-3 col-xs-12">

     <?php $form = ActiveForm::begin([
      'method' => 'GET',
      'action' => ['asrama/kesantrian'],
      'options' => [
            'id' => 'form_validation',
      ]
    ]); ?>
         <?php 
      if(count($list_anak) > 1){
       ?>
      <div class="form-group">
          <label class="control-label ">Mahasiswa</label>
          <?= Html::radioList('nim',$nim,\yii\helpers\ArrayHelper::map($list_anak,'nim_mhs','nama_mahasiswa'),['class'=>'','prompt' => '- Silakan Pilih Data Ananda -','separator' => '<br>']) ?>
      </div>
      <div class="form-group clearfix">
        <button type="submit" class="btn btn-primary" name="btn-cari" value="1"><i class="fa fa-search"></i> Lihat</button>

       
      </div>
    <?php } ?>
     <?php ActiveForm::end(); ?>
  </div>
</div>
<div class="panel">
    <div class="panel-heading">
        <h3 class="page-title"><?=$this->title;?></h3>
       
    </div>
    <div class="panel-body">
        



<?php 
if(!empty($model))
{
?>
<div class="row">
    <div class="col-md-12">
         <div class="widget-box transparent">
                        <div class="widget-header widget-header-small">
                            <h4 class="widget-title blue smaller">
                                <i class="ace-icon fa fa-rss orange"></i>
                                Biodata <?=$model->nama_mahasiswa;?>
                            </h4>

                        </div>
                        <div align="center">
                            <img src="<?=Url::to(['customer/foto','id'=>$model->id]);?>" border="1" width="200px" height="300px"></img>
                        </div> 
                                                   
                        <div class="kesantrian table-responsive">
                            <table class="table table-bordered">
                                
                                <tbody>
                                    <tr>
                                        <td colspan="3">
                                            <h4><strong>A. Data Pribadi Mahasiswa</strong></h4>
                                        </td>    
                                                             
                                    </tr>
                                    <tr class="success">
                                        <td>1.</td>
                                        <td>Nama</td>
                                        <td><?= $model->nama_mahasiswa?></td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Nama Arab</td>
                                        <td><?= $model->nama_mahasiswa?></td>
                                    </tr>
                                    <tr class="success">
                                        <td>3.</td>
                                        <td>No KTP</td>
                                        <td><?= $model->ktp?></td>
                                    </tr>
                                    <tr>
                                        <td>4.</td>
                                        <td>Kewarganegaraan</td>
                                        <td><?= $model->warga_negara?></td>
                                    </tr>
                                    <tr class="success">
                                        <td>5.</td>
                                        <td colspan="2">Alamat Lengkap</td>
                                    </tr>
                                    <tr>
                                        <td class="center">a.</td>
                                        <td>Jalan</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="center">b.</td>
                                        <td>No Rumah</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="center">c.</td>
                                        <td>RT / RW</td>
                                        <td>RT <?= $model->rt ?> RW <?= $model->rw ?></td>
                                    </tr>
                                    <tr>
                                        <td class="center">d.</td>
                                        <td>Desa/Kel/Dusun</td>
                                        <td>DUSUN <?= strtoupper($model->dusun)?> DESA <?= strtoupper($model->desa)?></td>
                                    </tr>
                                    <tr>
                                        <td class="center">e.</td>
                                        <td>Kecamatan</td>
                                        <td><?= $model->kecamatan?></td>
                                    </tr>
                                    <tr>
                                        <td class="center">f.</td>
                                        <td>Kab/Kodya</td>
                                        <td><?= !empty($kabupaten) ? $kabupaten->kab : ''?></td>
                                    </tr>
                                    <tr>
                                        <td class="center">g.</td>
                                        <td>Provinsi</td>
                                        <td><?= !empty($provinsi) ? $provinsi->prov : ''?></td>
                                    </tr>
                                    <tr>
                                        <td class="center">h.</td>
                                        <td>Kode POS</td>
                                        <td><?= $model->kode_pos?></td>
                                    </tr>
                                    <tr class="success">
                                        <td>6.</td>
                                        <td>Contact</td>
                                        <td><?= $model->hp?> </td>
                                    </tr>
                                    <tr>
                                        <td>7.</td>
                                        <td>Asrama</td>
                                        <td><?= !empty($model->kamar) ? $model->kamar->nama: null?> - <?= !empty($model->kamar->asrama) ? $model->kamar->asrama->nama : null?> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <h4><strong>B. Data Keluarga</strong></h4>
                                        </td>
                                    </tr>
                                <?php foreach ($model->ortuAyah as $row) { ?>
                                    <tr class="success">
                                        <td>1.</td>
                                        <td colspan="2">Ayah</td>  
                                    </tr>
                                    <tr>
                                        <td class="center">a.</td>
                                        <td>Nama</td>
                                        <td><?= strtoupper($row['nama']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="center">b.</td>
                                        <td>Pekerjaan</td>
                                        <td><?= $row['namaPekerjaan']->label ?></td>
                                    </tr>
                                    <tr>
                                        <td class="center">c.</td>
                                        <td>Pendidikan Terakhir</td>
                                        <td><?= $row['namaPendidikan']->label ?></td>
                                    </tr>
                                    <tr>
                                        <td class="center">d.</td>
                                        <td>Penghasilan</td>
                                        <td><?= $row['namaPenghasilan']->label ?></td>
                                    </tr>
                                    <tr>
                                        <td class="center">e.</td>
                                        <td>Kewarganegaraan</td>
                                        <td><?= strtoupper($row['negara']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="center">f.</td>
                                        <td>Kedudukan di Masyarakat</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="center">g.</td>
                                        <td>Contact</td>
                                        <td>TELP : <?= $row['telepon'] ?> / HP : <?= $row['hp'] ?></td>
                                    </tr>
                                <?php } ?>
                                    <tr class="success">
                                        <td>2.</td>
                                        <td colspan="2">Ibu</td>
                                    </tr>
                                <?php foreach ($model->ortuIbu as $row) { ?>
                                    <tr>
                                        <td class="center">a.</td>
                                        <td>Nama</td>
                                        <td><?= strtoupper($row['nama']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="center">b.</td>
                                        <td>Pekerjaan</td>
                                        <td><?= $row['namaPekerjaan']->label ?></td>
                                    </tr>
                                    <tr>
                                        <td class="center">c.</td>
                                        <td>Pendidikan Terakhir</td>
                                        <td><?= $row['namaPendidikan']->label ?></td>
                                    </tr>
                                    <tr>
                                        <td class="center">d.</td>
                                        <td>Penghasilan</td>
                                        <td><?= $row['namaPenghasilan']->label ?></td>
                                    </tr>
                                    <tr>
                                        <td class="center">e.</td>
                                        <td>Kewarganegaraan</td>
                                        <td><?= strtoupper($row['negara']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="center">f.</td>
                                        <td>Kedudukan di Masyarakat</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="center">g.</td>
                                        <td>Contact</td>
                                        <td>TELP : <?= $row['telepon'] ?> / HP : <?= $row['hp'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="center">h.</td>
                                        <td>Anak ke</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="center">i.</td>
                                        <td>Yang Membiayai Pendidikan</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="center">j.</td>
                                        <td>Kesanggupan Membiayai</td>
                                        <td></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($model->ortuWali)) { ?>
                                    <tr class="success">
                                        <td>1.</td>
                                        <td colspan="2">Wali</td>  
                                    </tr>
                                    <tr>
                                        <td class="center">a.</td>
                                        <td>Nama</td>
                                        <td><?= $row['nama'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="center">b.</td>
                                        <td>Pekerjaan</td>
                                        <td><?= $row['namaPekerjaan']->label ?></td>
                                    </tr>
                                    <tr>
                                        <td class="center">c.</td>
                                        <td>Pendidikan Terakhir</td>
                                        <td><?= $row['namaPendidikan']->label ?></td>
                                    </tr>
                                    <tr>
                                        <td class="center">d.</td>
                                        <td>Kewarganegaraan</td>
                                        <td><?= strtoupper($row['negara']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="center">e.</td>
                                        <td>Kedudukan di Masyarakat</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="center">f.</td>
                                        <td>Contact</td>
                                        <td>TELP : <?= $row['telepon'] ?> / HP : <?= $row['hp'] ?></td>
                                    </tr>
                                <?php }
                                else{
                                ?>
                                    <tr class="success">
                                        <td>3.</td>
                                        <td>Wali</td>
                                        <td> Tidak Ada Wali</td>
                                    </tr>
                                <?php 
                                }

                                ?>
                                </tbody>
                            </table>
   
                            
                        </div>

                    </div>
    </div>
    

</div>
<div class="row">
      <div class="col-xs-12">
            <div class="space-20"></div>

                 
                         <div class="widget-box transparent">
                        <div class="widget-header widget-header-small">
                            <h4 class="widget-title blue smaller">
                                <i class="ace-icon fa fa-rss orange"></i>
                                Riwayat Perpindahan Asrama
                            </h4>

                        </div>
                        <?php 
                        foreach ($riwayatKamar as $key => $value) {
                            # code...
                        
                        ?>
                        <div class="widget-body">
                            <div class="widget-main padding-8">
                                <div id="profile-feed-1" class="profile-feed">
                                    <div class="profile-activity clearfix">
                                        <div>
                                            
                                           <a class="user" href="#"><?=$model->nama_mahasiswa;?></a>
                                            pindah dari <?=!empty($value->dariKamar) ? $value->dariKamar->namaAsrama : '-';?>
                                            kamar <?=!empty($value->dariKamar) ? $value->dariKamar->nama : '-';?> ke <?=!empty($value->kamar) ? $value->kamar->namaAsrama : '-';?>
                                            kamar <?=!empty($value->kamar) ? $value->kamar->nama : '-';?> pada tanggal <?=MyHelper::YmdtodmY($value->created_at);?>

                                            <div class="time">
                                                <i class="ace-icon fa fa-clock-o bigger-110"></i>
                                                <?=\app\helpers\MyHelper::hitungDurasi(date('Y-m-d H:i:s'),$value->created_at);?> yang lalu
                                            </div>
                                        </div>

                                       
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php
                    }
                         ?>

                    </div>

                   
      </div>
</div>
    </div>
</div>



<?php 
}
$this->registerJs(' 

', \yii\web\View::POS_READY);

?>