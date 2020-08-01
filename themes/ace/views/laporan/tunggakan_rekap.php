<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\helpers\MyHelper;
use yii\grid\GridView;

use kartik\depdrop\DepDrop;

use yii\helpers\ArrayHelper;
// use app\models\TagihanSearch;

// use keygenqt\autocompleteAjax\AutocompleteAjax;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SalesStokGudangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Laporan Rekap Pembayaran';
$this->params['breadcrumbs'][] = $this->title;

$model->tanggal_awal = !empty($_GET['Tagihan']['tanggal_awal']) ? $_GET['Tagihan']['tanggal_awal'] : date('Y-m-d');
$model->tanggal_akhir = !empty($_GET['Tagihan']['tanggal_akhir']) ? $_GET['Tagihan']['tanggal_akhir'] : date('Y-m-d');

$status_aktivitas = !empty($_GET['status_aktivitas']) ? $_GET['status_aktivitas'] : '';
$kampus = !empty($_GET['kampus']) ? $_GET['kampus'] : '';
$prodi = !empty($_GET['prodi']) ? $_GET['prodi'] : '';
$tahun = !empty($_GET['tahun']) ? $_GET['tahun'] : '';
$komponen = !empty($_GET['komponen']) ? $_GET['komponen'] : '';
?>
<div class="sales-stok-gudang-index">

    <h1><?= Html::encode($this->title) ?></h1>
  <div class="row">
    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['laporan/rekap-pembayaran'],
        'options' => [
            'class' => 'form-horizontal'
        ]
    ]); ?>
     <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Kampus</label>
         <div class="col-lg-2 col-sm-10">
          <select id="kampus" name="kampus"  class="form-control">
              
          </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Prodi</label>
         <div class="col-lg-2 col-sm-10">
          <select id="prodi" name="prodi" class="form-control">
              
          </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Status Mahasiswa</label>
        <div class="col-lg-2 col-sm-10">
            <?= Html::dropDownList('status_aktivitas',$status_aktivitas,['N'=>'N - Non Aktif','C'=>'C - Cuti','A'=>'A - Aktif'],['id'=>'status_aktivitas','class'=>'form-control','prompt'=>'- Pilih Status -']);?>
                
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Tahun Akademik</label>
        <div class="col-lg-2 col-sm-10">
          <?= Html::dropDownList('tahun', $tahun,
      ArrayHelper::map(\app\models\Tahun::find()->orderBy(['id'=>SORT_DESC])->limit(10)->all(), 'id', function($data){
        return $data->id.' - '.$data->nama;
      }),['class' => 'form-control', 'id' => 'tahun','prompt'=>'- Pilih Tahun -']) ?>
        </div>
    </div>
   
    
   
    <div class="col-sm-offset-2">
        <?= Html::submitButton('<i class="ace-icon fa fa-search bigger-110"></i>Tampilkan Laporan ', ['class' => 'btn btn-info','name'=>'btn-search','id'=>'search','value'=>1]) ?>    
        <?= Html::submitButton(' <i class="ace-icon fa fa-check bigger-110"></i>Export XLS', ['class' => 'btn btn-success','name'=>'export','value'=>1]) ?>    
        
    </div>


     
</div>

    <?php ActiveForm::end(); ?>
<p>
    <small>Menampilkan <?=count($results);?> data</small>
</p>
<div class="row">
    <div class="table-responsive">
<table id="tabel_tagihan" class="table table-bordered table-striped">
    
    <thead>
    <tr>
        <th rowspan="2">No</th>
        <th rowspan="2">NIM</th>
        <th rowspan="2">Nama Mahasiswa</th>
        <th rowspan="2">Prodi</th>
        <th rowspan="2">Semester</th>
        <th rowspan="2">DU Genap/Ganjil</th>
        <th colspan="12" class="text-center">SPP Bulanan</th>
    </tr>
    <tr>
        <?php 
        foreach($listBulan as $b)
        {
        ?>
        <th  class="text-center"><?=$b->nama;?></th>
        <?php 
        }
        ?>
    </tr>
</thead>
<tbody>
    <?php 
    foreach($results as $q => $m)
    {


    ?>
    <tr>
        <td><?=$q+1;?></td>
        <td><?=$m['nim_mhs'];?></td>
        <td><?=$m['nama_mahasiswa'];?></td> 
        <td><?=$m['singkatan'];?></td>    
        <td><?=$m['semester'];?></td> 
        <td class="text-right"><?=MyHelper::formatRupiah($m['du']);?></td>
        <td class="text-right"><?=MyHelper::formatRupiah($m['syawal']);?></td>
        <td class="text-right"><?=MyHelper::formatRupiah($m['dzulqodah']);?></td>
        <td class="text-right"><?=MyHelper::formatRupiah($m['dzulhijjah']);?></td>
        <td class="text-right"><?=MyHelper::formatRupiah($m['muharram']);?></td>
        <td class="text-right"><?=MyHelper::formatRupiah($m['shafar']);?></td>
        <td class="text-right"><?=MyHelper::formatRupiah($m['rabiulawal']);?></td>
        <td class="text-right"><?=MyHelper::formatRupiah($m['rabiulakhir']);?></td>
        <td class="text-right"><?=MyHelper::formatRupiah($m['jumadilula']);?></td>
        <td class="text-right"><?=MyHelper::formatRupiah($m['jumadiltsani']);?></td>
        <td class="text-right"><?=MyHelper::formatRupiah($m['rajab']);?></td>
        <td class="text-right"><?=MyHelper::formatRupiah($m['syaban']);?></td>
        <td class="text-right"><?=MyHelper::formatRupiah($m['ramadan']);?></td> 
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
<?php

$uid = !empty($_GET['unit_id']) ? $_GET['unit_id'] : '';
$script = "


function getListKampus(){
    $.ajax({
        type : 'POST',
        url : '".Url::to(['/api/list-kampus'])."',
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
             var row = '<option value=\"\">- Pilih Kampus -</option>';
                   
            $.each(data.values,function(i, obj){
                row += '<option value=\"'+obj.kode_kampus+'\">'+obj.kode_kampus+' - '+obj.nama_kampus+'</option>';
                
            });

           
            $('#kampus').append(row);
            $('#kampus').val('".$kampus."');
            getListProdi('".$kampus."');
        }

    });
}

function getListProdi(kampus){
    $.ajax({
        type : 'POST',
        url : '/api/list-prodi',
        data : 'id='+kampus,
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
            var row = '<option value=\"\">- Pilih Prodi -</option>';
                   
            $.each(data,function(i, obj){

                row += '<option value=\"'+obj.kode_prodi+'\">'+obj.nama_prodi+'</option>';
                
            });

           
            $('#prodi').append(row);
            $('#prodi').val('".$prodi."');
        }

    });
}


$('#kampus').change(function(){
    getListProdi($(this).val());
});
getListKampus();


";
$this->registerJs(
    $script,
    \yii\web\View::POS_READY
);
// $this->registerJs($script);
?>