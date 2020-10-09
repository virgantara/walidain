<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use kartik\depdrop\DepDrop;

// use app\models\TagihanSearch;

// use keygenqt\autocompleteAjax\AutocompleteAjax;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SalesStokGudangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Komponen Biaya Pencekalan';
$this->params['breadcrumbs'][] = $this->title;

$list_prioritas = \app\helpers\MyHelper::getListPrioritas();

$tahun = !empty($_GET['tahun']) ? $_GET['tahun'] : '';
$kampus = !empty($_GET['kampus']) ? $_GET['kampus'] : '';

?>
<div class="sales-stok-gudang-index">

    <h1><?= Html::encode($this->title) ?></h1>
  
    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['komponen-biaya/pencekalan'],
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
        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Tahun Akademik</label>
        <div class="col-lg-2 col-sm-10">
          <?= Html::dropDownList('tahun', $tahun,
      ArrayHelper::map(\app\models\Tahun::find()->orderBy(['id'=>SORT_DESC])->limit(10)->all(), 'id', function($data){
        return $data->id.' - '.$data->nama;
      }),['class' => 'form-control', 'id' => 'tahun','prompt'=>'- Pilih Tahun -']) ?>
        </div>
    </div>
    
    <div class="col-sm-2">
        
    </div>
<div class="col-sm-3">

    <div class="form-group">
        <?= Html::submitButton(' <i class="ace-icon fa fa-search bigger-110"></i>Tampilkan', ['class' => 'btn btn-info','name'=>'btn-search','id'=>'search','value'=>1]) ?>    
        

    </div>

</div>
     


    <?php ActiveForm::end(); ?>

<table id="pencekalan" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>No</th>
            <th>Kategori</th>
            <th>Nama Komponen</th>
            <th>Prioritas</th>
            <th>Bulan</th>
            <th>
                <label class="fancy-checkbox"><input type="checkbox" id="checkAll"> <span><strong>Check All</strong></span></label>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach($results as $q => $v)
        {


        ?>
        <tr>
            <td><?=$q+1;?></td>
            <td><?=$v->kategori->kode;?> - <?=$v->kategori->nama;?></td>
            <td><?=$v->nama;?></td>
            <td><?=$list_prioritas[$v->prioritas];?></td>
            <td><?=$v->bulan->nama;?></td>
            <td>
                <label class="fancy-checkbox"><input type="checkbox" class="approval" data-item="<?=$v->id;?>" <?=$v->is_pencekalan == '1' ? "checked": "";?> value="<?=$v->is_pencekalan;?>"> <span><strong></strong></span></label>
            </td>    
        </tr>
        <?php 
        }
        ?>
    </tbody>
    
</table>
   
</div>
</div>
<?php

$script = "

function setCekal(id,cekal){
    var obj = new Object;
    obj.id = id;
    obj.cekal = cekal;
    $.ajax({
        type: 'POST',
        data: {
            dataPost:obj,
        },
        async: true,
        url: '".Url::to(['komponen-biaya/ajax-approve-cekal'])."',
        beforeSend : function(){
            Swal.showLoading();
        },
        error: function(e){
            Swal.fire({
                title: 'Oops!',
                icon: 'danger',
                text: e.responseText
            })
        },
        success: function(msg){
            var hsl = $.parseJSON(msg);

            if(hsl.code == '200'){
                Swal.fire({
                    title: 'Yeay!',
                    icon: 'success',
                    text: hsl.message
                });
            }

            else{
                Swal.fire({
                    title: 'Oops!',
                    icon: 'danger',
                    text: hsl.message
                })
            }
             
        }
    });
}



$('.approval').click(function(){
    setCekal($(this).data('item'), this.checked ? '1' : '0');
});



$('#checkAll').click(function(){
    $('input.approval').not(this).prop('checked', this.checked);

    var count = $('input.approval').length;
    var listChecks = []
    $('input.approval').each(function(i,obj){
        var id = $(this).data('item')

        var obj = new Object;
        obj.id = id;
        obj.cekal = $(this).prop('checked') ? '1' : '0';
        listChecks.push(obj)

        if (i+1 === count) {
            
            $.ajax({
                type: 'POST',
                data: {
                    dataPost:listChecks,
                },
                async: true,
                url: '".Url::to(['komponen-biaya/ajax-approve-cekal-bulk'])."',
                beforeSend : function(){
                    Swal.showLoading();
                },
                error: function(e){
                    Swal.fire({
                        title: 'Oops!',
                        icon: 'error',
                        text: e.responseText
                    });
                    
                },
                success: function(msg){
                    var hsl = $.parseJSON(msg);

                    if(hsl.code == '200'){
                        Swal.fire({
                            title: 'Yeay!',
                            icon: 'success',
                            text: hsl.message
                        });
                    }

                    else{
                        Swal.fire({
                            title: 'Oops!',
                            icon: 'error',
                            text: hsl.message
                        })
                    }
                     
                }
            });

        }
    })    
});

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
            var row = '<option value=\"\">- Pilih Kampus -</option>';
                   
            $.each(data.values,function(i, obj){
                row += '<option value=\"'+obj.kode_kampus+'\">'+obj.nama_kampus+'</option>';
                
            });

           
            $('#kampus').append(row);
            $('#kampus').val('".$kampus."');
            
        }

    });
}

$(document).ready(function(){
    getListKampus();
   
});

";
$this->registerJs(
    $script,
    \yii\web\View::POS_READY
);
// $this->registerJs($script);
?>