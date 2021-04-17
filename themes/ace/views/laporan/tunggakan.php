<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
// use app\models\TagihanSearch;

// use keygenqt\autocompleteAjax\AutocompleteAjax;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SalesStokGudangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Laporan Tunggakan';
$this->params['breadcrumbs'][] = $this->title;

$model->tanggal_awal = !empty($_GET['Tagihan']['tanggal_awal']) ? $_GET['Tagihan']['tanggal_awal'] : date('Y-m-d');
$model->tanggal_akhir = !empty($_GET['Tagihan']['tanggal_akhir']) ? $_GET['Tagihan']['tanggal_akhir'] : date('Y-m-d');
?>
<div class="sales-stok-gudang-index">

    <h1><?= Html::encode($this->title) ?></h1>
  
    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['laporan/tunggakan'],
        'options' => [
            'class' => 'form-horizontal',
            'id' => 'form-tunggakan'
        ]
    ]); ?>
  
     <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Kelas</label>
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
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Tahun Akademik</label>
        <div class="col-lg-2 col-sm-10">
          <?= Html::dropDownList('tahun', null,
      ArrayHelper::map(\app\models\Tahun::find()->orderBy(['id'=>SORT_DESC])->limit(10)->all(), 'id', function($data){
        return $data->id.' - '.$data->nama;
      }),['class' => 'form-control', 'id' => 'tahun','prompt'=>'- Pilih Tahun -']) ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Kategori Komponen</label>
        <div class="col-lg-2 col-sm-10">

         <?= Select2::widget([
            'data' => \yii\helpers\ArrayHelper::map(\app\models\Kategori::find()->all(),'id',function($data){
                return $data->kode.' - '.$data->nama;
            }),
            'name' => 'komponen',
            'options'=>['placeholder'=>Yii::t('app','- Pilih Kelompok Bidang -'),'id'=>'komponen'],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])?>  
        </div>
    </div>
    <div class="col-sm-2">
        
    </div>
<div class="col-sm-3">

    <div class="form-group">
        <?= Html::button(' <i class="ace-icon fa fa-check bigger-110"></i>Cari', ['class' => 'btn btn-info','id'=>'search','value'=>1]) ?>    
        <?= Html::submitButton(' <i class="ace-icon fa fa-check bigger-110"></i>Export XLS', ['class' => 'btn btn-success','name'=>'export','value'=>1]) ?>    
        <div class="lds-facebook" id="loading" style="height: 32px;display: none"><div></div><div></div><div></div></div>
    </div>

</div>
     


    <?php ActiveForm::end(); ?>

<table id="tabel_tagihan" class="table table-bordered table-striped">
    
    <thead>
        <tr>
            <th>No</th>
            <th>Komponen</th>
            <th>Cust. ID / NIM</th>
            <th>Nama</th>
            <th>Status</th>
            <th>Prodi</th>
            <th>Semester</th>
            <th>Nilai</th>
            <th>Terbayar</th>
            <th>Sisa Tagihan</th>
        </tr>
    </thead>
    <tbody>
        
    </tbody>
</table>
   
</div>
</div>
<?php

$uid = !empty($_GET['unit_id']) ? $_GET['unit_id'] : '';
$script = "

function getTagihan(){
    var obj = $('#form-tunggakan').serializeArray()
        .reduce(function(a, x) { a[x.name] = x.value; return a; }, {});
    $.ajax({
        type : 'POST',
        data : {
            dataPost : obj
        },
        url : '/api/tunggakan',
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
            $('#tabel_tagihan > tbody').empty();
            var row = '';
                   
            $.each(data.values,function(i, obj){

                var cls = '';

                if(obj.sisaAmount > 0){
                    cls = 'alert alert-danger';
                }

                row += '<tr>';
                row += '<td class=\"'+cls+'\">'+eval(i+1)+'</td>';
                row += '<td class=\"'+cls+'\">'+obj.komponen+'</td>';
                row += '<td class=\"'+cls+'\">'+obj.custid+'</td>';
                row += '<td class=\"'+cls+'\">'+obj.nama_mahasiswa+'</td>';
                row += '<td class=\"'+cls+'\">'+obj.status_mhs+'</td>';
                row += '<td class=\"'+cls+'\">'+obj.prodi+'</td>';
                row += '<td class=\"'+cls+'\">'+obj.semester+'</td>';
                row += '<td class=\"'+cls+'\" style=\"text-align:right\">'+obj.nilai+'</td>';
                row += '<td class=\"'+cls+'\" style=\"text-align:right\" >'+obj.terbayar+'</td>';
                row += '<td style=\"text-align:right\" class=\"'+cls+'\">'+obj.sisa+'</td>';
                
                row += '</tr>';
            });

            row += '<tr>';
            row += '<td colspan=\"8\"  style=\"text-align:right\">Total</td>';
            row += '<td style=\"text-align:right\">'+data.total_terbayar+'</td>';
            row += '<td style=\"text-align:right\">'+data.total_sisa+'</td>';
            
            row += '</tr>';      
            $('#tabel_tagihan > tbody').append(row);
            
        }

    });
}

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
            var row = '<option value=\"\">- Pilih Kelas -</option>';
                   
            $.each(data.values,function(i, obj){
                row += '<option value=\"'+obj.kode_kampus+'\">'+obj.nama_kampus+'</option>';
                
            });

           
            $('#kampus').append(row);
            
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
            var row = '<option value=\"\">Semua Prodi</option>';
                   
            $.each(data,function(i, obj){

                row += '<option value=\"'+obj.kode_prodi+'\">'+obj.nama_prodi+'</option>';
                
            });

           
            $('#prodi').append(row);
            
        }

    });
}

$(document).ready(function(){
    getListKampus();
    
    $('#search').click(function(){
        getTagihan();
    });

    $('#kampus').change(function(){
        getListProdi($(this).val());
    });
});
";
$this->registerJs(
    $script,
    \yii\web\View::POS_READY
);
// $this->registerJs($script);
?>