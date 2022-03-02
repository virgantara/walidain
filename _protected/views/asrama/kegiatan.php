<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SimakJadwalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Data AKPAM';
$this->params['breadcrumbs'][] = $this->title;

$alphabet = range('A', 'Z');
$prodi = !empty($_GET['prodi']) ? $_GET['prodi'] : '';
$nim = !empty($_GET['nim']) ? $_GET['nim'] : '';
$kampus = !empty($_GET['kampus']) ? $_GET['kampus'] : '';
$semester = !empty($_GET['semester']) ? $_GET['semester'] : '';
$status_aktivitas = !empty($_GET['status_aktivitas']) ? $_GET['status_aktivitas'] : '';
$tahun_id = !empty($_GET['tahun_id']) ? $_GET['tahun_id'] : '';
$is_approved = !empty($_GET['is_approved']) ? $_GET['is_approved'] : '';
$jenis_nilai = !empty($_GET['jenis_nilai']) ? $_GET['jenis_nilai'] : '';

?>
<style type="text/css">
  .swal2-popup {
  font-size: 1.6rem !important;
}
</style>
<h3 class="page-title"><?=$this->title;?></h3>
<?php 

$listKampus = [];
$query = \app\models\SimakKampus::find();
if(Yii::$app->user->identity->access_role =='akpam'){
    $query->andWhere(['kode_kampus'=>Yii::$app->user->identity->kampus]);
}   

$listKampus = $query->all();

$tglnow = date('Y-m-d H:i:s');

if(1){


?>
<div class="row">
   <?php
      foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
        echo '<div class="flash alert alert-' . $key . '">' . $message . '<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button></div>';
      }
      ?>
  <?php $form = ActiveForm::begin([
      'method' => 'GET',
      'action' => ['asrama/kegiatan'],
      'options' => [
            'id' => 'form_validation',
      ]
    ]); ?>
  <div class="col-md-3 col-lg-3 col-xs-12">
     

      <div class="form-group">
          <label class="control-label ">Tahun Akademik</label>
          <?= Html::dropDownList('tahun_id',$tahun_id,\yii\helpers\ArrayHelper::map($listTahun,'tahun_id','nama_tahun'),['class'=>'form-control','prompt' => '- Semua -']) ?>
      </div>
      <div class="form-group">
              <label class="control-label ">Ananda</label>
               <?= Html::dropDownList('nim',$nim,\yii\helpers\ArrayHelper::map($list_anak,'nim_mhs','nama_mahasiswa'),['class'=>'form-control','prompt' => '- Pilih Mahasiswa -']) ?>
          </div>
      
      
     
  </div>

    
      <div class="col-xs-12">
        <div class="form-group clearfix">
        <button type="submit" class="btn btn-primary" name="btn-cari" value="1"><i class="fa fa-search"></i> Cari</button>
        
      </div>
    
      </div>
  <?php ActiveForm::end(); ?>
