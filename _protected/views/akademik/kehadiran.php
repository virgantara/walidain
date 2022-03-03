<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SimakJadwalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kehadiran Kuliah';
$this->params['breadcrumbs'][] = $this->title;

$tahun_id = !empty($_GET['tahun_id']) ? $_GET['tahun_id'] : '';
$nim = !empty($_GET['nim']) ? $_GET['nim'] : '';

$list_absensi = \app\helpers\MyHelper::getListAbsensi();

?>

<h3 class="page-title"><?=$this->title;?></h3>
<div class="row">
  <div class="col-lg-8 col-lg-offset-2">
     <?php $form = ActiveForm::begin([
      'method' => 'GET',
      'action' => ['akademik/kehadiran'],
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
           <?= Html::radioList('nim',$nim,\yii\helpers\ArrayHelper::map($list_anak,'nim_mhs','nama_mahasiswa'),['class'=>'','prompt'=>'- Pilih Data Mahasiswa -','id'=>'nim','separator' => '<br>']) ?>
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
          <table class="table table-hover table-striped table-bordered">
            <thead>
              <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">Kode MK</th>
                <th rowspan="2">Mata Kuliah</th>
                <th style="text-align: center" colspan="16">Pertemuan ke</th>
                
              </tr>
              <tr>
                  <?php
                  for($i=1;$i<=16;$i++){
                    echo '<th>'.$i.'</th>';
                  }

                  ?>
              </tr>
            </thead>
            <tbody>
              <?php 
              $counter = 1;
              $total_sks = 0;
              foreach($listKrs as $q => $m)
              {
                $mk = $m['matkul'];
                $dosen = $m['dosen'];
                $jadwal = $m['jadwal'];
                $total_sks += $mk['sks_mk'];
              ?>
              <tr>
                <td><?=$counter;?></td>
                <td><?=$mk['kode_mk'];?></td>
                <td><?=$mk['nama_mk'];?></td>
                <?php
                    for($i=1;$i<=16;$i++){
                        $tmp = \app\models\SimakAbsenHarian::find();
                        $tmp->andWhere([
                            'mhs' => $mhs->nim_mhs,
                            'pertemuan' => $i,
                            'kode_jadwal' => $jadwal->id
                        ]);

                        $sh = $tmp->one();
                        $label = '-';
                        if(!empty($sh)){
                            $label = !empty($list_absensi[$sh->status_kehadiran]) ? $list_absensi[$sh->status_kehadiran] : '-';
                        }

                        echo '<td>'.$label.'</td>';
                    }

                  ?>
                
              </tr>
              <?php 
                $counter++;
              }
              ?>
            </tbody>
            
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
