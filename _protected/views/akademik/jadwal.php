<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SimakJadwalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jadwal Kuliah';
$this->params['breadcrumbs'][] = $this->title;

$tahun_id = !empty($_GET['tahun_id']) ? $_GET['tahun_id'] : '';
$nim = !empty($_GET['nim']) ? $_GET['nim'] : '';

?>

<h3 class="page-title"><?=$this->title;?></h3>
<div class="row">
  <div class="col-md-3 col-lg-3 col-xs-12">
     <?php $form = ActiveForm::begin([
      'method' => 'GET',
      'action' => ['akademik/jadwal'],
      'options' => [
            'id' => 'form_validation',
      ]
    ]); ?>
     <div class="form-group">
          <label class="control-label ">Tahun Akademik</label>
          <?= Html::dropDownList('tahun_id',$tahun_id,\yii\helpers\ArrayHelper::map($listTahun,'tahun_id','nama_tahun'),['class'=>'form-control']) ?>

          
          
      </div>
      <div class="form-group">
            <label class="control-label ">Ananda</label>
           <?= Html::dropDownList('nim',$nim,\yii\helpers\ArrayHelper::map($list_anak,'nim_mhs','nama_mahasiswa'),['class'=>'form-control','prompt'=>'- Pilih Data Mahasiswa -','id'=>'nim']) ?>
        </div>
      <div class="form-group clearfix">
        <button type="submit" class="btn btn-primary" name="btn-cari" value="1"><i class="fa fa-search"></i> Cari</button>
       
      </div>
    
     <?php ActiveForm::end(); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel">
      <div class="panel-body ">

   
        <div class="table-responsive">
          <table class="table table-hover table-striped ">
            <thead>
              <tr>
                <th>#</th>
                <th style="text-align: center">Hari</th>
                <th style="text-align: center">Jam</th>
                <th>Kode MK</th>
                <th>Mata Kuliah</th>
                <th style="text-align: center">SKS</th>
                <th>Dosen</th>
                
                <th style="text-align: center">Kelas</th>
                
                <th style="text-align: center">Semester</th>
                
              </tr>
            </thead>
            <tbody>
              <?php 
              $counter = 1;
              $total_sks = 0;
              foreach($listKrs as $q => $m)
              {
                $krs = $m['krs'];
                $mk = $m['matkul'];
                $dosen = $m['dosen'];
                $jadwal = $m['jadwal'];
                $total_sks += $mk['sks_mk'];
              ?>
              <tr>
                <td><?=$counter;?></td>
                <td style="text-align: center"><?=$jadwal['hari'];?></td>
                <td style="text-align: center"><?=$jadwal['jam'];?></td>
                <td><?=$mk['kode_mk'];?></td>
                <td><?=$mk['nama_mk'];?></td>
                <td style="text-align: center"><?=$mk['sks_mk'];?></td>
                <td><?=$dosen['nama_dosen'];?></td>
                
                <td style="text-align: center"><?=$jadwal['kelas'];?></td>
                <td style="text-align: center"><?=$jadwal['semester'];?></td>
                
              </tr>
              <?php 
                $counter++;
              }
              ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="5" style="text-align: right">Total SKS yang diambil</td>
                <td style="text-align: center"><?=$total_sks;?></td>
                <td colspan="3"></td>

            </tr>
        </tfoot>
          </table>
        </div>
      </div>
    </div>
   </div>
</div>
<?php

$this->registerJs(' 


$(document).on("click","#btn-copy",function(e){
    var copyText = document.getElementById("myInput");

    copyText.select();
    copyText.setSelectionRange(0, 99999); /* For mobile devices */
    document.execCommand("copy");

    alert("Copied the text: " + copyText.value);
})
  

$(document).on("click",".classroom",function(e){

    e.preventDefault()

    var obj = new Object;
    obj.courseId = $(this).data("item");

    $.ajax({

        type : "POST",
        url : "'.Url::to(['/simak-jadwal/ajax-classroom']).'",
        data : {
            dataPost : obj
        },
       
        beforeSend: function(){
           Swal.showLoading()
        },
        error: function(e){
            Swal.close()
        },
        success: function(data){
            Swal.hideLoading()
            var hasil = $.parseJSON(data);
            if(hasil.code==200){
                Swal.fire({
                  icon: \'success\',
                  title: hasil.course.enrollmentCode,

                  text: \'Enrollment Code. Copy this code and paste to your Google Classroom to join this class\'
                })
                
            }

            else if(hasil.code == 5403){
      
              Swal.fire({
                title: \'Oops!\',
                icon: \'error\',
                html: hasil.message+" Click <a href=\'"+hasil.authUrl+"\'>here</a> for authorization"
              }).then((result) => {
                if (result.value) {
                  location.reload(); 
                }
              });
            }

            else{
                Swal.fire({
                  icon: \'error\',
                  title: \'Oops...\',
                  text: hasil.message,
                })
            }
        }
    }); 

});


    ', \yii\web\View::POS_READY);

?>