</div>
<?php 
if(!empty($results))
{
?>
  <div class="row">
    <div class="col-md-12">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title"><?=$this->title;?></h3>
        </div>
        <div class="panel-body ">
          <div class="table-responsive">

           

         

            <table class="table table-hover table-striped " id="table-krs">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Kegiatan - Tema</th>
                  <th>Keterangan</th>
                  <th style="text-align: center">Tanggal</th>
                  
                  <th style="text-align: center">Nilai</th>
                  <th style="text-align: center">Bukti</th>
                  <th style="text-align: center">Status</th>
                </tr>
              </thead>
              <tbody>
                 <?php 
                 $counter = 0;
                 $grand_total = 0;
                 $grand_total_diakui = 0;
                foreach($listJenisKegiatan as $q => $jk)
                {


                    if($counter == 0)
                    {
                  ?>
                  <tr>
                    <td colspan="7"><strong><?=$alphabet[$q];?>. <?=$jk->nama_jenis_kegiatan;?></strong></td>
                  </tr>
                  <?php 
                      
                    }
                    
                    $counter++;
                  $is_approved = 0;
                  $total = 0;
                  foreach($results[$jk->id] as $qq => $m)
                  {

                      if($qq == count($results[$jk->id]) - 1){
                        $counter = 0;
                      }
                    
                      $is_approved = $m->is_approved;
                    

                    if($is_approved == 1)
                    {
                      $total += $m->nilai;

                    }
                    try {
                      $bukti_file = Html::a('<i class="fa fa-share"></i> Bukti File',$m->file,['target'=>'_blank','class'=>'btn btn-info btn-sm']);  
                    } catch (Exception $e) {
                      $bukti_file = Html::a('<i class="fa fa-ban"></i> Oops, file tidak ada atau tidak valid','',['class'=>'btn btn-danger btn-sm']);
                    }
                    

                 ?>
                  <tr>
                    <td><?=$qq+1;?></td>
                    <td><?=$m->kegiatan->nama_kegiatan;?> - <?=$m->tema;?>
                    </td>
                    <td><?=$m->keterangan;?>
                    </td>
                    <td style="text-align: center"><?=date('d M Y',strtotime($m->waktu));?></td>
                    
                    <td style="text-align: center"><?=$m->nilai;?></td>
                    <td style="text-align: center"><?=$bukti_file;?></td>
                    <td style="text-align: center">
                      
                      <?=$is_approved == 1 ? "<span class='label label-success'>Approved</span>": "<span class='label label-danger'>Not Approved</span>";?>
                    
                  </td>
                  </tr>
                  <?php 
                  }

                  ?>
                  <tr>
                    <td colspan="4" style="text-align: right"><strong>Total Nilai <?=$jk->nama_jenis_kegiatan;?> (Rentang Nilai <?=$jk->nilai_minimal.' - '.$jk->nilai_maximal;?>)</strong> </td>
                    <td style="text-align: center" id="total_sks"><?=$total;?></td>
                    <td colspan="2"></td>

                </tr>
                <tr>
                    <td colspan="4" style="text-align: right"><strong>Nilai <?=$jk->nama_jenis_kegiatan;?> yang diakui</strong> </td>
                    <td style="text-align: center" id="total_sks"><?=$total >= $jk->nilai_maximal ? $jk->nilai_maximal : $total;?></td>
                    <td colspan="2"></td>

                </tr>
                <tr>
                    <td colspan="4" style="text-align: right"><strong>Status Nilai <?=$jk->nama_jenis_kegiatan;?></strong> </td>
                    <td style="text-align: center" id="total_sks">
                      <?php

                      if($total >= $jk->nilai_maximal)
                      {
                        $grand_total += $jk->nilai_maximal;
                      }

                      else{
                        $grand_total += $total;
                      }

                      if($total >= $jk->nilai_minimal)
                      {
                        echo '<span class="label label-success">Sudah mencukupi</span>';
                      }

                      else
                      {
                        echo '<span class="label label-danger">Belum mencukupi</span>'; 
                      }

                      ?>
                        
                      </td>
                    <td colspan="2"></td>

                </tr>
                  <?php
                  
                }
            ?>
           
              </tbody>
              <tfoot>
              <tr>
                  <td colspan="4" style="text-align: right">Total</td>
                  <td style="text-align: center" id="total_sks"><?=$grand_total;?></td>
                  <td colspan="2"></td>

              </tr>
               <tr>
                  <td colspan="4" style="text-align: right">Keterangan</td>
                  <td style="text-align: center" id="total_sks"><?=$grand_total >= $tahun_akademik->nilai_lulus_akpam ? '<div class="alert alert-success">Sudah LULUS AKPAM</div>' : '<div class="alert alert-danger">Belum LULUS AKPAM</div>';?></td>
                  <td colspan="2"></td>

              </tr>
          </tfoot>
            </table>

          </div>
        </div>
      </div>
     </div>
  </div>
  

<?php 
  

  }
}

?>
<?php 

$this->registerJs(' 



function getMhs(){
  var obj = new Object;
  obj.kampus = $("#kampus").val();
  obj.prodi = $("#prodi").val();
  obj.semester = $("#semester").val();
  obj.mahasiswa = $("#nim").val();
  obj.status_aktivitas = $("#status_aktivitas").val();
  var ajax_url= "'.Url::to(['simak-mastermahasiswa/ajax-list-mahasiswa']).'";
  $(this).ajaxDataPost(ajax_url,obj,function(err, hasil){
    var row = \'\';
    $(\'#nim\').empty();
    row += \'<option value="">- Pilih Mahasiswa -</option>\';
    $.each(hasil, function(i, obj){
      row += \'<option  value="\'+obj.id+\'">\'+obj.label+\'</option>\';
    });


    $(\'#nim\').append(row);
    $(\'#nim\').val("'.$nim.'");
  });

  
}

getMhs();

$(document).on(\'change\',\'#kampus, #prodi, #semester, #status_aktivitas\',function(e){
    e.preventDefault();
    getMhs();
    
});


', \yii\web\View::POS_READY);

?>