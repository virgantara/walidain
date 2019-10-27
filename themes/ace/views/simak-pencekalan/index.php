<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SimakPencekalanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Simak Pencekalans';
$this->params['breadcrumbs'][] = $this->title;

$kampus = !empty($_GET['kampus']) ? $_GET['kampus'] : 0;
$prodi = !empty($_GET['prodi']) ? $_GET['prodi'] : 0;

?>
<div class="row">
<div class="col-xs-12">
    <h1><?= Html::encode($this->title) ?></h1>

 <?php $form = ActiveForm::begin([
        'action' => ['simak-pencekalan/index'],
        'method' => 'get',
        'options' => [
            'class' => 'form-horizontal'
        ]
    ]); ?>

    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Kampus</label>
        <div class="col-lg-2 col-sm-10">
          <select id="kampus" name="kampus">
              
          </select>
        </div>
    </div>
     <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Prodi</label>
        <div class="col-lg-2 col-sm-10">
          <select id="prodi" name="prodi">
              
          </select>
        </div>
    </div>

     <div class="clearfix form-actions">
        <div class="col-md-offset-3 col-md-9">

          <button class="btn btn-info" type="submit" value="1" name="btn-lihat">
            <i class="ace-icon glyphicon glyphicon-check bigger-110"></i>
            Lihat
          </button>
          
        
        </div>
      </div>


    <?php ActiveForm::end(); ?>
<div class="row">
    <div class="col-xs-12">
        
        <?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <i class="icon fa fa-check"></i><?= Yii::$app->session->getFlash('success') ?>
         
    </div>
<?php endif; ?>

        <br>
 <?php $form = ActiveForm::begin([
        'action' => ['simak-pencekalan/index'],
        'method' => 'post',
        'options' => [
            'class' => 'form-horizontal'
        ]
    ]); ?>

<input type="hidden" name="kampus" value="<?=$kampus;?>"/>
<input type="hidden" name="prodi" value="<?=$prodi;?>"/>
<table class="table table-striped table-bordered" id="table-mahasiswa">

  <thead>
    <tr>
      <th width="5%">No</th>
      <th width="25%">NIM</th>
      <th width="30%">Nama</th>
      <th width="10%">Tahfidz</th>
      <th width="10%">AKPAM</th>
      <th width="10%">ADM</th>
      <th width="10%">Akademik</th>
      
    </tr>
  </thead>
  <tbody>
    <?php 

    foreach($results as $q=>$item)
    {

        $cekal = \app\models\SimakPencekalan::find()->where([
            'tahun_id' => $tahunaktif,
            'nim' => $item->nim_mhs
        ])->one();

        $checkedTahfidz = '';
        $checkedAdm = '';
        $checkedAkpam = '';
        $checkedAkademik = '';
        if(!empty($cekal))
        {

            $checkedTahfidz = $cekal->tahfidz ? 'Tercekal' :'';
            $checkedAkpam = $cekal->akpam  ? 'Tercekal' :'';
            $checkedAdm = $cekal->adm  ? 'checked' :'';
            $checkedAkademik = $cekal->akademik ? 'Tercekal' :'';    
        }
        
    ?>
<tr>
    <td><?=$q+1;?></td>
    <td><?=$item->nim_mhs;?></td>
    <td><?=$item->nama_mahasiswa;?></td>
    <td><?=$checkedTahfidz;?></td>
    <td><?=$checkedAkpam;?></td>
    <td><input type="checkbox" name="adm_<?=$tahunaktif;?>_<?=$item->nim_mhs;?>" <?=$checkedAdm;?>></td>
    <td><?=$checkedAkademik;?></td>
</tr>
    <?php 
  }
    ?>
</tbody>
</table>
     <div class="clearfix form-actions">
        <div class="col-md-offset-3 col-md-9">

          <button class="btn btn-info" type="submit" value="1" name="btn-simpan">
            <i class="ace-icon glyphicon glyphicon-check bigger-110"></i>
            Simpan Data
          </button>
          
        
        </div>
      </div>
    </div>
</div>


    <?php ActiveForm::end(); ?>
</div>
</div>


<?php

$script = "


function getListKampus(){
    $.ajax({
        type : 'POST',
        url : '/api/list-kampus',
        beforeSend : function(){
            $('#loading').show();
        },
        error : function(err){
            console.log(err);
            $('#loading').hide();
        },
        success : function(data){

            var data = $.parseJSON(data);
            
            $('#loading').hide();  
            $('#kampus').empty();
            var row = '';
                   
            $.each(data.values,function(i, obj){
                row += '<option value=\"'+obj.kode_kampus+'\">'+obj.nama_kampus+'</option>';
                
            });


           
            $('#kampus').append(row);
            $('#kampus').val(".$kampus.");
        }

    });
}

function getListProdi(){
    $.ajax({
        type : 'POST',
        url : '/api/list-prodi',
        beforeSend : function(){
            $('#loading').show();
        },
        error : function(err){
            console.log(err);
            $('#loading').hide();
        },
        success : function(data){

            var data = $.parseJSON(data);
            
            $('#loading').hide();  
            $('#prodi').empty();
            var row = '';
                   
            $.each(data.values,function(i, obj){
                row += '<option value=\"'+obj.kode_prodi+'\">'+obj.nama_prodi+'</option>';
                
            });

           
            $('#prodi').append(row);
            $('#prodi').val(".$prodi.");
        }

    });
}

$(document).ready(function(){
    
    getListProdi();
    getListKampus();
    // $('#generate').click(function(){
    //     generate();
    // });
});

";
$this->registerJs(
    $script,
    \yii\web\View::POS_READY
);
// $this->registerJs($script);
?>