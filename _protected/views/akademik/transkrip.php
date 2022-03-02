<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SimakJadwalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transkrip Nilai';
$this->params['breadcrumbs'][] = $this->title;
$nim = !empty($_GET['nim']) ? $_GET['nim'] : '';
?>


<div class="panel">
  <div class="panel-heading">
    <h3 class="panel-title"><?=$this->title;?></h3>
  </div>
  <div class="panel-body">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
         <?php $form = ActiveForm::begin([
          'method' => 'GET',
          'action' => ['akademik/transkrip'],
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
             <?= Html::radioList('nim',$nim,\yii\helpers\ArrayHelper::map($list_anak,'nim_mhs','nama_mahasiswa'),['maxlength' => true,'prompt'=>'- Pilih Data Mahasiswa -','id'=>'nim']) ?>
          </div>

          <div class="form-group clearfix">
            <button type="submit" class="btn btn-primary" name="btn-cari" value="1"><i class="fa fa-search"></i> Cari</button>
            
          </div>
        
         <?php ActiveForm::end(); ?>
      </div>
    </div>
  </div>
</div>  

  <?php 


if(!empty($mhs))
{

  $total_sks = $mhs->SKSLulus;
?>
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="panel panel-profile">
        <table class="table">
          <tr>
            <td>NIM</td>
            <td>: <?=$mhs->nim_mhs;?></td>
          </tr>
          <tr>
            <td>Nama</td>
            <td>: <?=$mhs->nama_mahasiswa;?></td>
          </tr>
          <tr>
            <td>Fakultas</td>
            <td>: <?=$mhs->kodeProdi->kodeFakultas->nama_fakultas;?></td>
          </tr>
          <tr>
            <td>Prodi</td>
            <td>: <?=$mhs->kodeProdi->nama_prodi;?></td>
          </tr>
          <tr>
            <td>IPK</td>
            <td>: <?=$mhs->getIpk();?></td>
          </tr>
          <tr>
            <td>SKS Lulus</td>
            <td>: <?=$mhs->SKSLulus;?> sks</td>
          </tr>
        </table>
       
    </div>
  </div>
</div>

<?php

$total_bobot = 0;

// for($br=0;$br<4;$br++)
// {
  ?>

<div class="row">
  <?php
  // foreach($list_semester[$br] as $semester)
  // {
    
  //   if(empty($results[$semester])) continue;
    
    

  ?>
  <div class="col-md-8 col-md-offset-2">
    <div class="panel">
      <div class="panel-heading">
      </div>
      <div class="panel-body ">

        <div class="table-responsive">
          <table class="table table-hover table-striped ">
            <thead>
              <tr>
                <th>#</th>
                <th>Kode MK</th>
                <th>Mata Kuliah</th>

                <th style="text-align: center">Semester</th>
                <th style="text-align: center">SKS</th>
                <th style="text-align: center">Nilai</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 0;
              for($br=0;$br<4;$br++) { 
                foreach($list_semester[$br] as $semester) {
                  
                  if(empty($results[$semester])) continue;
                  $sks = 0;
                  
                  $subbobot = 0;
                  if(!empty($results[$semester])){
                    foreach($results[$semester] as $m)
                    {
                      // print_r($m);exit;
                      $m = (object) $m;
                      

                      

                      // if($m->sks == 0) continue;

                      $sks+= $m->sks;
                      
                      $i++;
                    ?>
                    <tr>
                      <td><?=$i;?></td>
                      <td><?=$m->kode_mk;?></td>
                      <td><?=$m->nama_mk;?><br><i><?=$m->nama_mk_en;?></i>&nbsp;</td>
                      <td style="text-align: center"><?=$m->semester;?></td>
                      <td style="text-align: center"><?=$m->sks;?></td>
                      <td style="text-align: center"><?=$m->nilai_huruf;?></td>
                     
                    </tr>
                    <?php 
                      $subbobot += $m->bobot_sks;
                    }
                  }
                  $total_bobot += $subbobot;
                }
            }
              ?>
            </tbody>
            
          </table>
        </div>
      </div>
    </div>
   </div>
   <?php 

 // }
   ?>
</div>
<?php 

// }

?>

<?php  

}
?>


</div>