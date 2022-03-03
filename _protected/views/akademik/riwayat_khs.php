<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SimakJadwalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Riwayat Kartu Hasil Studi (KHS)';
$this->params['breadcrumbs'][] = $this->title;
$nim = !empty($_GET['nim']) ? $_GET['nim'] : '';
?>


<div class="panel">
  <div class="panel-heading">
    <h3 class="panel-title"><?=$this->title;?></h3>
  </div>
  <div class="panel-body">
    <div class="row">
      <div class="col-lg-8 col-lg-offset-2">
         <?php $form = ActiveForm::begin([
          'method' => 'GET',
          'action' => ['akademik/riwayat-khs'],
          'options' => [
                'id' => 'form_validation',
          ]
        ]); ?>
        <?php 
        foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
          echo '<div class="flash alert alert-' . $key . '">' . $message . '<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button></div>';
        }
        ?>
            <div class="form-group">
              <label class="control-label ">Ananda</label>
             <?= Html::radioList('nim',$nim,\yii\helpers\ArrayHelper::map($list_anak,'nim_mhs','nama_mahasiswa'),['maxlength' => true,'prompt'=>'- Pilih Data Mahasiswa -','id'=>'nim','separator' => '<br>']) ?>
          </div>

          <div class="form-group clearfix">
            <button type="submit" class="btn btn-primary" name="btn-cari" value="1"><i class="fa fa-search"></i> Cari</button>
            
          </div>
        
         <?php ActiveForm::end(); ?>
      </div>
    </div>
  </div>
</div>  


<div class="row">
  <?php 
  foreach($results as $q => $res)
  {
    if(empty($res['krs'])) continue;
?>
  <div class="col-md-8 col-md-offset-2">

    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">Tahun Akademik <?=$q;?></h3>
      </div>
      <div class="panel-body ">

   
        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Kode MK</th>
                <th>Mata Kuliah</th>
                <th style="text-align: center">SKS</th>
                <!-- <th>Dosen</th> -->
                <th style="text-align: center">Kelas</th>
                <th style="text-align: center">Semester</th>
                <th style="text-align: center">Nilai</th>
                
              </tr>
            </thead>
            <tbody>
              <?php 

             
              $total_sks = $res['rekap']['total_sks'];
              $ips = $res['rekap']['ips'];
              // print_r($results['krs']);exit;
              foreach($res['krs'] as $q => $m)
              {

                

              ?>
              <tr>
                <td><?=$q+1;?></td>
                <td><?=$m['kode_mk'];?></td>
                <td><?=$m['nama_mk'];?></td>
                <td style="text-align: center"><?=$m['sks'];?></td>
                <td style="text-align: center"><?=$m['kelas'];?></td>
                <td style="text-align: center"><?=$m['semester'];?></td>
                <td style="text-align: center"><?=$m['nilai_huruf'];?></td>
            
              </tr>
              <?php 
              }


              
              ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3" style="text-align: right">Total SKS</td>
                <td style="text-align: center"><?=$total_sks;?></td>
                <td colspan="4"></td>

            </tr>
            <tr>
                <td colspan="3" style="text-align: right">IPS</td>
                <td style="text-align: center"><?=$ips;?></td>
                <td colspan="4"></td>

            </tr>
        </tfoot>
          </table>
        </div>
      </div>
    </div>
   </div>
 <?php } ?>
</div>


<?php 


$this->registerJs(' 


', \yii\web\View::POS_READY);

?>